<?php
header('Location: ../../pages/auth/login.php');
session_start();
try {
	include_once('../lib/connection.php');
	array_map("htmlspecialchars", $_POST);

    // if this user is already logged in 
    if (isset($_SESSION["StudentID"])) {
        header('Location: ../../pages/app/home.php');
        return;
    }

    $stmt = $conn->prepare("SELECT Password from Students WHERE ID=:id");
    
    $stmt->bindParam(':id', $_POST["ID"]);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $password = $row["Password"];
    $provided_password = $_POST["password"];

    // Compare passwords
    if (!password_verify($provided_password, $password)) {
        echo "Incorrect password!";
        return;
    }

    // Set session
    $_SESSION["StudentID"] = $_POST["ID"];

    $conn=null;
} catch(PDOException $e) {
    echo "error".$e->getMessage();
}
?>