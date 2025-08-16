<?php
session_start();

$productId = $_POST['product_id'] ?? null;
$action = $_POST['action'] ?? 'add';
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

// Basic validation
if (!$productId) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid product ID']);
    exit;
}

// Handle actions
switch ($action) {
    case 'add':
        if (!isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] = 0;
        }
        $_SESSION['cart'][$productId] += $quantity;
        $message = '✅ Product added to cart!';
        break;

    case 'update':
        if ($quantity > 0) {
            $_SESSION['cart'][$productId] = $quantity;
            $message = '✅ Cart updated successfully!';
        } else {
            unset($_SESSION['cart'][$productId]);
            $message = '✅ Product removed from cart!';
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$productId]);
        $message = '✅ Product removed from cart!';
        break;

    default:
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit;
}

// Detect AJAX request by checking X-Requested-With header
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => $message]);
    exit;
}

// For non-AJAX requests redirect to cart page
header("Location: cart.php");
exit;











