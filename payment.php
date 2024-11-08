<?php
session_start();
include 'db.php'; // Koneksi ke database

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];

// Ambil pengajuan judul yang diterima
$stmt = $conn->prepare("SELECT * FROM submissions WHERE user_id = ? AND status = 'accepted'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$submission = $result->fetch_assoc(); // Ambil satu pengajuan

// Variabel untuk pesan sukses atau error
$message = "";

// Proses upload bukti pembayaran jika ada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['payment_receipt'])) {
    $file = $_FILES['payment_receipt'];

    // Validasi file
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    if (in_array($file['type'], $allowed_types) && $file['error'] == 0) {
        $upload_dir = 'uploads/'; // Pastikan folder ini ada dan dapat ditulis

        // Cek dan buat folder jika tidak ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_path = $upload_dir . basename($file['name']);

        // Pindahkan file ke direktori upload
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Simpan informasi bukti pembayaran ke database
            $stmt = $conn->prepare("UPDATE submissions SET payment_receipt = ? WHERE id = ?");
            $stmt->bind_param("si", $file_path, $submission['id']);
            $stmt->execute();

            $message = "Bukti pembayaran berhasil di-upload.";

            // Redirect ke dashboard setelah upload berhasil
            header('Location: dashboard.php');
            exit();
        } else {
            $message = "Gagal meng-upload bukti pembayaran.";
        }
    } else {
        $message = "Format file tidak valid atau terjadi kesalahan saat upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran</title>
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
        input[type="file"] {
            margin: 10px 0;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Bukti Pembayaran</h1>
        <?php if ($submission): ?>
            <form method="POST" enctype="multipart/form-data">
                <p>Judul: <?php echo htmlspecialchars($submission['title']); ?></p>
                <input type="file" name="payment_receipt" required>
                <button type="submit">Upload Bukti Pembayaran</button>
            </form>
        <?php else: ?>
            <p>Tidak ada pengajuan judul yang diterima.</p>
        <?php endif; ?>

        <?php if ($message): ?>
            <p class="message <?php echo isset($error) ? 'error' : 'success'; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>