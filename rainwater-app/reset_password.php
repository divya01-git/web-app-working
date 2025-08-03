<?php
include('includes/db.php');

$email = $_GET['email'] ?? '';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'];
    $newpass = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email' AND otp='$otp'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE users SET password='$newpass', otp=NULL WHERE email='$email'");

        // Redirect to login.php after successful reset
        header("Location: login.php?reset=success");
        exit();
    } else {
        $msg = "❌ Invalid OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reset Password - Rainwater Planner</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Same styles as login.php */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: linear-gradient(to right, #aee1f9, #d4f1f9);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
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

    nav a:hover {
      text-decoration: underline;
    }

    .reset-box {
      max-width: 400px;
      margin: 80px auto;
      padding: 2rem;
      background: white;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    .reset-box h2 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 1.5rem;
    }

    .reset-box input[type="text"],
    .reset-box input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .reset-box button {
      width: 100%;
      background-color: #0077b6;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 10px;
    }

    .reset-box button:hover {
      background-color: #005f8a;
    }

    .message {
      text-align: center;
      margin-top: 10px;
      font-size: 1rem;
    }

    .message.success {
      color: green;
    }

    .message.error {
      color: red;
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
      <a href="#about">About Us</a>
      <a href="signup.php">Signup</a>
      <a href="login.php">Login</a>
    </nav>
  </header>

  <div class="reset-box">
    <h2>Reset Password</h2>

    <?php if ($msg): ?>
      <p class="message <?= strpos($msg, '✅') === 0 ? 'success' : 'error' ?>">
        <?= $msg ?>
      </p>
    <?php endif; ?>

    <form method="post" action="">
      <label for="otp">OTP</label>
      <input type="text" id="otp" name="otp" required>

      <label for="new_password">New Password</label>
      <input type="password" id="new_password" name="new_password" required>

      <button type="submit">Reset Password</button>
    </form>
  </div>

  <footer>
    © 2025 Rainwater Harvesting Planner. All rights reserved.
  </footer>

</body>
</html>
