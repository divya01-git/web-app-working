<?php
session_start();
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    if (!isset($_SESSION['pending_user'])) {
        $_SESSION['error'] = "Session expired. Please register again.";
        header("Location: signup.php");
        exit();
    }

    $user = $_SESSION['pending_user'];

    if ($entered_otp == $user['otp']) {
        // Check if email already exists
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $user['email']);
        $checkEmail->execute();
        $checkEmail->store_result();

        if ($checkEmail->num_rows > 0) {
            $_SESSION['error'] = "Email already exists. Please login or use another email.";
            header("Location: signup.php");
            exit();
        }

        // Insert user and set verified to 1
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, verified) VALUES (?, ?, ?, 1)");
        $stmt->bind_param("sss", $user['name'], $user['email'], $user['password']);
        $stmt->execute();

        unset($_SESSION['pending_user']);

        $_SESSION['user_id'] = $stmt->insert_id;
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['otp_error'] = "Incorrect OTP. Please try again.";
        header("Location: verify_otp.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #d0f0fc, #f0ffff);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .verify-container {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 350px;
    }
    .verify-container h2 {
      color: #0077b6;
      margin-bottom: 20px;
      text-align: center;
    }
    .verify-container input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .verify-container input[type="submit"] {
      background-color: #0077b6;
      color: white;
      padding: 10px;
      width: 100%;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .verify-container input[type="submit"]:hover {
      background-color: #005f8e;
    }
    .error-msg {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="verify-container">
    <h2>Enter OTP</h2>
    <?php
      if (isset($_SESSION['otp_error'])) {
        echo '<p class="error-msg">' . $_SESSION['otp_error'] . '</p>';
        unset($_SESSION['otp_error']);
      }
    ?>
    <form method="POST">
      <input type="number" name="otp" placeholder="Enter OTP" required>
      <input type="submit" value="Verify">
    </form>
  </div>
</body>
</html>
