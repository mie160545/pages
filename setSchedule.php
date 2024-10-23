<?php
// เชื่อมต่อฐานข้อมูล
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่ามีข้อมูล startTime และ stopTime ถูกส่งมาหรือไม่
    if (isset($_POST['startTime']) && isset($_POST['stopTime'])) {
        $startTime = $_POST['startTime'];
        $stopTime = $_POST['stopTime'];

        // ตรวจสอบรูปแบบเวลา (ในรูปแบบ HH:MM:SS)
        if (preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $startTime) && 
            preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $stopTime)) {
            
            // SQL query สำหรับบันทึกเวลาลงในตารางการตั้งเวลาการรดน้ำ
            $sql = "INSERT INTO pump_schedule (start_time, stop_time) VALUES ('$startTime', '$stopTime')";

            if ($conn->query($sql) === TRUE) {
                echo "ตั้งเวลาสำเร็จ";
            } else {
                echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $conn->error;
            }
        } else {
            echo "รูปแบบเวลาไม่ถูกต้อง";
        }
    } else {
        echo "กรุณาเลือกเวลาเริ่มและหยุด";
    }
} else {
    echo "Invalid Request Method";
}

$conn->close();
?>
