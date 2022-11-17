<?php

session_start();
try {
	include_once('../lib/connection.php');
	array_map("htmlspecialchars", $_POST);
	
    // if this user is already logged in 
    if (!isset($_SESSION["StudentID"])) {
        header('Location: ../../pages/auth/login.php');
        return;
    }

    // if this user is not an admin
    $stmt = $conn->prepare("SELECT Role FROM Students WHERE ID=:id");
    $stmt->bindParam(":id", $_SESSION["StudentID"]);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row["Role"] != 1) {
        header('Location: ../../pages/app/home.php');
        return;
    }

    array_map("htmlspecialchars", $_POST);

    // if the user is an admin, delete the tuck
    $stmt = $conn->prepare("DELETE FROM Tuck WHERE ID=:id");

    $stmt->bindParam(":id", $_POST["ID"]);
    $stmt->execute();

    // redirect to manage page
    header('Location: ../../pages/app/manage.php');

    $conn=null;
} catch(PDOException $e) {
    echo "error".$e->getMessage();
}
?>