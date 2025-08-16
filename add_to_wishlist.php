<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);

    if (!isset($_SESSION['wishlist'])) {
        $_SESSION['wishlist'] = [];
    }

    if (!in_array($productId, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $productId;
        $message = '✅ Added to wishlist!';
    } else {
        $message = 'ℹ️ Already in wishlist.';
    }

    // Return JSON response for AJAX requests
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => true, 'message' => $message]);
        exit;
    }

    // Otherwise redirect to wishlist page
    header('Location: wishlist.php');
    exit;
} else {
    // If accessed directly or no product_id provided, redirect
    header('Location: products.php');
    exit;
}










