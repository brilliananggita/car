<?php
include '../koneksi.php';
include '../akun/session_check.php';

// Pastikan hanya member yang dapat mengakses halaman ini
checkRole('member');

// Mengambil nik dari session
$nik = $_SESSION['nik'];

// Query untuk mengambil riwayat transaksi sesuai nik
$sql = "
    SELECT 
        t.*, 
        u.nik AS username, 
        m.nopol 
    FROM tb_transaksi t 
    JOIN tb_member u ON u.nik = t.nik 
    JOIN tb_mobil m ON m.nopol = t.nopol 
    WHERE t.nik = ?
";

// Mempersiapkan statement
$stmt = $koneksi->prepare($sql);
if ($stmt === false) {
    die('Prepare error: ' . htmlspecialchars($koneksi->error));
}

// Mengikat parameter
$stmt->bind_param('s', $nik);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     <!-- kode buat navbar -->
     <div class="navbar">
        <div class="navbar-title"><button class="toggle-btn" onclick="toggleSidebar()">☰</button> Rental Mobil </div>
        <!-- Buat profil member -->
        <div class="profile">
            <a href="profil.php">
                <img src="../image/pp.png" alt="Profile" class="profile-img">
            </a>
        </div>
    </div>

    <!-- buat side bar -->
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="mobil.php">Pesan Mobil</a></li>
            <li><a href="riwayat.php">Riwayat Pemesanan</a></li>
            <li><a href="../logout.php">Logout</a></li> <!-- Link untuk logout -->
        </ul>
    </div>

    <div class="content" id="content">
            
        <h1 class="mt-4">Riwayat Transaksi</h1>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <tr>
                        <th>No</th>
                        <th>Mobil</th>
                        <th>Tanggal Booking</th>
                        <th>Tanggal Ambil</th>
                        <th>Tanggal Kembali</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    $i = 1;
                    while($data = $result->fetch_assoc()) {
                    ?>
                        <tr>
                        <td><?php echo $i++; ?></td>
        <td><?php echo htmlspecialchars($data['nopol']); ?></td>
        <td><?php echo htmlspecialchars($data['tgl_booking']); ?></td>
        <td><?php echo htmlspecialchars($data['tgl_ambil']); ?></td>
        <td><?php echo htmlspecialchars($data['tgl_kembali']); ?></td>
        <td><?php echo 'Rp' . number_format($data['total'], 2); ?></td>
        <td>
            <?php
            // Menentukan warna badge berdasarkan status
            if ($data['status'] == 'approve') {
                echo '<span class="badge bg-success">Approved</span>';
            } elseif ($data['status'] == 'ambil') {
                echo '<span class="badge bg-warning text-dark">ambil</span>';
            } elseif ($data['status'] == 'kembali') {
                echo '<span class="badge bg-success">kembali</span>';
            } elseif ($data['status'] == 'ambil') {
                echo '<span class="badge bg-info">ambil</span>';
            } elseif ($data['status'] == 'booking') {
                echo '<span class="badge bg-secondary">booking</span>';
            }
            ?>
        </td>

                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            sidebar.classList.toggle('active');
            content.classList.toggle('active'); // Menambahkan kelas aktif pada konten
        }
    </script>
</body>
</html>

<?php
$stmt->close(); // Tutup statement
mysqli_close($koneksi); // Tutup koneksi
?>
