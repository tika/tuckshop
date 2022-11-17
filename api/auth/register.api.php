<?php

session_start();
try {
	include_once('../lib/connection.php');
	array_map("htmlspecialchars", $_POST);
	
    // if this user is already logged in 
    if (isset($_SESSION["StudentID"])) {
        header('Location: ../../pages/app/home.php');
        return;
    }

    print_r($_POST);

    $stmt = $conn->prepare("INSERT INTO Students (ID,Forename,Surname,Password,House,Year,Role,Balance) VALUES (null,:forename,:surname,:password,:house,:year,:role,null)");

    switch($_POST["role"]){
        case "User":
            $role = 0;
            break;
        case "Admin":
            $role = 1;
            break;
    }
    
    echo $_POST["password"];

    # encrypt password
    $hashed = password_hash($_POST["password"], PASSWORD_DEFAULT);
    
    $stmt->bindParam(':forename', $_POST["forename"]);
    $stmt->bindParam(':surname', $_POST["surname"]);
    $stmt->bindParam(':password', $hashed);
    $stmt->bindParam(':house', $_POST["house"]);
    $stmt->bindParam(':year', $_POST["year"]);
    $stmt->bindParam(':role', $role);
    $stmt->execute();

    $stmt = $conn->prepare("SELECT LAST_INSERT_ID()");
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Set session
    $_SESSION["StudentID"] = $row["LAST_INSERT_ID()"];

    header("Location: ../../pages/app/home.php");
    
    $conn=null;
} catch(PDOException $e) {
    echo "error".$e->getMessage();
}
?>