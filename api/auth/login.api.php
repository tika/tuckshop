<?php
// header('Location: subjects.php');
try {
	include_once('./lib/connection.php');
	array_map("htmlspecialchars", $_POST);
	
	$stmt = $conn->prepare("INSERT INTO TblSubjects (SubjectID,Subjectname,Teacher)VALUES (NULL,:subjectname,:teacher)");
	$stmt->bindParam(':subjectname', $_POST["subjectname"]);
	$stmt->bindParam(':teacher', $_POST["teacher"]);

	$stmt->execute();
} catch(PDOException $e) {
    echo "error".$e->getMessage();
}
$conn=null;
?>