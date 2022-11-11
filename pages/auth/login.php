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
    <title>Login</title>
</head>

<body>
    <h1>LOGIN STUDENT</h1>
    <h2>If you are a new student,
        <a href="./register.php">click here</a>
        to navigate to the register page
    </h2>
    <form method="POST" action="../../api/auth/login.api.php">
        <select>
            <?php
        include_once('../../api/lib/connection.php');

        $stmt = $conn->prepare("SELECT ID, Forename, Surname FROM Students");

        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            echo('<option value='.$row["ID"].'>'.$row["Forename"]." ".$row["Surname"].'</option>');
        }
        ?>
        </select>
        <input name="Password" type="password" placeholder="Password" />
        <input type="submit" />
    </form>

</body>

</html>