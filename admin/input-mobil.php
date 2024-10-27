<?php
session_start();
include '../koneksi.php';

// Check if the user is logged in and has the role of petugas
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login if not logged in or not a petugas
    header('Location: ../akun/login.php');
    exit;
}
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
if (isset($_POST['submit'])) {
    include '../koneksi.php';

    $nopol = $_POST['nopol'];
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $tahun = $_POST['tahun'];
    $harga = $_POST['harga'];
    $foto = $_POST['foto'];
    $status = 'tersedia'; // Status default mobil adalah tersedia

    $sql = "INSERT INTO tb_mobil (nopol, brand, type, tahun, harga, foto, status) 
            VALUES ('$nopol', '$brand', '$type', '$tahun', '$harga', '$foto', '$status')";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Mobil berhasil ditambahkan!'); window.location.href='data-mobil.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Tambah Mobil</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="nopol" class="form-label">Nomor Polisi</label>
                <input type="text" class="form-control" id="nopol" name="nopol" required>
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe</label>
                <input type="text" class="form-control" id="type" name="type" required>
            </div>
            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="number" class="form-control" id="tahun" name="tahun" min="1900" max="2099" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga Sewa per Hari</label>
                <input type="text" class="form-control" id="harga" name="harga" required>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto (URL)</label>
                <input type="text" class="form-control" id="foto" name="foto" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
