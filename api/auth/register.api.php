<?php
// header('Location: subjects.php');
try {
	include_once('../lib/connection.php');
	array_map("htmlspecialchars", $_POST);
	
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

    $stmt->bindParam(':forename', $_POST["forename"]);
    $stmt->bindParam(':surname', $_POST["surname"]);
    $stmt->bindParam(':password', $_POST["password"]);
    $stmt->bindParam(':house', $_POST["house"]);
    $stmt->bindParam(':year', $_POST["year"]);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    $conn=null;

	$stmt->execute();
} catch(PDOException $e) {
    echo "error".$e->getMessage();
}
?>