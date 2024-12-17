<?php
session_start(); // Mulai sesi untuk mengambil data yang telah disimpan

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

// Ambil username dari sesi yang sudah login
$usernameUser = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Query untuk mendapatkan data pengguna berdasarkan username dan mengambil calorie_need
$query = "SELECT calorie_need, group_name FROM data_user WHERE username = '$usernameUser' LIMIT 1";
$result = $conn->query($query);

// Ambil data golongan dan calorie_need dari hasil query
$golongan = "Tidak Diketahui"; // Default jika tidak ditemukan
$totalKalori = 0; // Default kalori jika tidak ditemukan
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $golongan = $row['group_name'];
    $totalKalori = $row['calorie_need']; // Ambil calorie_need dari database
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(to bottom, #135589 0%, #2A93D5 35%, #3DDAD7 75%, #AED9DA 94%, #EDFAFD 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }

        .container {
            background-color: rgb(217, 217, 217, 0.5);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 800px;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .profile-details {
            padding: 20px;
            border-radius: 10px;
            display: flex;
            flex-flow: row;
            height: 400px;
            width: 760px;
        }

        .detail-item {
            margin: 10px 0;
            margin-bottom: 8px;
            display: flex;
            flex-direction: row;
            background-color: #2A93D5;
            width: 360px;
            color: #fff;
            border-radius: 10px;
            height: 55px;
        }

        .detail-item span {
            font-weight: 1000;
            width: 200px;
            display: flex;
            justify-content: center;
            padding-top: 10px;
        }

        .detail-item p {
            font-weight: 1000;
            width: 200px;
            height: max-content;
            display: flex;
            justify-content: center;
        }

        .menu {
            background-color: #DA5C3D;
        }

        .selengkapnya {
            background-color: #3DDAD7;
            width: 160px;
        }

        .selanjutnya {
            background-color: #2A93D5;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Profil Pengguna</h1>
        </div>
        <div class="profile-details">
            <div style="width: 400px;">
                <div class="detail-item">
                    <span>Berat Badan :</span>
                    <p>70 kg</p>
                </div>
                <div class="detail-item">
                    <span>Tinggi Badan :</span>
                    <p>170 cm</p>
                </div>
                <div class="detail-item">
                    <span>Jenis Kelamin :</span>
                    <p>Laki-laki</p>
                </div>
                <div class="detail-item">
                    <span>Aktivitas Harian:</span>
                    <p>Ringan</p>
                </div>
            </div>
            <div style="background-color: rgb(237, 250, 253,0.4); border-radius: 15px; padding: 20px;">
                <h2 style="margin-bottom:50px ;">ANDA TERMASUK GOLONGAN: <?php echo htmlspecialchars($golongan); ?></h2>
                <h3 style="margin-bottom:60px ;">JUMLAH KALORI YANG DIBUTUHKAN: </h3>
                <h1 style="display: flex; justify-content: end;"><?php echo round($totalKalori) . " kkal"; ?></h1>
            </div>
        </div>
        <div style="display: flex; justify-content: space-evenly;">
            <button class="menu"><a href="Dashboard.php" style="color: #fff;">Kembali</a></button>
            <button class="selengkapnya">Selengkapnya</button>
            <button class="selanjutnya"><a href="output.php" style="color: #fff;">Lanjutkan</a></button>
        </div>
    </div>
</body>

</html>