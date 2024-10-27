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
// Mengambil NIK dari parameter URL
if (isset($_GET['nik'])) {
    $nik = $_GET['nik'];

    // Cek apakah ada transaksi yang terkait
    $checkTransaksiSql = "SELECT * FROM tb_transaksi WHERE nik = '$nik'";
    $result = $koneksi->query($checkTransaksiSql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Tidak dapat menghapus member. Terdapat transaksi yang terkait!'); window.location.href='petugas.php';</script>";
    } else {
        // Query untuk menghapus member berdasarkan NIK
        $sql = "DELETE FROM tb_member WHERE nik = '$nik'";
        if ($koneksi->query($sql) === TRUE) {
            echo "<script>alert('Member berhasil dihapus!'); window.location.href='petugas.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    }
} else {
    echo "NIK tidak ditemukan!";
}

$koneksi->close();
?>
