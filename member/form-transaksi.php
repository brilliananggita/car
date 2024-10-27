<?php
session_start();
include '../koneksi.php';

// Check if the user is logged in and has the role of member
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'member') {
    // Redirect to login if not logged in or not a member
    header('Location: ../akun/login.php');
    exit;
}

// Cek apakah 'nopol' ada dalam query string
if (isset($_GET['nopol'])) {
    $nopol = $_GET['nopol'];
    $result = mysqli_query($koneksi, "SELECT * FROM tb_mobil WHERE nopol = '$nopol'");
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Mobil tidak ditemukan.");
    }
    $hargaSewa = $row['harga']; // Ambil harga mobil dari database
} else {
    die("Nomor polisi mobil tidak diberikan.");
}

// Ambil username dari session (user yang login)
$username = $_SESSION['username'] ?? '';

// Fungsi untuk mengganti nama hari dan bulan ke bahasa Indonesia
function tanggalIndo($timestamp) {
    $hari = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $bulan = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli',
        'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober',
        'November' => 'November', 'December' => 'Desember'
    ];

    $namaHari = $hari[date('l', $timestamp)];
    $tanggal = date('d', $timestamp);
    $namaBulan = $bulan[date('F', $timestamp)];
    $tahun = date('Y', $timestamp);

    return "$namaHari, $tanggal $namaBulan $tahun";
}

// Tampilkan tanggal sekarang dalam format bahasa Indonesia
$tanggalBooking = tanggalIndo(time());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rent Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        /* Padding to prevent content from hiding under navbar */
        body {
            padding-top: 64px; /* Adjust based on the header height */
        }
    </style>
    <script>
        function hitungTotal() {
            const hargaMobil = <?php echo $hargaSewa; ?>;
            const supir = document.getElementById('supir').value;
            const tglAmbil = new Date(document.getElementById('tgl_ambil').value);
            const tglKembali = new Date(document.getElementById('tgl_kembali').value);
            const hariSewa = Math.ceil((tglKembali - tglAmbil) / (1000 * 60 * 60 * 24)) + 1;
            let total = hargaMobil * hariSewa;
            let dp = parseFloat(document.getElementById('dp').value) || 0;

            if (supir === '1') {
                total += 100000 * hariSewa; // Biaya supir per hari
            }

            let kekurangan = total - dp;
            document.getElementById('kekurangan').value = kekurangan;
            document.getElementById('total').value = total;
        }
    </script>
</head>
<body class="bg-white text-gray-800 font-sans">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between p-6 bg-white shadow-md">
        <div class="flex items-center space-x-2">
            <img src="../img/logo.png" alt="logo" class="w-11 h-11">
            <span class="text-2xl font-bold text-purple-700">Rental Mobil</span>
        </div>
        <nav class="space-x-4 flex items-center">
            <a href="member.php" class="text-gray-600 hover:text-purple-700">Beranda</a>
            <a href="#member.php" class="text-gray-600 hover:text-purple-700">Mobil</a>
            <a href="#member.php" class="text-gray-600 hover:text-purple-700">Riwayat</a>
            <a href="profil.php" class="flex items-center">
                <img src="../img/pp.png" alt="User Profile" class="w-10 h-10 rounded-full border-2 border-purple-700">
            </a>
        </nav>
    </header>

    <!-- FOrm transaksi Section -->
    <section id="home" class="container mx-auto px-6 py-16 flex flex-col md:flex-row items-center">
        <div class="md:w-1/1 mb-10 md:mb-0">
            <h2 class="text-center mb-4">Form Transaksi Penyewaan Mobil</h2>

             <form action="" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Username (Penyewa):</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="nopol" class="form-label">Nomor Polisi:</label>
                    <input type="text" class="form-control" id="nopol" name="nopol" value="<?php echo $nopol; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="tgl_booking" class="form-label">Tanggal Booking:</label>
                    <input type="text" class="form-control" id="tgl_booking" name="tgl_booking" value="<?php echo $tanggalBooking; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="tgl_ambil" class="form-label">Tanggal Ambil:</label>
                    <input type="date" class="form-control" id="tgl_ambil" name="tgl_ambil" required onchange="hitungTotal()">
                </div>
                <div class="mb-3">
                    <label for="tgl_kembali" class="form-label">Tanggal Kembali:</label>
                    <input type="date" class="form-control" id="tgl_kembali" name="tgl_kembali" required onchange="hitungTotal()">
                </div>
                <div class="mb-3">
                    <label for="supir" class="form-label">Sewa Supir:</label>
                    <select class="form-select" id="supir" name="supir" onchange="hitungTotal()">
                        <option value="0">Tanpa Supir</option>
                        <option value="1">Dengan Supir</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="dp" class="form-label">Down Payment (DP):</label>
                    <input type="text" class="form-control" id="dp" name="dp" value="0" oninput="hitungTotal()">
                </div>
                <div class="mb-3">
                    <label for="kekurangan" class="form-label">Kekurangan:</label>
                    <input type="text" class="form-control" id="kekurangan" name="kekurangan" readonly>
                </div>
                <div class="mb-3">
                    <label for="total" class="form-label">Total Biaya:</label>
                    <input type="text" class="form-control" id="total" name="total" value="<?php echo $hargaSewa; ?>" readonly>
                </div>
                <button type="submit" name="pinjam" value="submit" class="btn btn-primary">Sewa</button>
            </form>
    </div>
    </section>


   

    <footer class="py-12 bg-gray-100 mt-10 text-center">
        <p class="text-gray-500 text-sm">Car Rent &copy; 2024 - All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Menangani proses penyimpanan transaksi
if (isset($_POST['pinjam'])) {
    $nopol = $_POST['nopol'];
    $username = $_POST['username'];
    $supir = $_POST['supir'];
    $dp = $_POST['dp'];
    $kekurangan = $_POST['kekurangan'];
    $total = $_POST['total'];

    // Tanggal booking saat ini dan tanggal ambil/kembali dari form
    $tgl_booking = date('Y-m-d');
    $tgl_ambil = $_POST['tgl_ambil'];
    $tgl_kembali = $_POST['tgl_kembali'];

    // Query untuk mengambil nik berdasarkan username dari tabel tb_member
    $result_member = $koneksi->query("SELECT nik FROM tb_member WHERE username = '$username'");

    if ($result_member) {
        $row_member = $result_member->fetch_assoc();

        // Jika nik ditemukan
        if ($row_member) {
            $nik = $row_member['nik'];

            // Query untuk menyimpan data transaksi ke tb_transaksi
            $sql = "INSERT INTO tb_transaksi (nik, nopol, tgl_booking, tgl_ambil, tgl_kembali, supir, total, downpayment, kekurangan, status) 
                    VALUES ('$nik', '$nopol', '$tgl_booking', '$tgl_ambil', '$tgl_kembali', '$supir', '$total', '$dp', '$kekurangan', 'booking')";
            $query = $koneksi->query($sql);

            if ($query) {
                // Update status mobil menjadi 'tidak tersedia'
                $sql1 = "UPDATE tb_mobil SET `status` = 'tidak' WHERE `nopol` = '$nopol'";
                $query1 = $koneksi->query($sql1);

                if ($query1) {
                    echo "<script>alert('Penyewaan berhasil! Silakan tunggu konfirmasi dari petugas.'); window.location.href='../member/member.php';</script>";
                } else {
                    echo "Error: " . $koneksi->error;
                }
            } else {
                echo "Error: " . $koneksi->error;
            }
        } else {
            echo "Member tidak ditemukan.";
        }
    }
}
?>
