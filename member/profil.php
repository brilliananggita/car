
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
session_start();
include '../koneksi.php';

// Check if the user is logged in and has the role of member
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'member') {
    // Redirect to login if not logged in or not a member
    header('Location: ../akun/login.php');
    
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

    ?>

    <div class="container mx-auto p-6">
        <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex items-center p-6">
                <!-- Foto Profil -->
                <div class="w-24 h-24 rounded-full overflow-hidden">
                    <?php if (!empty($member['foto'])): ?>
                        <img src="../img/<?php echo $member['foto']; ?>" alt="Foto Profil" class="w-full h-full object-cover">
                    <?php else: ?>
                        <img src="../img/pp.png" alt="Foto Default" class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-gray-800"><?php echo htmlspecialchars($member['nama']); ?></h1>
                    <p class="text-gray-600">@<?php echo htmlspecialchars($member['username']); ?></p>
                   
                </div>
            </div>
            <div class="p-6 border-t">
                <h2 class="text-lg font-semibold text-gray-700">Informasi Pribadi</h2>
                <p class="text-gray-600">NIK: <?php echo htmlspecialchars($member['nik']); ?></p>
                <p class="text-gray-600">No Telepon: <?php echo htmlspecialchars($member['telp']); ?></p>
                <p class="text-gray-600">Jenis Kelamin: <?php echo htmlspecialchars($member['jk']); ?></p>
                <p class="text-gray-600">Alamat: <?php echo htmlspecialchars($member['alamat']); ?></p>
               
            </div>
            <div class="p-6 border-t text-center">
                <a href="../landing.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>
