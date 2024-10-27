//------------------------------------------------------------
document.getElementById("ESP32_01_Temp").innerHTML = "NN"; 
document.getElementById("ESP32_01_Humd").innerHTML = "NN";
document.getElementById("ESP32_01_Rain").innerHTML = "NN";
document.getElementById("ESP32_01_Soil").innerHTML = "NN";
document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = "NN";
document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
//------------------------------------------------------------

// เรียกใช้ข้อมูลจาก ESP32 เมื่อโหลดหน้า
document.addEventListener('DOMContentLoaded', function () {
    Get_Data("esp32_01");
    setInterval(myTimer, 5000);
});

function myTimer() {
    Get_Data("esp32_01");
}

function Get_Data(id) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            const data = JSON.parse(this.responseText);
            const temperatureElement = document.getElementById("ESP32_01_Temp");
            const humidityElement = document.getElementById("ESP32_01_Humd");
            const soilyElement = document.getElementById("ESP32_01_Soil");
            // ตรวจสอบว่ามีอีลีเมนต์อยู่
            if (temperatureElement) {
                temperatureElement.innerHTML = data.temperature; // สมมุติว่าค่าที่ส่งคืนมาเป็นอุณหภูมิ
            }
            if (humidityElement) {
                humidityElement.innerHTML = data.humidity; // สมมุติว่าค่าที่ส่งคืนมาเป็นความชื้น
            }
            // เพิ่มการตรวจสอบอื่น ๆ สำหรับอีลีเมนต์อื่น ๆ
            if (soilyElement) {
                soilyElement.innerHTML = data.soil; // สมมุติว่าค่าที่ส่งคืนมาเป็นความชื้น
            }
        }
    };
    xmlhttp.open("GET", "url_to_fetch_data", true); // ปรับ URL ตามความเหมาะสม
    xmlhttp.send();
}
    setInterval(function() {
      Get_Data("esp32_01");
    }, (5000)
  );
  // ดึงชื่อผู้ใช้จาก LocalStorage หรือ Cookie

