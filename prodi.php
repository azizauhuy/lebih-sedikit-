<?php
session_start();
include 'db.php'; // Koneksi ke database

// Cek apakah user adalah prodi
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'prodi') {
    header('Location: login.php');
    exit();
}

// Ambil semua pengajuan dari semua mahasiswa
$stmt = $conn->prepare("SELECT s.*, u.name as student_name FROM submissions s JOIN users u ON s.user_id = u.id WHERE s.status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
$submissions = $result->fetch_all(MYSQLI_ASSOC);

// Proses form jika ada
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $submission_id = $_POST['submission_id'];
    $status = $_POST['status'];
    $rejection_reason = null;

    if ($status === 'rejected') {
        $rejection_reason = $_POST['rejection_reason'];
    }

    $stmt = $conn->prepare("UPDATE submissions SET status = ?, rejection_reason = ? WHERE id = ?");
    $stmt->bind_param("ssi", $status, $rejection_reason, $submission_id);
    $stmt->execute();

    // Logout setelah proses
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleksi Pengajuan Judul</title>
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
        .actions {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .accept {
            background-color: #5cb85c;
            color: white;
        }
        .reject {
            background-color: #d9534f;
            color: white;
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pilih Pengajuan Judul</h1>
        <table>
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Judul</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($submissions as $submission): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($submission['student_name']); ?></td>
                        <td><?php echo htmlspecialchars($submission['title']); ?></td>
                        <td><?php echo htmlspecialchars($submission['status']); ?></td>
                        <td>
                            <div class="actions">
                                <form method="POST" action="">
                                    <input type="hidden" name="submission_id" value="<?php echo $submission['id']; ?>">
                                    <select name="status" required>
                                        <option value="accepted">Terima</option>
                                        <option value="rejected">Tolak</option>
                                    </select>
                                    <input type="text" name="rejection_reason" placeholder="Alasan (jika ditolak)" style="display:none;">
                                    <button type="submit" class="accept">Proses</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Handle showing the rejection reason field based on the selected status
        document.querySelectorAll('select[name="status"]').forEach(select => {
            select.addEventListener('change', function() {
                const rejectionReasonInput = this.closest('form').querySelector('input[name="rejection_reason"]');
                if (this.value === 'rejected') {
                    rejectionReasonInput.style.display = 'inline';
                    rejectionReasonInput.required = true;
                } else {
                    rejectionReasonInput.style.display = 'none';
                    rejectionReasonInput.required = false;
                }
            });
        });
    </script>
</body>
</html>