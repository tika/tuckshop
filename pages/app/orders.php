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
    <title>Manage Orders</title>
</head>

<body>
    <h1>MANAGE ORDERS</h1>
    <h2>Update status or cancel orders</h2>

    <table>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Placed by</th>
            <th>Total</th>
            <th>Items</th>
            <th>Cancelled?</th>
            <th>Delivered?</th>
        </tr>
        <?php
        // list orders
        $stmt = $conn->prepare("SELECT T1.ID, T1.StudentID, T1.Date, T2.Forename, T2.Surname, T1.Total, T1.Cancelled, T1.Delivered FROM Orders T1 INNER JOIN Students T2 ON t2.id=t1.StudentId");
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $row["Date"] . "</td>";
            echo "<td>" . $row["Forename"] . " " . $row["Surname"] . "</td>";
            
            echo "<td> £<span>" . $row["Total"] . "</span></td>";

            // echo item names and quantities
            echo "<td>";
            $stmt = $conn->prepare("SELECT * FROM Baskets FULL JOIN Tuck ON TuckID = Tuck.ID WHERE OrderID = :id");
            $stmt->bindParam(":id", $row["ID"]);
            $stmt->execute();

            $baskets = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($baskets as $basket) {
                echo $basket["Name"] . " x" . $basket["Qty"] . "<br>";
            }

            echo "</td>";

            echo "<td>
                <form id=cancel".$row["ID"]." action='../../api/order/cancel.api.php' method='POST'>
                    <input type='hidden' name='ID' value=" . $row["ID"] . " />
                    <input onclick=cancel(".$row["ID"].") type='checkbox' name=status " . ($row["Cancelled"] == 1 ? "checked" : "") . " />
                </form>
            </td>";
            echo "<td>
                <form id=deliver".$row["ID"]." action='../../api/order/deliver.api.php' method='POST'>
                    <input type='hidden' name='ID' value=" . $row["ID"] . " />
                    <input onclick=deliver(".$row["ID"].") type='checkbox' name=status " . ($row["Delivered"] == 1 ? "checked" : "") . " />
                </form>
            </td>";
            
            // checkbox if order cancelled
            // echo "<td><input type='checkbox' id=cancel onclick=updateCancel() name='cancelled' value='" . $row["ID"] . "' " . ($row["Cancelled"] == 1 ? "checked" : "") . " /></td>";
            // echo "<td><input type='checkbox' id=deliver onclick=updateDelivered() name='delivered' value='" . $row["ID"] . "' " . ($row["Delivered"] == 1 ? "checked" : "") . " /></td>";
            
            echo "</tr>";
        }
        ?>

        <script>
        function cancel(id) {
            document.getElementById("cancel" + id).submit();
        }

        function deliver(id) {
            document.getElementById("deliver" + id).submit();
        }
        </script>
    </table>
</body>

</html>