<?php
include_once("./lib/connection.php");

// Create database
$stmt = $conn->prepare("CREATE DATABASE IF NOT EXISTS Tuckshop");
$stmt->execute();

// Drop tables
$stmt = $conn->prepare("DROP TABLE IF EXISTS Students");
$stmt->execute();

$stmt = $conn->prepare("DROP TABLE IF EXISTS Tuck");
$stmt->execute();

$stmt = $conn->prepare("DROP TABLE IF EXISTS Orders");
$stmt->execute();

$stmt = $conn->prepare("DROP TABLE IF EXISTS Baskets");
$stmt->execute();

echo "Deleted previous tables<br>";

// Create tables
// Students
$stmt = $conn->prepare("CREATE TABLE Students
(
    ID       INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Forename VARCHAR(16) NOT NULL,
    Surname  VARCHAR(16) NOT NULL,
    Password VARCHAR(72) NOT NULL,
    House    VARCHAR(20) NOT NULL,
    Year     INT(2) NOT NULL,
    Role     TINYINT(1) DEFAULT 0,
    Balance  DECIMAL(15, 2) DEFAULT 0
)");
$stmt->execute();

echo "Created table students<br>";

// Tuck
$stmt = $conn->prepare("CREATE TABLE Tuck
(
    ID       INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name     VARCHAR(24) NOT NULL,
    Price    DECIMAL(15, 2) NOT NULL,
    StockQty INT(4) DEFAULT 0
)");
$stmt->execute();

echo "Created table tuck<br>";

// Orders
$stmt = $conn->prepare("CREATE TABLE Orders
(
    ID        INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Date      DATE NOT NULL,
    Cancelled TINYINT(1),
    Delivered TINYINT(1)
)");
$stmt->execute();

echo "Created table orders<br>";

// Baskets
$stmt = $conn->prepare("CREATE TABLE Baskets
(
    ID INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Qty INT
)");
$stmt->execute();

echo "Created table baskets<br>";

// "Log out"
$_SESSION = null;

$conn=null;
?>