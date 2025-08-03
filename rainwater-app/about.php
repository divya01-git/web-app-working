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
  <meta charset="UTF-8" />
  <title>About Us - Rainwater Harvesting Planner</title>
  <link rel="stylesheet" href="css/style.css" />
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

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 20px;
    }

    h1, h2 {
      color: #0077b6;
      text-align: center;
    }

    p {
      font-size: 1.1rem;
      line-height: 1.6;
      margin: 20px auto;
      max-width: 700px;
      text-align: center;
      color: #333;
    }

    .about-image {
      display: block;
      max-width: 400px;
      width: 100%;
      margin: 30px auto;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.2);
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
    <h1>About Us</h1>
	<img 
      src="img/rwh.png" 
      alt="Rainwater Harvesting System Diagram" 
      class="about-image"
    >
    <p>
      At <strong>Rainwater Harvesting Planner</strong>, we are passionate about helping communities and individuals make the most of nature’s precious gift — rainwater. Our mission is to promote sustainable water conservation by providing easy-to-use tools that predict rainfall and offer smart, actionable rainwater harvesting solutions.
    </p>
    <p>
      Water scarcity is a growing challenge worldwide, and harvesting rainwater is one of the most effective, eco-friendly ways to conserve water, reduce dependence on groundwater, and improve resilience to droughts. With our planner, users can get accurate, up-to-date weather forecasts tailored to their location and receive personalized suggestions on how to efficiently collect and reuse rainwater.
    </p>
    <p>
      Our team combines expertise in environmental science, web technology, and data analytics to bring you a platform that is not only informative but also user-friendly and interactive. We believe that every drop counts, and with better planning, we can all contribute to a greener, water-secure future.
    </p>

    

    <p><em>Join us in making a difference — one raindrop at a time.</em></p>
  </div>

  <footer>
    &copy; <?php echo date("Y"); ?> Rainwater Harvesting Planner. All rights reserved.
  </footer>

</body>
</html>
