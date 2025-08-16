<?php
session_start();

// Connect to the database
include 'connection.php';

// Fetch products from the database
function getProductsFromDatabase($conn) {
    $products = [];

    $result = $conn->query("SELECT id, name, price, image FROM products");

    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = [
            'name' => $row['name'],
            'price' => (float)$row['price'],
            'image' => $row['image']
        ];
    }

    return $products;
}

$products = getProductsFromDatabase($conn);

// Cart session
$cart = $_SESSION['cart'] ?? [];
$total = 0;
$totalItems = 0;
$totalAmount = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .cart-container {
      max-width: 1000px;
      margin: 40px auto;
    }

    .cart-card {
      display: flex;
      align-items: center;
      border: 1px solid #ddd;
      border-radius: 10px;
      margin-bottom: 20px;
      padding: 15px;
      background-color: #fff;
    }

    .cart-card img {
      width: 150px;
      height: auto;
      margin-right: 20px;
      border-radius: 8px;
    }

    .cart-details {
      flex: 1;
    }

    .cart-actions {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }

    .cart-actions input[type="number"] {
      width: 60px;
      padding: 5px;
    }

    .update-btn, .remove-btn {
      background-color: #b1415c;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
    }

    .update-btn:hover, .remove-btn:hover {
      background-color: #8e2f45;
    }

    .total {
      text-align: right;
      margin-top: 30px;
      font-size: 1.2rem;
    }

    .empty-msg {
      text-align: center;
      margin-top: 100px;
      font-size: 1.2rem;
    }

    .cart-summary {
      text-align: right;
      margin-top: 30px;
    }

    .cart-summary button {
      margin-top: 10px;
      margin-bottom: 50px;
      padding: 10px 20px;
      background-color: #b1415c;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .cart-summary button:hover {
      background-color: #8e2f45;
    }
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<h2 style="text-align:center; margin-top: 80px;">Your Shopping Cart</h2>

<div class="cart-container">
<?php if (count($cart) > 0): ?>
  <?php foreach ($cart as $id => $qty):
    if (!isset($products[$id])) continue;
    $product = $products[$id];
    $subtotal = $product['price'] * $qty;
    $total += $subtotal;
    $totalItems += $qty;
    $totalAmount += $subtotal;
  ?>
    <div class="cart-card">
      <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />

      <div class="cart-details">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <p>Price: €<?= number_format($product['price'], 2) ?></p>
        <p>Subtotal: €<?= number_format($subtotal, 2) ?></p>

        <div class="cart-actions">
          <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <input type="hidden" name="action" value="update">
            <input type="number" name="quantity" value="<?= $qty ?>" min="1">
            <button type="submit" class="update-btn">Update</button>
          </form>

          <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $id ?>">
            <input type="hidden" name="action" value="remove">
            <button type="submit" class="remove-btn">Remove</button>
          </form>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="cart-summary">
    <p>Total Items: <strong><?= $totalItems ?></strong></p>
    <p>Total Amount: <strong>€<?= number_format($totalAmount, 2) ?></strong></p>

    <form action="order.php" method="POST">
      <input type="hidden" name="total_items" value="<?= $totalItems ?>">
      <input type="hidden" name="total_amount" value="<?= $totalAmount ?>">
      <button type="submit">Proceed to Checkout</button>
    </form>
  </div>

<?php else: ?>
  <div class="empty-msg">
    Your cart is empty. <br><br>
    <a href="products.php" class="update-btn">Continue Shopping</a>
  </div>
<?php endif; ?>
</div>

</body>
</html>



