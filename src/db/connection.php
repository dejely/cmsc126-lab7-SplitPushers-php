<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connected failed ". $conn->connect_error);
}

echo "Connected Successfully<br/>"
?>