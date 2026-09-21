<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;

if (isset($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $quantity) {

        $cart_count += $quantity;
    }
}

?>
<nav>
    <a href="index.php">
        Home
    </a>

    <a href="products.php">
        Products
    </a>

    <a href="cart.php">
        Cart (<?= $cart_count ?>)
    </a>

    <?php if (isset($_SESSION['user_id'])): ?>

        <span>
            Hello,
            <?= htmlspecialchars($_SESSION['user_name']) ?>
        </span>

        <a href="logout.php">
            Logout
        </a>

    <?php else: ?>

        <a href="login.php">
            Login
        </a>

        <a href="register.php">
            Register
        </a>

    <?php endif; ?>

</nav>