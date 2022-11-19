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

    $stmt = $conn->prepare("SELECT Forename, Balance, Role from Students WHERE ID=:id");
    $stmt->bindParam(':id', $_SESSION["StudentID"]);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $isAdmin = $row["Role"] == 1;

    echo "Hello, " . $row["Forename"] . "! <br>";
    echo "You have £" . $row["Balance"] . "<br>";
    ?>

    <?php
        if ($isAdmin) {
            echo "<a href='../app/manage.php'>Manage stock</a><br>";
            echo "<a href='../app/students.php'>Manage students</a><br>";
        }
    ?>

    <a href="../../api/auth/logout.api.php">Logout</a>

    <h2>Available tuck</h2>

    <ul>
        <?php
            $stmt = $conn->prepare("SELECT * FROM Tuck");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // generate a dictionary list of tuckId => name
            $tuckNames = array();

            foreach ($rows as $row) {
                $tuckNames[$row["ID"]] = $row["Name"];
            }

            // set an invisible html field for javascript to use $tuckNames
            echo "<input type='hidden' id='tuckNames' value='" . json_encode($tuckNames) . "'>";
            
            foreach ($rows as $row) {
                echo "<li>";
                echo "<span>".$row["StockQty"]." ".$row["Name"]." in stock. £".$row["Price"]."</span>";
                // when Add to basket is clicked, add to basket variable in javascript
                echo "<button onclick='addToBasket(" . $row["ID"] . ")'>Add to basket</button>";
                echo "</li>";
            }
        ?>
    </ul>



    <h2>Your basket</h2>
    <div id="container">
        <span>No items in your basket</span>
    </div>

    <span>Total: <span id="total">£0.00</span></span>

    <form action="../../api/order/order.api.php" method="POST">
        <button id="order-btn">
            Checkout <span id="count">0</span> items
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
        updateBasketElement(basket);
    }

    // set div id: basket to show all items in basket array
    function updateBasketElement(basket) {
        // document.getElementById("basket").innerText = basket.length;

        // set container to have a list of all items in basket, using tuckNames
        const tuckNames = JSON.parse(document.getElementById("tuckNames").value);

        // collect like items, e.g. KitKat x5
        const basketItems = {};
        basket.forEach((item) => {
            // find name
            const name = tuckNames[item];

            if (basketItems[name]) {
                basketItems[name] += 1;
            } else {
                basketItems[name] = 1;
            }
        });

        // generate html
        let html = "<ul>";
        for (const [name, count] of Object.entries(basketItems)) {
            html += `<li>${name} x${count}</li>`;
        }
        html += "</ul>";

        document.getElementById("container").innerHTML = html;

        if (basket.length == 0) {
            document.getElementById("order-btn").disabled = true;
        }

        // set total by finding the price of each item in the basket
        const tuck = <?php echo json_encode($rows); ?>;
        let total = 0;

        basket.forEach((item) => {
            const price = parseInt(tuck.find((t) => t.ID == item).Price);
            total += price;
        });

        document.getElementById("total").innerHTML = `£${total.toFixed(2)}`;
    }
    </script>
</body>

</html>