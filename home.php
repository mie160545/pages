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
    <title>หน้าหลัก</title>
    <meta charset="UTF-8"> <!-- รองรับภาษาไทย -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="homestyles.css"> <!-- เชื่อมไฟล์ CSS -->
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
        
      </div>
    </div>

    <br>
    
    <div class="content">
      <div class="cards">
        <div class="card header" style="border-radius: 15px;">
            <h3 style="font-size: 0.7rem;">LAST TIME RECEIVED DATA FROM ESP32 [ <span id="ESP32_01_LTRD"></span> ]</h3>
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
        Get_Data()
      }
      //------------------------------------------------------------

      //------------------------------------------------------------


  // ฟังก์ชันควบคุมปั๊มน้ำ
        function controlWaterPump(action) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "updatePUMs.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert("ปั๊มน้ำถูก " + action + " สำเร็จ");
                }
            };
            xhr.send("id=esp32_01&lednum=PUM_01&ledstate=" + action);
        }


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
                    
                }
            }
        };
        xmlhttp.open("POST","getdata.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
    }

      //------------------------------------------------------------
      
      //------------------------------------------------------------
      function Update_PUMs(id,lednum,ledstate) {
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
        xmlhttp.open("POST","updatePUMs.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id+"&pumnum="+lednum+"&pumstate="+ledstate);
			}
      //------------------------------------------------------------

      function setPumpSchedule() {
          var minMoisture = parseInt(document.getElementById("minMoisture").value);
          var maxMoisture = parseInt(document.getElementById("maxMoisture").value);

          // ตรวจสอบว่าค่าความชื้นถูกตั้งอย่างถูกต้อง
          if (minMoisture >= 0 && maxMoisture <= 100 && minMoisture < maxMoisture) {
              alert("ตั้งค่าความชื้นต่ำสุดเป็น " + minMoisture + "% และสูงสุดเป็น " + maxMoisture + "% สำเร็จ!");
              // คุณสามารถเพิ่มโค้ดบันทึกค่าลงในฐานข้อมูลที่นี่
          } else {
              alert("การตั้งค่าความชื้นไม่สำเร็จ! กรุณาตรวจสอบค่าที่ป้อน.");
          }
      }

</script> 
</body>
</html>
