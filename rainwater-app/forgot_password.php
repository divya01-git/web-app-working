<?php
include('includes/db.php');
include('includes/mailer.php');

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    $res = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($res->num_rows > 0) {
        $conn->query("UPDATE users SET otp='$otp' WHERE email='$email'");
        if (sendOTP($email, $otp)) {
            header("Location: reset_password.php?email=$email");
            exit;
        } else {
            $msg = "❌ Could not send OTP.";
        }
    } else {
        $msg = "⚠️ Email not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Forgot Password - Rainwater Planner</title>
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

    .forgot-box {
      max-width: 400px;
      margin: 80px auto;
      padding: 2rem;
      background: white;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    .forgot-box h2,
    .forgot-box h1 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 1.5rem;
    }

    .forgot-box input[type="email"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .forgot-box button {
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

    .forgot-box button:hover {
      background-color: #005f8a;
    }

    .message {
      text-align: center;
      margin-top: 10px;
      font-size: 1rem;
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

  <div class="forgot-box">
    <h1>Forgot Password</h1>

    <?php if ($msg): ?>
      <p class="message"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <label for="email">Enter your email</label>
      <input type="email" id="email" name="email" required>

      <button type="submit">Send OTP</button>
    </form>
  </div>

  <footer>
    © 2025 Rainwater Harvesting Planner. All rights reserved.
  </footer>

</body>
</html>
