<?php
session_start();

// ensure user is logged in
if (!isset($_SESSION['StudentID'])) {
    header("Location: ../auth/login.php");
}

include_once("../../api/lib/connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>FUN STATS</title>
</head>

<body>
    <h1>FUN STATS</h1>
    <?php
    // find top 10 spenders
    $stmt = $conn->prepare("SELECT Forename, Surname, Balance FROM Students ORDER BY Balance DESC LIMIT 10");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Top 10 spenders</h2>";
    echo "<table>";
    echo "<tr><th>Name</th><th>Balance</th></tr>";
    
    foreach ($rows as $row) {
        echo "<tr>";
        echo "<td>" . $row["Forename"] . " " . $row["Surname"] . "</td>";
        echo "<td>£" . $row["Balance"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // find most popular tuck
    // look through Baskets and find the most popular TuckID then find Tuck name from Tuck
    $stmt = $conn->prepare("SELECT Name, COUNT(TuckID) AS Count FROM Baskets FULL JOIN Tuck ON Tuck.ID = TuckID GROUP BY TuckID ORDER BY Count DESC LIMIT 1");

    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h2>Most popular tuck</h2>";
    echo "<p>" . $row["Name"] . " with " . $row["Count"] . " sales</p>";
    
    
    ?>
</body>

</html>