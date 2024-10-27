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
        echo "<script>alert('Mobil berhasil ditambahkan!'); window.location.href='petugas.php';</script>";
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
    <title>Petugas Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        
      
    /* Adjust sidebar styles */
    aside a {
        color: white; /* Set text color to white */
        text-decoration: none; /* Remove underline */
    }
    aside a:hover {
        color: #f5f5f5; /* Lighter color on hover */
    }

    /* Adjust font size for report section */
    .table-custom td, .table-custom th {
        font-size: 0.875rem; /* Smaller font size for reports */
    }

    /* Gaya untuk tabel custom */
    .table-custom th {
        background-color: #4287f5; /* Background color for table headers */
        color: black; /* Text color for headers */
    }
    .table-custom td, .table-custom th {
        border: 1px solid #dee2e6; /* Border for table cells */
    }
    .table-custom {
        border-collapse: collapse; /* Collapses borders */
        background-color: white; /* Background color for the table */
    }
    
    /* New rule to set text color to black for all table cells */
    .table-custom td {
        color: black; /* Set text color for table data cells to black */
    }

    /* Image thumbnail style */
    .img-thumbnail {
        width: 100px; /* Adjust image size */
        height: auto; /* Maintain aspect ratio */
    }


    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex">
        <!-- Sidebar -->
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


        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-36">
           <!-- Top Bar -->
           <header class="bg-purple-700 text-white p-2 shadow flex justify-between items-center fixed w-full" style="left: 9rem; top: 0;">
            <h2 class="text-xl font-semibold">Selamat Datang, Petugas</h2>
            <div class="absolute flex items-center space-x-2" style="left: 28cm;">
                <img src="../img/pp.png" a herf= "profil.php"alt="Foto Profil" class="w-10 h-10 rounded-full" >
            </div>
        </header>





            <!-- Main Dashboard -->
            <main class="flex-1 p-6 mt-16">
                <!-- Dashboard Section -->
                <section >
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


                </section>


            


            </main>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




