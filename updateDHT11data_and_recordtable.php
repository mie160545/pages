<?php
  require 'database.php';
  
  //---------------------------------------- Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    // Keep track of POST values
    $id = $_POST['id'];
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $rain = $_POST['rain'];
    $soil = $_POST['soil'];
    $status_read_sensor_dht11 = $_POST['status_read_sensor_dht11'];
    $pum_01 = $_POST['pum_01'];
    $pum_02 = $_POST['pum_02'];
    
    // Get the time and date
    date_default_timezone_set("Asia/Jakarta");
    $tm = date("H:i:s");
    $dt = date("Y-m-d");
    
    // Update data in the table
    $sql = "UPDATE esp32_table_dht11_leds_update 
            SET temperature = ?, humidity = ?, rain = ?, soil=?, status_read_sensor_dht11 = ?, time = ?, date = ? 
            WHERE id = ?";
    $q = $conn->prepare($sql);
    $q->bind_param('sssssssi', $temperature, $humidity, $rain, $soil, $status_read_sensor_dht11, $tm, $dt, $id);
    $q->execute();
    
    // Insert data into a record table
    $id_key;
    $board = $_POST['id'];
    $found_empty = false;
    
    // Check if "id" is already in use
    while (!$found_empty) {
      $id_key = generate_string_id(10);
      $sql = 'SELECT * FROM esp32_table_dht11_leds_record WHERE id = ?';
      $q = $conn->prepare($sql);
      $q->bind_param('s', $id_key);
      $q->execute();
      $result = $q->get_result();
      
      if (!$result->fetch_assoc()) {
        $found_empty = true;
      }
    }
    
    // Insert data into the table
    $sql = "INSERT INTO esp32_table_dht11_leds_record 
            (id, board, temperature, humidity, rain, soil, status_read_sensor_dht11, PUM_01, PUM_02, time, date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $conn->prepare($sql);
    $q->bind_param('sssssssssss', $id_key, $board, $temperature, $humidity, $rain, $soil, $status_read_sensor_dht11, $pum_01, $pum_02, $tm, $dt);
    $q->execute();
  }

  //---------------------------------------- Function to create "id" based on numbers and characters.
  function generate_string_id($strength = 16) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
      $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    }
    return $random_string;
  }
?>
