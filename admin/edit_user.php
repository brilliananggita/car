<?php
session_start();
include '../koneksi.php';

// Check if the user is logged in and has the role of admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../akun/login.php');
    exit;
}

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Check if id_user is set in GET request
if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];

    // Query to get user data
    $query = "SELECT * FROM tb_user WHERE id_user='$id_user'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $role = $user['role'];
    } else {
        echo "Data user tidak ditemukan.";
        exit;
    }
} else {
    echo "ID user tidak ditemukan.";
    exit;
}

if (isset($_POST['submit'])) {
    $id_user = $_POST['id_user'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $username = $koneksi->real_escape_string($username);
    $role = $koneksi->real_escape_string($role);

    $sql = "UPDATE tb_user SET username='$username', role='$role' WHERE id_user='$id_user'";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data user berhasil diupdate!'); window.location='admin.php';</script>";
    } else {
        echo "Error updating record: " . $koneksi->error;
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
        aside a { color: white; text-decoration: none; }
        aside a:hover { color: #f5f5f5; }
        .table-custom td, .table-custom th { font-size: 0.875rem; }
        .table-custom th { background-color: #4287f5; color: white; }
        .table-custom td, .table-custom th { border: 1px solid #dee2e6; }
        .table-custom { border-collapse: collapse; background-color: white; }
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
            <header class="bg-purple-700 text-white p-2 shadow flex justify-between items-center fixed w-full" style="left: 9rem; top: 0;">
                <h2 class="text-xl font-semibold">Selamat Datang, Admin</h2>
                <div class="absolute flex items-center space-x-2" style="left: 28cm;">
                    <img src="../img/pp.png" alt="Foto Profil" class="w-10 h-10 rounded-full">
                </div>
            </header>

            <main class="flex-1 p-6 mt-16">
                <section id="dashboard" class="mb-10">
                    <h2 class="mb-4">Edit User</h2>
                    <form method="post" action="">
                        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin" <?php if(isset($role) && $role == 'admin') echo 'selected'; ?>>Admin</option>
                                <option value="petugas" <?php if(isset($role) && $role == 'petugas') echo 'selected'; ?>>Petugas</option>
                            </select>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
