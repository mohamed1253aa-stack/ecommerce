<?php
session_start();
require_once "config/database.php";
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Product not found";
}
$id = $_GET['id']; 

$sql = "SELECT products.*, categories.name AS category_name
        FROM products
        LEFT JOIN categories
        ON products.category_id = categories.id
        WHERE products.id = :id";
        $stmt = $conn->prepare($sql);

$stmt->execute([
    ':id' => $id
]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$product) {
    echo "Product not found";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
     <nav class="navbar">

        <div class="logo">
            E-Shop
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="products.php">Products</a></li>
            <li><a href="cart.php">Cart</a></li>
        </ul>

    </nav>

     <section class="product-details">

        <div class="product-image">

            <img
                src="<?= htmlspecialchars($product['image']) ?>"
                alt="<?= htmlspecialchars($product['name']) ?>"
            >

        </div>
        
 <div class="product-info">

            <h1>
                <?= htmlspecialchars($product['name']) ?>
            </h1>

            <p class="category">
                Category:
                <?= htmlspecialchars($product['category_name']) ?>
            </p>

            <div class="price">

                <span class="current-price">
                    $<?= htmlspecialchars($product['price']) ?>
                </span>
                <p class="stock">

                <?php if ($product['stock'] > 0): ?>

                    In Stock: <?= $product['stock'] ?>

                <?php else: ?>

                    Out of Stock

                <?php endif; ?>

            </p>


            <?php if ($product['stock'] > 0): ?>

                <button class="btn">
                    Add To Cart
                </button>

            <?php else: ?>

                <button class="btn" disabled>
                    Out of Stock
                </button>

            <?php endif; ?>

        </div>

    </section>

</body>
</html>