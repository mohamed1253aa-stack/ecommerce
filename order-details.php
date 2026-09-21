<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    
}

$order_id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

$user_id = $_SESSION['user_id'];
$sql = "SELECT *
        FROM orders
        WHERE id = :order_id
        AND user_id = :user_id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':order_id' => $order_id,
    ':user_id' => $user_id
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {

    echo "Order not found.";
}
$sql = "SELECT
            order_items.*,
            products.name,
            products.image
        FROM order_items

        INNER JOIN products
            ON order_items.product_id = products.id

        WHERE order_items.order_id = :order_id";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':order_id' => $order_id
]);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>
    Order #<?= $order['id'] ?>
</h1>

<p>
    Status:
    <strong>
        <?= htmlspecialchars($order['status']) ?>
    </strong>
</p>

<p>
    Date:
    <?= htmlspecialchars($order['created_at']) ?>
</p>

<hr>

<h2>Products</h2>

<?php foreach ($items as $item): ?>

    <div>

        <?php if (!empty($item['image'])): ?>

            <img
                src="assets/images/<?= htmlspecialchars($item['image']) ?>"
                width="100"
                alt=""
            >

        <?php endif; ?>

        <h3>
            <?= htmlspecialchars($item['name']) ?>
        </h3>

        <p>Price:$<?= number_format($item['price'], 2) ?>  </p>
            
            
      

        <p> Quantity:<?= $item['quantity'] ?></p>
        <p>
            Subtotal:
            $<?= number_format(
                $item['price'] * $item['quantity'], 2 ) ?>
                
           
        </p>

    </div>

    <hr>

<?php endforeach; ?>

<h2>
    Total:
    $<?= number_format($order['total_price'], 2) ?>
</h2>

<a href="my-orders.php"> Back to My Orders</a>
    

</body>
</html>
