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
    <title>แสดงผลข้อมูล</title>
    <meta charset="UTF-8"> <!-- เพิ่ม charset ให้รองรับภาษาไทย -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="recordtablestyle.css"> <!-- Link to CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="topnav">
        <h3>ระบบควบคุมและบริหารจัดการน้ำแปลงปลูกผัก</h3>
        <div class="hamburger" onclick="toggleMenu()">
                <i class="fas fa-bars"></i>
            </div>
            <div class="username">
                <i class="fas fa-user" ></i>
                <p>สวัสดี, <?php echo $_SESSION['username']; ?>!</p> <!-- แสดงชื่อผู้ใช้จาก session -->
            </div>
        </div>

    <!-- เมนูแถบด้านข้าง -->
    <div class="left-content" id="menu">
        <ul class="nav">
            <li class="nav-item">
                <a href="home.php" class="nav-link" ><i class="fa-solid fa-house"></i>หน้าหลัก</a>
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
        <script src="recordtablescrip.js"></script>
            <script>
                function toggleMenu() {
                    var menu = document.getElementById("menu");
                    if (menu.style.display === "block") {
                        menu.style.display = "none";
                    } else {
                        menu.style.display = "block";
                    }
                }
                function exportTableToExcel() {
                    window.location.href = 'export_excel.php'; // เรียกไฟล์ export_excel.php
                }
                
                
                // เรียกข้อมูลจากไฟล์ PHP เพื่อสร้างแผนภูมิ
               
                
            </script>
          
    
    <table class="styled-table" id="table_id">
        <thead>
            <tr>
                <th>id</th>
                <th>NAME</th>
                <th>TEMPERATURE (°C)</th>
                <th>HUMIDITY (%)</th>
                <th>RAIN (%)</th>
                <th>SOIL (%)</th>
                <th>STATUS READ SENSOR DHT11</th>
                <th>PUM 01</th>
                <th>PUM 02</th>
                <th>TIME</th>
                <th>DATE (dd-mm-yyyy)</th>
            </tr>
        </thead>
        <tbody id="tbody_table_record">
            <?php
            include 'database.php'; // เชื่อมต่อไฟล์ database.php

            $num = 0;
            $sql = 'SELECT * FROM esp32_table_dht11_leds_record ORDER BY date, time';
            $result = $conn->query($sql); // ใช้ $conn ที่ได้จากการเชื่อมต่อ
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $date = date_create($row['date']);
                    $dateFormat = date_format($date,"d-m-Y");
                    $num++;
                    echo '<tr>';
                    echo '<td class="bdr">'. $row['board'] . '</td>';
                    echo '<td class="bdr">'. $row['NAME'] . '</td>';
                    echo '<td class="bdr">'. $row['temperature'] . '</td>';
                    echo '<td class="bdr">'. $row['humidity'] . '</td>';
                    echo '<td class="bdr">'. $row['rain'] . '</td>';
                    echo '<td class="bdr">'. $row['soil'] . '</td>';
                    echo '<td class="bdr">'. $row['status_read_sensor_dht11'] . '</td>';
                    echo '<td class="bdr">'. $row['PUM_01'] . '</td>';
                    echo '<td class="bdr">'. $row['PUM_02'] . '</td>';
                    echo '<td class="bdr">'. $row['time'] . '</td>';
                    echo '<td>'. $dateFormat . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="9">ไม่พบข้อมูล</td></tr>'; // หากไม่มีข้อมูล
            }
          
            // ปิดการเชื่อมต่อ
            $conn->close();
            ?>
            <script>
                
            var currentPage = 1;
            var rowsPerPage = 10; // จำนวนแถวที่จะแสดงต่อหน้า
            var table = document.getElementById("table_id");
            var tbody = table.getElementsByTagName("tbody")[0];
            var rows = tbody.getElementsByTagName("tr");
            var totalRows = rows.length;
            var totalPages = Math.ceil(totalRows / rowsPerPage);

            // ฟังก์ชันในการแสดงหน้าแรก
            function displayTable() {
                // ซ่อนทุกแถวก่อน
                for (var i = 0; i < totalRows; i++) {
                    rows[i].style.display = "none";
                }

                // แสดงเฉพาะแถวที่อยู่ในหน้าปัจจุบัน
                var start = (currentPage - 1) * rowsPerPage;
                var end = start + rowsPerPage;
                for (var i = start; i < end && i < totalRows; i++) {
                    rows[i].style.display = "table-row";
                }

                // อัพเดทตัวบ่งชี้หน้าปัจจุบัน
                document.getElementById("page").innerText = currentPage + "/" + totalPages;
            }

            // ฟังก์ชันเปลี่ยนไปหน้าก่อนหน้า
            function prevPage() {
                if (currentPage > 1) {
                    currentPage--;
                    displayTable();
                }
            }

            // ฟังก์ชันเปลี่ยนไปหน้าถัดไป
            function nextPage() {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayTable();
                }
            }

            // ฟังก์ชันสำหรับเปลี่ยนจำนวนแถวต่อหน้า
            function apply_Number_of_Rows() {
                var select = document.getElementById("number_of_rows");
                rowsPerPage = parseInt(select.value);
                currentPage = 1; // รีเซ็ตหน้าปัจจุบัน
                totalPages = Math.ceil(totalRows / rowsPerPage);
                displayTable();
            }

            // เริ่มต้นแสดงข้อมูล
            displayTable();
        </script>

         
        </tbody>
    </table>
    
    <div class="btn-group">
        <button class="button" id="btn_prev" onclick="prevPage()">Prev</button>
        <button class="button" id="btn_next" onclick="nextPage()">Next</button>
        <div style="display: inline-block; position:relative; margin-left: 2px;">
            <p style="position:relative; font-size: 14px;"> Table : <span id="page"></span></p>
        </div>
        <select name="number_of_rows" id="number_of_rows">
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
        <button class="button" id="btn_apply" onclick="apply_Number_of_Rows()">Apply</button>
        <button class="button" onclick="exportTableToExcel()">Export to Excel</button>
    </div>

 
</body>
</html>
