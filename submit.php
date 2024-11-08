<?php
session_start();
include 'db.php'; // Koneksi ke database

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $user_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO submissions (user_id, title, abstract) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $abstract);
    
    if ($stmt->execute()) {
        // Jika pengajuan berhasil, alihkan ke dashboard.php
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Terjadi kesalahan saat mengajukan judul.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Judul</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
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
        textarea {
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
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ajukan Judul</h1>
        <form method="POST" action="">
            <input type="text" name="title" placeholder="Judul" required>
            <textarea name="abstract" placeholder="Abstrak" rows="5" required></textarea>
            <button type="submit">Ajukan</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="message error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p class="message success"><?php echo $success; ?></p>
            <div class="link">
                <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>