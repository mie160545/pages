<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM table_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // เก็บข้อมูลในเซสชันเมื่อล็อกอินสำเร็จ
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username; // เก็บชื่อผู้ใช้ในเซสชัน
            header("Location: home.php");
            exit();
        } else {
            $error = "รหัสผ่านไม่ถูกต้อง";
        }
    } else {
        $error = "ชื่อผู้ใช้งานไม่ถูกต้อง";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        html {
            font-family: Arial, display: inline-block; 
            text-align: center;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('pages\ผัก.png'); /* แทนที่ด้วยเส้นทางของภาพที่ต้องการใช้ */
            background-size: cover; /* ทำให้ภาพเต็มหน้าจอ */
            background-position: center; /* จัดภาพให้อยู่กลาง */
            background-repeat: no-repeat; /* ไม่ให้ภาพซ้ำ */
            color: #333;
        }
        .topnav {
            overflow: hidden;
            background-color: #4F6F52;
            color: white;
            font-size: 1.2rem;
            padding: 10px;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9); /* ทำให้พื้นหลังของกล่องลงทะเบียนโปร่งใส */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4F6F52;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .input-container {
            position: relative;
            margin: 10px 0;
        }
        .input-container i {
            position: absolute;
            left: 10px; /* ระยะห่างจากซ้าย */
            top: 50%; /* วางตรงกลาง */
            transform: translateY(-50%); /* ปรับตำแหน่งให้อยู่กลาง */
            color: #888; /* สีของไอคอน */
            font-size: 1.2em; /* ขนาดของไอคอน */
        }
        .input-container input {
            padding: 10px 10px 10px 40px; /* เพิ่ม padding ด้านซ้ายเพื่อเว้นระยะสำหรับไอคอน */
            width: 90%; /* ทำให้ช่องป้อนข้อมูลกว้างเต็ม */
            border: 1px solid #ddd; /* กรอบช่องป้อนข้อมูล */
            border-radius: 5px; /* มุมกลม */
            font-size: 1em; /* ขนาดฟอนต์ */
        }
        button {
            padding: 10px;
            font-size: 1em;
            color: #fff;
            background: #4F6F52;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px; /* เพิ่มระยะห่างระหว่างปุ่มและช่องป้อนข้อมูล */
        }
        button:hover {
            background: #4F6F52;
        }
        .error {
            color: red;
            text-align: center;
            margin: 10px 0; /* เพิ่มระยะห่างด้านบนและด้านล่าง */
        }
        .register-link {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="topnav">
        <h3>ระบบควบคุมและบริหารจัดการน้ำแปลงปลูกผัก</h3>
    </div>
    <div class="container">
        <h1>เข้าสู่ระบบ</h1>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
        <form method="post" action="index.php">
            <div class="input-container">
                <i class="fas fa-user"></i> <!-- ไอคอนสำหรับชื่อผู้ใช้ -->
                <input type="text" name="username" placeholder="ชื่อผู้ใช้" required>
            </div>
            <div class="input-container">
                <i class="fas fa-lock"></i> <!-- ไอคอนสำหรับรหัสผ่าน -->
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
            </div>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <div class="register-link">
            <p>ยังไม่มีบัญชี? <a href="register.html">ลงทะเบียน</a></p>
        </div>
    </div>
</body>
</html>
