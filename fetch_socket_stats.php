<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include_once('db_connection.php');

// Count occurrences where s1, s2, s3, or s4 is ON (1)
$sql = "SELECT 
            SUM(s1) AS s1_count,
            SUM(s2) AS s2_count,
            SUM(s3) AS s3_count,
            SUM(s4) AS s4_count
        FROM socket";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Calculate total ON occurrences (sum of all ON values)
    $totalOn = (int)$row["s1_count"] + (int)$row["s2_count"] + (int)$row["s3_count"] + (int)$row["s4_count"];

    // Calculate percentage for each socket (relative to total ON states)
    $data = [
        "s1" => $totalOn > 0 ? round(($row["s1_count"] / $totalOn) * 100, 2) : 0,
        "s2" => $totalOn > 0 ? round(($row["s2_count"] / $totalOn) * 100, 2) : 0,
        "s3" => $totalOn > 0 ? round(($row["s3_count"] / $totalOn) * 100, 2) : 0,
        "s4" => $totalOn > 0 ? round(($row["s4_count"] / $totalOn) * 100, 2) : 0
    ];

    echo json_encode($data);
} else {
    echo json_encode(["s1" => 0, "s2" => 0, "s3" => 0, "s4" => 0]);
}

$conn->close();
?>
