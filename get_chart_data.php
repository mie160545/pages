<?php
include 'database.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงวันที่ปัจจุบัน
$currentDate = date('Y-m-d');

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลตามวันที่ปัจจุบัน
$sql = $conn->prepare("SELECT date, time, temperature, humidity, rain, soil FROM esp32_table_dht11_leds_record WHERE date = ? ORDER BY time ASC");
$sql->bind_param('s', $currentDate); // ป้องกัน SQL Injection
$sql->execute();
$result = $sql->get_result();

$labels = [];
$temperature = [];
$humidity = [];
$rain = [];
$soil = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // รวมวันที่และเวลาเพื่อใช้ใน labels
        $labels[] = $row['date'] . ' ' . $row['time'];
        $temperature[] = $row['temperature'];
        $humidity[] = $row['humidity'];
        $rain[] = $row['rain'];
        $soil[] = $row['soil'];
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$sql->close();
$conn->close();

// ส่งข้อมูลเป็น JSON กลับไปยัง JavaScript
echo json_encode([
    'labels' => $labels,
    'temperature' => $temperature,
    'humidity' => $humidity,
    'rain' => $rain,
    'soil' => $soil,
]);
?>
