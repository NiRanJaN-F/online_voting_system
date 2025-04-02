<?php
include 'db.php'; // Your database connection

$query = "SELECT name, votes FROM candidates";
$result = mysqli_query($conn, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data); // Send data in JSON format
?>
