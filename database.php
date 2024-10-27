<?php
$servername = "localhost"; // หรือชื่อเซิร์ฟเวอร์ของคุณ
$username = "smartframk_smartframk";
$password = "de3kr_wtzbf_B-d";
$dbname = "smartframk_esp32_project";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
else{
    echo("");
}
?>
