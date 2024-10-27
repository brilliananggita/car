<?php
session_start();
include '../koneksi.php';

// Cek login petugas
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'petugas') {
    header('Location: ../akun/login.php');
    exit;
}
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil id_transaksi
$id_transaksi = isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : null;
$detail_transaksi = null;

if ($id_transaksi) {
    // Ambil data transaksi termasuk kekurangan dan foto mobil
    $sql = "SELECT t.*, m.nopol, m.status, CONCAT('../img/', m.foto) AS foto, m.harga, t.kekurangan 
            FROM tb_transaksi t
            JOIN tb_mobil m ON t.nopol = m.nopol
            WHERE t.id_transaksi = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id_transaksi);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $detail_transaksi = $result->fetch_assoc();
    }
}

// Proses form pengembalian
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tgl_kembali = $_POST['tgl_kembali'];
    $kondisi_mobil = $_POST['kondisi_mobil'];
    $biaya_tambahan = $_POST['biaya_tambahan'];
    $denda = $_POST['denda'];

    // Simpan data pengembalian ke tb_kembali
    $sql_insert = "INSERT INTO tb_kembali (id_transaksi, tgl_kembali, kondisi_mobil, biaya_tambahan, denda) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $koneksi->prepare($sql_insert);
    $stmt_insert->bind_param("issii", $id_transaksi, $tgl_kembali, $kondisi_mobil, $biaya_tambahan, $denda);
    $stmt_insert->execute();

    // Update status mobil menjadi "tersedia"
    $sql_update_mobil = "UPDATE tb_mobil SET status = 'tersedia' WHERE nopol = ?";
    $stmt_update_mobil = $koneksi->prepare($sql_update_mobil);
    $stmt_update_mobil->bind_param("s", $detail_transaksi['nopol']);
    $stmt_update_mobil->execute();

    echo "<script>alert('Data pengembalian berhasil disimpan dan status mobil diubah menjadi tersedia.'); window.location.href='petugas.php';</script>";
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
    <style>
        aside a { color: white; text-decoration: none; }
        aside a:hover { color: #f5f5f5; }
        .table-custom td, .table-custom th { font-size: 0.875rem; }
        .table-custom th { background-color: #4287f5; color: black; }
        .table-custom td { color: black; }
        img { width: 7cm; height: auto; }
    </style>
    <script>
     function hitungTotalDenda() {
            const hargaSewaPerHari = parseFloat(<?php echo $detail_transaksi ? $detail_transaksi['harga'] : 0; ?>);
            const kekurangan = parseFloat(<?php echo $detail_transaksi ? $detail_transaksi['kekurangan'] : 0; ?>);
            const tglKembaliSeharusnya = new Date('<?php echo $detail_transaksi ? $detail_transaksi['tgl_kembali'] : ''; ?>');
            const tglKembali = new Date(document.querySelector('input[name="tgl_kembali"]').value);
            const biayaTambahan = parseFloat(document.querySelector('input[name="biaya_tambahan"]').value) || 0;

            // Hitung denda berdasarkan selisih hari
            const timeDiff = tglKembali - tglKembaliSeharusnya;
            const selisihHari = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            let denda = selisihHari > 0 ? selisihHari * hargaSewaPerHari : 0;
            document.querySelector('input[name="denda"]').value = denda;

            // Hitung total semua (denda + kekurangan + biaya tambahan)
            const totalSemua = denda + kekurangan + biayaTambahan;
            document.querySelector('input[name="total_semua"]').value = totalSemua;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="tgl_kembali"]').addEventListener('change', hitungTotalDenda);
            document.querySelector('input[name="biaya_tambahan"]').addEventListener('input', hitungTotalDenda);
        });
    </script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex">
        <aside class="w-65 bg-purple-700 text-white min-h-screen fixed flex flex-col">
            <div class="p-6">
                <img src="../img/logo.png" alt="Rental Car Logo" class="w-16 h-16 rounded-full">
            </div>
            <nav class="mt-9 flex-grow">
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Dashboard</a>
                <!-- Other sidebar items -->
            </nav>
            <footer class="p-6">
                <a href="../akun/logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Logout</a>
            </footer>
        </aside>

        <div class="flex-1 flex flex-col ml-36">
            <header class="bg-purple-700 text-white p-2 shadow flex justify-between items-center fixed w-full" style="left: 9rem; top: 0;">
                <h2 class="text-xl font-semibold">Selamat Datang, Petugas</h2>
            </header>
            <main class="flex-1 p-6 mt-16">
                <section>
                    <div class="container mt-5">
                        <div class="row">
                            <!-- Detail Transaksi -->
                            <div class="col-md-6">
                                <h2>Detail Transaksi</h2>
                                <?php if ($detail_transaksi): ?>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <p><strong>No Polisi:</strong> <?php echo $detail_transaksi['nopol']; ?></p>
                                            <p><strong>Tanggal Ambil:</strong> <?php echo $detail_transaksi['tgl_ambil']; ?></p>
                                            <p><strong>Tanggal Kembali Seharusnya:</strong> <?php echo $detail_transaksi['tgl_kembali']; ?></p>
                                            <p><strong>Harga Sewa Per Hari:</strong> Rp <?php echo number_format($detail_transaksi['harga'], 0, ',', '.'); ?></p>
                                            <p><strong>Kekurangan:</strong> Rp <?php echo number_format($detail_transaksi['kekurangan'], 0, ',', '.'); ?></p>
                                            <img src="<?php echo $detail_transaksi['foto']; ?>" alt="Gambar Mobil" class="img">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-danger">Detail transaksi tidak ditemukan.</div>
                                <?php endif; ?>
                            </div>

                            <!-- Form Pengembalian Mobil -->
                        <div class="col-md-6">
                            <h2>Form Pengembalian Mobil</h2>
                            <form action="" method="POST">
                                <input type="hidden" name="id_transaksi" value="<?php echo $id_transaksi; ?>">
                                <div class="mb-3">
                                    <label for="tgl_kembali" class="form-label">Tanggal Kembali:</label>
                                    <input type="date" class="form-control" id="tgl_kembali" name="tgl_kembali" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kondisi_mobil" class="form-label">Kondisi Mobil:</label>
                                    <input type="text" class="form-control" id="kondisi_mobil" name="kondisi_mobil" required>
                                </div>
                                <div class="mb-3">
                                    <label for="biaya_tambahan" class="form-label">Biaya Tambahan:</label>
                                    <input type="number" class="form-control" id="biaya_tambahan" name="biaya_tambahan" value="0">
                                </div>
                                <div class="mb-3">
                                    <label for="denda" class="form-label">Denda:</label>
                                    <input type="number" class="form-control" id="denda" name="denda" value="0" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="total_semua" class="form-label">Total Semua:</label>
                                    <input type="number" class="form-control" id="total_semua" name="total_semua" value="0" readonly>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Pengembalian</button>
                            </form>
                        </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
