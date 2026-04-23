<?php
$sql = "CREATE DATABASE lab7";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
}else{
    echo "Error creating database: ".$conn->error;
}
?>