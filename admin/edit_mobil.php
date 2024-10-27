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
// Memeriksa apakah nopol ada dalam permintaan GET
if (isset($_GET['nopol'])) {
    $nopol = $_GET['nopol'];
    
    // Ambil data mobil berdasarkan nopol
    $sql = "SELECT * FROM tb_mobil WHERE nopol = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $nopol);
    $stmt->execute();
    $result = $stmt->get_result();
    $mobil = $result->fetch_assoc();

    // Jika data mobil tidak ditemukan, tampilkan pesan
    if (!$mobil) {
        echo "Data tidak ditemukan.";
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses pengeditan data
    $nopol = $_POST['nopol'];
    $brand = $_POST['brand'];
    $type = $_POST['type'];
    $tahun = $_POST['tahun'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    // Mengelola unggah gambar jika ada
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($gambar);

        // Pindahkan file gambar ke direktori target
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            // Update query untuk memasukkan gambar
            $sql = "UPDATE tb_mobil SET brand = ?, type = ?, tahun = ?, harga = ?, status = ?, gambar = ? WHERE nopol = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param('sssssss', $brand, $type, $tahun, $harga, $status, $gambar, $nopol);
        } else {
            echo "Gagal mengunggah gambar.";
            exit;
        }
    } else {
        // Jika tidak ada gambar baru yang diunggah
        $sql = "UPDATE tb_mobil SET brand = ?, type = ?, tahun = ?, harga = ?, status = ? WHERE nopol = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param('ssssss', $brand, $type, $tahun, $harga, $status, $nopol);
    }

    $stmt->execute();
    header("Location: data-mobil.php"); // Arahkan kembali setelah berhasil
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Mobil</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nopol" class="form-label">Nopol</label>
                <input type="text" class="form-control" id="nopol" name="nopol" value="<?= $mobil['nopol']; ?>" disabled>
                <input type="hidden" name="nopol" value="<?= $mobil['nopol']; ?>"> <!-- Hidden field for nopol -->
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?= $mobil['brand']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe</label>
                <input type="text" class="form-control" id="type" name="type" value="<?= $mobil['type']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun</label>
                <input type="DATE" class="form-control" id="tahun" name="tahun" value="<?= $mobil['tahun']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?= $mobil['harga']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <input type="text" class="form-control" id="status" name="status" value="<?= $mobil['status']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Mobil (Opsional)</label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*">
                <?php if (!empty($mobil['gambar'])): ?>
                    <p>Gambar saat ini: <img src="uploads/<?= $mobil['gambar']; ?>" alt="Gambar Mobil" width="100"></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
