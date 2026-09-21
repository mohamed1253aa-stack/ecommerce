<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");

}

$user_id = $_SESSION['user_id'];

$sql = "SELECT *
        FROM orders
        WHERE user_id = :user_id
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':user_id' => $user_id
]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>my orders</title>
    <link rel="stylesheet" href="assets/css/style.css">
          
</head>
<body>
   <h1>My Orders</h1>

<?php if (empty($orders)): ?>

    <h2>You don't have any orders yet.</h2>

    <a href="products.php">
        Start Shopping
    </a>
     <?php foreach ($orders as $order): ?>

        <div class="order">

            <h3>
                Order #<?= $order['id'] ?>
            </h3>

            <p>
                Total: $<?= number_format($order['total_price'], 2) ?>
               
            </p>

            <p>
                Status: <?= htmlspecialchars($order['status']) ?>
               
            </p>

            <p>
                Date:<?= htmlspecialchars($order['created_at']) ?>
                
            </p>
                <a href="order-details.php?id=<?= $order['id'] ?>">
                View Order
            </a>

        </div>

        <hr>

    <?php endforeach; ?>


</body>
</html>