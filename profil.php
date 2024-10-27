<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Member</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php
    // Koneksi ke database
    include 'koneksi.php';

    session_start();

    // Memeriksa apakah user sudah login
    if (!isset($_SESSION['username'])) {
        echo "<p class='text-center text-red-500 mt-6'>Anda harus login terlebih dahulu.</p>";
        exit;
    }

    $username = $_SESSION['username'];

    // Mengambil detail member dari database
    $sql = "SELECT * FROM tb_member WHERE username = ?";
    $stmt = $koneksi->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $result->num_rows > 0) {
            $member = $result->fetch_assoc();
        } else {
            echo "<p class='text-center text-red-500 mt-6'>Member tidak ditemukan.</p>";
            exit;
        }
    } else {
        echo "<p class='text-center text-red-500 mt-6'>Gagal mengambil data member.</p>";
        exit;
    }

    // Memeriksa status transaksi penyewaan mobil
    $sql_transaksi = "SELECT * FROM tb_transaksi WHERE nik = ? AND status = 'approved'";
    $stmt_transaksi = $koneksi->prepare($sql_transaksi);
    if ($stmt_transaksi) {
        $stmt_transaksi->bind_param('s', $member['nik']);
        $stmt_transaksi->execute();
        $result_transaksi = $stmt_transaksi->get_result();
        $transaksi = $result_transaksi ? $result_transaksi->fetch_assoc() : null;
    } else {
        $transaksi = null;
    }
    ?>

    <div class="container mx-auto p-6">
        <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex items-center p-6">
                <!-- Foto Profil -->
                <div class="w-24 h-24 rounded-full overflow-hidden">
                    <?php if (!empty($member['foto'])): ?>
                        <img src="../img/<?php echo $member['foto']; ?>" alt="Foto Profil" class="w-full h-full object-cover">
                    <?php else: ?>
                        <img src="img/pp.png" alt="Foto Default" class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($member['nama']); ?></h1>
                    <p class="text-gray-600">@<?php echo htmlspecialchars($member['username']); ?></p>
                    <p class="text-gray-600">NIK: <?php echo htmlspecialchars($member['nik']); ?></p>
                </div>
            </div>
            <div class="p-6 border-t">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Pribadi</h2>
                <p class="text-gray-600">Alamat: <?php echo htmlspecialchars($member['alamat']); ?></p>
                <p class="text-gray-600">No Telepon: <?php echo htmlspecialchars($member['telp']); ?></p>
                <p class="text-gray-600">Jenis Kelamin: <?php echo htmlspecialchars($member['jk']); ?></p>
            </div>
            <div class="p-6 border-t">
                <h2 class="text-lg font-semibold text-gray-700">Status Transaksi Penyewaan Mobil</h2>
                <?php if ($transaksi): ?>
                    <p class="text-gray-600">Status: <span class="text-green-500 font-bold">Disetujui</span></p>
                    <p class="text-gray-600">Tanggal Sewa: <?php echo htmlspecialchars($transaksi['tgl_sewa']); ?></p>
                    <p class="text-gray-600">Tanggal Kembali: <?php echo htmlspecialchars($transaksi['tgl_kembali']); ?></p>
                    <p class="text-gray-600">Total Bayar: Rp<?php echo number_format($transaksi['total_bayar'], 0, ',', '.'); ?></p>
                <?php else: ?>
                    <p class="text-red-500 font-bold">Belum ada transaksi yang disetujui.</p>
                <?php endif; ?>
            </div>
            <div class="p-6 border-t text-center">
                <a href="../akun/logout.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
