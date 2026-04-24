<?php
// Prevents this handler from running unless the select form was submitted.
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["select"])) {
    header("Location: ../app/index.php");
    exit;
}

// Loads database settings for the select query.
require_once __DIR__ . "/../db/config.php";

// Reads the student ID and prepares response variables for index.php.
$operationStudentID = trim($_POST["studentID"] ?? "");
$operationRows = [];
$operationMessage = "";

// Requires a student ID because selection is ID-based only.
if ($operationStudentID === "") {
    $operationMessage = "Enter a student ID.";
    return;
}

// Opens the MariaDB connection used by this select request.
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Selects the matching student row together with its academic information.
$sql = "
    SELECT
        student.studentID,
        student.studentName,
        student.age,
        student.email,
        student.profilePath,
        academics.courseID,
        academics.courseName,
        academics.yearLvl,
        academics.graduating
    FROM student
    LEFT JOIN academics ON student.studentID = academics.studentID
    WHERE student.studentID = ?
";

// Runs the prepared select query and stores any returned rows for display.
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    $operationMessage = "Error preparing select query: " . $conn->error;
} else {
    $stmt->bind_param("i", $operationStudentID);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $operationRows[] = $row;
    }

    if (count($operationRows) === 0) {
        $operationMessage = "No student record found.";
    }

    $stmt->close();
}

// Closes the database connection after the select workflow finishes.
$conn->close();
?>
