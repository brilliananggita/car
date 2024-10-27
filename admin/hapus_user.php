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
if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];

    // Query untuk menghapus user
    $sql = "DELETE FROM tb_user WHERE id_user='$id_user'";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('User berhasil dihapus!'); window.location='admin.php';</script>";
    } else {
        echo "Error deleting record: " . $koneksi->error;
    }

    $koneksi->close();
}
?>
