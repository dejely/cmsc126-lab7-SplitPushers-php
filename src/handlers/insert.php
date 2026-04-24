<?php
$insertMessage = "";

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["submit"])) {
    header("Location: ../app/index.php");
    exit;
}

require_once __DIR__ . "/../db/config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    $last_id = $conn->insert_id;
}else{
    $insertMessage = "Error inserting into student table: " . $conn->error;
}

if ($insertMessage === "") {
    $sql =
    "INSERT INTO academics (studentID, courseID, courseName, yearLvl, graduating)
    VALUES ('{$last_id}', '{$courseID}', '{$courseName}', '{$yearLvl}', '{$graduating}')
    ";
    if ($conn->query($sql) == TRUE) {
        $insertMessage = "Student record inserted successfully.";
    }else{
        $insertMessage = "Error inserting into academics table: " . $conn->error;
    }
}

$conn->close();
?>
