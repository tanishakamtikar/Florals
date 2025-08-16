<?php
session_start();
$product_id = $_POST['product_id'];
$quantity = max(1, intval($_POST['quantity']));

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['quantity'] = $quantity;
}

header("Location: cart.php");
exit;
