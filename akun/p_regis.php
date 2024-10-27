<?php
if (isset($_POST['submit'])) {
    include '../koneksi.php';

    $nik = $_POST['nik'];
    $nama = $_POST['nama'];
    $jk = $_POST['jk'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];
    $username = $_POST['username'];
    $pass = md5($_POST['pass']); // Enkripsi password menggunakan MD5

    // Cek apakah NIK atau username sudah digunakan
    $cekNik = "SELECT * FROM tb_member WHERE nik='$nik'";
    $cekUsername = "SELECT * FROM tb_member WHERE username='$username'";
    $resultNik = mysqli_query($koneksi, $cekNik);
    $resultUsername = mysqli_query($koneksi, $cekUsername);

    if (mysqli_num_rows($resultNik) > 0) {
        echo "<script>alert('NIK sudah digunakan! Silakan gunakan NIK lain.'); window.history.back();</script>";
    } elseif (mysqli_num_rows($resultUsername) > 0) {
        echo "<script>alert('Username sudah digunakan! Silakan gunakan username lain.'); window.history.back();</script>";
    } else {
        $sql = "INSERT INTO tb_member (nik, nama, jk, telp, alamat, username, pass) 
                VALUES ('$nik', '$nama', '$jk', '$telp', '$alamat', '$username', '$pass')";

        if (mysqli_query($koneksi, $sql)) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
        }
    }

    mysqli_close($koneksi);
}
?>
