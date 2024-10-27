<?php
session_start();
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

if (isset($_GET['id_kembali'])) {
    $id_kembali = $_GET['id_kembali'];

    // Query untuk mendapatkan data dari tb_kembali
    $query = "SELECT id_transaksi, denda, biaya_tambahan FROM tb_kembali WHERE id_kembali = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_kembali);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $id_transaksi = $data['id_transaksi'];
        $denda = $data['denda'];
        $biaya_tambahan = $data['biaya_tambahan'];

        // Query untuk mendapatkan `nopol` dari `tb_transaksi`
        $transaksi_query = "SELECT nopol, kekurangan FROM tb_transaksi WHERE id_transaksi = ?";
        $transaksi_stmt = $koneksi->prepare($transaksi_query);
        $transaksi_stmt->bind_param("i", $id_transaksi);
        $transaksi_stmt->execute();
        $transaksi_result = $transaksi_stmt->get_result();

        if ($transaksi_result->num_rows > 0) {
            $transaksi_data = $transaksi_result->fetch_assoc();
            $nopol = $transaksi_data['nopol'];
            $kekurangan = $transaksi_data['kekurangan'];

            // Query untuk mendapatkan gambar dari `tb_mobil` berdasarkan `nopol`
            $mobil_query = "SELECT foto FROM tb_mobil WHERE nopol = ?";
            $mobil_stmt = $koneksi->prepare($mobil_query);
            $mobil_stmt->bind_param("s", $nopol);
            $mobil_stmt->execute();
            $mobil_result = $mobil_stmt->get_result();

            if ($mobil_result->num_rows > 0) {
                $mobil_data = $mobil_result->fetch_assoc();
                $car_image = "../img/" . $mobil_data['foto'];
            } else {
                // Jika data gambar tidak ditemukan, gunakan gambar default
                $car_image = "../img/default.jpg";
            }
        } else {
            $kekurangan = 0; // Jika data tidak ditemukan, anggap kekurangan 0
        }

        // Hitung total bayar dengan menambahkan denda, biaya tambahan, dan kekurangan
        $total_bayar = $denda + $biaya_tambahan + $kekurangan;

    } else {
        echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
        exit();
    }
} else {
    echo "<div class='alert alert-danger'>ID Kembali tidak ditemukan.</div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kembali = $_POST['id_kembali'];
    $tgl_bayar = date('Y-m-d'); // Tanggal pembayaran otomatis hari ini
    $status = $_POST['status'];

    // Pastikan total_bayar dihitung dengan benar dari nilai yang sudah ada
    $total_bayar = $denda + $biaya_tambahan + $kekurangan;

    // Insert into tb_bayar
    $insert_query = "INSERT INTO tb_bayar (id_kembali, tgl_bayar, total_bayar, status) VALUES (?, ?, ?, ?)";
    $stmt = $koneksi->prepare($insert_query);
    $stmt->bind_param("isds", $id_kembali, $tgl_bayar, $total_bayar, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Pembayaran berhasil disimpan!'); window.location.href='petugas.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Gagal menyimpan pembayaran: " . $stmt->error . "</div>";
    }
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
        aside a {
            color: white;
            text-decoration: none;
        }
        aside a:hover {
            color: #f5f5f5;
        }
        .table-custom td, .table-custom th {
            font-size: 0.875rem;
        }
        .table-custom th {
            background-color: #4287f5;
            color: black;
        }
        .table-custom td, .table-custom th {
            border: 1px solid #dee2e6;
        }
        .table-custom {
            border-collapse: collapse;
            background-color: white;
        }
        .table-custom td {
            color: black;
        }
        .img-thumbnail {
            width: 300px;
            height: auto;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex">
        <aside class="w-65 bg-purple-700 text-white min-h-screen fixed flex flex-col">
            <div class="p-6">
                <img src="../img/logo.png" alt="Rental Car Logo" class="w-16 h-16 rounded-full">
            </div>
            <nav class="mt-9 flex-grow">
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Dashboard</a>
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Mobil</a>
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Member</a>
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Transaksi</a>
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Mobil Kembali</a>
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Dashboard</a>
                <a href="petugas.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Laporan</a>
            </nav>
            <footer class="p-6">
                <a href="../akun/logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Logout</a>
            </footer>
        </aside>

        <div class="flex-1 flex flex-col ml-36">
            <header class="bg-purple-700 text-white p-2 shadow flex justify-between items-center fixed w-full" style="left: 9rem; top: 0;">
                <h2 class="text-xl font-semibold">Selamat Datang, Petugas</h2>
                <div class="absolute flex items-center space-x-2" style="left: 28cm;">
                    <img src="../img/pp.png" alt="Foto Profil" class="w-10 h-10 rounded-full" >
                </div>
            </header>

            <main class="flex-1 p-6 mt-16">
    <section>
        <h2 class="text-center mb-4">Pembayaran</h2>
        <div class="row">
            <div class="col-md-4 d-flex align-items-center">
                <img src="<?= $car_image ?>" alt="Mobil" class="img-fluid img-thumbnail">
            </div>
            <div class="col-md-8">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">ID Kembali:</label>
                        <p class="form-control-static"><?= $id_kembali ?></p>
                        <input type="hidden" name="id_kembali" value="<?= $id_kembali ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ID Transaksi:</label>
                        <p class="form-control-static"><?= $id_transaksi ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kekurangan:</label>
                        <p class="form-control-static"><?= $kekurangan ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Denda:</label>
                        <p class="form-control-static"><?= $denda ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biaya Tambahan:</label>
                        <p class="form-control-static"><?= $biaya_tambahan ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Total Bayar:</label>
                        <p class="form-control-static"><?= $total_bayar ?></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status:</label>
                        <select name="status" class="form-select" required>
                            <option value="lunas">Lunas</option>
                            <option value="belum lunas">Belum Lunas</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                </form>
            </div>
        </div>
    </section>



            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
