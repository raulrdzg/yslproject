<?php
// db.php
$host = "localhost"; // Replace with your host (usually localhost)
$username = "root";  // Replace with your database username
$password = "";      // Replace with your database password
$dbname = "cpsm";    // Replace with your database name

$conn = mysqli_connect($host, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
