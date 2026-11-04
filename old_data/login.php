<?php
require 'koneksi_login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="logo2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="login.css">

    <title>Login SIP-OPT</title>
</head>

<body>

<div class="login">
    <div class="login-form">

        <!-- Logo -->
        <img src="logo2.png" class="login-logo" alt="Logo BBPPTP">

        <!-- Judul -->
        <p class="text-center mb-1">
            SIP-OPT BBPPTP MEDAN
        </p>

        <h1 class="h1 text-center">
            LOGIN
        </h1>

        <!-- Form Login -->
        <form method="post">

            <!-- Username -->
            <div class="mb-3">
                <label class="form-label">
                    Username
                </label>

                <input 
                    type="text" 
                    name="username" 
                    class="form-control"
                    placeholder="Masukkan username"
                    required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="form-label">
                    Password
                </label>

                <input 
                    type="password" 
                    name="password" 
                    class="form-control"
                    placeholder="Masukkan password"
                    required>
            </div>

            <!-- Tombol Login -->
            <button 
                type="submit" 
                name="login" 
                class="btn btn-primary w-100">
                Login
            </button>

        </form>

    </div>
</div>

</body>
</html>