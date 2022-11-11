<?php
session_start();

// ensure user is not logged in
if ($_SESSION["StudentID"] != null) {
    header("Location: ../app/home.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Register</title>
</head>

<body>
    <h1>REGISTER STUDENT</h1>
    <h2>If you aren't a new student,
        <a href="./login.php">click here</a>
        to navigate to the login page
    </h2>

    <form action="../../api/auth/register.api.php" method="POST">
        Forename: <input type="text" name="forename"><br>
        Surname:<input type="text" name="surname"><br>
        Password:<input type="password" name="password"><br>
        House:<input type="text" name="house"><br>
        Year:<input type="text" name="year"><br>
        <input type="radio" name="role" value="User" checked> User<br>
        <input type="radio" name="role" value="Admin"> Admin<br>
        <input type="submit" value="Register">
    </form>

</body>

</html>