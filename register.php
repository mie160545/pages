<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $plot_name = isset($_POST['plot_name']) ? $_POST['plot_name'] : '';

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // ตรวจสอบว่าชื่อผู้ใช้มีอยู่แล้วหรือไม่
    $sql_check_username = "SELECT * FROM table_users WHERE username = ?";
    $stmt_check_username = $conn->prepare($sql_check_username);
    $stmt_check_username->bind_param("s", $username);
    $stmt_check_username->execute();
    $result_check_username = $stmt_check_username->get_result();

    // ตรวจสอบว่าชื่อแปลงมีอยู่แล้วหรือไม่
    $sql_check_plotname = "SELECT * FROM table_users WHERE plot_name = ?";
    $stmt_check_plotname = $conn->prepare($sql_check_plotname);
    $stmt_check_plotname->bind_param("s", $plot_name);
    $stmt_check_plotname->execute();
    $result_check_plotname = $stmt_check_plotname->get_result();

    // ตรวจสอบชื่อผู้ใช้และชื่อแปลง
    if ($result_check_username->num_rows > 0) {
        echo "ชื่อผู้ใช้นี้มีอยู่แล้ว กรุณาเลือกชื่อผู้ใช้อื่น";
    } elseif ($result_check_plotname->num_rows > 0) {
        echo "ชื่อแปลงนี้มีอยู่แล้ว กรุณาเลือกชื่อแปลงอื่น";
    } else {
        // เพิ่มข้อมูลผู้ใช้ใหม่ลงในฐานข้อมูล
        $sql = "INSERT INTO table_users (username, password, plot_name) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $plot_name);

        if ($stmt->execute()) {
            echo "ลงทะเบียนสำเร็จ";
        } else {
            echo "เกิดข้อผิดพลาดในการลงทะเบียน";
        }
    }
}
?>
