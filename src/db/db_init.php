<?php
//connection init
$servername = "localhost";
$username = "root";
$password = "";
$conn = new mysqli($servername, $username, $password, $dbname);

//database init
$sql_db = "CREATE DATABASE lab7";
if ($conn->query($sql_db) === TRUE) {
    echo "Database created successfully";
}else{
    echo "Error creating database: ".$conn->error;
}

$conn->close();
?>