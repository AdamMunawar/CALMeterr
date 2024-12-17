<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "kalori";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM data_user WHERE username = '$username' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $weight = $data['weight'];
    $height = $data['height'];
    $age = $data['age'];
    $gender = $data['gender'];
    $activity = $data['activity'];

    if ($gender == "Laki-Laki") {
        $bmr = 66.5 + (13.75 * $weight) + (5.003 * $height) - (6.75 * $age);
    } else {
        $bmr = 655.1 + (9.563 * $weight) + (1.850 * $height) - (4.676 * $age);
    }

    switch ($activity) {
        case "Ringan":
            $calorie_need = $bmr * 1.2;
            break;
        case "Sedang":
            $calorie_need = $bmr * 1.5;
            break;
        case "Berat":
            $calorie_need = $bmr * 1.9;
            break;
        default:
            $calorie_need = $bmr;
            break;
    }

    if ($gender == "Laki-Laki") {
        if ($calorie_need < 2500) {
            $group = "GOLONGAN A (Kalori Rendah)";
        } elseif ($calorie_need < 3000) {
            $group = "GOLONGAN B (Kalori Sedang)";
        } else {
            $group = "GOLONGAN C (Kalori Tinggi)";
        }
    } else { 
        if ($calorie_need < 2000) {
            $group = "GOLONGAN A (Kalori Rendah)";
        } elseif ($calorie_need < 2500) {
            $group = "GOLONGAN B (Kalori Sedang)";
        } else {
            $group = "GOLONGAN C (Kalori Tinggi)";
        }
    }
    $update_sql = "UPDATE data_user 
                   SET calorie_need = ?, group_name = ? 
                   WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("dsi", $calorie_need, $group, $data['id']);
    $stmt->execute();
} else {
    $group = "Tidak ada data.";
    $calorie_need = 0;
}

$conn->close();
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

        .button.primary:hover {
            background-color: #0056b3;
        }

        .button.secondary {
            background-color: #135589;
            color: #fff;
        }

        .button.secondary:hover {
            background-color: #495057;
        }

        .button a {
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="star">⭐</div>
        <div class="text">ANDA TERMASUK <?= htmlspecialchars($group) ?></div>
        <div class="text">Jumlah Kalori Harian Yang Dibutuhkan:</div>
        <div class="calorie"><?= number_format($calorie_need, 2) ?> kkal</div>
        <div class="buttons">
            <button class="button secondary">Pilih Selengkapnya</button>
            <button class="button primary"><a href="dashboard.php">Lanjutkan</a></button>
        </div>
    </div>
</body>
</html>