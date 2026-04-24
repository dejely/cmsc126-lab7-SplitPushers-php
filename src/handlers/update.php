<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["update"])) {
    header("Location: ../app/index.php");
    exit;
}

require_once __DIR__ . "/../db/config.php";

$operationStudentID = trim($_POST["studentID"] ?? "");
$operationRows = [];
$operationMessage = "";

if ($operationStudentID === "") {
    $operationMessage = "Enter a student ID.";
    return;
}

$studentName = trim($_POST["updateStudentName"] ?? "");
$age = trim($_POST["updateAge"] ?? "");
$email = trim($_POST["updateEmail"] ?? "");
$courseName = trim($_POST["updateCourseName"] ?? "");
$yearLvl = trim($_POST["updateYearLvl"] ?? "");
$graduating = isset($_POST["updateGraduating"]) ? 1 : 0;
$courses = ["BS Computer Science"=>1, "BS Statistics"=>2, "BS Applied Mathematics"=>3, "BS Chemistry"=>4];
$courseID = $courses[$courseName] ?? 0;

if ($studentName === "" || $age === "" || $email === "" || $courseName === "" || $yearLvl === "") {
    $operationMessage = "Fill in all update fields before updating.";
    return;
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$checkSql = "SELECT studentID FROM student WHERE studentID = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("i", $operationStudentID);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    $operationMessage = "No student record found to update.";
    $checkStmt->close();
    $conn->close();
    return;
}

$checkStmt->close();

$studentSql = "
    UPDATE student
    SET studentName = ?, age = ?, email = ?
    WHERE studentID = ?
";
$studentStmt = $conn->prepare($studentSql);

if ($studentStmt === false) {
    $operationMessage = "Error preparing student update: " . $conn->error;
    $conn->close();
    return;
}

$studentStmt->bind_param("sisi", $studentName, $age, $email, $operationStudentID);
$studentUpdated = $studentStmt->execute();
$studentStmt->close();

$acadSql = "
    UPDATE academics
    SET courseID = ?, courseName = ?, yearLvl = ?, graduating = ?
    WHERE studentID = ?
";
$acadStmt = $conn->prepare($acadSql);

if ($acadStmt === false) {
    $operationMessage = "Error preparing academics update: " . $conn->error;
    $conn->close();
    return;
}

$acadStmt->bind_param("isiii", $courseID, $courseName, $yearLvl, $graduating, $operationStudentID);
$acadUpdated = $acadStmt->execute();
$acadStmt->close();

if ($studentUpdated && $acadUpdated) {
    $operationMessage = "Student record updated successfully.";
} else {
    $operationMessage = "Error updating student record: " . $conn->error;
}

$conn->close();
?>
