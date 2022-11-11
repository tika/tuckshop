<?php
session_start();

// ensure user is logged in
if ($_SESSION["StudentID"] == null) {
    header("Location: ../auth/login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
</head>

<body>
    <h1>HOME</h1>
</body>

</html>