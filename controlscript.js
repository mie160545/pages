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
            // ตรวจสอบว่ามีอีลีเมนต์อยู่
            if (temperatureElement) {
                temperatureElement.innerHTML = data.temperature; // สมมุติว่าค่าที่ส่งคืนมาเป็นอุณหภูมิ
            }
            if (humidityElement) {
                humidityElement.innerHTML = data.humidity; // สมมุติว่าค่าที่ส่งคืนมาเป็นความชื้น
            }
            if (rainElement) {
                rainElement.innerHTML = data.rain; // สมมุติว่าค่าที่ส่งคืนมาเป็นความชื้น
            }
            if (soilElement) {
                soilElement.innerHTML = data.soil; // สมมุติว่าค่าที่ส่งคืนมาเป็นความชื้น
            }
            // เพิ่มการตรวจสอบอื่น ๆ สำหรับอีลีเมนต์อื่น ๆ
        }
    };
    xmlhttp.open("GET", "url_to_fetch_data", true); // ปรับ URL ตามความเหมาะสม
    xmlhttp.send();
}


function openTab(tabName) {
    var tabcontent = document.getElementsByClassName("tab-content");
    for (var i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }
    document.getElementById(tabName).classList.add("active");
}

function setHumidity() {
    var humidity = document.getElementById("humidity").value;
    alert("ตั้งค่าความชื้นที่ " + humidity + "%");
    // ส่งข้อมูลไปที่ backend เพื่อกำหนดความชื้นได้
}

const hamburger = document.querySelector('.hamburger');
const leftContent = document.querySelector('.left-content');

hamburger.addEventListener('click', () => {
    leftContent.classList.toggle('active');
});


function setAutoHumidity() {
    let humidity = document.getElementById("autoHumidity").value;
    // ส่งค่าความชื้นขั้นต่ำไปยัง backend เพื่อใช้ในการคำนวณการทำงานอัตโนมัติ
    fetch('/api/setAutoHumidity', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ humidity })
    }).then(response => response.json())
    .then(data => {
        alert('ตั้งค่าความชื้นอัตโนมัติสำเร็จ');
    }).catch(error => {
        console.error('Error:', error);
    });
}

function GetTogBtnLEDState(pumpID) {
    let pumpState = document.getElementById(pumpID).checked ? 'on' : 'off';
    
    // ส่งสถานะของปั๊มน้ำไปยัง backend
    fetch('/api/controlPump', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pumpID, pumpState })
    }).then(response => response.json())
    .then(data => {
        console.log('สถานะปั๊มน้ำ:', data);
    }).catch(error => {
        console.error('Error:', error);
    });
}       

function fetchESP32Data() {
    fetch('/api/getSensorData')
    .then(response => response.json())
    .then(data => {
        document.getElementById('ESP32_01_Temp').innerText = data.temperature;
        document.getElementById('ESP32_01_Humd').innerText = data.humidity;
        document.getElementById('ESP32_01_rain').innerText = data.rain;
        document.getElementById('ESP32_01_soil').innerText = data.soil;
        document.getElementById('ESP32_01_Status_Read_DHT11').innerText = data.sensorStatus;
        document.getElementById('ESP32_01_LTRD').innerText = data.lastReadTime;
    })
    .catch(error => {
        console.error('Error fetching sensor data:', error);
    });
}

// เรียก fetchESP32Data ทุกๆ 5 วินาที
setInterval(fetchESP32Data, 5000);

function controlPump(pumpID, pumpState) {
    fetch('/api/controlPump', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pumpID, pumpState })
    }).then(response => response.json())
    .then(data => {
        console.log('สถานะปั๊มน้ำ:', data);
    }).catch(error => {
        console.error('Error:', error);
    });
}

