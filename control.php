<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // ส่งผู้ใช้กลับไปหน้า login ถ้ายังไม่ได้เข้าสู่ระบบ
    exit();
}

?>

<!DOCTYPE HTML>
<html lang="th">

<head>
    <title>หน้าควบคุม</title>
    <meta charset="UTF-8"> <!-- รองรับภาษาไทย -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="controlstyle.css"> <!-- เชื่อมไฟล์ CSS -->
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

    <!-- เมนูแถบด้านข้าง -->
    <div class="tab-control">
        <div class="quizoption">
            <input type="radio" name="quizoption" id="quizoption1" value="1" onclick="handleRadioClick('control.php')">
            <label for="quizoption1"></label>
            <a href="control.php">ควบคุมตนเอง</a>

        </div>
        <div class="quizoption">
            <input type="radio" name="quizoption" id="quizoption3" value="3" onclick="handleRadioClick ('controlmanual.php')">
            <label for="quizoption3"></label>
            <a href="controlmanual.php">กำหนดเวลา</a>

        </div>
        <div>
            <button class="tab-btn" id="button1" onclick="openTab('control.php')">โหมดควบคุมด้วยตนเอง</button>
            <button class="tab-btn" id="button3" onclick="openTab('controlmanual.php')">โหมดกำหนดเวลา</button>
        </div>
    </div>

    <!-- เนื้อหาหลัก -->
    <div class="content">
        <div class="cards">

            <!-- == MONITORING ======================================================================================== -->
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">แสดงผล</h3>
                </div>

                <!-- Displays the humidity and temperature values received from ESP32. *** -->
                <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i> TEMPERATURE</h4>
                <p class="temperatureColor"><span class="reading"><span id="ESP32_01_Temp"></span> &deg;C</span></p>
                <h4 class="humidityColor"><i class="fas fa-tint"></i> HUMIDITY</h4>
                <p class="humidityColor"><span class="reading"><span id="ESP32_01_Humd"></span> &percnt;</span></p>
                <h4 class="rainColor"><i class="fa-solid fa-cloud-showers-heavy"></i> RAIN</h4>
                <p class="rainColor"><span class="reading"><span id="ESP32_01_Rain"></span> &percnt;</span></p>
                <h4 class="soilColor"><i class="fa-solid fa-seedling"></i> SOIL</h4>
                <p class="soilColor"><span class="reading"><span id="ESP32_01_Soil"></span> &percnt;</span></p>
                <!-- *********************************************************************** -->


                <p class="statusreadColor"><span>Status Read Sensor DHT11 : </span><span id="ESP32_01_Status_Read_DHT11"></span></p>
            </div>
            <!-- ======================================================================================================= -->
            <!-- == CONTROLLING ======================================================================================== -->
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">ควบคุมน้ำ</h3>
                </div>

                <!-- Buttons for controlling the LEDs on Slave 2. ************************** -->

                <h4 class="PUMColor"><i class="fa-solid fa-arrow-up-from-water-pump"></i> PUM1</h4>
                <label class="switch">
                    <input type="checkbox" id="ESP32_01_TogPUM_01" onclick="GetTogBtnPUMState('ESP32_01_TogPUM_01')">
                    <div class="sliderTS"></div>
                </label>
                <h4 class="PUMColor"><i class="fa-solid fa-arrow-up-from-water-pump"></i> PUM 2</h4>
                <label class="switch">
                    <input type="checkbox" id="ESP32_01_TogPUM_02" onclick="GetTogBtnPUMState('ESP32_01_TogPUM_02')">
                    <div class="sliderTS"></div>
                </label>
                <!-- *********************************************************************** -->
            </div>

            <!-- ======================================================================================================= -->


        </div>
    </div>

    <br>

    <div class="content">
        <div class="cards">
            <div class="card header" style="border-radius: 15px;">
                <h3 style="font-size: 0.7rem;">LAST TIME RECEIVED DATA FROM ESP32 <span id="ESP32_01_LTRD"></span> </h3>
                <button onclick="window.open('recordtable.php', '_blank');">Open Record Table</button>
                <h3 style="font-size: 0.7rem;"></h3>
            </div>
        </div>
    </div>


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

    <!-- ปุ่มแท็บสำหรับเลือกโหมด -->
    <script>
        let activeTab = null; // ตัวแปรเก็บหน้าที่เปิดอยู่

        function openTab(pageUrl) {
            // นำทางไปยังหน้า URL ที่ระบุ
            window.location.href = pageUrl;
        }


        function myFunction(page, checkbox) {
            // ถ้า checkbox ถูกเลือก
            if (checkbox.checked) {
                // ถ้ายังไม่มี activeTab
                if (activeTab === null) {
                    activeTab = page; // ตั้งค่า activeTab
                    // ล็อค checkbox อื่น
                    lockOtherCheckboxes(checkbox);
                    // นำทางไปยังหน้าที่เลือก
                    window.location.href = page;
                } else {
                    alert("กรุณาทำงานในหน้าที่เปิดอยู่ให้เสร็จก่อน!");
                    checkbox.checked = false; // ยกเลิกการเลือก checkbox
                }
            } else {
                // ถ้าไม่ต้องการให้ทำอะไรเมื่อไม่เลือก
                checkbox.checked = false; // ยกเลิกการเลือก checkbox
            }
        }

        function lockOtherCheckboxes(activeCheckbox) {
            // ล็อค checkbox อื่น ๆ
            document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function(input) {
                if (input !== activeCheckbox) {
                    input.disabpum = true; // ปิดการใช้งาน checkbox อื่น
                }
            });
        }

        // ฟังก์ชันสำหรับปลดล็อคเมื่อเสร็จสิ้นงาน
        function completeTask() {
            alert("งานเสร็จสิ้น! สามารถเลือกเมนูอื่นได้แล้ว");
            activeTab = null; // รีเซ็ต activeTab
            document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function(input) {
                input.disabpum = false; // เปิดใช้งาน checkbox ทั้งหมด
                input.checked = false; // รีเซ็ตสถานะ checkbox
            });
        }
    </script>

    <script>
        //------------------------------------------------------------
        document.getElementById("ESP32_01_Temp").innerHTML = "NN";
        document.getElementById("ESP32_01_Humd").innerHTML = "NN";
        document.getElementById("ESP32_01_Rain").innerHTML = "NN";
        document.getElementById("ESP32_01_Soil").innerHTML = "NN";
        document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = "NN";
        document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
        //------------------------------------------------------------

        Get_Data();

        setInterval(myTimer, 5000);

        //------------------------------------------------------------
        function myTimer() {
            Get_Data();
        }
        //------------------------------------------------------------
        // ฟังก์ชันควบคุมปั๊มน้ำ
        function Update_PUMs(lednum, ledstate) {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById("demo").innerHTML = this.responseText;
                }
            }
            xmlhttp.open("POST", "updatePUMs.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("pumnum=" + lednum + "&pumstate=" + ledstate);
        }

        //------------------------------------------------------------

        // เรียกใช้ฟังก์ชันเมื่อมีการอัปเดตข้อมูลจาก ESP32
        function Get_Data() {
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const myObj = JSON.parse(this.responseText);

                    if (myObj.id) {
                        document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
                        document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
                        document.getElementById("ESP32_01_Rain").innerHTML = myObj.rain;
                        document.getElementById("ESP32_01_Soil").innerHTML = myObj.soil;
                        document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = myObj.status_read_sensor_dht11;
                        document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " + myObj.ls_time + " | Date : " + myObj.ls_date + " (dd-mm-yyyy)";
                        if (myObj.PUM_01 == "ON") {
                            document.getElementById("ESP32_01_TogPUM_01").checked = true;
                        } else if (myObj.PUM_01 == "OFF") {
                            document.getElementById("ESP32_01_TogPUM_01").checked = false;
                        }
                        if (myObj.PUM_02 == "ON") {
                            document.getElementById("ESP32_01_TogPUM_02").checked = true;
                        } else if (myObj.PUM_02 == "OFF") {
                            document.getElementById("ESP32_01_TogPUM_02").checked = false;
                        }
                        // checkMoistureAndControl(); // ตรวจสอบความชื้นและควบคุมการให้น้ำที่นี่
                    }
                }
            };
            xmlhttp.open("POST", "getdata.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send();
        }

        //------------------------------------------------------------

        // ฟังก์ชันสำหรับตรวจสอบและส่งคำสั่งควบคุมปั๊มน้ำ
        function GetTogBtnPUMState(togbtnid) {
            var togbtnchecked, togbtncheckedsend;
            if (togbtnid == "ESP32_01_TogPUM_01") {
                togbtnchecked = document.getElementById(togbtnid).checked;
                togbtncheckedsend = togbtnchecked ? "ON" : "OFF";
                Update_PUMs("PUM_01", togbtncheckedsend); // เปลี่ยนเป็น PUM_01
            }
            if (togbtnid == "ESP32_01_TogPUM_02") {
                togbtnchecked = document.getElementById(togbtnid).checked;
                togbtncheckedsend = togbtnchecked ? "ON" : "OFF";
                Update_PUMs("PUM_02", togbtncheckedsend); // เปลี่ยนเป็น PUM_02
            }
        }
    </script>
</body>

</html>