<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
   
}

$cart = $_SESSION['card'] ?? [];

if (empty($cart)) {
    header("Location: card.php");
  
}
$stmt = $conn->prepare($sql);
$total = 0;
$products = [];

foreach ($card as $product_id => $quantity) {

    $sql = "SELECT * FROM products WHERE id = :id";

    

    $stmt->execute([
        ':id' => $product_id
    ]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        continue;
    }

    $subtotal = $product['price'] * $quantity;

    $total += $subtotal;

    $products[] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'subtotal' => $subtotal
    ];
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    if (empty($address) || empty($phone)) {

        $error = "Please enter your address and phone.";

    } else {

        try {

            $conn->beginTransaction();

         

            $sql = "INSERT INTO orders
                    (user_id, total_price, status)
                    VALUES
                    (:user_id, :total_price, 'Pending')";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ':user_id' => $_SESSION['user_id'],
                ':total_price' => $total
            ]);

            $order_id = $conn->lastInsertId();

        

            foreach ($products as $product) {

                $sql = "INSERT INTO order_items
                        (order_id, product_id, quantity, price)
                        VALUES
                        (:order_id, :product_id, :quantity, :price)";

                $stmt = $conn->prepare($sql);

                $stmt->execute([
                    ':order_id' => $order_id,
                    ':product_id' => $product['id'],
                    ':quantity' => $product['quantity'],
                    ':price' => $product['price']
                ]);
            }

           

           

            $_SESSION['card'] = [];

            header("Location: order-success.php?id=" . $order_id);

          

        } catch (Exception $e) {

            $conn->rollBack();

            $error = "Something went wrong. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Checkout</title>

    <link rel="stylesheet" href="assets/css/style.css">
        

</head>

<body>

<h1>Checkout</h1>

<?php if ($error): ?>

    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>


<h2>Order Summary</h2>

<?php foreach ($products as $product): ?>

    <div>

        <strong>
            <?= htmlspecialchars($product['name']) ?>
        </strong>

        <p>
            Quantity:
            <?= $product['quantity'] ?>
        </p>

        <p>
            Price:
            $<?= number_format($product['price'], 2) ?>
        </p>

        <p>
            Subtotal:
            $<?= number_format($product['subtotal'], 2) ?>
        </p>

    </div>

    <hr>

<?php endforeach; ?>


<h2>
    Total:
    $<?= number_format($total, 2) ?>
</h2>


<form method="POST">

    <div>

        <label>
            Phone
        </label>

        <input
            type="text"
            name="phone"
            required
        >

</div>

    <div>

        <label>
            Address
        </label>

        <textarea
            name="address"
            rows="5"
            required
        ></textarea>

    </div>

    <br>

    <button type="submit">
        Place Order
    </button>

</form>

</body>

</html>