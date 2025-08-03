<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$apiKey = "b30924bfd9f243e2bc5141043253107";
$location = $_GET['location'] ?? 'Lucknow';

$locations = ['Lucknow', 'Lakhimpur', 'Barabanki', 'Sitapur', 'Unnao'];
$weatherUrl = "http://api.weatherapi.com/v1/forecast.json?key=$apiKey&q=" . urlencode($location) . "&days=7&aqi=no&alerts=no";
$response = file_get_contents($weatherUrl);
$data = json_decode($response, true);

// Current Weather
$currentTemp = $data['current']['temp_c'];
$humidity = $data['current']['humidity'];
$precip = $data['current']['precip_mm'];
$condition = $data['current']['condition']['text'];

// Hourly Forecast (Next 12 hours)
$hourlyData = [];
$labels = [];
$rainChances = [];
$now = time();
foreach ($data['forecast']['forecastday'][0]['hour'] as $hour) {
    $hourTime = strtotime($hour['time']);
    if ($hourTime >= $now && count($hourlyData) < 12) {
        $hourlyData[] = [
            'time' => date('g A', $hourTime),
            'temp_c' => $hour['temp_c'],
            'rain_chance' => $hour['chance_of_rain'] ?? 0
        ];
        $labels[] = date('g A', $hourTime);
        $rainChances[] = $hour['chance_of_rain'] ?? 0;
    }
}

// 7-Day Forecast
$forecastData = [];
$totalRainChance = 0;
foreach ($data['forecast']['forecastday'] as $day) {
    $rainChance = $day['day']['daily_chance_of_rain'];
    $forecastData[] = [
        'date' => date('D, M j', strtotime($day['date'])),
        'min_temp' => $day['day']['mintemp_c'],
        'max_temp' => $day['day']['maxtemp_c'],
        'condition' => $day['day']['condition']['text'],
        'rain_chance' => $rainChance
    ];
    $totalRainChance += $rainChance;
}
$averageRainChance = $totalRainChance / count($forecastData);

// Dynamic Suggestions
$suggestions = [];
if ($averageRainChance > 80) {
    $suggestions = [
        "🌧️ Heavy rains expected. Install rooftop rainwater collection systems.",
        "🧼 Ensure all storage tanks are clean and covered.",
        "🌿 Use harvested rainwater for irrigation and household cleaning."
    ];
} elseif ($averageRainChance > 50) {
    $suggestions = [
        "🪣 Moderate chance of rain. Set up rain barrels to collect runoff.",
        "🧹 Clean gutters to ensure smooth water flow.",
        "🪵 Prepare basic storage like drums or tanks for rainwater."
    ];
} else {
    $suggestions = [
        "🌤️ Low rain chances. Focus on conserving existing stored water.",
        "💧 Use drip irrigation for gardens to save water.",
        "🧰 Check and repair your rain harvesting systems for future use."
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Weather - <?php echo htmlspecialchars($location); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="css/style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f5faff;
      color: #222;
    }
    .container {
      max-width: 1000px;
      margin: auto;
      padding: 20px;
    }
    h1, h2, h3 {
      color: #0077b6;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }
    table, th, td {
      border: 1px solid #ccc;
    }
    th, td {
      text-align: center;
      padding: 10px;
    }
    th {
      background: #caf0f8;
    }
    tr:nth-child(even) {
      background: #edf6f9;
    }
    .suggestions {
      background: #d9f9d9;
      padding: 20px;
      border-left: 6px solid green;
      border-radius: 5px;
    }
    .suggestions ul {
      padding-left: 20px;
    }
    canvas {
      margin: 30px auto;
      display: block;
      max-width: 100%;
    }
    .btn-download {
      background: #0077b6;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      font-size: 16px;
      margin-bottom: 20px;
      border-radius: 5px;
    }
    .btn-download:hover {
      background: #023e8a;
    }
    @media (max-width: 768px) {
      table, tr, th, td {
        font-size: 14px;
      }
      h1 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>
  <div class="container" id="pdf-content">
    <h1>Rainwater Harvesting Planner</h1>

    <button class="btn-download" onclick="downloadPDF()">📄 Download PDF</button>

    <h2>Current Weather in <?php echo htmlspecialchars($location); ?></h2>
    <p><strong>Temperature:</strong> <?php echo $currentTemp; ?>°C</p>
    <p><strong>Humidity:</strong> <?php echo $humidity; ?>%</p>
    <p><strong>Precipitation:</strong> <?php echo $precip; ?> mm</p>
    <p><strong>Condition:</strong> <?php echo $condition; ?></p>

    <h3>Hourly Forecast (Next 12 Hours)</h3>
    <table>
      <tr><th>Time</th><th>Temp (°C)</th><th>Rain (%)</th></tr>
      <?php foreach ($hourlyData as $hour): ?>
        <tr>
          <td><?php echo $hour['time']; ?></td>
          <td><?php echo $hour['temp_c']; ?>°C</td>
          <td><?php echo $hour['rain_chance']; ?>%</td>
        </tr>
      <?php endforeach; ?>
    </table>

    <canvas id="rainChart" height="100"></canvas>

    <h3>7-Day Forecast</h3>
    <table>
      <tr><th>Date</th><th>Min Temp</th><th>Max Temp</th><th>Condition</th><th>Rain (%)</th></tr>
      <?php foreach ($forecastData as $day): ?>
        <tr>
          <td><?php echo $day['date']; ?></td>
          <td><?php echo $day['min_temp']; ?>°C</td>
          <td><?php echo $day['max_temp']; ?>°C</td>
          <td><?php echo $day['condition']; ?></td>
          <td><?php echo $day['rain_chance']; ?>%</td>
        </tr>
      <?php endforeach; ?>
    </table>

    <div class="suggestions">
      <h3>Suggestions for Rainwater Harvesting</h3>
      <ul>
        <?php foreach ($suggestions as $tip): ?>
          <li><?php echo $tip; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <a href="index.php" style="display:block;margin-top:20px;text-align:center;">⬅ Back to Home</a>
  </div>

  <script>
    // Chart.js line chart
    const ctx = document.getElementById('rainChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
          label: 'Rain Probability (%)',
          data: <?php echo json_encode($rainChances); ?>,
          borderColor: '#0077b6',
          backgroundColor: 'rgba(0, 119, 182, 0.2)',
          fill: true,
          tension: 0.4,
          pointBackgroundColor: '#0077b6',
          pointRadius: 5,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 100
          }
        }
      }
    });

    // PDF download function
    function downloadPDF() {
      const element = document.getElementById('pdf-content');
      html2pdf().from(element).set({
        margin: 0.5,
        filename: 'Rainwater_Weather_Report.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
      }).save();
    }
  </script>
</body>
</html>
