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
    <title>Manage</title>
</head>

<body>
    <h1>MANAGE</h1>

    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Stock Qty</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        // list items available
        $stmt = $conn->prepare("SELECT * FROM Tuck");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . $row["Name"] . "</td>";
            echo "<td> £" . $row["Price"] . "</td>";
            echo "<td>" . $row["StockQty"] . "</td>";
            echo "<td><button>Edit</button></td>";
            echo "<td><form action='../../api/tuck/remove.api.php' method='POST'><input type='hidden' name='ID' value=" . $row["ID"] . " /><button type='submit'>x</button></form></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <form action="../../api/tuck/add.api.php" method="POST">
        <input placeholder="Item Name" name="name" />
        <input placeholder="Price (without £)" name="price" />
        <input placeholder="Stock qty" name="stockqty" />
        <input type="submit" />
    </form>
</body>

</html>