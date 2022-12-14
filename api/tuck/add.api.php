<?php

session_start();
try {
	include_once('../lib/connection.php');
	array_map("htmlspecialchars", $_POST);
	
    // if this user is already logged in 
    if (!isset($_SESSION["StudentID"])) {
        // Error
        echo "You are not logged in";
        header('Refresh:2;url = ../../pages/auth/login.php');
        return;
    }

    // if this user is not an admin
    $stmt = $conn->prepare("SELECT Role FROM Students WHERE ID=:id");
    $stmt->bindParam(":id", $_SESSION["StudentID"]);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row["Role"] != 1) {
        // Error
        echo "You are not an admin";
        header('Refresh:2;url = ../../pages/app/home.php');
        return;
    }

    array_map("htmlspecialchars", $_POST);

    // if the user is an admin, add the tuck
    $stmt = $conn->prepare("INSERT INTO Tuck (Image, Name, Price, StockQty) VALUES (:image, :name, :price, :stockqty)");

    $stmt->bindParam(":image", $_POST["image"]);    
    $stmt->bindParam(":name", $_POST["name"]);
    $stmt->bindParam(":stockqty", $_POST["stockqty"]);
    $stmt->bindParam(":price", $_POST["price"]);

    $stmt->execute();

    // redirect to manage page
    header('Location: ../../pages/app/manage.php');

    $conn=null;
} catch(PDOException $e) {
    echo "error".$e->getMessage();
}
?>