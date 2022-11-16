<?php
session_start();

// ensure user is logged in
if (!isset($_SESSION['StudentID'])) {
    header("Location: ../auth/login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
</head>

<body>
    <h1>HOME</h1>
    <?php
    include_once("../../api/lib/connection.php");

    $stmt = $conn->prepare("SELECT Forename from Students WHERE ID=:id");
    $stmt->bindParam(':id', $_SESSION["StudentID"]);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Hello, " . $row["Forename"] . "!";
    ?>

    <a href="../app/manage.php">Manage stock</a>
    <a href="../../api/auth/logout.api.php">Logout</a>
</body>

</html>