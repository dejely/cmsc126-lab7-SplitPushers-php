<?php
// Stores the message that index.php displays after registration.
$insertMessage = "";

// Prevents this handler from running unless the register form was submitted.
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["submit"])) {
    header("Location: ../app/index.php");
    exit;
}

// Loads database settings and the shared upload helper.
require_once __DIR__ . "/../db/config.php";
require_once __DIR__ . "/upload.php";

// Opens the MariaDB connection used by this insert request.
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Maps selected course names to their stored course IDs.
$courses = ["BS Computer Science"=>1, "BS Statistics"=>2, "BS Applied Mathematics"=>3, "BS Chemistry"=>4];

// Reads submitted registration values and saves the optional uploaded profile file.
$studentName = mysqli_real_escape_string($conn, $_POST["studentName"]);
$age = mysqli_real_escape_string($conn, $_POST["age"]);
$email = mysqli_real_escape_string($conn, $_POST["email"]);
$courseName = mysqli_real_escape_string($conn, $_POST["courseName"]);
$courseID = $courses[$courseName];
$yearLvl = mysqli_real_escape_string($conn, $_POST["yearLvl"]);
$graduating = isset($_POST["graduating"]) ? 1:0;
$profilePath = saveUploadedProfile("profile", $insertMessage);

// Stops the insert when the uploaded file cannot be saved.
if ($profilePath === false) {
    $conn->close();
    return;
}

// Inserts the student's personal information and stored profile path.
$sql = 
"INSERT INTO student (studentName, age, email, profilePath)
VALUES ('{$studentName}', '{$age}', '{$email}', '{$profilePath}')
";
if ($conn->query($sql) == TRUE) {
    $last_id = $conn->insert_id;
}else{
    $insertMessage = "Error inserting into student table: " . $conn->error;
}

// Inserts the student's academic information after the student row succeeds.
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

// Closes the database connection after the insert workflow finishes.
$conn->close();
?>
