<?php
$host = "localhost";
$dbname = "ecommerce";
$username = "root";
$password = "";


$conn = new PDO(
    "mysql:host=$host;dbname=$dbname;charset=utf8",
    $username,
    $password
);
?>