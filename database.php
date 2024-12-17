<?php
$servername = "ns1.srvphp.my.id";
$username = "cpehfif2527";  
$password = "";      
$dbname = "cpehfif2527_kalori";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
