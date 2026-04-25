<?php
$insertMessage = "";
$operationStudentID = trim($_POST["studentID"] ?? "");
$operationRows = [];
$operationMessage = "";
$updateStudentName = trim($_POST["updateStudentName"] ?? "");
$updateAge = trim($_POST["updateAge"] ?? "");
$updateEmail = trim($_POST["updateEmail"] ?? "");
$updateCourseName = trim($_POST["updateCourseName"] ?? "");
$updateYearLvl = trim($_POST["updateYearLvl"] ?? "");
$updateGraduating = isset($_POST["updateGraduating"]);
$showUpdateForm = false;

function displayValue($value) {
    return htmlspecialchars((string) ($value ?? ""));
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["select"])) {
    require_once __DIR__ . "/../handlers/select.php";
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["showUpdate"])) {
    $showUpdateForm = true;
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update"])) {
    $showUpdateForm = true;
    require_once __DIR__ . "/../handlers/update.php";
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete"])) {
    require_once __DIR__ . "/../handlers/delete.php";
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    require_once __DIR__ . "/../handlers/insert.php";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div id = "mainBox">
    <div id = "studentInfoBox">
        <form method = "post" action = "index.php" enctype = "multipart/form-data">
            <h3>Student Information</h3>
            <label for = "studentName">Student Name: </label><br>
            <input type = "text" name = "studentName" id = "studentName"><br>
            <label for = "age">Age: </label><br>
            <input type = "number" min = "0" max = "100" name = "age" id = "age"><br>
            <label for = "email">Email: </label><br>
            <input type = "email" name = "email" id = "email"><br>
            <h3>Academic Information</h3>
            <label for = "courseName">Course: </label><br>
            <select name = "courseName" id = "courseName">
                <option value = "BS Computer Science">BS Computer Science</option>
                <option value = "BS Statistics">BS Statistics</option>
                <option value = "BS Applied Mathematics">BS Applied Mathematics</option>
                <option value = "BS Chemistry">BS Chemistry</option>
            </select><br>
            <label for = "yearLvl">Year: </label><br>
            <input type = "number" min = "0" max = "50" name = "yearLvl" id = "yearLvl"><br><br>
            <label for = "graduating">Graduating?</label>
            <input type = "checkbox" name = "graduating" id = "graduating" value=1><br><br>
            <label for = "profile">Profile File/Image: </label><br>
            <input type = "file" name = "profile" id = "profile"><br>
            <br><input type = "submit" name = "submit" value = "Register">
            
        </form>

        <?php if ($insertMessage !== ""): ?>
            <p><?php echo displayValue($insertMessage); ?></p>
        <?php endif; ?>
    </div>

    <div id = "operationsBox">
        <form method = "post" action = "index.php" enctype = "multipart/form-data">
            <h3>Student Operations</h3>
            <div class = "operationRow">
                <div>
                    <label for = "operationStudentID">Student ID: </label><br>
                    <input
                        type = "number"
                        name = "studentID"
                        id = "operationStudentID"
                        value = "<?php echo displayValue($operationStudentID); ?>"
                    >
                </div>
                <input type = "submit" name = "select" value = "Select Student">
                <input type = "submit" name = "showUpdate" value = "Update Student">
                <input type = "submit" name = "delete" value = "Delete Student">
            </div>
        </div>

            <?php if ($showUpdateForm): ?>
                <h4>Update Information</h4>
                <div class = "operationField">
                    <label for = "updateStudentName">Student Name: </label><br>
                    <input
                        type = "text"
                        name = "updateStudentName"
                        id = "updateStudentName"
                        value = "<?php echo displayValue($updateStudentName); ?>"
                    >
                </div>
                <div class = "operationField">
                    <label for = "updateAge">Age: </label><br>
                    <input
                        type = "number"
                        min = "0"
                        max = "100"
                        name = "updateAge"
                        id = "updateAge"
                        value = "<?php echo displayValue($updateAge); ?>"
                    >
                </div>
                <div class = "operationField">
                    <label for = "updateEmail">Email: </label><br>
                    <input
                        type = "email"
                        name = "updateEmail"
                        id = "updateEmail"
                        value = "<?php echo displayValue($updateEmail); ?>"
                    >
                </div>
                <div class = "operationField">
                    <label for = "updateCourseName">Course: </label><br>
                    <select name = "updateCourseName" id = "updateCourseName">
                        <option value = "">Select Course</option>
                        <option value = "BS Computer Science" <?php echo $updateCourseName === "BS Computer Science" ? "selected" : ""; ?>>BS Computer Science</option>
                        <option value = "BS Statistics" <?php echo $updateCourseName === "BS Statistics" ? "selected" : ""; ?>>BS Statistics</option>
                        <option value = "BS Applied Mathematics" <?php echo $updateCourseName === "BS Applied Mathematics" ? "selected" : ""; ?>>BS Applied Mathematics</option>
                        <option value = "BS Chemistry" <?php echo $updateCourseName === "BS Chemistry" ? "selected" : ""; ?>>BS Chemistry</option>
                    </select>
                </div>
                <div class = "operationField">
                    <label for = "updateYearLvl">Year: </label><br>
                    <input
                        type = "number"
                        min = "0"
                        max = "50"
                        name = "updateYearLvl"
                        id = "updateYearLvl"
                        value = "<?php echo displayValue($updateYearLvl); ?>"
                    >
                </div>
                <div class = "operationField">
                    <label for = "updateGraduating">Graduating </label><br>
                    <input
                        type = "checkbox"
                        name = "updateGraduating"
                        id = "updateGraduating"
                        value = "1"
                        <?php echo $updateGraduating ? "checked" : ""; ?>
                    >
                </div>
                <div class = "operationField">
                    <label for = "updateProfile">New Profile File/Image: </label><br>
                    <input type = "file" name = "updateProfile" id = "updateProfile">
                </div>
                <br>
                <input type = "submit" name = "update" value = "Update Student">
            <?php endif; ?>
        </form>

        <?php if ($operationMessage !== ""): ?>
            <p><?php echo displayValue($operationMessage); ?></p>
        <?php endif; ?>

        <?php if (count($operationRows) > 0): ?>
            <table border = "1" cellpadding = "8" cellspacing = "0">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Profile Path</th>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Year Level</th>
                        <th>Graduating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($operationRows as $row): ?>
                        <tr>
                            <td><?php echo displayValue($row["studentID"]); ?></td>
                            <td><?php echo displayValue($row["studentName"]); ?></td>
                            <td><?php echo displayValue($row["age"]); ?></td>
                            <td><?php echo displayValue($row["email"]); ?></td>
                            <td><?php echo displayValue($row["profilePath"]); ?></td>
                            <td><?php echo displayValue($row["courseID"]); ?></td>
                            <td><?php echo displayValue($row["courseName"]); ?></td>
                            <td><?php echo displayValue($row["yearLvl"]); ?></td>
                            <td><?php echo $row["graduating"] === null ? "" : ($row["graduating"] ? "Yes" : "No"); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
