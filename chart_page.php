<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // ส่งผู้ใช้กลับไปหน้า login ถ้ายังไม่ได้เข้าสู่ระบบ
    exit();
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>แสดงผลกราฟ</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="chart_pagestyle.css"> <!-- Link to CSS file -->
</head>

<body>
    <div class="topnav">
        <h3>ระบบควบคุมและบริหารจัดการน้ำแปลงปลูกผัก</h3>
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        <div class="username">
            <i class="fas fa-user"></i>
            <p>สวัสดี, <?php echo $_SESSION['username']; ?>!</p> <!-- แสดงชื่อผู้ใช้จาก session -->
        </div>
    </div>

    <!-- เมนูแถบด้านข้าง -->
    <div class="left-content" id="menu">
        <ul class="nav">
            <li class="nav-item">
                <a href="home.php" class="nav-link"><i class="fa-solid fa-house"></i>หน้าหลัก</a>
            </li>
            <li class="nav-item">
                <a href="control.php" class="nav-link"><i class="fa-solid fa-microchip"></i>ควบคุมการทำงาน</a>
            </li>
            <li class="nav-item">
                <a href="recordtable.php" class="nav-link"><i class="fa-solid fa-square-poll-vertical"></i>แสดงผลข้อมูล</a>
            </li>
            <li class="nav-item">
                <a href="chart_page.php" class="nav-link"><i class="fa-solid fa-square-poll-vertical"></i>กราฟแสดงผลข้อมูล</a>
            </li>
            <li class="nav-item">
                <a href="edituser.php" class="nav-link"><i class="fa-solid fa-user-pen"></i>เปลี่ยนรหัสผ่าน</a>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link"><i class="fa-solid fa-outdent"></i>ออกจากระบบ</a>
            </li>
        </ul>
    </div>

    <!-- == MONITORING ======================================================================================== -->
    <div class="card">
        <div class="container">
            <h2>กราฟแสดงผลข้อมูล</h2>

            <canvas id="myChart" class="custom-chart" width="50" height="50"></canvas> 
        </div>
    </div>

    <!-- ======================================================================================================= -->

    <script>
        // Fetch data from the server to render the chart
        fetch('get_chart_data.php')
            .then(response => response.json())
            .then(data => {
                // Get the canvas context
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line', // Type of chart
                    data: {
                        labels: data.labels, // X-axis labels
                        datasets: [{
                                label: 'Temperature (°C)',
                                data: data.temperature, // Temperature data
                                borderColor: '#C62E2E',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                fill: true
                            },
                            {
                                label: 'Humidity (%)',
                                data: data.humidity, // Humidity data
                                borderColor: '#0D92F4',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                fill: true
                            },
                            {
                                label: 'Rain (%)',
                                data: data.rain, // Rain data
                                borderColor: '#4F6F52',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                fill: true
                            },
                            {
                                label: 'Soil Moisture (%)',
                                data: data.soil, // Soil moisture data
                                borderColor: '#AB886D',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                fill: true
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true // Start y-axis at 0
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>

    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        }
    </script>
</body>

</html>