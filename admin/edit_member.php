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


// Mengambil NIK dari parameter URL
if (isset($_GET['nik'])) {
    $nik = $_GET['nik'];

    // Query untuk mengambil data member berdasarkan NIK
    $sql = "SELECT * FROM tb_member WHERE nik = '$nik'";
    $result = $koneksi->query($sql);

    // Memeriksa apakah member ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Member tidak ditemukan.");
    }
} else {
    die("NIK tidak ditemukan.");
}

// Memeriksa apakah data dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $username = $_POST['username'];

    // Query untuk memperbarui data member
    $sql = "UPDATE tb_member SET nama='$nama', jk='$jk', telp='$telp', alamat='$alamat', username='$username' WHERE nik='$nik'";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data member berhasil diperbarui!'); window.location.href='admin.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
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
                <h2 class="text-center mb-4">Edit Member</h2>
        <form action="" method="POST">
            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" value="<?php echo $row['nik']; ?>" disabled>
            </div>
            <input type="hidden" name="nik" value="<?php echo $row['nik']; ?>"> <!-- Menyimpan NIK untuk digunakan saat update -->

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $row['nama']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="jk" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jk" name="jk" required>
                    <option value="L" <?php echo $row['jk'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="P" <?php echo $row['jk'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="telp" class="form-label">Telepon</label>
                <input type="text" class="form-control" id="telp" name="telp" value="<?php echo $row['telp']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo $row['alamat']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="data-member.php" class="btn btn-secondary">Kembali</a>
        </form>
                    
                </section>


            </main>
        </div>
    </div>

    </script>
</body>
</html>
