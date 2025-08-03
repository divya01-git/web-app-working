<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

include('includes/db.php');

// Use correct PHPMailer path from Composer's vendor directory
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $otp = rand(100000, 999999);

    // Check if email already exists
    $checkQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkQuery->bind_param("s", $email);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['signup_error'] = "Email already exists. Please login or use another email.";
        header("Location: signup.php");
        exit;
    }

    // Store details temporarily in session
    $_SESSION['pending_user'] = [
        'name' => $name,
        'email' => $email,
        'password' => $password,
        'otp' => $otp
    ];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'projectclgsrmcem@gmail.com';
        $mail->Password = 'pngn cjxa yddn sshu'; // App password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('projectclgsrmcem@gmail.com', 'Rainwater App');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP for signup is <b>$otp</b>";

        $mail->send();
        header("Location: verify_otp.php");
        exit;
    } catch (Exception $e) {
        echo "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Signup - Rainwater Planner</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Open Sans', sans-serif;
    }
    body {
      background: linear-gradient(to right, #aee1f9, #d4f1f9);
      color: #333;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }
    header {
      width: 100%;
      background-color: #0077b6;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header h1 {
      color: white;
      font-weight: 700;
    }
    nav a {
      color: white;
      margin-left: 20px;
      text-decoration: none;
      font-weight: 600;
    }
    .signup-container {
      background: white;
      padding: 30px;
      margin-top: 50px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 400px;
    }
    .signup-container h2 {
      color: #0077b6;
      margin-bottom: 20px;
      text-align: center;
    }
    .signup-container input[type="text"],
    .signup-container input[type="email"],
    .signup-container input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .signup-container input[type="submit"] {
      background-color: #0077b6;
      color: white;
      padding: 10px;
      width: 100%;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
    }
    .signup-container input[type="submit"]:hover {
      background-color: #005f8e;
    }
    .error-message {
      background-color: #ffdddd;
      border-left: 5px solid #f44336;
      color: #a94442;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      font-weight: bold;
      text-align: center;
    }
    footer {
      margin-top: auto;
      width: 100%;
      background-color: #e0f7fa;
      padding: 10px 0;
      text-align: center;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Rainwater Planner</h1>
    <nav>
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="signup.php">Signup</a>
      <a href="login.php">Login</a>
    </nav>
  </header>

  <div class="signup-container">
    <h2>Create an Account</h2>
    <?php if (isset($_SESSION['signup_error'])): ?>
      <div class="error-message">
        <?= $_SESSION['signup_error']; unset($_SESSION['signup_error']); ?>
      </div>
    <?php endif; ?>
    <form action="signup.php" method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" value="Signup">
    </form>
  </div>

  <footer>
    &copy; 2025 Rainwater Harvesting Planner. All rights reserved.
  </footer>
</body>
</html>
