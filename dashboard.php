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

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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
$usernameUser = $_SESSION['username'];
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

$conn->close();

// Array quotes motivasi
$quotes = [
    "Kesehatan adalah kekayaan terbesar kita.",
    "Setiap langkah kecil adalah kemajuan menuju tujuan besar.",
    "Makan dengan baik, hidup dengan baik.",
    "Tetap konsisten, karena perubahan membutuhkan waktu.",
    "Hari ini adalah peluang untuk menjadi lebih baik dari kemarin.",
    "Keseimbangan adalah kunci dari kehidupan yang sehat.",
    "Tidak ada rahasia untuk kesuksesan, hanya kerja keras dan dedikasi.",
    "Bergeraklah untuk dirimu sendiri, tubuhmu akan berterima kasih.",
    "Jangan menyerah, tujuan besar membutuhkan usaha besar.",
    "Kesehatan bukan hanya tentang tubuh, tetapi juga tentang pikiran dan jiwa."
];

// Pilih quote acak
$motivasiHariIni = $quotes[array_rand($quotes)];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Custom styles -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Kalori App</div>
            </a>
            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link" href="inputmakan.php">
                    <i class="fas fa-fw fa-utensils"></i>
                    <span>Input Makanan</span>
                </a>
            </li>
            <hr class="sidebar-divider">
        </ul>
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="profile.php" id="userDropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($usernameUser); ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>

                    <!-- Section Quote Motivasi -->
                    <div class="row mb-4">
                        <div class="col">
                            <div class="alert alert-info text-center" role="alert">
                                <strong>Motivasi Hari Ini:</strong> <?php echo $motivasiHariIni; ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Kebutuhan Kalori -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Kebutuhan Kalori Harian</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo number_format($kebutuhanKalori, 2); ?> kkal</div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Kalori -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kalori Hari Ini</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo round($totalKaloriHarian); ?> kkal</div>
                                </div>
                            </div>
                        </div>

                        <!-- Sisa Kalori -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sisa Kalori</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo round($sisaKalori); ?> kkal</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Reset -->
                    <form method="post">
                        <button type="submit" name="reset" class="btn btn-danger">Reset Kalori</button>
                    </form>
                </div>
                <!-- End Page Content -->
            </div>
        </div>
        <!-- End Content Wrapper -->
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>