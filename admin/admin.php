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
// Menghitung Total Pengguna
$sql_pengguna = "SELECT COUNT(DISTINCT nik) as total_pengguna FROM tb_member"; // Menghitung total user_id yang unik
$result_pengguna = $koneksi->query($sql_pengguna);
$total_pengguna = $result_pengguna->fetch_assoc()['total_pengguna'];

// Menghitung Total Mobil
$sql_mobil = "SELECT COUNT(DISTINCT nopol) as total_mobil FROM tb_mobil"; // Hitung total mobil
$result_mobil = $koneksi->query($sql_mobil);
$total_mobil = $result_mobil->fetch_assoc()['total_mobil'];

/// Menghitung Total Transaksi dari tb_transaksi
$sql_transaksi = "SELECT COUNT(DISTINCT id_transaksi) as total_transaksi FROM tb_transaksi"; // Hitung total transaksi
$result_transaksi = $koneksi->query($sql_transaksi);
$total_transaksi = $result_transaksi->fetch_assoc()['total_transaksi'];



// Menghitung Pendapatan Total
$sql_pendapatan = "SELECT SUM(total_bayar) as total_pendapatan FROM tb_bayar"; // Hitung total pendapatan
$result_pendapatan = $koneksi->query($sql_pendapatan);
$total_pendapatan = $result_pendapatan->fetch_assoc()['total_pendapatan'];

// Memastikan pendapatan tidak kosong
$total_pendapatan = $total_pendapatan ? $total_pendapatan : 0;

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
        <a href="#dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Dashboard</a>
        <a href="#users" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Pengguna</a>
        <a href="#member" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Member</a>
        <a href="#laporan" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-purple-800">Laporan</a>
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
                    <h3 class="text-2xl font-bold text-purple-700 mb-10">Tinjauan Dashboard</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="bg-white shadow-lg rounded-lg p-5">
                            <h4 class="text-gray-700 font-bold">Total Member</h4>
                            <p class="text-3xl font-bold text-purple-700"><?php echo $total_pengguna; ?></p>
                        </div>
                        <div class="bg-white shadow-lg rounded-lg p-5">
                            <h4 class="text-gray-700 font-bold">Total Mobil</h4>
                            <p class="text-3xl font-bold text-purple-700"><?php echo $total_mobil; ?></p>
                        </div>
                        <div class="bg-white shadow-lg rounded-lg p-5">
                            <h4 class="text-gray-700 font-bold">Total Transaksi</h4>
                            <p class="text-3xl font-bold text-purple-700"><?php echo $total_transaksi; ?></p>
                        </div>
                        <div class="bg-white shadow-lg rounded-lg p-5">
                            <h4 class="text-gray-700 font-bold">Pendapatan</h4>
                            <p class="text-3xl font-bold text-purple-700">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></p>
                            <td></td> <!-- Format Rupiah untuk Kekurangan -->
                        </div>
                    
                </section>


                <!-- Users Section -->
                <section id="users" class="mb-10">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-purple-700">Pengguna</h3>
                        <a href="input-user.php" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 no-underline">Tambah Pengguna</a>
                    </div>
                    
                    <div class="bg-white shadow rounded-lg overflow-y-auto max-h-64"> 
                        <?php
                        $sql_user = "SELECT * FROM tb_user";
                        $result_user = $koneksi->query($sql_user);
                        if ($result_user->num_rows > 0) {
                            echo "<table class='table table-bordered table-hover table-striped table-custom'>
                                    <thead>
                                        <tr>
                                            <th>ID User</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            while ($row = $result_user->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["id_user"] . "</td>
                                        <td>" . $row["username"] . "</td>
                                        <td>" . $row["role"] . "</td>
                                        <td>
                                            <a href='edit_user.php?id_user=" . $row["id_user"] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='hapus_user.php?id_user=" . $row["id_user"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus user ini?\")'>Hapus</a>
                                        </td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<div class='alert alert-warning'>Tidak ada data user yang ditemukan.</div>";
                        }
                        ?>
                    </div>
                </section>

                <!-- member Section -->
                <section id="member" class="mb-10">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-purple-700">Daftar Member</h3>
                        <a href="input-member.php" class="bg-purple-700 text-white px-4 py-2 rounded hover:bg-purple-800 no-underline">Tambah Member</a>
                    </div>
                    <div class="bg-white shadow rounded-lg overflow-y-auto max-h-64">
                        <?php
                        $sql_member = "SELECT * FROM tb_member";
                        $result_member = $koneksi->query($sql_member);
                        if ($result_member->num_rows > 0) {
                            echo "<table class='table table-bordered table-hover table-striped table-custom'>
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Username</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            while ($row = $result_member->fetch_assoc()) {
                                echo "<tr>
                                        <td>" . $row["nik"] . "</td>
                                        <td>" . $row["nama"] . "</td>
                                        <td>" . $row["jk"] . "</td>
                                        <td>" . $row["telp"] . "</td>
                                        <td>" . $row["alamat"] . "</td>
                                        <td>" . $row["username"] . "</td>
                                        <td>
                                            <a href='edit_member.php?nik=" . $row["nik"] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='hapus_member.php?nik=" . $row["nik"] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus member ini?\")'>Hapus</a>
                                        </td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<div class='alert alert-warning'>Tidak ada data member yang ditemukan.</div>";
                        }
                        ?>
                    </div>
                </section>

                <!-- laporan Section -->
                <section id="laporan" class="mb-10">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-purple-700">Laporan</h3>
                    
                    </div>
                    <div class="bg-white shadow rounded-lg overflow-y-auto max-h-64"> 
                    <?php
                        $sql = "SELECT * FROM tb_bayar";
                        $result = $koneksi->query($sql);

                        if ($result->num_rows > 0) {
                            $i = 1; // Inisialisasi nomor urut
            
                            echo "<table class='table table-bordered table-hover table-striped table-custom'>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Kembali</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Total Bayar</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            while($row = $result->fetch_assoc()) {
                                // Tentukan kelas berdasarkan status
                                $status_class = ($row["status"] === "belum lunas") ? "text-red-500" : "text-green-500";
                
                                echo "<tr>
                                        <td>" . $i++ . "</td> <!-- Menampilkan nomor urut -->
                                        <td>" . $row["id_kembali"] . "</td>
                                        <td>" . $row["tgl_bayar"] . "</td>
                                        <td>Rp " . number_format($row["total_bayar"], 2, ',', '.') . "</td> 
                                        <td class='$status_class'>" . $row["status"] . "</td>
                                      </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Tidak ada data pembayaran</td></tr>";
                        }
                        ?>
                        
                       
                    </div>
                </section>

            </main>
        </div>
    </div>

   
</body>
</html>
