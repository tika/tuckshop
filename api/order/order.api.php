<?php

session_start();
try {
	include_once('../lib/connection.php');
	array_map("htmlspecialchars", $_POST);
	
    // if this user is already logged in 
    if (!isset($_SESSION["StudentID"])) {
        header('Location: ../../pages/auth/login.php');
        return;
    }

    // make sure this is the real user
    // if this user is not an admin
    $stmt = $conn->prepare("SELECT Role FROM Students WHERE ID=:id");
    $stmt->bindParam(":id", $_SESSION["StudentID"]);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // i.e. are they actually real
    if ($row["Role"] == null) {
        header('Location: ../../pages/app/home.php');
        return;
    }

    // convert string array to actual array

    $singleBasketIds = explode(",", $_POST["basket"]);

    $multipleBasketIds = array();
    
    // convert [1, 1, 1, 1, 1, 2]
    // to [1 => 5, 2 => 1]
    foreach ($singleBasketIds as $_ => $i) {
        if (array_key_exists($i, $multipleBasketIds)) {
            $multipleBasketIds[$i] = (int) $multipleBasketIds[$i] + 1;
        } else {
            // echo "New";
            $multipleBasketIds[$i] = 1;
        }
    }

    // create order id
    $stmt = $conn->prepare("INSERT INTO Orders (StudentID) VALUES (:studentid)");

    $stmt->bindParam(":studentid", $_SERVER["StudentID"]);

    $stmt->execute();
    
    $stmt = $conn->prepare("SELECT LAST_INSERT_ID()");
    $stmt->execute();
    
    $orderid = $stmt->fetch(PDO::FETCH_ASSOC)["LAST_INSERT_ID()"];

    // get all tuck ids and prices
    $stmt = $conn->prepare("SELECT ID, Price FROM Tuck");
    $stmt->execute();

    $tuck = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $order_total = 0;

    // make sure all in $multipleBasketIds are in $tuck
    foreach ($multipleBasketIds as $id => $qty) {
        $found = false;
        foreach ($tuck as $_ => $t) {
            if ($t["ID"] == $id) {
                $found = true;
                $order_total += $t["Price"] * $qty;
                break;
            }
        }
        if (!$found) {
            // not found
            header('Location: ../../pages/app/home.php');
            return;
        }
    }

    // make sure user has enough money
    $stmt = $conn->prepare("SELECT Balance FROM Students WHERE ID=:id");
    $stmt->bindParam(":id", $_SESSION["StudentID"]);
    $stmt->execute();
    
    $balance = $stmt->fetch(PDO::FETCH_ASSOC)["Balance"];
    
    if ($balance < $order_total) {
        header('Location: ../../pages/app/home.php');
        return;
    }

    // remove from balance
    $stmt = $conn->prepare("UPDATE Students SET Balance=:bal WHERE ID=:id");
    $new_balance = $balance - $order_total;
    $stmt->bindParam(":bal", $new_balance);
    $stmt->execute();
    
    // make sure user has enough stock
    foreach ($multipleBasketIds as $id => $qty) {
        $stmt = $conn->prepare("SELECT StockQty FROM Tuck WHERE ID=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        
        $stockqty = $stmt->fetch(PDO::FETCH_ASSOC)["StockQty"];
        
        if ($stockqty < $qty) {
            header('Location: ../../pages/app/home.php');
            return;
        } else {
            // remove from stock
            $stmt = $conn->prepare("UPDATE Tuck SET StockQty=:qty WHERE ID=:id");
            $stmt->bindParam(":id", $id);
            $new_qty = $stockqty - $qty;
            $stmt->bindParam(":qty", $n);
            $stmt->execute();
        }
    }

    // add to baskets in sql
    foreach ($multipleBasketIds as $tid => $qty) {
        // echo $k . " " . $v;
        $stmt = $conn->prepare("INSERT INTO Baskets (OrderID, TuckID, Qty) VALUES (:oid, :tid, :qty)");
        $stmt->bindParam(":oid", $orderid);
        $stmt->bindParam(":tid", $tid);
        $stmt->bindParam(":qty", $qty);
        $stmt->execute();
    }

    // update order
    $stmt = $conn->prepare("UPDATE Orders SET Total=:total WHERE ID=:id");

    $stmt->bindParam(":total", $order_total);
    $stmt->bindParam(":id", $orderid);

    $stmt->execute();

    // print out order summary
    echo "Order Summary: <br>";
    echo "Order ID: " . $orderid . "<br>";
    echo "Total: " . $order_total . "<br>";
    echo "Items: <br>";
    foreach ($multipleBasketIds as $tid => $qty) {
        echo $tid . " x " . $qty . "<br>";
    }

    $conn=null;
} catch(PDOException $e) {
    echo "error".$e->getMessage();
}
?>