<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <div id = "studentInfoBox">
        <form method = "post" action = "../insert.php">
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
    </div>
    <div id = "acadInfoBox">

    </div>
</body>
</html>