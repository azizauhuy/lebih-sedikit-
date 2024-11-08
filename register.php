<?php
session_start();
include 'db.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $nim_nidn = $_POST['nim_nidn'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = $_POST['role'];

    // Memastikan NIM/NIDN unik
    $stmt = $conn->prepare("SELECT * FROM users WHERE nim_nidn = ?");
    $stmt->bind_param("s", $nim_nidn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "NIM/NIDN sudah terdaftar.";
    } else {
        // Menyimpan data pengguna baru
        $stmt = $conn->prepare("INSERT INTO users (name, nim_nidn, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $nim_nidn, $password, $role);
        
        if ($stmt->execute()) {
            header('Location: login.php'); // Redirect setelah registrasi sukses
            exit();
        } else {
            $error = "Terjadi kesalahan saat mendaftar.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrasi Pengguna</h1>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Nama" required>
            <input type="text" name="nim_nidn" placeholder="NIM/NIDN" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="" disabled selected>-- Pilih Peran --</option>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="prodi">Prodi</option>
                <option value="staff">Staff</option>
            </select>
            <button type="submit">Daftar</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <p style="text-align: center;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>