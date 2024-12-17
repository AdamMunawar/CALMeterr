<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Makanan</title>
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
            background: rgba(217, 217, 217, 0.6);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            width: 600px;
            height: 400px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 80%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .food-list,
        .food-details {
            display: inline-block;
            vertical-align: top;
        }

        .food-list {
            width: 45%;
            margin-right: 5%;
            max-height: 300px;
            /* Menentukan tinggi maksimum untuk kontainer */
            overflow-y: auto;
            /* Menambahkan scroll jika konten melebihi batas */
        }

        .food-list button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 10px;
            background-color: rgb(19, 85, 137, 0.6);
            color: white;
            cursor: pointer;
            height: 40px;
        }

        .food-details {
            width: 45%;
            background-color: rgb(237, 250, 253, 0.6);
            padding: 20px;
            border-radius: 10px;
        }

        .food-details h2 {
            margin-top: 0;
        }

        .food-details .calories {
            font-size: 24px;
            margin: 20px 0;
        }

        .food-details input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .food-details button {
            width: 48%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .food-details .add-button {
            background-color: #4caf50;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 30px;
            margin-right: 5px;
            padding-left: 15px;
        }

        .save-button {
            background-color: #2196f3;
            color: white;
            margin-right: 10px;
            margin-top: 10px;
            height: 25px;
            border-radius: 5px;
        }

        .cancel-button {
            background-color: #f44336;
            color: white;
            margin-right: 10px;
            margin-top: 10px;
            height: 25px;
            border-radius: 5px;
        }

        .input {
            display: flex;
            max-width: 50px;
        }
    </style>
</head>

<body>
    <?php
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

    // Query untuk mengambil data makanan
    $query = "SELECT * FROM makanan";
    $result = $conn->query($query);
    ?>
    <div class="container">
        <div class="header">
            <h1>Input Makanan</h1>
        </div>
        <div class="search-bar">
            <input type="text" id="search" placeholder="cari makanan">
        </div>
        <div style="display: flex; flex-flow: row;">
            <div class="food-list" id="foodList">
                <?php
                // Menampilkan data makanan dalam daftar
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<button onclick="showDetails(\'' . $row['nama'] . '\', \'' . $row['kalori'] . '\')">' . $row['nama'] . '</button>';
                    }
                } else {
                    echo '<p>Tidak ada data makanan tersedia.</p>';
                }
                ?>
            </div>
            <div class="food-details">
                <h2 id="foodName">Pilih Makanan</h2>
                <div class="calories" id="foodCalories">0 kkal/100 gr</div>
                <form method="POST" action="process_input.php">
                    <div style="display: flex; justify-content: space-between; align-items: end; height: 130px;">
                        <input type="hidden" name="nama" id="selectedFood" value="">
                        <input class="input" type="number" name="porsi" placeholder="0 gram" required>
                        <button type="submit" class="add-button">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
        <div style="display: flex; justify-content: end;">
            <button class="cancel-button"><a href="Dashboard.php" style="color: #fff;">Kembali</a></button>
            <button class="save-button"><a href="profile.php" style="color: #fff;">Simpan</a></button>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan detail makanan
        function showDetails(nama, kalori) {
            document.getElementById('foodName').innerText = nama;
            document.getElementById('foodCalories').innerText = kalori + " kkal/100 gr";
            document.getElementById('selectedFood').value = nama;
        }

        document.getElementById('search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const buttons = document.querySelectorAll('.food-list button');
            buttons.forEach(button => {
                if (button.innerText.toLowerCase().includes(searchTerm)) {
                    button.style.display = 'block';
                } else {
                    button.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>