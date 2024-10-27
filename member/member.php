<?php
session_start();
include '../koneksi.php';

// Check if the user is logged in and has the role of member
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'member') {
    // Redirect to login if not logged in or not a member
    header('Location: ../akun/login.php');
    exit;
}


// Ambil semua data mobil dari tabel tb_mobil
$sql = "SELECT * FROM tb_mobil";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rent Service</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }
        
        body {
            padding-top: 64px; 
        }

         /* bikin mpbilnya ngambang*/
         @keyframes float {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0);
            }
        }
        /* gerak */
        .animate-float {
            animation: float 9s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white text-gray-800 font-sans">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 flex items-center justify-between p-6 bg-white shadow-md">
        <div class="flex items-center space-x-2">
            <img src="../img/logo.png" alt="logo" class="w-11 h-11">
            <span class="text-2xl font-bold text-purple-700">Rental Mobil</span>
        </div>
        <nav class="space-x-4 flex items-center">
            <a href="#home" class="text-gray-600 hover:text-purple-700">Beranda</a>
            <a href="#cars" class="text-gray-600 hover:text-purple-700">Mobil</a>
            <a href="#transactions" class="text-gray-600 hover:text-purple-700">Riwayat</a>
            <a href="profil.php" class="flex items-center">
                <img src="../img/pp.png" alt="User Profile" class="w-10 h-10 rounded-full border-2 border-purple-700">
            </a>
        </nav>
    </header>

    <!-- Home Section -->
    <section id="home" class="container mx-auto px-6 py-16 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-10 md:mb-0">
            <h1 class="text-5xl font-bold leading-tight text-gray-800 mb-6">
                Sewa Mobil Mudah dan Terjangkau
            </h1>
            <p class="text-gray-500 text-lg mb-8">
                Temukan mobil yang sempurna untuk perjalanan Anda. Harga terjangkau, lokasi yang mudah dijangkau, dan berbagai pilihan mobil yang bisa Anda pilih!
            </p>
            <div class="space-x-4">
                <a href="#cars" class="px-6 py-3 bg-purple-700 text-white rounded-full font-semibold hover:bg-purple-800">Sewa Mobil</a>
            </div>
        </div>
        <div class="md:w-1/2 flex justify-center relative">
            <img src="../img/car.jpg" alt="Car Dashboard" class="animate-float w-full max-w-md">
        </div>
    </section>

    
   <!-- Car Listing Section -->
<section id="cars" class="container mx-auto px-6 py-16">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Daftar Mobil untuk Disewa</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='max-w-xs mx-auto bg-white border border-gray-200 rounded-lg shadow-lg'>";
                echo "<img src='../img/" . ($row['foto'] ? $row['foto'] : 'default.jpg') . "' alt='Gambar Mobil' class='w-full h-48 object-cover rounded-t-lg'>";
                echo "<div class='p-4'>";
                echo "<h3 class='text-lg font-bold mb-2'>" . $row['brand'] . " " . $row['type'] . "</h3>";
                echo "<p class='text-gray-500 mb-2'>Nopol: " . $row['nopol'] . "</p>";
                echo "<p class='text-gray-500 mb-2'>Tahun: " . date('Y', strtotime($row['tahun'])) . "</p>";
                echo "<p class='text-gray-700 font-semibold mb-2'>Harga Sewa: Rp" . number_format($row['harga'], 2, ',', '.') . "/hari</p>";
                echo "<p class='text-" . ($row['status'] == 'tersedia' ? 'green-500' : 'red-500') . " mb-2'>Status: " . ($row['status'] == 'tersedia' ? 'Tersedia' : 'Tidak Tersedia') . "</p>";
                
                if ($row['status'] == 'tersedia') {
                    echo "<a href='form-transaksi.php?nopol=" . $row['nopol'] . "' class='inline-block px-4 py-2 mt-2 text-white bg-green-500 rounded-md hover:bg-green-600'>Sewa</a>";
                } else {
                    echo "<button disabled class='inline-block px-4 py-2 mt-2 text-white bg-gray-500 rounded-md'>Tidak Tersedia</button>";
                }

                echo "</div></div>";
            }
        } else {
            echo "<p class='text-gray-500'>Tidak ada mobil yang tersedia saat ini.</p>";
        }
        ?>
    </div>
</section>



            <!-- Transaction History Section -->
<section id="transactions" class="container mx-auto px-6 py-16">
    <h2 class="text-3xl font-semibold text-gray-800 mb-6">Riwayat Transaksi</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
            
            $nik = $_SESSION['nik']; ;
            $query = "SELECT tb_transaksi.*, tb_mobil.brand, tb_mobil.type, tb_mobil.foto 
                      FROM tb_transaksi
                      JOIN tb_mobil ON tb_transaksi.nopol = tb_mobil.nopol
                      WHERE tb_transaksi.nik = '$nik'";
            $result = $koneksi->query($query);

            //mengecek transaksi dan card riwayat
            if ($result->num_rows > 0) {
                while($tampil = $result->fetch_assoc()) {
                    ?>
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        <img src="../img/<?php echo $tampil['foto']; ?>" alt="Car Image" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold"><?php echo $tampil['brand']; ?></h3>
                            <p class="text-gray-600"><?php echo $tampil['type']; ?></p>
                            <p class="text-gray-800 font-bold">Kekurangan: Rp <?php echo number_format($tampil['kekurangan'], 0, ',', '.'); ?></p>
                            <p class="text-gray-600">Tanggal Kembali: <?php echo $tampil['tgl_kembali']; ?></p>
                            <div class="mt-4">
                                <!-- Transaction status message -->
                                <?php if ($tampil['status'] == 'booking') { ?>
                                    <p class="text-yellow-500">Menunggu Konfirmasi dari Petugas</p>
                                <?php } elseif ($tampil['status'] == 'approve') { ?>
                                    <p class="text-green-500">Sudah Terkonfirmasi, Silahkan Ambil Sesuai Tanggal Ambil</p>
                                <?php } elseif ($tampil['status'] == 'ambil') { ?>
                                    <p class="text-blue-500">Mobil Sudah Diambil, Kembalikan Sesuai Tanggal Kembali</p>
                                <?php } elseif ($tampil['status'] == 'kembali') { ?>
                                    <p class="text-purple-500">Mobil Sudah Dikembalikan</p>
                                <?php } elseif ($tampil['status'] == 'selesai') { ?>
                                    <p class="text-gray-500">Sewa Telah Selesai</p>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php 
                }
            } else {
                echo "<p class='text-gray-500'>Tidak ada riwayat transaksi.</p>";
            }
        ?>
    </div>
</section>

        </div>
    </section>

    <footer class="py-12 bg-gray-100 mt-10 text-center">
        <p class="text-gray-500 text-sm">Car Rent &copy; 2024 - All Rights Reserved</p>
    </footer>
</body>
</html>
