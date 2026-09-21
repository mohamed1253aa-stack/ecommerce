<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $action = $_POST['action'];
 if (isset($_SESSION['cart'][$id])) {
     if ($action === 'increase') {

            $_SESSION['cart'][$id]++;
            }
             elseif ($action === 'decrease') {

            $_SESSION['cart'][$id]--;
             if ($_SESSION['cart'][$id] <= 0) {
                unset($_SESSION['cart'][$id]);
             }
             }
              elseif ($action === 'remove') {

            unset($_SESSION['cart'][$id]);

        }
    }
}
header("Location: cart.php");
?>