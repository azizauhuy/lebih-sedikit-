<?php
session_start();
include 'db.php'; // Koneksi ke database

// Cek apakah user adalah staff
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'staff') {
    header('Location: login.php');
    exit();
}

// Hitung jumlah pengajuan judul yang diterima
$stmt = $conn->prepare("SELECT COUNT(*) as accepted_count FROM submissions WHERE status = 'accepted'");
$stmt->execute();
$result = $stmt->get_result();
$accepted_data = $result->fetch_assoc();
$accepted_count = $accepted_data['accepted_count'];

// Hitung jumlah mahasiswa yang telah melakukan pembayaran
$stmt = $conn->prepare("SELECT COUNT(DISTINCT user_id) as paid_count FROM payments");
$stmt->execute();
$result = $stmt->get_result();
$paid_data = $result->fetch_assoc();
$paid_count = $paid_data['paid_count'];

// Proses logout jika tombol ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['finish_report'])) {
    // Hapus sesi pengguna
    session_unset();
    session_destroy();
    header('Location: login.php'); // Arahkan kembali ke halaman login
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi</title>
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
        .report {
            text-align: center;
            margin-top: 20px;
        }
        .link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Rekapitulasi</h1>
        <div class="report">
            <p>Jumlah Pengajuan Judul yang Diterima: <strong><?php echo $accepted_count; ?></strong></p>
            <p>Jumlah Mahasiswa yang Telah Melakukan Pembayaran: <strong><?php echo $paid_count; ?></strong></p>
            <form method="POST">
                <button type="submit" name="finish_report">Selesai dan Logout</button>
            </form>
        </div>
    </div>
</body>
</html>