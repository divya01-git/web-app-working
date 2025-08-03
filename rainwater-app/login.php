<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: home.php");
    exit();
}

$error = "";

// Success message for password reset redirect
$success_msg = "";
if (isset($_GET['reset']) && $_GET['reset'] === 'success') {
    $success_msg = "✅ Password reset successful! Please login.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "rainwater_db");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $user_id;
            header("Location: home.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Rainwater Planner</title>
  <link rel="stylesheet" href="style.css">
  <style>
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

    .login-box {
      max-width: 400px;
      margin: 80px auto;
      padding: 2rem;
      background: white;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
    }

    .login-box h2 {
      text-align: center;
      color: #0077b6;
      margin-bottom: 1.5rem;
    }

    .login-box input[type="email"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 1rem;
    }

    .login-box button {
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

    .login-box button:hover {
      background-color: #005f8a;
    }

    .login-box .links {
      text-align: center;
      margin-top: 1rem;
    }

    .login-box .links a {
      color: #0077b6;
      text-decoration: none;
      margin: 0 10px;
      font-size: 0.95rem;
    }

    .login-box .links a:hover {
      text-decoration: underline;
    }

    .error {
      color: red;
      text-align: center;
      margin-top: 10px;
      font-size: 0.95rem;
    }

    .success {
      color: green;
      text-align: center;
      margin-top: 10px;
      font-size: 0.95rem;
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

  <div class="login-box">
    <h2>Login</h2>
    <form method="post" action="">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success_msg): ?>
      <div class="success"><?= htmlspecialchars($success_msg) ?></div>
    <?php endif; ?>

    <div class="links">
      <a href="signup.php">Create Account</a> |
      <a href="forgot_password.php">Forgot Password?</a>
    </div>
  </div>

  <footer>
    © 2025 Rainwater Harvesting Planner. All rights reserved.
  </footer>

</body>
</html>
