<?php
session_start();
include '../koneksi.php';

// Check if the user is logged in and has the role of member
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'member') {
    // Redirect to login if not logged in or not a member
    header('Location: ../akun/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Mobil</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* CSS untuk card mobil */
        .card {
            border: 1px solid #ccc;
            padding: 16px;
            margin: 16px;
            border-radius: 8px;
            box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
            display: inline-block;
            width: 200px;
            vertical-align: top;
        }

        .card h3 {
            margin: 0;
            padding: 0;
        }

        .card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .card p {
            margin: 5px 0;
            font-size: 15px;
        }

        .button-sewa {
            background-color: #28a745;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
        }

        .button-sewa:hover {
            background-color: #218838;
        }/* CSS untuk card mobil */
        .card {
            border: 1px solid #ccc;
            padding: 16px;
            margin: 16px;
            border-radius: 8px;
            box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
            display: inline-block;
            width: 200px;
            vertical-align: top;
        }

        .card h3 {
            margin: 0;
            padding: 0;
        }

        .card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .card p {
            margin: 5px 0;
            font-size: 15px;
        }

        .button-sewa {
            background-color: #28a745;
            color: white;
            padding: 10px 16px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 10px;
        }

        .button-sewa:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- kode buat navbar nyaa alias yg atas -->
    <div class="navbar">
        <div class="navbar-title"><button class="toggle-btn" onclick="toggleSidebar()">☰</button> Rental Mobil </div>
        <!-- buat profil nya apa -->
        <div class="profile">
            <a href="profil.php">
                <img src="image/pp.png" alt="Profile" class="profile-img">
            </a>
        </div>
    </div>

    <!-- buat side bar -->
    <div class="sidebar" id="sidebar">
        <ul class="sidebar-menu">
            <li><a href="index.php">Home</a></li>
            <li><a href="mobil.php">Pesan Mobil</a></li>
            <li><a href="riwayat.php">Riwayat Pemesanan</a></li>
            <li><a href="../logout.php">Logout</a></li> <!-- Link untuk logout -->
        </ul>
    </div>

    <div class="content" id="content">
        <!-- Konten utama di sini -->
        <h2>Daftar Mobil untuk Disewa</h2>

<div class="mobil-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card'>";
            echo "<img src='../image/" . ($row['foto'] ? $row['foto'] : '.jpg') . "' alt='Gambar Mobil'>";
            echo "<h3>" . $row['brand'] . " " . $row['type'] . "</h3>";
            echo "<p>Nopol: " . $row['nopol'] . "</p>";
            echo "<p>Tahun: " . date('Y', strtotime($row['tahun'])) . "</p>";
            echo "<p>Harga Sewa: Rp" . number_format($row['harga'], 2, ',', '.') . "/hari</p>";
            echo "<p>Status: " . ($row['status'] == 'tersedia' ? 'Tersedia' : 'Tidak Tersedia') . "</p>";
            
            // Jika mobil tersedia, tampilkan tombol "Sewa"
            if ($row['status'] == 'tersedia') {
                echo "<a href='form-transaksi.php?nopol=" . $row['nopol'] . "' class='button-sewa'>Sewa</a>";
            } else {
                echo "<button disabled class='button-sewa' style='background-color: grey;'>Tidak Tersedia</button>";
            }

            echo "</div>";
        }
    } else {
        echo "<p>Tidak ada mobil yang tersedia saat ini.</p>";
    }
    ?>
      
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            sidebar.classList.toggle('active');
            content.classList.toggle('active'); // Menambahkan kelas aktif pada konten
        }
    </script>
</body>
</html>


<?php
$koneksi->close(); // Menutup koneksi database
?>
