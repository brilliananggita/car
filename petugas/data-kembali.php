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


// Query untuk mengambil data dari tb_kembali dan tb_transaksi menggunakan JOIN
$sql = "SELECT 
            k.id_kembali,
            k.id_transaksi,
            k.tgl_kembali,
            k.kondisi_mobil,
            k.denda,
            k.biaya_tambahan,
            t.kekurangan, 
            (k.denda + k.biaya_tambahan + t.kekurangan) AS total
        FROM 
            tb_kembali k
        JOIN 
            tb_transaksi t ON k.id_transaksi = t.id_transaksi";

$result = $koneksi->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengembalian Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-custom th {
            background-color: #4287f5; /* Warna biru untuk header tabel */
            color: white; /* Warna teks header tabel menjadi putih */
        }
        .table-custom {
            background-color: white; /* Warna latar belakang tabel isi putih */
        }
    </style>
</head>
<body>
    <div class="container mt-5">

        <?php
        // Memeriksa apakah ada data
        if ($result->num_rows > 0) {
            echo "<table class='table table-bordered table-hover table-striped table-custom'>
        <thead>
            <tr>
                <th>ID Kembali</th>
                <th>ID Transaksi</th>
                <th>Tanggal Kembali</th>
                <th>Kondisi Mobil</th>
                <th>Denda</th>
                <th>Biaya Tambahan</th>
                <th>Kekurangan</th> <!-- Kolom Kekurangan dari tb_transaksi -->
                <th>Total</th>
                <th>Aksi</th> <!-- Kolom untuk aksi -->
            </tr>
        </thead>
        <tbody>";

        // Mengambil data dari setiap baris
        while ($row = $result->fetch_assoc()) {
            // Cek apakah kolom status ada dalam data
            $status = isset($row["status"]) ? $row["status"] : null; // Menggunakan null jika status tidak ada

            // Jika belum dibayar, tampilkan tombol "Bayar"
            if ($status != "dibayar") {
                echo "<tr>
                        <td>" . $row["id_kembali"] . "</td>
                        <td>" . $row["id_transaksi"] . "</td>
                        <td>" . $row["tgl_kembali"] . "</td>
                        <td>" . $row["kondisi_mobil"] . "</td>
                        <td>Rp " . number_format($row["denda"], 2, ',', '.') . "</td> <!-- Format Rupiah untuk Denda -->
                        <td>Rp " . number_format($row["biaya_tambahan"], 2, ',', '.') . "</td> <!-- Format Rupiah untuk Biaya Tambahan -->
                        <td>Rp " . number_format($row["kekurangan"], 2, ',', '.') . "</td> <!-- Format Rupiah untuk Kekurangan -->
                        <td>Rp " . number_format($row["total"], 2, ',', '.') . "</td> <!-- Format Rupiah untuk Total -->
                        <td>
                        <a href='bayar.php?id_kembali=" . $row["id_kembali"] . "' class='btn btn-warning btn-sm'>Bayar</a>
                        </td>
                    </tr>";
            } 
        }
        echo "</tbody></table>";
        } else {
            echo "<div class='alert alert-warning'>Tidak ada data pengembalian yang ditemukan.</div>";
        }
        ?>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
