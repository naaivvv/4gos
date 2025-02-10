<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include_once('db_connection.php');

$sql = "SELECT s1, s2, s3, s4 FROM socket ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["s1" => 0, "s2" => 0, "s3" => 0, "s4" => 0]);
}

$conn->close();
?>
