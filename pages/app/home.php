<?php
session_start();

// ensure user is logged in
if (!isset($_SESSION['StudentID'])) {
    header("Location: ../auth/login.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
</head>

<body>
    <h1>HOME</h1>
    <?php
    include_once("../../api/lib/connection.php");

    $stmt = $conn->prepare("SELECT Forename from Students WHERE ID=:id");
    $stmt->bindParam(':id', $_SESSION["StudentID"]);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Hello, " . $row["Forename"] . "!";
    ?>

    <a href="../app/manage.php">Manage stock</a>
    <a href="../../api/auth/logout.api.php">Logout</a>

    <ul>
        <?php
            $stmt = $conn->prepare("SELECT * FROM Tuck");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 
            foreach ($rows as $row) {
                echo "<li>";
                echo "<span>".$row["StockQty"]." ".$row["Name"]." in stock. £".$row["Price"]."</span>";
                // when Add to basket is clicked, add to basket variable in javascript
                echo "<button onclick='addToBasket(" . $row["ID"] . ")'>Add to basket</button>";
                echo "</li>";
            }
        ?>
    </ul>

    <form action="../../api/order/order.api.php" method="POST">
        <button>
            Order <span id="count">0</span> items
        </button>
        <input type="hidden" name="basket" value="" id="basket">
    </form>


    <script type="text/javascript">
    // an array of ids of tuck in the cart
    const basket = [];

    function addToBasket(itemId) {
        basket.push(itemId);
        document.getElementById("count").innerHTML = basket.length;
        document.getElementById("basket").value = basket;
    }
    </script>
</body>

</html>