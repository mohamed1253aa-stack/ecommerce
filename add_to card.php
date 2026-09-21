<?php
session_start();
require_once "config/database.php";
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Product not found";
}
$id = $_GET['id']; 
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $conn->prepare($sql);

$stmt->execute([
    ':id' => $id
]);
if (!$product) {
    echo "Product not found";
}


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$id])) {

   
    $_SESSION['cart'][$id]++;

} else {

    
    $_SESSION['cart'][$id] = 1;
}

    header("Location: cart.php");
exit;

?>