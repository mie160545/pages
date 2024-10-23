<?php
  require 'database.php';

  //---------------------------------------- Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    //........................................ keep track post values
    $id = $_POST['id'];
    $pumnum = $_POST['pumnum'];
    $pumstate = $_POST['pumstate'];

    echo "ID: " . $id . " has been updated successfully!";

    // ตรวจสอบชื่อคอลัมน์ที่ถูกต้อง (เฉพาะ PUM_01 หรือ PUM_02)
    $valid_columns = ['PUM_01', 'PUM_02'];
    if (!in_array($pumnum, $valid_columns)) {
      die("Invalid column name.");
    }

    // เชื่อมต่อฐานข้อมูล
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // อัปเดตสถานะของ PUM (รีเลย์)
    $sql = "UPDATE esp32_table_dht11_leds_update SET " . $pumnum . " = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($pumstate, $id)); // แก้ไขจาก $ledstate เป็น $pumstate

    // ปิดการเชื่อมต่อฐานข้อมูล
    Database::disconnect();

    // แสดงค่า id
  }
  //---------------------------------------- 
?>
