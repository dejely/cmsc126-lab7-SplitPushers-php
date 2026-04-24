<?php
require_once __DIR__ . "/config.php";

// Connect to MariaDB first, then create the project database.
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_db = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if ($conn->query($sql_db) === TRUE) {
    echo "Database ready successfully";
}else{
    echo "Error creating database: ".$conn->error;
}

$conn->close();
?>
