<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "kalori";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $activity = $_POST['activity'];

    $sql = "INSERT INTO data_user (username, weight, height, age, gender, activity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $weight, $height, $age, $gender, $activity);

    if ($stmt->execute()) {
        header("Location: klasifikasi.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Diri</title>
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

        .form-container {
            background: rgba(217, 217, 217, 0.6);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            width: 400px;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 8px;
            display: flex;
            flex-direction: row;
            background-color: #2A93D5;
            border-radius: 10px;
            height: 55px;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        .form-group input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
            background-color: #135589;
        }

        .radio-group {
            display: flex;
            justify-content: space-between;
        }

        .radio-group label {
            font-weight: bold;
        }

        .radio-group input {
            margin-right: 5px;
        }

        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .form-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 14px;
        }

        .form-buttons .cancel {
            background-color: #ff5f5f;
            color: white;
        }

        .form-buttons .reset {
            background-color: #ffa500;
            color: white;
        }

        .form-buttons .submit {
            background-color: #4caf50;
            color: white;
        }

        .form-buttons button:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Masukan Data Diri</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label style="width: 400px;" for="weight">Berat Badan (kg)</label>
                <input type="number" id="weight" name="weight" min="0" placeholder="Masukkan berat badan" required>
            </div>
            <div class="form-group">
                <label style="width: 400px;" for="height">Tinggi Badan (cm)</label>
                <input type="number" id="height" name="height" min="0" placeholder="Masukkan tinggi badan" required>
            </div>
            <div class="form-group">
                <label style="width: 400px;" for="age">Usia</label>
                <input type="number" id="age" name="age" min="0" placeholder="Masukkan usia anda" required>
            </div>
            <div class="form-group">
                <label style="width: 150px;">Jenis Kelamin</label>
                <div class="radio-group">
                    <label><input type="radio" name="gender" value="Laki-Laki" required> Laki-Laki</label>
                    <label><input type="radio" name="gender" value="Perempuan" required> Perempuan</label>
                </div>
            </div>
            <div class="form-group">
                <label style="width: 150px;">Aktivitas Harian</label>
                <div class="radio-group">
                    <label><input type="radio" name="activity" value="Ringan" required> Ringan</label>
                    <label><input type="radio" name="activity" value="Sedang" required> Sedang</label>
                    <label><input type="radio" name="activity" value="Berat" required> Berat</label>
                </div>
            </div>
            <div class="form-buttons">
                <button type="button" class="cancel"><a href="login.html" style="color: #fff;">Kembali</a></button>
                <button type="reset" class="reset">Reset</button>
                <button type="submit" class="submit">Submit</button>
            </div>
        </form>
    </div>
</body>

</html>