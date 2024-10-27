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

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $username = $_POST['username'];
    $role = $_POST['role'];
    $pass = $_POST['pass'];

    // Query to add user data
    $sql = "INSERT INTO tb_user (username, role, pass) VALUES ('$username', '$role', '$pass')";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
              alert('User berhasil ditambahkan!');
              window.location.href='admin.php';
            </script>";
    } else {
        echo "<script>
              alert('Error: Gagal menambahkan user!');
              window.location.href='admin.php';
            </script>";
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
        
        /* Adjust font size for report section */
        .table-custom td, .table-custom th {
            font-size: 0.875rem; /* Smaller font size for reports */
        }

        /* Gaya untuk tabel custom */
        .table-custom th {
            background-color: #4287f5;
            color: white;
        }
        .table-custom td, .table-custom th {
            border: 1px solid #dee2e6;
        }
        .table-custom {
            border-collapse: collapse;
            background-color: white;
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
                    <img src="../img/pp.png" alt="Foto Profil" class="w-10 h-10 rounded-full">
                </div>
            </header>

            <!-- Main Dashboard -->
            <main class="flex-1 p-6 mt-16">
                <section id="dashboard" class="mb-10">
                    <h2>Tambah User</h2>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Password</label>
                            <input type="password" class="form-control" id="pass" name="pass" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
