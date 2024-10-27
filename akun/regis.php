<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery CDN untuk menggunakan AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            padding: 20px;
            background-color: #f4e4ff; 
        }
        .card {
            margin-top: 100px; /* Jarak atas dari card */
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            margin-bottom: 2px; /* Jarak bawah label */
        }
        .form-control {
            margin-bottom: 5px; /* Jarak bawah input */
        }
        h2 {
            color: #800080; /* Purple color for heading */
        }
        .btn-purple {
            background-color: #800080; /* Purple color for register button */
            color: white;
        }
        .btn-purple:hover {
            background-color: #5a005a; /* Darker purple on hover */
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow-lg p-3" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-3">Daftar Akun</h2> <!-- Judul -->
        <form method="post" action="p_regis.php">
            <div class="mb-1">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" class="form-control" id="nik" name="nik" required>
                <div id="nikWarning" class="text-danger mt-1" style="display: none;">NIK sudah terdaftar!</div>
            </div>
            <div class="mb-1">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="mb-1">
                <label for="jk" class="form-label">Jenis Kelamin</label>
                <select class="form-select" id="jk" name="jk" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="mb-1">
                <label for="telp" class="form-label">No. Telepon</label>
                <input type="text" class="form-control" id="telp" name="telp" required>
            </div>
            <div class="mb-1">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="2" required></textarea>
            </div>
            <div class="mb-1">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
                <div id="usernameWarning" class="text-danger mt-1" style="display: none;">Username sudah terdaftar!</div>
            </div>
            <div class="mb-1">
                <label for="pass" class="form-label">Password</label>
                <input type="password" class="form-control" id="pass" name="pass" required>
            </div>
            <br>
            <button type="submit" name="submit" class="btn btn-purple w-100">Registrasi</button>
        </form>
        <a href="login.php" class="btn btn-secondary w-100 mt-2">Kembali ke Login</a>
    </div>

    <!-- AJAX untuk cek NIK -->
    <script>
        $(document).ready(function() {
            $('#nik').on('keyup', function() {
                var nik = $(this).val();
                if (nik != '') {
                    $.ajax({
                        url: 'cek_nik.php', // File PHP untuk pengecekan
                        method: 'POST',
                        data: {nik: nik},
                        success: function(response) {
                            if (response == 'exists') {
                                $('#nikWarning').show();
                            } else {
                                $('#nikWarning').hide();
                            }
                        }
                    });
                } else {
                    $('#nikWarning').hide();
                }
            });
        });
    </script>

    <!-- AJAX untuk cek username -->
    <script>
        $(document).ready(function() {
            $('#username').on('keyup', function() {
                var username = $(this).val();
                if (username != '') {
                    $.ajax({
                        url: 'cek_username.php', // File PHP untuk pengecekan
                        method: 'POST',
                        data: {username: username},
                        success: function(response) {
                            if (response == 'exists') {
                                $('#usernameWarning').show();
                            } else {
                                $('#usernameWarning').hide();
                            }
                        }
                    });
                } else {
                    $('#usernameWarning').hide();
                }
            });
        });
    </script>

    <!-- Bootstrap JS & Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
