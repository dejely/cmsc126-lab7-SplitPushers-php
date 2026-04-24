<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["select"])) {
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

$sql = "
    SELECT
        student.studentID,
        student.studentName,
        student.age,
        student.email,
        academics.courseID,
        academics.courseName,
        academics.yearLvl,
        academics.graduating
    FROM student
    LEFT JOIN academics ON student.studentID = academics.studentID
    WHERE student.studentID = ?
";

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

$conn->close();
?>
