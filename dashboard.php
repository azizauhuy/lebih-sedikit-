<?php
session_start();
include 'db.php'; // Koneksi ke database

// Pastikan pengguna sudah login dan perannya adalah 'mahasiswa'
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mahasiswa') {
    header('Location: login.php'); // Arahkan ke halaman login jika bukan mahasiswa
    exit();
}

$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT * FROM submissions WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$submissions = $result->fetch_all(MYSQLI_ASSOC);

// Proses upload bukti pembayaran jika ada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['payment_receipt'])) {
    $submission_id = $_POST['submission_id'];
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
            $stmt->bind_param("si", $file_path, $submission_id);
            $stmt->execute();
            $success_message = "Bukti pembayaran berhasil di-upload.";
        } else {
            $error_message = "Gagal meng-upload bukti pembayaran.";
        }
    } else {
        $error_message = "Format file tidak valid atau terjadi kesalahan saat upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
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
        h2 {
            color: #5cb85c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #5cb85c;
            color: white;
        }
        .status {
            font-weight: bold;
        }
        .accepted {
            color: green;
        }
        .rejected {
            color: red;
        }
        .pending {
            color: orange;
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
        button {
            padding: 5px 10px;
            background-color: #5cb85c;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        .upload-form {
            margin-top: 10px;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard Mahasiswa</h1>
        <h2>Pengajuan Judul</h2>
        
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>

        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Abstrak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['title']); ?></td>
                        <td><?php echo htmlspecialchars($submission['abstract']); ?></td>
                        <td class="status <?php echo htmlspecialchars($submission['status']); ?>">
                            <?php echo htmlspecialchars($submission['status']); ?>
                        </td>
                        <td>
                            <?php if ($submission['status'] === 'accepted'): ?>
                                <form method="POST" class="upload-form" enctype="multipart/form-data">
                                    <input type="hidden" name="submission_id" value="<?php echo $submission['id']; ?>">
                                    <input type="file" name="payment_receipt" required>
                                    <button type="submit">Upload Bukti Pembayaran</button>
                                </form>
                            <?php elseif ($submission['status'] === 'rejected'): ?>
                                <p>Alasan Ditolak: <?php echo htmlspecialchars($submission['rejection_reason']); ?></p>
                            <?php endif; ?>
                            <button onclick="window.location.href='edit.php?id=<?php echo $submission['id']; ?>'">Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="link">
            <a href="submit.php">Ajukan Judul Baru</a>
        </div>
        <form method="POST">
            <button type="submit" name="finish_report">Selesai dan Logout</button>
        </form>
    </div>
</body>
</html>