<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab7";
$conn = new mysqli($servername, $username, $password, $dbname);
/*
Redirect back to registration after submission
if(isset($_POST["submit"])) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
}
*/
//might just turn this array into a table in the database
$courses = ["BS Computer Science"=>1, "BS Statistics"=>2, "BS Applied Mathematics"=>3, "BS Chemistry"=>4];


$studentName = mysqli_real_escape_string($conn, $_POST["studentName"]);
$age = mysqli_real_escape_string($conn, $_POST["age"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$courseName = mysqli_real_escape_string($conn, $_POST["courseName"]);
$courseID = $courses[$courseName];
$yearLvl = mysqli_real_escape_string($conn, $_POST["yearLvl"]);
$graduating = isset($_POST["graduating"]) ? 1:0;

$sql = 
"INSERT INTO student (studentName, age, email)
VALUES ('{$studentName}', '{$age}', '{$email}')
";
if ($conn->query($sql) == TRUE) {
    echo "Student record inserted successfully<br>";
    $last_id = $conn->insert_id;
}else{
    echo "Error inserting into student table: ".$conn->error;
}

$sql = 
"INSERT INTO academics (studentID, courseID, courseName, yearLvl, graduating)
VALUES ('{$last_id}', '{$courseID}', '{$courseName}', '{$yearLvl}', '{$graduating}')
";
if ($conn->query($sql) == TRUE) {
    echo "Student academic record inserted successfully<br>";
}else{
    echo "Error inserting into academics table: ".$conn->error;
}

$conn->close();
?>