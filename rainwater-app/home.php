<?php
include('includes/db.php');
session_start();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Check login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['login_time'])) {
    $_SESSION['login_time'] = time();
}

$userId = $_SESSION['user']; // Assuming this is the user ID

// Fetch user name from DB
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
$userName = $userData['name'] ?? 'User';

$loginTime = $_SESSION['login_time'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Rainwater Harvesting Planner</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f3f9fc;
    }

    .top-panel {
      background-color: #ffffff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
    }

    .panel-section {
      flex: 1;
      min-width: 200px;
    }

    .panel-title {
      font-size: 20px;
      font-weight: bold;
      color: #1976d2;
    }

    .user-card {
      background-color: #e3f2fd;
      padding: 12px 20px;
      border-radius: 10px;
      display: inline-block;
      font-size: 14px;
    }

    .user-card strong {
      color: #1565c0;
    }

    .timer-badge {
      display: inline-block;
      background-color: #1976d2;
      color: white;
      padding: 3px 10px;
      border-radius: 20px;
      margin-left: 10px;
      font-size: 13px;
    }

    .logout-btn {
      background-color: #f44336;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 14px;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #c62828;
    }

    .container {
      max-width: 600px;
      margin: 50px auto;
      background-color: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #1976d2;
    }

    form {
      margin-top: 20px;
    }

    label, select, button {
      display: block;
      width: 100%;
      margin-bottom: 15px;
      font-size: 16px;
    }

    button {
      background-color: #1976d2;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    button:hover {
      background-color: #1565c0;
    }

    @media (max-width: 768px) {
      .top-panel {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</head>
<body>

  <div class="top-panel">
    <div class="panel-section panel-title">🌧️ Rainwater Planner</div>

    <div class="panel-section">
      <div class="user-card">
        Logged in as: <strong><?php echo htmlspecialchars($userName); ?></strong>
        <span class="timer-badge" id="sessionTimer">--:--</span>
      </div>
    </div>

    <div class="panel-section" style="text-align: right;">
      <a class="logout-btn" href="logout.php">Logout</a>
    </div>
  </div>

  <div class="container">
    <h1>Select Location</h1>
    <form method="get" action="weather.php">
      <label for="location">Choose your area:</label>
      <select name="location" id="location">
        <option value="Lucknow">Lucknow</option>
        <option value="Lakhimpur">Lakhimpur</option>
        <option value="Barabanki">Barabanki</option>
        <option value="Sitapur">Sitapur</option>
        <option value="Unnao">Unnao</option>
        <option value="Ayodhya">Ayodhya</option>
        <option value="Aliganj">Aliganj</option>
      </select>
      <button type="submit">Check Weather</button>
    </form>
  </div>

  <script>
    const loginTime = <?php echo $loginTime; ?>;

    function updateTimer() {
      const now = Math.floor(Date.now() / 1000);
      const diff = now - loginTime;

      const minutes = Math.floor(diff / 60);
      const seconds = diff % 60;

      document.getElementById("sessionTimer").innerText =
        `${minutes}m ${seconds < 10 ? '0' : ''}${seconds}s`;
    }

    updateTimer();
    setInterval(updateTimer, 1000);
  </script>

</body>
</html>
