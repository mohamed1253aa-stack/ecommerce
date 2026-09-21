<?php
require_once "config/database.php";
$sql = "
    SELECT 
        products.*,
        categories.name AS category_name

    FROM products

    INNER JOIN categories
        ON products.category_id = categories.id

    ORDER BY products.id DESC";

$stmt = $conn->prepare($sql);

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>products</title>
</head>
<body>
    
    <header class="navbar">

    <div class="container nav-content">


        <a href="index.php"
           class="logo">

            My<span>Store</span>

        </a>


        <nav>

            <ul class="nav-links">

                <li>
                    <a href="index.php">
                        Home
                    </a>
                </li>

                <li>
                    <a href="products.php">
                        Products
                    </a>
                </li>

                <li>
                    <a href="cart.php">
                        Cart 🛒
                    </a>
                </li>

            </ul>

        </nav>


    </div>

</header>


<section class="products page-section">

    <div class="container">


        <div class="section-title">

            <p>
                Our Store
            </p>

            <h1>
                All Products
            </h1>

        </div>


        <div class="products-grid">


            <?php foreach ($products as $product): ?>


                <div class="product-card">


                    <div class="product-image">

                        <div class="product-placeholder">

                            🛍️

                        </div>

                    </div>


                    <div class="product-info">


                        <p class="product-category">

                            <?= htmlspecialchars(
                                $product['category_name']
                            ) ?>

                        </p>


                        <h3>

                            <?= htmlspecialchars(
                                $product['name']
                            ) ?>

                        </h3>


                        <div class="price">

                            <span class="current-price">

                                $<?= $product['price'] ?>

                            </span>

                        </div>


                        <a
                            href="product-details.php?id=<?= $product['id'] ?>"
                            class="add-cart"
                        >

                            View Product

                        </a>


                    </div>


                </div>


            <?php endforeach; ?>


        </div>


    </div>

</section>
    
</body>
</html>