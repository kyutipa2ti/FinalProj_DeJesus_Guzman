<?php
$xmlFile = 'main.xml';
$departments = [];
$positions = [];
$totalUsers = 0;

if (file_exists($xmlFile)) {
    $xml = simplexml_load_file($xmlFile);
    foreach ($xml->user as $user) {
        $totalUsers++;
        $dept = (string) $user->department;
        $pos  = (string) $user->position;

        $departments[$dept] = ($departments[$dept] ?? 0) + 1;
        $positions[$pos] = ($positions[$pos] ?? 0) + 1;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            width: 100vw;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #061e4e;
            padding: 12px 20px;
            margin: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100vw;
            box-sizing: border-box;
            overflow-x: hidden;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar .logo img {
            height: 100px;
            margin-top: -15px;
            margin-bottom: -15px;
        }

        .navbar .logo span {
            color: white;
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
        }

        .navbar .buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .navbar .buttons form {
            margin: 0;
        }

        .navbar .buttons form button {
            background-color: white;
            color: #061e4e;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar .buttons form.dashboard-active button {
            background-color: #061e4e;
            color: white;
        }

        .navbar .buttons form button:hover {
            background-color: #061e4e;
            color: white;
        }

        h1, h2 {
            text-align: center;
            color: #061e4e;
            margin: 0 0 20px 0;
        }

        .card-container {
            max-width: 200px;
            margin: 20px auto 30px;
            background: white;
            border-radius: 12px;
            padding: 30px 20px;
            box-shadow: 0 4px 16px #061e4e;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card-container .number {
            font-size: 28px;
            font-weight: bold;
            color: #111;
            margin-top: 5px;
        }

        .chart-container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 8px 20px #061e4e;
            height: 550px;
        }

        canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .back-button {
            text-align: center;
            margin: 50px 0;
        }

        .back-button a {
            background-color: #061e4e;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .back-button a:hover {
            background-color: #0056b3;
        }

        @media (max-width: 600px) {
            .navbar .buttons form button {
                padding: 8px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="bilog.png" alt="Logo" />
        <span>M.A. Staff Management System</span>
    </div>
    <div class="buttons">
        <form method="POST" action="LandingPage.php">
            <button type="submit"><i class='bx bx-home'></i>Home</button>
        </form>
        <form method="POST" action="AddMain.php">
            <button type="submit"><i class='bx bx-user-plus'></i>Add User</button>
        </form>
        <form method="POST" action="dashboard.php" class="dashboard-active">
            <button type="submit"><i class='bx bx-bar-chart-alt'></i>Dashboard</button>
        </form>
         <form method="POST" action="aboutus.php">
            <button type="submit"><i class='bx bx-info-circle'></i>About Us</button>
        </form>
        <form method="POST" action="index.php">
            <button type="submit"><i class='bx bx-log-out'></i>Logout</button>
        </form>
    </div>
</div>

<h1><i class='bx bx-bar-chart-alt'></i> School Users Dashboard</h1>

<div class="card-container">
    <i class='bx bx-user' style="font-size: 32px; color: #007BFF;"></i>
    <div style="margin-top: 8px; font-weight: 500; color: #555;">Total Users</div>
    <div class="number"><?= $totalUsers ?></div>
</div>

<div class="chart-container">
    <h2>Users by Department</h2>
    <canvas id="departmentChart"></canvas>
</div>

<div class="chart-container">
    <h2>Users by Position</h2>
    <canvas id="positionChart"></canvas>
</div>

<div class="back-button">
    <a href="LandingPage.php"><i class='bx bx-arrow-back'></i> Back to Home</a>
</div>

<script>
    const departments = <?= json_encode(array_keys($departments)) ?>;
    const departmentCounts = <?= json_encode(array_values($departments)) ?>;
    const positions = <?= json_encode(array_keys($positions)) ?>;
    const positionCounts = <?= json_encode(array_values($positions)) ?>;

    new Chart(document.getElementById("departmentChart"), {
        type: 'bar',
        data: {
            labels: departments,
            datasets: [{
                label: 'Users by Department',
                data: departmentCounts,
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById("positionChart"), {
        type: 'bar',
        data: {
            labels: positions,
            datasets: [{
                label: 'Users by Position',
                data: positionCounts,
                backgroundColor: '#ffc107'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

</body>
</html>
