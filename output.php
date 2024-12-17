<?php
session_start();

// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'kalori';
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Reset kalori jika tombol reset ditekan
if (isset($_POST['reset'])) {
    $_SESSION['total_kalori'] = 0; // Mengatur total kalori menjadi 0
    header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman setelah reset
    exit();
}

// Ambil data total kalori dari sesi
$totalKaloriHarian = $_SESSION['total_kalori'] ?? 0;
$currentGolongan = $_SESSION['golongan'] ?? "Tidak Diketahui";

// Ambil data kebutuhan kalori harian pengguna
$usernameUser = $_SESSION['username'] ?? '';
$query = "SELECT calorie_need FROM data_user WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $usernameUser);
$stmt->execute();
$result = $stmt->get_result();

$kebutuhanKalori = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $kebutuhanKalori = $row['calorie_need'] ?? 0;
}

$sisaKalori = $kebutuhanKalori - $totalKaloriHarian;
$sisaKalori = max($sisaKalori, 0); // Tidak boleh negatif
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kalkulasi Kalori</title>
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
            background-color: rgba(217, 217, 217, 0.6);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 800px;
            height: 500px;
            text-align: center;
            padding: 20px;
            display: flex;
            flex-flow: column;
            justify-content: space-evenly;
        }

        .star {
            font-size: 2em;
            color: #fbc02d;
            margin: 10px 0;
        }

        .text {
            font-size: 18px;
            color: white;
            margin: 10px 0;
        }

        .calorie {
            font-size: 28px;
            font-weight: bold;
            color: white;
            margin: 20px 0;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button.primary {
            background-color: #2A93D5;
            color: #fff;
        }

        .button.secondary {
            background-color: #3DDAD7;
            color: #fff;
        }

        .button a {
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="star">⭐</div>
        <div class="text">ANDA TERMASUK GOLONGAN: <?php echo htmlspecialchars($currentGolongan); ?></div>
        <div class="text">Jumlah Kalori Dari Makanan Hari ini:</div>
        <div class="calorie"><?php echo round($totalKaloriHarian); ?> kkal</div>
        <div class="text">Jumlah Kalori Harian Yang Dibutuhkan:</div>
        <div class="calorie"><?php echo round($kebutuhanKalori); ?> kkal</div>
        <div class="text">Sisa Kalori yang Bisa Dikonsumsi Hari Ini:</div>
        <div class="calorie"><?php echo round($sisaKalori); ?> kkal</div>
        <div class="buttons">
            <!-- Form untuk Reset Kalori -->
            <form method="post">
                <button type="submit" name="reset" class="button secondary">Reset Kalori</button>
            </form>
            <button class="button primary"><a href="dashboard.php">Selesai</a></button>
        </div>
    </div>
</body>

</html>