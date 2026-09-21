<?php

session_start();

require_once "config/database.php";

$cart = $_SESSION['cart'] ?? [];

$total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>shopping card</title>
      <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">

    <div class="logo">
        E-Shop
    </div>

    <ul class="nav-links">

        <li>
            <a href="index.php">Home</a>
        </li>

        <li>
            <a href="products.php">Products</a>
        </li>

        <li>
            <a href="cart.php">Cart</a>
        </li>

    </ul>

</nav>
<section class="cart">

    <h1>Shopping Cart</h1>


    <?php if (empty($cart)): ?>

        <p>Your cart is empty.</p>

        <a href="products.php" class="btn">
            Continue Shopping
        </a>

            <?php else: ?>
        <?php foreach ($cart as $product_id => $quantity): ?>
         <?php
        $sql = "SELECT * FROM products WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':id' => $product_id
        ]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            continue;
        }

        $subtotal = $product['price'] * $quantity;

        $total += $subtotal;
        ?>

        <div class="cart-item">

            <img 
                src="images/<?php echo $product['image']; ?>" 
                alt="<?php echo $product['name']; ?>"
            >

            <h3>
                <?php echo $product['name']; ?>
            </h3>

            <p>
                Price:
                $<?php echo $product['price']; ?>
            <div class="quantity">

    <form action="update-cart.php" method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= $product['id'] ?>"
        >

        <input
            type="hidden"
            name="action"
            value="decrease"
        >

        <button type="submit">
            -
        </button>

    </form>


    <span>
        <?= $quantity ?>
    </span>


    <form action="update-cart.php" method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= $product['id'] ?>"
        >

        <input
            type="hidden"
            name="action"
            value="increase"
        >

        <button type="submit">
            +
        </button>

    </form>

</div>
<form action="update-cart.php" method="POST">

    <input
        type="hidden"
        name="id"
        value="<?= $product['id'] ?>"
    >

    <input
        type="hidden"
        name="action"
        value="remove"
    >

    <button type="submit">
        Remove
    </button>

</form>
                Subtotal:
                $<?php echo $subtotal; ?>
            </p>

        </div>

    <?php endforeach; ?>

    <h2>
        Total: $<?php echo $total; ?>
    </h2>

    <?php endif; ?>   
</body>
</html>