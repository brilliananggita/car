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
// Memeriksa apakah nopol ada dalam permintaan POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nopol'])) {
    $nopol = $_POST['nopol'];

    // Query untuk menghapus data mobil
    $sql = "DELETE FROM tb_mobil WHERE nopol = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $nopol);
    $stmt->execute();

    header("Location: petugas.php"); // Arahkan kembali setelah menghapus
    exit;
}
?>
