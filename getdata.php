<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // ชื่อเซิร์ฟเวอร์
$username = "smartframk_smartframk"; // ชื่อผู้ใช้ฐานข้อมูล
$password = "de3kr_wtzbf_B-d"; // รหัสผ่านฐานข้อมูล
$dbname = "smartframk_esp32_project"; // ชื่อฐานข้อมูล

// เชื่อมต่อฐานข้อมูล
try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ตั้งค่าให้แสดงข้อผิดพลาด
  $pdo->exec("set names utf8"); // ตั้งค่าให้รองรับ UTF-8 (สำหรับภาษาไทย)
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage()); // ถ้าเชื่อมต่อไม่ได้ ให้แสดงข้อผิดพลาด
}

session_start();

if (isset($_SESSION['user_id'])) {
  $id = $_SESSION['user_id'];

  // Initialize an empty object
  $myObj = (object)array();

  // SQL query
  $sql = 'SELECT * FROM esp32_table_dht11_leds_update WHERE user_id="' . $id . '"';

  // Execute the query
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  
  if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $date = date_create($row['date']);
    $dateFormat = date_format($date, "d-m-Y");

    $myObj->id = $row['id'];
    $myObj->temperature = $row['temperature'];
    $myObj->humidity = $row['humidity'];
    $myObj->rain = $row['rain'];
    $myObj->soil = $row['soil'];
    $myObj->status_read_sensor_dht11 = $row['status_read_sensor_dht11'];
    $myObj->PUM_01 = $row['PUM_01'];
    $myObj->PUM_02 = $row['PUM_02'];
    $myObj->ls_time = $row['time'];
    $myObj->ls_date = $dateFormat;

    $myJSON = json_encode($myObj);
    echo $myJSON;
  } else {
    echo json_encode(["error" => "No data found for the given ID"]);
  }
} else {
  echo json_encode(["error" => "User ID not set in session"]);
}
?>