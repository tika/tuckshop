<?php
session_start();
unset($_SESSION["StudentID"]);
header("Location: ../../pages/auth/login.php");
?>