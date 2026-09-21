<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = isset($_GET['id'])
    ? (int) $_GET['id']: 0;
    

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Order Success</title>

</head>

<body>

    <h1>Order Placed Successfully 🎉</h1>

    <p>
        Thank you for your order.
    </p>

    <p>
        Your Order ID:
        <strong>#<?= $order_id ?></strong>
    </p>

    <a href="products.php">
        Continue Shopping
    </a>

</body>

</html>