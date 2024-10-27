<?php
include 'database.php'; // เชื่อมต่อกับฐานข้อมูล

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=record_data.csv');
$output = fopen('php://output', 'w');

// เขียนหัวตาราง
fputcsv($output, array('ID', 'NAME', 'TEMPERATURE (°C)', 'HUMIDITY (%)', 'RAIN (%)', 'STATUS READ SENSOR DHT11', 'PUM 01', 'PUM 02', 'TIME', 'DATE (dd-mm-yyyy)'));

// ดึงข้อมูลจากฐานข้อมูล
$sql = 'SELECT * FROM esp32_table_dht11_leds_record ORDER BY date, time';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = date_create($row['date']);
        $dateFormat = date_format($date,"d-m-Y");
        
        // เขียนข้อมูลลงในไฟล์ CSV
        fputcsv($output, array(
            $row['board'], 
            $row['NAME'], 
            $row['temperature'], 
            $row['humidity'], 
            $row['rain'], 
            $row['soil'], 
            $row['status_read_sensor_dht11'], 
            $row['PUM_01'], 
            $row['PUM_02'], 
            $row['time'], 
            $dateFormat
        ));
    }
} else {
    // หากไม่มีข้อมูล
    fputcsv($output, array('ไม่พบข้อมูล'));
}

fclose($output);
$conn->close();
?>
