<?php
require_once '../includes/auth.php';
require_once '../config/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Get stats
$users = $pdo->query("SELECT role, COUNT(*) as total FROM users GROUP BY role")->fetchAll();
$total_consults = $pdo->query("SELECT COUNT(*) FROM consultations")->fetchColumn();
$accepted_consults = $pdo->query("SELECT COUNT(*) FROM consultations WHERE status = 'accept'")->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Reports – JustiNet</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #001f3f;
            color: #ffeb3b;
            padding: 40px;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #ffeb3b;
            text-shadow: 1px 1px 3px #000;
        }

        ul {
            list-style: none;
            padding: 0;
            max-width: 600px;
            margin: auto;
        }

        ul li {
            background: #0d1b2a;
            padding: 15px 20px;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4);
        }

        .chart-container {
            max-width: 700px;
            margin: 50px auto;
            background: #0b2b40;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }

        canvas {
            background: #fff;
            border-radius: 10px;
            padding: 10px;
        }

        p {
            text-align: center;
            margin-top: 30px;
            color: #ffe066;
        }

        .float-btn {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #ffd600, #fff176);
            color: #000;
            border-radius: 50%;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .float-btn:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
            background: linear-gradient(135deg, #fff176, #ffee58);
        }

        .float-btn span {
            font-weight: bold;
            text-shadow: 1px 1px 2px #00000050;
        }
    </style>
</head>
<body>

<h2>Admin Reports & Stats</h2>

<ul>
    <?php foreach ($users as $u): ?>
        <li>Total <?= ucfirst($u['role']) ?>s: <?= $u['total'] ?></li>
    <?php endforeach; ?>
    <li>Total Consultations: <?= $total_consults ?></li>

</ul>

<div class="chart-container">
    <h3 style="text-align:center; color: #ffe066;">User Role Distribution</h3>
    <canvas id="roleChart"></canvas>
</div>



<p><strong>Tip:</strong> You can export reports or add more visualizations easily!</p>

<a href="../index.php" class="float-btn" title="Back to Home">
    <span>⟵</span>
</a>

<script>
    // Data from PHP
    const roleData = {
        labels: <?= json_encode(array_column($users, 'role')) ?>,
        datasets: [{
            label: 'Users by Role',
            data: <?= json_encode(array_column($users, 'total')) ?>,
            backgroundColor: ['#ffeb3b', '#ffd600', '#ffee58'],
            borderColor: '#333',
            borderWidth: 1
        }]
    };

    const roleConfig = {
        type: 'pie',
        data: roleData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#000' }
                }
            }
        }
    };

    const consultData = {
        labels: ['Accepted', 'Pending/Others'],
        datasets: [{
            label: 'Consultations',
            data: [<?= $accepted_consults ?>, <?= $total_consults - $accepted_consults ?>],
            backgroundColor: ['#ffd600', '#999'],
            borderColor: '#222',
            borderWidth: 1
        }]
    };

    const consultConfig = {
        type: 'bar',
        data: consultData,
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { color: '#000' } },
                x: { ticks: { color: '#000' } }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    };

    // Render charts
    new Chart(document.getElementById('roleChart'), roleConfig);
    new Chart(document.getElementById('consultChart'), consultConfig);
</script>

</body>
</html>
