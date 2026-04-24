<?php
$insertMessage = "";
$selectStudentID = "";
$selectStudentName = "";
$selectRows = [];
$selectMessage = "";

function displayValue($value) {
    return htmlspecialchars((string) ($value ?? ""));
}

// tell the server to post back to this location and display the error message

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["select"])) {
    require_once __DIR__ . "/../handlers/select.php";
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
</head>
<body>
    <div id = "studentInfoBox">
        <form method = "post" action = "index.php">
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
            <input type = "number" min = "0" max = "50" name = "yearLvl" id = "yearLvl"><br>
            <label for = "graduating">Graduating </label>
            <input type = "checkbox" name = "graduating" id = "graduating" value=1><br>
            <br><input type = "submit" name = "submit" value = "Register">
            
        </form>

        <?php if ($insertMessage !== ""): ?>
            <p><?php echo displayValue($insertMessage); ?></p>
        <?php endif; ?>
    </div>
    <div id = "acadInfoBox">
        <form method = "post" action = "index.php">
            <h3>Select Student Record</h3>
            <label for = "selectStudentID">Student ID: </label><br>
            <input
                type = "number"
                name = "studentID"
                id = "selectStudentID"
                value = "<?php echo displayValue($selectStudentID); ?>"
            ><br>
            <label for = "selectStudentName">Student Name: </label><br>
            <input
                type = "text"
                name = "studentName"
                id = "selectStudentName"
                value = "<?php echo displayValue($selectStudentName); ?>"
            ><br>
            <br><input type = "submit" name = "select" value = "Select Student">
        </form>

        <?php if ($selectMessage !== ""): ?>
            <p><?php echo displayValue($selectMessage); ?></p>
        <?php endif; ?>

        <?php if (count($selectRows) > 0): ?>
            <table border = "1" cellpadding = "8" cellspacing = "0">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Age</th>
                        <th>Email</th>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Year Level</th>
                        <th>Graduating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selectRows as $row): ?>
                        <tr>
                            <td><?php echo displayValue($row["studentID"]); ?></td>
                            <td><?php echo displayValue($row["studentName"]); ?></td>
                            <td><?php echo displayValue($row["age"]); ?></td>
                            <td><?php echo displayValue($row["email"]); ?></td>
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
