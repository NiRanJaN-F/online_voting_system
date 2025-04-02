<?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default is empty
$database = "voting_system"; // Change if your DB name is different

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
