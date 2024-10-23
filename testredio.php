<!DOCTYPE HTML>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หน้าควบคุม</title>
    <style>
        /* สไตล์สำหรับ checkbox */
        .custom-checkbox {
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .custom-checkbox input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-checkbox:after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            border: 2px solid #ccc;
            background-color: white;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .custom-checkbox input:checked + div {
            background-color: #4CAF50; /* สีที่แสดงเมื่อถูกเลือก */
            border-color: #4CAF50;
        }

        /* ปุ่มล็อคเมนู */
        .lock {
            pointer-events: none;
            opacity: 0.5;
        }
    </style>
</head>
<body>

<div class="tab-control"> <br>
    <div class="quizoption">
        หน้าควบคุมควบคุมด้วยตนเอง: 
        <label class="custom-checkbox">
            <input type="checkbox" id="checkbox1" onclick="myFunction('control.php', this)">
            <div></div>
        </label>
    </div>
    <div class="quizoption">
        หน้าควบคุมอัตโนมัติ: 
        <label class="custom-checkbox">
            <input type="checkbox" id="checkbox2" onclick="myFunction('controlAuto.php', this)">
            <div></div>
        </label>
    </div>
    <div class="quizoption">
        หน้ากำหนดเวลา: 
        <label class="custom-checkbox">
            <input type="checkbox" id="checkbox3" onclick="myFunction('controlmanual.php', this)">
            <div></div>
        </label>
    </div>
</div>

<script>
let activeTab = null; // ตัวแปรสำหรับเก็บหน้าเมนูที่ถูกเลือก

function myFunction(page, checkbox) {
    // ถ้า checkbox ถูกเลือก
    if (checkbox.checked) {
        // ตรวจสอบว่าไม่มี tab อื่นที่เปิดอยู่
        if (activeTab === null) {
            activeTab = page; // ตั้งค่า activeTab
            window.location.href = page; // นำทางไปยังหน้าใหม่
        } else {
            alert("กรุณาทำงานในหน้าที่เปิดอยู่ให้เสร็จก่อน!");
            checkbox.checked = false; // ยกเลิกการเลือก checkbox
        }
    } else {
        // ถ้าต้องการให้ล็อคเมนูอื่นเมื่อยกเลิก checkbox
        checkbox.checked = false;
    }

    // ถ้าหน้ากำลังทำงานอยู่ (activeTab ถูกตั้งค่า)
    if (activeTab) {
        // ล็อคเมนูทั้งหมด
        document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function(input) {
            if (input !== checkbox) {
                input.disabled = true;
            }
        });
    } else {
        // เปิดใช้งานเมนูทั้งหมดเมื่อไม่มี activeTab
        document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function(input) {
            input.disabled = false;
        });
    }
}

// ฟังก์ชันสำหรับปลดล็อคเมนูเมื่อทำงานเสร็จ
function completeTask() {
    alert("งานเสร็จสิ้น! สามารถเลือกเมนูอื่นได้แล้ว");
    activeTab = null; // รีเซ็ต activeTab
    document.querySelectorAll('.quizoption input[type="checkbox"]').forEach(function(input) {
        input.disabled = false; // เปิดใช้งานเมนูทั้งหมด
    });
}
</script>

<button onclick="completeTask()">เสร็จสิ้นงาน</button> <!-- ปุ่มสำหรับเสร็จสิ้นงาน -->

</body>
</html>
