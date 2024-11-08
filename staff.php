<?php
session_start();
include 'db.php'; // Koneksi ke database

// Cek apakah user adalah staff
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

// Ambil semua pengajuan yang diterima
$stmt = $conn->prepare("SELECT s.*, u.name as student_name FROM submissions s JOIN users u ON s.user_id = u.id WHERE s.status = 'accepted'");
$stmt->execute();
$result = $stmt->get_result();
$accepted_submissions = $result->fetch_all(MYSQLI_ASSOC);

// Proses upload file jika ada
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['letter'])) {
    $submission_id = $_POST['submission_id'];
    $file = $_FILES['letter'];

    // Validasi file
    $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (in_array($file['type'], $allowed_types) && $file['error'] == 0) {
        $upload_dir = 'uploads/'; // Pastikan folder ini ada dan dapat ditulis

        // Cek dan buat folder jika tidak ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Membuat folder jika belum ada
        }

        $file_path = $upload_dir . basename($file['name']);

        // Pindahkan file ke direktori upload
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            // Simpan informasi surat pengantar ke database
            $stmt = $conn->prepare("INSERT INTO letters (submission_id, staff_id, file_path) VALUES (?, ?, ?)");
            $staff_id = $_SESSION['user']['id'];
            $stmt->bind_param("iis", $submission_id, $staff_id, $file_path);
            $stmt->execute();
            $success_message = "Surat pengantar berhasil di-upload.";

            // Arahkan ke halaman staf laporan setelah upload
            header('Location: staff_report.php');
            exit();
        } else {
            $error_message = "Gagal meng-upload file.";
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
    <title>Upload Surat Pengantar</title>
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
        .link {
            text-align: center;
            margin-top: 20px;
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
        <h1>Upload Surat Pengantar</h1>
        <?php if (isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
        <?php if (isset($success_message)) echo "<p class='success'>$success_message</p>"; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Judul</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accepted_submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($submission['title']); ?></td>
                        <td>
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="submission_id" value="<?php echo $submission['id']; ?>">
                                <input type="file" name="letter" required>
                                <button type="submit">Upload Surat</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
       
    </div>
</body>
</html>