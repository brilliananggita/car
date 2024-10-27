<?php
include '../koneksi.php';

// Check if the user is logged in and has the role of petugas
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'petugas') {
    // Redirect to login if not logged in or not a petugas
    header('Location: ../akun/login.php');
    exit;
}
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Fungsi untuk mengupdate status transaksi
if (isset($_POST['update_status'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $new_status = $_POST['new_status'];

    $update_sql = "UPDATE tb_transaksi SET status = ? WHERE id_transaksi = ?";
    $update_stmt = $koneksi->prepare($update_sql);
    $update_stmt->bind_param('si', $new_status, $id_transaksi);
    $update_stmt->execute();
}

// Mengambil transaksi berdasarkan status
$statuses = ['Booking', 'Approve', 'Ambil', 'Kembali'];
$transactions = [];

foreach ($statuses as $status) {
    $sql = "SELECT * FROM tb_transaksi WHERE status = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactions[$status] = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Petugas Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex">
      <div class="content" id="content">
        <nav id="transactions-table-tab" class="transactions-table-tab app-nav-tabs nav shadow-sm flex-column flex-sm-row mb-4">
                        <a class="flex-sm-fill text-sm-center nav-link active" id="transactions-booking-tab" data-bs-toggle="tab" href="#transactions-booking" role="tab" aria-controls="transactions-booking" aria-selected="true">Booking</a>
                        <a class="flex-sm-fill text-sm-center nav-link" id="transactions-approve-tab" data-bs-toggle="tab" href="#transactions-approve" role="tab" aria-controls="transactions-approve" aria-selected="false">Approve</a>
                        <a class="flex-sm-fill text-sm-center nav-link" id="transactions-ambil-tab" data-bs-toggle="tab" href="#transactions-ambil" role="tab" aria-controls="transactions-ambil" aria-selected="false">Ambil</a>
                        <a class="flex-sm-fill text-sm-center nav-link" id="transactions-kembali-tab" data-bs-toggle="tab" href="#transactions-kembali" role="tab" aria-controls="transactions-kembali" aria-selected="false">Kembali</a>
                    </nav>

                    <div class="tab-content" id="transactions-table-tab-content">
                        <!-- Tab Booking -->
                        <div class="tab-pane fade show active" id="transactions-booking" role="tabpanel" aria-labelledby="transactions-booking-tab">
                            <div class="app-card app-card-transactions-table shadow-sm mb-5">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">ID Transaksi</th>
                                                    <th class="cell">NIK</th>
                                                    <th class="cell">No. Polisi</th>
                                                    <th class="cell">Tanggal Booking</th>
                                                    <th class="cell">Tanggal Ambil</th>
                                                    <th class="cell">Tanggal Kembali</th>
                                                    <th class="cell">Kekurangan</th>
                                                    <th class="cell">Status</th>
                                                    <th class="cell">Aksi</th> <!-- Kolom Aksi -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions['Booking'] as $trans): ?>
                                                    <tr>
                                                        <td class="cell"><?php echo $trans['id_transaksi']; ?></td>
                                                        <td class="cell"><?php echo $trans['nik']; ?></td>
                                                        <td class="cell"><?php echo $trans['nopol']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_booking']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_ambil']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_kembali']; ?></td>
                                                        <td class="cell"><?php echo 'Rp' . number_format($trans['kekurangan'], 2); ?></td>
                                                        <td class="cell"><span class="badge bg-info"><?php echo $trans['status']; ?></span></td>
                                                        <td class="cell">
                                                            <form method="POST" action="">
                                                                <input type="hidden" name="id_transaksi" value="<?php echo $trans['id_transaksi']; ?>">
                                                                <input type="hidden" name="new_status" value="Approve">
                                                                <button type="submit" name="update_status" class="btn btn-sm btn-success">Approve</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>		
                            </div>	
                        </div>

                        <!-- Tab Approve -->
                        <div class="tab-pane fade" id="transactions-approve" role="tabpanel" aria-labelledby="transactions-approve-tab">
                            <div class="app-card app-card-transactions-table mb-5">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">ID Transaksi</th>
                                                    <th class="cell">NIK</th>
                                                    <th class="cell">No. Polisi</th>
                                                    <th class="cell">Tanggal Booking</th>
                                                    <th class="cell">Tanggal Ambil</th>
                                                    <th class="cell">Tanggal Kembali</th>
                                                    <th class="cell">Kekurangan</th>
                                                    <th class="cell">Status</th>
                                                    <th class="cell">Aksi</th> <!-- Kolom Aksi -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions['Approve'] as $trans): ?>
                                                    <tr>
                                                        <td class="cell"><?php echo $trans['id_transaksi']; ?></td>
                                                        <td class="cell"><?php echo $trans['nik']; ?></td>
                                                        <td class="cell"><?php echo $trans['nopol']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_booking']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_ambil']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_kembali']; ?></td>
                                                        <td class="cell"><?php echo 'Rp' . number_format($trans['kekurangan'], 2); ?></td>
                                                        <td class="cell"><span class="badge bg-warning"><?php echo $trans['status']; ?></span></td>
                                                        <td class="cell">
                                                            <form method="POST" action="">
                                                                <input type="hidden" name="id_transaksi" value="<?php echo $trans['id_transaksi']; ?>">
                                                                <input type="hidden" name="new_status" value="Ambil">
                                                                <button type="submit" name="update_status" class="btn btn-sm btn-primary">Ambil</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Ambil -->
                        <div class="tab-pane fade" id="transactions-ambil" role="tabpanel" aria-labelledby="transactions-ambil-tab">
                            <div class="app-card app-card-transactions-table mb-5">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 text-left">
                                            <thead>
                                                <tr>
                                                    <th class="cell">ID Transaksi</th>
                                                    <th class="cell">NIK</th>
                                                    <th class="cell">No. Polisi</th>
                                                    <th class="cell">Tanggal Booking</th>
                                                    <th class="cell">Tanggal Ambil</th>
                                                    <th class="cell">Tanggal Kembali</th>
                                                    <th class="cell">Kekurangan</th>
                                                    <th class="cell">Status</th>
                                                    <th class="cell">Aksi</th> <!-- Kolom Aksi -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions['Ambil'] as $trans): ?>
                                                    <tr>
                                                        <td class="cell"><?php echo $trans['id_transaksi']; ?></td>
                                                        <td class="cell"><?php echo $trans['nik']; ?></td>
                                                        <td class="cell"><?php echo $trans['nopol']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_booking']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_ambil']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_kembali']; ?></td>
                                                        <td class="cell"><?php echo 'Rp' . number_format($trans['kekurangan'], 2); ?></td>
                                                        <td class="cell"><span class="badge bg-success"><?php echo $trans['status']; ?></span></td>
                                                        <td class="cell">
                                                            <form method="POST" action="">
                                                                <input type="hidden" name="id_transaksi" value="<?php echo $trans['id_transaksi']; ?>">
                                                                <input type="hidden" name="new_status" value="Kembali">
                                                                <button type="submit" name="update_status" class="btn btn-sm btn-danger">Kembali</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Kembali -->
                        <div class="tab-pane fade" id="transactions-kembali" role="tabpanel" aria-labelledby="transactions-kembali-tab">
                            <div class="app-card app-card-transactions-table mb-5 w-100">
                                <div class="app-card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 text-left w-100" >
                                            <thead>
                                                <tr>
                                                    <th class="cell">ID Transaksi</th>
                                                    <th class="cell">NIK</th>
                                                    <th class="cell">No. Polisi</th>
                                                    <th class="cell">Tanggal Booking</th>
                                                    <th class="cell">Tanggal Ambil</th>
                                                    <th class="cell">Tanggal Kembali</th>
                                                    <th class="cell">Kekurangan</th>
                                                    <th class="cell">Status</th>
                                                    <th class="cell">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($transactions['Kembali'] as $trans): ?>
                                                    <tr>
                                                        <td class="cell"><?php echo $trans['id_transaksi']; ?></td>
                                                        <td class="cell"><?php echo $trans['nik']; ?></td>
                                                        <td class="cell"><?php echo $trans['nopol']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_booking']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_ambil']; ?></td>
                                                        <td class="cell"><?php echo $trans['tgl_kembali']; ?></td>
                                                        <td class="cell"><?php echo 'Rp' . number_format($trans['kekurangan'], 2); ?></td>
                                                        <td class="cell"><span class="badge bg-secondary"><?php echo $trans['status']; ?></span></td>
                                                        <td class="cell">
                                                        <a href="form-kembali.php?id_transaksi=<?php echo $trans['id_transaksi']; ?>" class="btn-sm app-btn-danger">Cek Mobil</a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
