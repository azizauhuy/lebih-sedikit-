<?php
$host = 'localhost'; // Alamat server database
$db = 'skripsi_db'; // Ganti dengan nama database Anda
$user = 'root'; // Username default di XAMPP
$pass = ''; // Password kosong jika menggunakan XAMPP

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>