CREATE DATABASE IF NOT EXISTS Tuckshop;

-- STUDENTS
DROP TABLE IF EXISTS Students;
CREATE TABLE Students
-- COLUMNS
(
    ID       INT(4) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Forename VARCHAR(16) NOT NULL,
    Surname  VARCHAR(16) NOT NULL,
    Password VARCHAR(72) NOT NULL,
    House    VARCHAR(20) NOT NULL,
    Year     INT(2) NOT NULL,
    Role     TINYINT(1) DEFAULT 0,
    Balance  DECIMAL(15, 2) DEFAULT 0
);

-- ITEMS - stores the stock of items, and data
DROP TABLE IF EXISTS Tuck;
CREATE TABLE Tuck
(
    ID INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(24) NOT NULL,
    Price DECIMAL(15, 2) NOT NULL,
    StockQty INT()
);

-- ORDERS
-- When an order is placed 
-- -> all items bought by user get inserted into basket
-- -> total cost is calculated and a new transaction is made with total amount
DROP TABLE IF EXISTS Orders;
CREATE TABLE Orders
(
    ID INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Date DATE() NOT NULL,
    Cancelled TINYINT(1),
    Delivered TINYINT(1)
);

-- BASKET
DROP TABLE IF EXISTS Baskets;
CREATE TABLE Baskets
(
    ID INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Qty INT()
);