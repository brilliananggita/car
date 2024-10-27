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

if(isset($_POST['submit'])){
    include '../koneksi.php';

    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $username = $_POST['username'];
    $pass = md5($_POST['pass']); // Menggunakan MD5 untuk enkripsi password

    // Query untuk menambahkan data member
    $sql = "INSERT INTO tb_member (nik, nama, jk, telp, alamat, username, pass) 
            VALUES ('$nik', '$nama', '$jk', '$telp', '$alamat', '$username', '$pass')";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Member berhasil ditambahkan!'); window.location.href='admin.php';</script>";
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
    <title>Admin Dashboard</title>
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
        <a href="admin.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Dashboard</a>
        <a href="admin.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Pengguna</a>
        <a href="admin.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Member</a>
        <a href="admin.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Laporan</a>
    </nav>
    <footer class="p-6">
        <a href="../akun/logout.php" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Logout</a>
    </footer>
</aside>


        <!-- Main Content -->
        <div class="flex-1 flex flex-col ml-36">
           <!-- Top Bar -->
           <header class="bg-purple-700 text-white p-2 shadow flex justify-between items-center fixed w-full" style="left: 9rem; top: 0;">
            <h2 class="text-xl font-semibold">Selamat Datang, Admin</h2>
            <div class="absolute flex items-center space-x-2" style="left: 28cm;">
                <img src="../img/pp.png" a herf= "profil.php"alt="Foto Profil" class="w-10 h-10 rounded-full" >
            </div>
        </header>


            <!-- Main Dashboard -->
            <main class="flex-1 p-6 mt-16">
                <!-- Dashboard Section -->
                <section id="dashboard" class="mb-10">
                <h2 class="text-center mb-4">Tambah Anggota</h2>
        <form method="post" action="">
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="number" class="form-control" id="nik" name="nik" required>
            </div>
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-3">
                <label for="jk" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jk" name="jk" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="telp" class="form-label">No. Telepon</label>
                <input type="number" class="form-control" id="telp" name="telp" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="pass" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass" name="pass" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            <a href="admin.php" class="btn btn-secondary">Kembali</a>
        </form>
                    
                </section>


            </main>
        </div>
    </div>

    </script>
</body>
</html>
