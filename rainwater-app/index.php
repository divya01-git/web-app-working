<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rainwater Harvesting Planner</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .navbar {
      background-color: #0077b6;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
      border-radius: 0 0 10px 10px;
    }

    .navbar h2 {
      margin: 0;
      font-size: 1.5rem;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      margin-left: 1.5rem;
      font-weight: bold;
      font-size: 1rem;
    }

    .navbar a:hover {
      text-decoration: underline;
    }

    .btn-group {
      text-align: center;
      margin-top: 1.5rem;
    }

    .btn-group a {
      display: inline-block;
      margin: 0.5rem;
      padding: 10px 20px;
      background-color: #0077b6;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .btn-group a:hover {
      background-color: #005f8a;
    }

    .section {
      margin-top: 2rem;
      padding: 1.5rem;
    }

    footer {
      text-align: center;
      margin-top: 3rem;
      padding: 1rem;
      background-color: #e0f7fa;
      border-top: 1px solid #ccc;
      border-radius: 0 0 10px 10px;
      color: #333;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <h2>Rainwater Planner</h2>
    <div>
      <a href="index.php">Home</a>
      <a href="about.php">About Us</a>
      <a href="signup.php">Signup</a>
      <a href="login.php">Login</a>
    </div>
  </div>

  <div class="container">
    <h1>Welcome to the Rainwater Harvesting Planner</h1>
    <p style="text-align:center;">
      This tool helps you plan for efficient water usage by forecasting rainfall and offering harvesting suggestions.
      Join us in creating a sustainable future.
    </p>

    <div class="btn-group">
      <a href="signup.php">Sign Up</a>
      <a href="login.php">Login</a>
    </div>

    <div id="about" class="section suggestions">
      <h2 style="text-align:center; color:#0077b6;">About Us</h2>
      <p style="text-align:center;">
        We are a team passionate about solving real-world environmental challenges through technology. Our goal is to help every
        household harvest and reuse rainwater effectively with live weather insights and planning tools.
      </p>
    </div>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> Rainwater Harvesting Planner. All rights reserved.
  </footer>

</body>
</html>
