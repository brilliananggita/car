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
    header("Location: petugas.php"); // Arahkan kembali setelah berhasil
    exit;
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

                </section>


            


            </main>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




