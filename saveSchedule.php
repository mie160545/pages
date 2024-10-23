<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // หรือชื่อเซิร์ฟเวอร์ของคุณ
$username = "smartframk_smartframk";
$password = "de3kr_wtzbf_B-d";
$dbname = "smartframk_esp32_project";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่าจากแบบฟอร์ม
$startTime1 = $_POST['startTime1'];
$stopTime1 = $_POST['stopTime1'];
$startTime2 = $_POST['startTime2'];
$stopTime2 = $_POST['stopTime2'];
$startTime3 = $_POST['startTime3'];
$stopTime3 = $_POST['stopTime3'];

// ตรวจสอบว่าข้อมูลถูกส่งมาอย่างถูกต้องหรือไม่
echo "Start Time 1: " . $_POST['startTime1'] . "<br>";
echo "Stop Time 1: " . $_POST['stopTime1'] . "<br>";
echo "Start Time 2: " . $_POST['startTime2'] . "<br>";
echo "Stop Time 2: " . $_POST['stopTime2'] . "<br>";
echo "Start Time 3: " . $_POST['startTime3'] . "<br>";
echo "Stop Time 3: " . $_POST['stopTime3'] . "<br>";
echo "Username: " . $_SESSION['username'] . "<br>";

// ตรวจสอบว่าข้อมูลครบถ้วนก่อนบันทึก
if (!empty($startTime1) && !empty($stopTime1) && !empty($startTime2) && !empty($stopTime2) && !empty($startTime3) && !empty($stopTime3)) {
    // SQL สำหรับบันทึกข้อมูลการตั้งเวลาในฐานข้อมูล
    $sql = "INSERT INTO pump_schedule (start_time1, stop_time1, start_time2, stop_time2, start_time3, stop_time3, username) 
            VALUES ('$startTime1', '$stopTime1', '$startTime2', '$stopTime2', '$startTime3', '$stopTime3', '{$_SESSION['username']}')";

    if ($conn->query($sql) === TRUE) {
        echo "บันทึกการตั้งเวลาเสร็จสิ้น";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "กรุณากรอกข้อมูลให้ครบถ้วน";
}



// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
