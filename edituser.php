<?php
session_start();
$_SESSION["Id"] = 1;
$conn = mysqli_connect("localhost", "smartframk_smartframk", "de3kr_wtzbf_B-d", "smartframk_esp32_project");

$message = ""; // ประกาศตัวแปร message เพื่อเก็บข้อความแจ้งเตือน

if (count($_POST) > 0) {
    $sql = "SELECT * FROM table_users WHERE id= ?";
    $statement = $conn->prepare($sql);
    $statement->bind_param('i', $_SESSION["Id"]); // ใช้ 'Id' แทน 'userId'
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();

    if (!empty($row)) {
        $hashedPassword = $row["password"];
        $password = password_hash($_POST["newPassword"], PASSWORD_DEFAULT);
        if (password_verify($_POST["currentPassword"], $hashedPassword)) {
            $sql = "UPDATE table_users SET password=? WHERE id=?"; // ใช้ 'id' แทน 'userId'
            $statement = $conn->prepare($sql);
            $statement->bind_param('si', $password, $_SESSION["Id"]);
            $statement->execute();
            $message = "เปลี่ยนรหัสผ่านสำเร็จแล้ว!";
        } else {
            $message = "รหัสผ่านปัจจุบันไม่ถูกต้อง";
        }
    } else {
        $message = "ไม่พบผู้ใช้"; // เพิ่มข้อความแจ้งเตือนเมื่อไม่พบผู้ใช้
    }
}
?>

<html>
<head>
    <title>ผู้ใช้งาน</title>
    <link rel="stylesheet" type="text/css" href="edituserstyle.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="topnav">
        <h3>ระบบควบคุมและบริหารจัดการน้ำแปลงปลูกผัก</h3>
        <div class="hamburger" onclick="toggleMenu()">
            <i class="fas fa-bars"></i>
        </div>
        <div class="username">
            <i class="fas fa-user"></i>
            <p>สวัสดี, <?php echo $_SESSION['username']; ?>!</p>
        </div>
    </div>

    <div class="left-content" id="menu">
        <ul class="nav">
            <li class="nav-item">
                <a href="home.php" class="nav-link"><i class="fa-solid fa-house"></i>หน้าหลัก</a>
            </li>
            <li class="nav-item">
                <a href="control.php" class="nav-link"><i class="fa-solid fa-microchip"></i>ควบคุมการทำงาน</a>
            </li>
            <li class="nav-item">
                <a href="recordtable.php" class="nav-link"><i class="fa-solid fa-square-poll-vertical"></i>แสดงผลข้อมูล</a>
            </li>
            <li class="nav-item">
                <a href="chart_page.php" class="nav-link"><i class="fa-solid fa-square-poll-vertical"></i>กราฟแสดงผลข้อมูล</a>
            </li>
            <li class="nav-item">
                <a href="edituser.php" class="nav-link"><i class="fa-solid fa-user-pen"></i>เปลี่ยนรหัสผ่าน</a>
            </li>
            <li class="nav-item">
                <a href="logout.php" class="nav-link"><i class="fa-solid fa-outdent"></i>ออกจากระบบ</a>
            </li>
        </ul>
    </div>

    <div class="container">
        <form name="frmChange" method="post" action="" onsubmit="return validatePassword()">
            <h2>เปลี่ยนรหัสผ่าน</h2>

            <div class="validation-message text-center"><?php if (isset($message)) { echo $message; } ?></div>

            <div class="row">
                <label>รหัสผ่านปัจจุบัน</label>
                <input type="password" name="currentPassword" />
                <span id="currentPassword" class="validation-message"></span>
            </div>

            <div class="row">
                <label>รหัสผ่านใหม่</label>
                <input type="password" name="newPassword" />
                <span id="newPassword" class="validation-message"></span>
            </div>

            <div class="row">
                <label>ยืนยันรหัสผ่าน</label>
                <input type="password" name="confirmPassword" />
                <span id="confirmPassword" class="validation-message"></span>
            </div>

            <div class="row">
                <input type="submit" name="submit" value="Submit" class="full-width" />
            </div>
            <div class="row">
            <button onclick="window.location.href='home.php'" class="full-width">กลับ</button>
        </div>
        </form>
        
    </div>

    <script>
        function validatePassword() {
            var currentPassword = document.frmChange.currentPassword;
            var newPassword = document.frmChange.newPassword;
            var confirmPassword = document.frmChange.confirmPassword;
            var isValid = true;

            document.getElementById("currentPassword").innerHTML = "";
            document.getElementById("newPassword").innerHTML = "";
            document.getElementById("confirmPassword").innerHTML = "";

            if (!currentPassword.value) {
                document.getElementById("currentPassword").innerHTML = "จำเป็นต้องมีรหัสผ่านปัจจุบัน";
                isValid = false;
            }
            if (!newPassword.value) {
                document.getElementById("newPassword").innerHTML = "จำเป็นต้องใส่รหัสผ่านใหม่";
                isValid = false;
            }
            if (!confirmPassword.value) {
                document.getElementById("confirmPassword").innerHTML = "กรุณายืนยันรหัสผ่านใหม่ของคุณ";
                isValid = false;
            }
            if (newPassword.value !== confirmPassword.value) {
                document.getElementById("confirmPassword").innerHTML = "รหัสผ่านไม่ตรงกัน";
                isValid = false;
            }

            return isValid;
        }
    </script>
    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        }
    </script>
</body>
</html>
