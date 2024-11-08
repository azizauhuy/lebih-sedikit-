<?php
session_start();
include 'db.php'; // Koneksi ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim_nidn = $_POST['nim_nidn'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE nim_nidn = ? LIMIT 1");
    $stmt->bind_param("s", $nim_nidn);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        // Arahkan pengguna ke halaman berdasarkan perannya
        switch ($user['role']) {
            case 'mahasiswa':
                header('Location: dashboard.php'); // Halaman untuk mahasiswa
                break;
            case 'prodi':
                header('Location: prodi.php'); // Halaman untuk prodi
                break;
            case 'staff':
                header('Location: staff.php'); // Halaman untuk staff
                break;
            default:
                header('Location: login.php'); // Halaman default jika peran tidak dikenali
                break;
        }
        exit();
    } else {
        $error = "NIM/NIDN atau Password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            margin: 100px auto;
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
        input[type="password"] {
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
        .link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form method="POST" action="">
            <input type="text" name="nim_nidn" placeholder="NIM/NIDN" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <div class="link">
            <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>
</body>
</html>