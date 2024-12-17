<?php
include 'database.php'; 
$message = "";
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Username sudah terdaftar, silakan pilih username lain.";
    } else {
        if ($password == $confirm_password) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $message = "Signup berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else {
                $message = "Terjadi kesalahan, coba lagi.";
            }
        } else {
            $message = "Password dan konfirmasi password tidak cocok.";
        }
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <loginBar>
    <img src="kalori.png" alt="" style="width: 125px" />
    <cal style="margin: 20px; color: white">SIGN UP</cal>
    <?php if ($message): ?>
      <div style="color: white; margin: 10px; font-size: small;"><?= $message; ?></div>
    <?php endif; ?>
    <form method="POST" action="register.php" style="display: flex; flex-direction: column; align-items: center;">
      <input class="inputLogin" type="text" name="username" placeholder="Username" required>
      <input class="inputLogin" type="password" name="password" placeholder="Password" required>
      <input class="inputLogin" type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button class="sign" type="submit" name="register">Sign Up</button>
    </form>
    <div class="teks">
      Already have an account? <a class="a" href="login.php">Sign In, It's free!</a>
    </div>
  </loginBar>
</body>
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
  loginBar {
      background-color: rgba(217, 217, 217, 0.5);
      display: flex;
      height: 400px;
      width: 300px;
      border-radius: 5%;
      padding: 5%;
      opacity: 100%;
      flex-flow: column;
      justify-content: end;
      align-items: center;
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.1),
        0 6px 20px 0 rgba(0, 0, 0, 0.19);
      }
      .inputLogin {
      background-color: rgb(42, 147, 213);
      height: 25px;
      width: 260px;
      border-radius: 10px;
      padding: 5px;
      padding-left: 20px;
      margin-block: 5px;
      font-size: small;
      opacity: 90%;
      font-weight: 200;
      color: white;
      border: none;
    }
    .sign {
      background-color: rgb(19, 85, 137);
      height: 30px;
      width: 230px;
      border-radius: 20px;
      padding: 5px;
      margin-block: 1px;
      font-size: small;
      font-weight: 200;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      border: none;
      cursor: pointer;
    }
    .teks {
      margin-top: 10px;
      margin-bottom: 15px;
      color: aliceblue;
      font-size: small;
    }
    .a {
      color: white;
      font-weight: bold;
      text-decoration: none;
    }
    img {
      width: 20px;
    }
</style>
</html>