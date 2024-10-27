<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>หน้าโหมดตั้งเวลา</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            <p>สวัสดี, <?php echo $_SESSION['username']; ?>!</p>

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
                <a href="recordtable.php" class="nav-link"><i
                        class="fa-solid fa-square-poll-vertical"></i>แสดงผลข้อมูล</a>
            </li>
            <li class="nav-item">
                <a href="chart_page.php" class="nav-link"><i
                        class="fa-solid fa-square-poll-vertical"></i>กราฟแสดงผลข้อมูล</a>
            </li>
            <li class="nav-item">
                <a href="edituser.php" class="nav-link"><i class="fa-solid fa-user-pen"></i>เปลี่ยนรหัสผ่าน</a>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link"><i class="fa-solid fa-outdent"></i>ออกจากระบบ</a>
            </li>
        </ul>
    </div>

    <!-- ปุ่มแท็บสำหรับเลือกโหมด -->
    <div class="tab-control">
        <div class="quizoption">
            <input type="radio" name="quizoption" id="quizoption1" value="1" onclick="handleRadioClick('control.php')">
            <label for="quizoption1"></label>
            <a href="control.php">หน้าหลักการควบคุม</a>

        </div>
        <div class="quizoption">
            <input type="radio" name="quizoption" id="quizoption3" value="3"
                onclick="handleRadioClick ('controlmanual.php')">
            <label for="quizoption3"></label>
            <a href="controlmanual.php">กำหนดเวลา</a>

        </div>
        <div>
            <button class="tab-btn" id="button1" onclick="openTab('control.php')">โหมดควบคุมด้วยตนเอง</button>
            <button class="tab-btn" id="button3" onclick="openTab('controlmanual.php')">โหมดกำหนดเวลา</button>
        </div>
    </div>
    <script>
        let activeTab = null; // ตัวแปรสำหรับเก็บหน้าเมนูที่ถูกเลือก

        function myFunction(page, checkbox) {
            // ถ้า checkbox ถูกเลือก
            if (checkbox.checked) {
                // ตรวจสอบว่าไม่มี tab อื่นที่เปิดอยู่
                if (activeTab === null) {
                    activeTab = page; // ตั้งค่า activeTab
                    window.location.href = page; // นำทางไปยังหน้าใหม่
                } else {
                    alert("กรุณาทำงานในหน้าที่เปิดอยู่ให้เสร็จก่อน!");
                    checkbox.checked = false; // ยกเลิกการเลือก checkbox
                }
            } else {
                // ถ้าต้องการให้ล็อคเมนูอื่นเมื่อยกเลิก checkbox
                checkbox.checked = false;
            }

            // ถ้าหน้ากำลังทำงานอยู่ (activeTab ถูกตั้งค่า)
            if (activeTab) {
                // ล็อคเมนูทั้งหมด
                document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function (input) {
                    if (input !== checkbox) {
                        input.disabled = true;
                    }
                });
            } else {
                // เปิดใช้งานเมนูทั้งหมดเมื่อไม่มี activeTab
                document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function (input) {
                    input.disabled = false;
                });
            }
        }

        // ฟังก์ชันสำหรับปลดล็อคเมนูเมื่อทำงานเสร็จ
        function completeTask() {
            alert("งานเสร็จสิ้น! สามารถเลือกเมนูอื่นได้แล้ว");
            activeTab = null; // รีเซ็ต activeTab
            document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function (input) {
                input.disabled = false; // เปิดใช้งานเมนูทั้งหมด
            });
        }
    </script>

    <script>
        let activeTab = null;

        function handleRadioClick(page) {
            if (!activeTab) {
                // ถ้ายังไม่มี tab ที่ active ให้ทำการเปิด tab นั้น
                openTab(page);
                // ล็อค radio และ button อื่นๆ
                disableOtherOptions();
            } else {
                alert("กรุณาทำงานในหน้านี้ให้เสร็จก่อนเปลี่ยนเมนู");
            }
        }

        function openTab(page) {
            activeTab = page; // ตั้งค่า activeTab เป็นหน้าใหม่
            window.location.href = page;
        }

        function disableOtherOptions() {
            // ปิดการใช้งาน radio และ button อื่นๆ
            document.querySelectorAll("input[type='radio']").forEach(input => {
                if (!input.checked) {
                    input.disabled = true;
                }
            });
            document.querySelectorAll("button.tab-btn").forEach(button => {
                if (button !== document.querySelector(".tab-btn[onclick*='" + activeTab + "']")) {
                    button.disabled = true;
                }
            });
        }

        function enableAllOptions() {
            // เปิดการใช้งาน radio และ button ทุกอัน
            document.querySelectorAll("input[type='radio']").forEach(input => input.disabled = false);
            document.querySelectorAll("button.tab-btn").forEach(button => button.disabled = false);
            activeTab = null; // เคลียร์ activeTab หลังทำงานเสร็จ
        }

        // ตัวอย่างของปุ่มที่กดเพื่อเสร็จสิ้นงานในหน้านั้นแล้วปลดล็อคตัวเลือก
        function completeTask() {
            alert("งานเสร็จสิ้น! สามารถเลือกเมนูอื่นได้แล้ว");
            enableAllOptions();
        }
    </script>

    <!-- เนื้อหาของโหมดกำหนดเอง -->
    <div class="fromsettime" id="manual">
        <h3>โหมดกำหนดเอง</h3>
        <p>ตั้งเวลาการทำงานของปั๊มน้ำ</p>

        <!-- ตั้งเวลา 3 ครั้ง -->
        <div>
            <h4>ตั้งเวลา 1</h4>
            <label for="startTime1">เวลาเริ่ม:</label>
            <input type="time" id="startTime1" name="startTime1">
            <label for="stopTime1">เวลาหยุด:</label>
            <input type="time" id="stopTime1" name="stopTime1">
        </div>

        <div>
            <h4>ตั้งเวลา 2</h4>
            <label for="startTime2">เวลาเริ่ม:</label>
            <input type="time" id="startTime2" name="startTime2">
            <label for="stopTime2">เวลาหยุด:</label>
            <input type="time" id="stopTime2" name="stopTime2">
        </div>

        <div>
            <h4>ตั้งเวลา 3</h4>
            <label for="startTime3">เวลาเริ่ม:</label>
            <input type="time" id="startTime3" name="startTime3">
            <label for="stopTime3">เวลาหยุด:</label>
            <input type="time" id="stopTime3" name="stopTime3">
        </div>

        <div>
        <h4 class="PUMColor"><i class="fa-solid fa-arrow-up-from-water-pump"></i> P UM1</h4>
          <label class="checkbox">
            <input type="checkbox" id="ESP32_01_TogPUM_01" onclick="GetTogBtnPUMState('ESP32_01_TogPUM_01')">
            <div class=""></div>
          </label>
          <h4 class="PUMColor"><i class="fa-solid fa-arrow-up-from-water-pump"></i> PUM 2</h4>
          <label class="checkbox">
            <input type="checkbox" id="ESP32_01_TogPUM_02" onclick="GetTogBtnPUMState('ESP32_01_TogPUM_02')">
            <div class=""></div>
          </label>
        </div>

        <!-- <button onclick="completeTask()" onclick="setPumpSchedule()">ตั้งเวลา</button> <br> -->
        <button onclick="setPumpSchedule()">ตั้งเวลา</button> <br>

        <!-- ปุ่มบันทึกและยกเลิก -->
    </div>

    <!-- ควบคุมปั๊มน้ำ -->
    <script>
        // ฟังก์ชันนี้สำหรับตั้งเวลาปั๊มน้ำและส่งไปยัง PHP
        function setPumpSchedule() {
            var schedules = [
                {
                    start: document.getElementById("startTime1").value,
                    stop: document.getElementById("stopTime1").value
                },
                {
                    start: document.getElementById("startTime2").value,
                    stop: document.getElementById("stopTime2").value
                },
                {
                    start: document.getElementById("startTime3").value,
                    stop: document.getElementById("stopTime3").value
                }
            ];

            // ตรวจสอบว่ามีการกรอกค่าเวลาเริ่มและหยุดครบถ้วนหรือไม่
            let incomplete = false;
            schedules.forEach(schedule => {
                if (!schedule.start || !schedule.stop) {
                    alert("กรุณาใส่เวลาเริ่มและเวลาหยุดให้ครบทุกครั้ง");
                    incomplete = true;
                }
            });
            if (incomplete) return; // หยุดการทำงานถ้าข้อมูลไม่ครบ

            // ส่งข้อมูลไปที่ PHP ผ่าน AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "updatePUMs.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            // จัดรูปแบบข้อมูลที่ต้องการส่ง
            var params = "startTime1=" + schedules[0].start + "&stopTime1=" + schedules[0].stop +
                "&startTime2=" + schedules[1].start + "&stopTime2=" + schedules[1].stop +
                "&startTime3=" + schedules[2].start + "&stopTime3=" + schedules[2].stop;

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert('success'); // แสดงผลลัพธ์ที่ได้จาก PHP
                }
            };
            xhr.send(params); // ส่งข้อมูลไปที่ PHP
        }


        // ฟังก์ชันควบคุมปั๊มน้ำ
        function controlWaterSupply(action) {
            if (action === "ON") {
                console.log("เปิดปั๊มน้ำ");
                // เพิ่มโค้ดสำหรับเปิดปั๊มน้ำที่นี่
            } else if (action === "OFF") {
                console.log("หยุดปั๊มน้ำ");
                // เพิ่มโค้ดสำหรับหยุดปั๊มน้ำที่นี่
            }
        }
    </script>

    <!-- __ DISPLAYS MONITORING AND CONTROLLING ____________________________________________________________________________________________ -->
    <div class="content">
        <div class="cards">
            <div class="card">
                <div class="card header">
                    <h3 style="font-size: 1rem;">แสดงผล</h3>
                </div>

                <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i> TEMPERATURE</h4>
                <p class="temperatureColor"><span class="reading"><span id="ESP32_01_Temp"></span> &deg;C</span></p>
                <h4 class="humidityColor"><i class="fas fa-tint"></i> HUMIDITY</h4>
                <p class="humidityColor"><span class="reading"><span id="ESP32_01_Humd"></span> &percnt;</span></p>
                <h4 class="rainColor"><i class="fa-solid fa-cloud-showers-heavy"></i> RAIN</h4>
                <p class="rainColor"><span class="reading"><span id="ESP32_01_Rain"></span> &percnt;</span></p>
                <h4 class="soilColor"><i class="fa-solid fa-seedling"></i> SOIL</h4>
                <p class="soilColor"><span class="reading"><span id="ESP32_01_Soil"></span> &percnt;</span></p>

                <p class="statusreadColor"><span>Status Read Sensor DHT11 : </span><span
                        id="ESP32_01_Status_Read_DHT11"></span></p>
            </div>
        </div>
    </div>

    <br>

    <div class="content">
        <div class="cards">
            <div class="card header" style="border-radius: 15px;">
                <h3 style="font-size: 0.7rem;">LAST TIME RECEIVED DATA FROM ESP32 [ <span id="ESP32_01_LTRD"></span> ]
                </h3>
                <button onclick="window.open('recordtable.php', '_blank');">Open Record Table</button>
            </div>
        </div>
    </div>

    <script>
        //------------------------------------------------------------
        document.getElementById("ESP32_01_Temp").innerHTML = "NN";
        document.getElementById("ESP32_01_Humd").innerHTML = "NN";
        document.getElementById("ESP32_01_Rain").innerHTML = "NN";
        document.getElementById("ESP32_01_Soil").innerHTML = "NN";
        document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = "NN";
        document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
        //------------------------------------------------------------

        Get_Data("esp32_01");

        setInterval(myTimer, 5000);

        //------------------------------------------------------------
        function myTimer() {
            Get_Data("esp32_01");
        }
        //------------------------------------------------------------

        function Update_PUMs(id, lednum, ledstate) {
            if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
            } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                //document.getElementById("demo").innerHTML = this.responseText;
            }
            }
            console.log("Sending: id=" + id + "&lednum=" + lednum + "&ledstate=" + ledstate);  // ตรวจสอบข้อมูลที่จะส่งไปยัง PHP
            xmlhttp.open("POST", "updatePUMs.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send("id=" + id + "&pumnum=" + lednum + "&pumstate=" + ledstate);
        }

        //------------------------------------------------------------
        function Get_Data(id) {
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const myObj = JSON.parse(this.responseText);
                if (myObj.id == "esp32_01") {
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
        xmlhttp.open("POST","getdata.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id);
    }

        //------------------------------------------------------------

        function GetTogBtnPUMState(togbtnid) {
            var togbtnchecked, togbtncheckedsend;
            if (togbtnid == "ESP32_01_TogPUM_01") {
                togbtnchecked = document.getElementById(togbtnid).checked;
                togbtncheckedsend = togbtnchecked ? "ON" : "OFF";
                Update_PUMs("esp32_01", "PUM_01", togbtncheckedsend);  // เปลี่ยนเป็น PUM_01
            }
            if (togbtnid == "ESP32_01_TogPUM_02") {
                togbtnchecked = document.getElementById(togbtnid).checked;
                togbtncheckedsend = togbtnchecked ? "ON" : "OFF";
                Update_PUMs("esp32_01", "PUM_02", togbtncheckedsend);  // เปลี่ยนเป็น PUM_02
            }
        }
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