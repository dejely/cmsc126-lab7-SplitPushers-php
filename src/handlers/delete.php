<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["delete"])) {
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
    $operationMessage = "No student record found to delete.";
    $checkStmt->close();
    $conn->close();
    return;
}

$checkStmt->close();

$acadSql = "DELETE FROM academics WHERE studentID = ?";
$acadStmt = $conn->prepare($acadSql);
$acadStmt->bind_param("i", $operationStudentID);
$acadDeleted = $acadStmt->execute();
$acadStmt->close();

$studentSql = "DELETE FROM student WHERE studentID = ?";
$studentStmt = $conn->prepare($studentSql);
$studentStmt->bind_param("i", $operationStudentID);
$studentDeleted = $studentStmt->execute();
$studentStmt->close();

if ($acadDeleted && $studentDeleted) {
    $operationMessage = "Student record deleted successfully.";
} else {
    $operationMessage = "Error deleting student record: " . $conn->error;
}

$conn->close();
?>
