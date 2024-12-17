<?php
session_start(); // Memulai sesi untuk menyimpan data sementara

// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kalori'; // Ganti dengan nama database Anda
$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Periksa apakah form telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirim melalui POST
    $nama = $_POST['nama'];
    $porsi = $_POST['porsi'];

    // Ambil kalori makanan dari database berdasarkan nama
    $query = "SELECT kalori FROM makanan WHERE nama = '$nama'";
    $result = $conn->query($query);
    $kalori = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $kalori = $row['kalori'];
    }

    // Hitung total kalori berdasarkan porsi yang dimasukkan
    $totalKalori = ($kalori / 100) * $porsi; // Kalori per 100 gram, dikalikan dengan porsi

    // Simpan total kalori ke sesi
    if (!isset($_SESSION['total_kalori'])) {
        $_SESSION['total_kalori'] = 0;
    }
    $_SESSION['total_kalori'] += $totalKalori;

    // Tentukan golongan berdasarkan total kalori
    if ($_SESSION['total_kalori'] < 1500) {
        $_SESSION['golongan'] = "GOLONGAN A (Kalori Rendah)";
    } elseif ($_SESSION['total_kalori'] >= 1500 && $_SESSION['total_kalori'] <= 2500) {
        $_SESSION['golongan'] = "GOLONGAN B (Kalori Sedang)";
    } else {
        $_SESSION['golongan'] = "GOLONGAN C (Kalori Tinggi)";
    }

    // Redirect ke halaman profil atau halaman lainnya
    header("Location: profile.php"); // Ganti dengan halaman profil atau tujuan lainnya
    exit();
}
