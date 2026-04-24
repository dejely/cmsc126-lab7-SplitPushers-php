<?php
// Prevents this handler from running unless the delete form was submitted.
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["delete"])) {
    header("Location: ../app/index.php");
    exit;
}

// Loads database settings for the delete query.
require_once __DIR__ . "/../db/config.php";

// Reads the target student ID and prepares response variables for index.php.
$operationStudentID = trim($_POST["studentID"] ?? "");
$operationRows = [];
$operationMessage = "";

// Requires a student ID because deletes are ID-based only.
if ($operationStudentID === "") {
    $operationMessage = "Enter a student ID.";
    return;
}

// Opens the MariaDB connection used by this delete request.
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Checks that the target student exists before deleting rows.
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

// Deletes academic data first so the student row can be removed cleanly.
$acadSql = "DELETE FROM academics WHERE studentID = ?";
$acadStmt = $conn->prepare($acadSql);
$acadStmt->bind_param("i", $operationStudentID);
$acadDeleted = $acadStmt->execute();
$acadStmt->close();

// Deletes the student row after its academic row has been removed.
$studentSql = "DELETE FROM student WHERE studentID = ?";
$studentStmt = $conn->prepare($studentSql);
$studentStmt->bind_param("i", $operationStudentID);
$studentDeleted = $studentStmt->execute();
$studentStmt->close();

// Reports whether both delete statements succeeded.
if ($acadDeleted && $studentDeleted) {
    $operationMessage = "Student record deleted successfully.";
} else {
    $operationMessage = "Error deleting student record: " . $conn->error;
}

// Closes the database connection after the delete workflow finishes.
$conn->close();
?>
