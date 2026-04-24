<?php
// Prevents this handler from running unless the final update form was submitted.
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["update"])) {
    header("Location: ../app/index.php");
    exit;
}

// Loads database settings and the shared upload helper.
require_once __DIR__ . "/../db/config.php";
require_once __DIR__ . "/upload.php";

// Reads the target student ID and prepares response variables for index.php.
$operationStudentID = trim($_POST["studentID"] ?? "");
$operationRows = [];
$operationMessage = "";

// Requires a student ID because updates are ID-based only.
if ($operationStudentID === "") {
    $operationMessage = "Enter a student ID.";
    return;
}

// Reads submitted update values and maps the selected course to its ID.
$studentName = trim($_POST["updateStudentName"] ?? "");
$age = trim($_POST["updateAge"] ?? "");
$email = trim($_POST["updateEmail"] ?? "");
$courseName = trim($_POST["updateCourseName"] ?? "");
$yearLvl = trim($_POST["updateYearLvl"] ?? "");
$graduating = isset($_POST["updateGraduating"]) ? 1 : 0;
$courses = ["BS Computer Science"=>1, "BS Statistics"=>2, "BS Applied Mathematics"=>3, "BS Chemistry"=>4];
$courseID = $courses[$courseName] ?? 0;

// Stops the update when required update fields are missing.
if ($studentName === "" || $age === "" || $email === "" || $courseName === "" || $yearLvl === "") {
    $operationMessage = "Fill in all update fields before updating.";
    return;
}

// Opens the MariaDB connection used by this update request.
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Checks that the target student exists before updating either table.
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

// Saves a replacement profile file when one is uploaded.
$profilePath = saveUploadedProfile("updateProfile", $operationMessage);
if ($profilePath === false) {
    $conn->close();
    return;
}

// Chooses whether the student update should also replace the stored profile path.
if ($profilePath === "") {
    $studentSql = "
        UPDATE student
        SET studentName = ?, age = ?, email = ?
        WHERE studentID = ?
    ";
} else {
    $studentSql = "
        UPDATE student
        SET studentName = ?, age = ?, email = ?, profilePath = ?
        WHERE studentID = ?
    ";
}

$studentStmt = $conn->prepare($studentSql);

// Stops when the student update statement cannot be prepared.
if ($studentStmt === false) {
    $operationMessage = "Error preparing student update: " . $conn->error;
    $conn->close();
    return;
}

// Binds the student update parameters based on whether a new file path exists.
if ($profilePath === "") {
    $studentStmt->bind_param("sisi", $studentName, $age, $email, $operationStudentID);
} else {
    $studentStmt->bind_param("sissi", $studentName, $age, $email, $profilePath, $operationStudentID);
}

$studentUpdated = $studentStmt->execute();
$studentStmt->close();

// Updates the matching academic information for the same student ID.
$acadSql = "
    UPDATE academics
    SET courseID = ?, courseName = ?, yearLvl = ?, graduating = ?
    WHERE studentID = ?
";
$acadStmt = $conn->prepare($acadSql);

// Stops when the academic update statement cannot be prepared.
if ($acadStmt === false) {
    $operationMessage = "Error preparing academics update: " . $conn->error;
    $conn->close();
    return;
}

$acadStmt->bind_param("isiii", $courseID, $courseName, $yearLvl, $graduating, $operationStudentID);
$acadUpdated = $acadStmt->execute();
$acadStmt->close();

// Reports whether both student and academic updates succeeded.
if ($studentUpdated && $acadUpdated) {
    $operationMessage = "Student record updated successfully.";
} else {
    $operationMessage = "Error updating student record: " . $conn->error;
}

// Closes the database connection after the update workflow finishes.
$conn->close();
?>
