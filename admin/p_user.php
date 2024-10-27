<?php 
/// Query untuk mengambil data dari tb_member
$sql = "SELECT * FROM tb_member";
$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
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
} else {
    echo "<tr><td colspan='7' class='text-center'>Tidak ada data member</td></tr>";
}



?>







