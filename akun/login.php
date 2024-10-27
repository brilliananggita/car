<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e4ff; /* Light purple background color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .logo {
            width: 120px; /* Increased logo size */
            margin-bottom: 8px; /* Added more margin to lower it */
        }

        h2 {
            color: #800080; /* Purple color for heading */
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #800080; /* Purple border color */
            border-radius: 4px;
        }

        button {
            width: 100%;
            background-color: #800080; /* Purple button color */
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #5a005a; /* Darker purple on hover */
        }

        .error-message {
            color: red;
            text-align: center;
        }

        .register-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
        }

        .register-link a {
            color: #800080;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <form method="post" action="p_login.php">
        <!-- Logo Image -->
        <img src="../img/logo.png" alt="Logo" class="logo">
        
        <h2>Login</h2>
        
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="pass">Password:</label>
        <input type="password" id="pass" name="pass" required><br>

        <button type="submit" name="submit">Login</button>

        <!-- Error message if login fails -->
        <div class="error-message">
            <?php if (isset($error_message)) echo $error_message; ?>
        </div>

        <!-- Link to registration page -->
        <div class="register-link">
            Belum punya akun? <a href="regis.php">Daftar di sini</a>
        </div>
    </form>

</body>
</html>
