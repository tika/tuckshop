<?php
session_start();

// ensure user is logged in
if (!isset($_SESSION['StudentID'])) {
    header("Location: ../auth/login.php");
}

include_once("../../api/lib/connection.php");

// see if student is admin
$stmt = $conn->prepare("SELECT role FROM students WHERE ID=:id");
$stmt->bindParam(":id", $_SESSION["StudentID"]);

$stmt->execute();

if ($stmt->fetch(PDO::FETCH_ASSOC)["role"] == 0) {
    header("Location: ../app/home.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Students</title>
</head>

<body>
    <h1>MANAGE STUDENTS</h1>

    <table>
        <tr>
            <th>Name</th>
            <th>Balance</th>
            <th>Delete</th>
        </tr>
        <?php
        // list items available
        $stmt = $conn->prepare("SELECT * FROM Students");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . $row["Forename"] . " " . $row["Surname"] . "</td>";
            
            echo "<td> £<span>" . $row["Balance"] .
                "<form method='POST' action='../../api/balance-update.api.php'>
                    <input placeholder='" . $row["Balance"] . "' name='Balance'>
                    <input type='hidden' name='ID' value=" . $row["ID"] . ">
                    <button type='submit'>Update</button>
                </form>
            </td>";
            
            echo "<td><form action='../../api/auth/remove.api.php' method='POST'><input type='hidden' name='ID' value=" . $row["ID"] . " /><button type='submit'>x</button></form></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>