<?php
session_start();
if (isset($_POST['submit'])) {
    include '../koneksi.php';

    $username = $_POST['username'];
    $pass = md5($_POST['pass']); // Enkripsi password menggunakan MD5

    // Query untuk mengecek di tb_user
    $sql_user = "SELECT * FROM tb_user WHERE username = ? AND pass = ?";
    $stmt_user = $koneksi->prepare($sql_user);
    $stmt_user->bind_param("ss", $username, $pass);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        // Jika ditemukan di tb_user
        $row_user = $result_user->fetch_assoc();
        $_SESSION['username'] = $row_user['username'];
        $_SESSION['role'] = $row_user['role'];

        // Redirect berdasarkan peran user di tb_user
        if ($row_user['role'] == 'admin') {
            header("Location: ../admin/admin.php");
            exit();
        } elseif ($row_user['role'] == 'petugas') {
            header("Location: ../petugas/petugas.php");
            exit();
        }
    } else {
        // Jika tidak ditemukan di tb_user, cek di tb_member
        $sql_member = "SELECT * FROM tb_member WHERE username = ? AND pass = ?";
        $stmt_member = $koneksi->prepare($sql_member);
        $stmt_member->bind_param("ss", $username, $pass);
        $stmt_member->execute();
        $result_member = $stmt_member->get_result();

        if ($result_member->num_rows > 0) {
            // Jika ditemukan di tb_member
            $row_member = $result_member->fetch_assoc();
            $_SESSION['username'] = $row_member['username'];
            $_SESSION['role'] = 'member'; // Set role sebagai 'member'
            $_SESSION['nik'] = $row_member['nik']; // Simpan NIK jika perlu

            // Redirect ke halaman member
            header("Location:../member/member.php");
            exit();
        } else {
            // Jika tidak ditemukan di kedua tabel
            $error_message = "Username atau password salah.";
        }
    }

    $stmt_user->close();
    $stmt_member->close();
    mysqli_close($koneksi);
}
?>