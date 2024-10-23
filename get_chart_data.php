<?php
include 'database.php'; // เชื่อมต่อฐานข้อมูล

// ดึงวันที่ปัจจุบัน
$currentDate = date('Y-m-d');

// สร้าง SQL ที่กรองข้อมูลเฉพาะวันที่ปัจจุบัน
$sql = "SELECT * FROM esp32_table_dht11_leds_record WHERE date = '$currentDate' ORDER BY time ASC";
$result = $conn->query($sql);

$labels = [];
$temperature = [];
$humidity = [];
$rain = [];
$soil = [];


if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $labels[] = $row['time']; // ดึงข้อมูลเวลาไปใช้เป็น labels บนแกน X
        $temperature[] = $row['temperature']; // ดึงข้อมูลอุณหภูมิ
        $humidity[] = $row['humidity']; // ดึงข้อมูลความชื้น
        $rain[] = $row['rain']; // ดึงข้อมูลความชื้น
        $soil[] = $row['soil']; // ดึงข้อมูลความชื้น
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
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
