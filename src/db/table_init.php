<?php
require_once __DIR__ . "/connection.php";

//create tables
//considering making a seperate table to track courses and course names
$sql_student_table = 
"CREATE TABLE IF NOT EXISTS student(
    studentID INT(8) AUTO_INCREMENT PRIMARY KEY,
    studentName VARCHAR(30) NOT NULL,
    age INT(2) NOT NULL,
    email VARCHAR(50) NOT NULL
    )
";
$sql_acad_table = 
"CREATE TABLE IF NOT EXISTS academics(
    studentID INT(8) PRIMARY KEY,
    courseID INT(4),
    courseName VARCHAR(50) NOT NULL,
    yearLvl INT(2),
    graduating BOOL
)
";
if ($conn->query($sql_student_table) === TRUE) {
    echo "Student table ready successfully<br>";
}else{
    echo "Error creating student table: ".$conn->error;
}
if ($conn->query($sql_acad_table) === TRUE) {
    echo "Academics table ready successfully<br>";
}else{
    echo "Error creating academics table: ".$conn->error;
}
$conn->close();
?>
