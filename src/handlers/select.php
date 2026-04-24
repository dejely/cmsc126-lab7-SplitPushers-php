<?php
$selectStudentID = "";
$selectStudentName = "";
$selectRows = [];
$selectMessage = "";

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["select"])) {
    header("Location: ../app/index.php");
    exit;
}

require_once __DIR__ . "/../db/config.php";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selectStudentID = trim($_POST["studentID"] ?? "");
$selectStudentName = trim($_POST["studentName"] ?? "");

    # GET logic:
if ($selectStudentID === "" && $selectStudentName === "") {
    $selectMessage = "Enter a student ID or student name.";
} else {
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
        WHERE (? = '' OR student.studentID = ?)
            AND (? = '' OR student.studentName LIKE ?)
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $selectMessage = "Error preparing select query: " . $conn->error;
    } else {
        $nameSearch = "%" . $selectStudentName . "%";
        $stmt->bind_param("ssss", $selectStudentID, $selectStudentID, $selectStudentName, $nameSearch);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $selectRows[] = $row;
        }

        if (count($selectRows) === 0) {
            $selectMessage = "No student record found.";
        }

        $stmt->close();
    }
}

$conn->close();
?>
