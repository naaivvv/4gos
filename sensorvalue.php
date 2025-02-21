<?php

$servername = "localhost";
$dbname = "outlet";
$username = "root";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve POST data
    $sensor_name = $_POST["sensor_name"];
    $temp = $_POST["temp"];
    $is_fan_on = $_POST["is_fan_on"];
    $energy = $_POST["energy"];
    $voltage = $_POST["voltage"];
    $current = $_POST["current"];
    $ssid = $_POST['ssid'] ?? 'Unknown';
    $ip = $_POST['ip'] ?? 'Unknown';

    // Establish a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert into the `sensor` table
    $sql_sensor = "INSERT INTO sensor (sensor_name, temp, is_fan_on, energy, voltage, current)
                   VALUES ('$sensor_name', '$temp', '$is_fan_on', '$energy', '$voltage', '$current')";

    if ($conn->query($sql_sensor) === TRUE) {
        echo "Sensor data inserted successfully.<br>";
    } else {
        echo "Error: " . $sql_sensor . "<br>" . $conn->error . "<br>";
    }

    // Insert into the `status` table
    $sql_status = "INSERT INTO status (ssid, ip)
                   VALUES ('$ssid', '$ip')";

    if ($conn->query($sql_status) === TRUE) {
        echo "Status data inserted successfully.";
    } else {
        echo "Error: " . $sql_status . "<br>" . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    echo "No data posted with HTTP POST.";
}

?>
