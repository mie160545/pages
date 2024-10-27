<?php
// ข้อมูลการเชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "smartframk_smartframk";
$password = "de3kr_wtzbf_B-d";
$dbname = "smartframk_esp32_project";

session_start();

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec("set names utf8");
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

// ตรวจสอบว่า POST ถูกส่งมาหรือไม่
if (!empty($_POST)) {
  // รับค่าจาก POST
  $pumnum = $_POST['pumnum'];
  $pumstate = $_POST['pumstate'];
  $user_id = $_SESSION['user_id']; // สมมติว่า user_id มาจากเซสชัน

  // ตรวจสอบค่าที่รับเข้ามา
  var_dump($_POST);

  // ตรวจสอบชื่อคอลัมน์ที่ถูกต้อง (เฉพาะ PUM_01 หรือ PUM_02)
  $valid_columns = ['PUM_01', 'PUM_02'];
  if (!in_array($pumnum, $valid_columns)) {
    die("Invalid column name.");
  }

  // ดึง `id` จากฐานข้อมูลตาม `user_id`
  $sql = "SELECT id FROM esp32_table_dht11_leds_update WHERE user_id = :user_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':user_id' => $user_id]);

  // ตรวจสอบว่ามีข้อมูล `id` หรือไม่
  if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['id']; // แทนค่า `$id` ด้วยค่าที่ดึงมา

    // เตรียม SQL สำหรับอัปเดตข้อมูลสถานะของ PUM
    $updateSql = "UPDATE esp32_table_dht11_leds_update SET $pumnum = :pumstate WHERE id = :id AND user_id = :user_id";
    $q = $pdo->prepare($updateSql);

    // แสดง SQL statement เพื่อการ debug
    echo "SQL: " . $updateSql . "<br>";
    echo "Params: pumstate = $pumstate, id = $id, user_id = $user_id<br>";

    // ส่งข้อมูลเพื่ออัปเดต
    if ($q->execute([':pumstate' => $pumstate, ':id' => $id, ':user_id' => $user_id])) {
      echo "Updated PUM state to " . htmlspecialchars($pumstate);
    } else {
      echo "Failed to update PUM state.";
    }
  } else {
    echo "No record found for the given user_id.";
  }
}
