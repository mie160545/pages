<?php
  session_start();
  session_unset(); // ลบตัวแปรเซสชันทั้งหมด
  session_destroy(); // ลบ session ทั้งหมด
  header("Location:index.php"); // เปลี่ยนเส้นทางไปยังหน้า login
  exit();
?>
