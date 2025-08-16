<?php
session_start();
include 'connection.php'; // Make sure this connects to your DB

// Get wishlist from session
$wishlist = $_SESSION['wishlist'] ?? [];
$products = [];

if (!empty($wishlist)) {
    $ids = implode(',', array_map('intval', $wishlist)); // Sanitize IDs

    // Fetch only wishlist products from DB
    $result = $conn->query("SELECT id, name, price, image FROM products WHERE id IN ($ids)");

    while ($row = $result->fetch_assoc()) {
        $products[$row['id']] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Wishlist</title>
</head>
<body>

<?php include 'header.php'; ?>

<div class="wishlist-container">
  <h2>Your Wishlist</h2>

  <?php if (empty($products)): ?>
    <p class="empty-msg">Your wishlist is empty.</p>
  <?php else: ?>
    <?php foreach ($products as $product): ?>
      <div class="wishlist-item">
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        <div class="wishlist-info">
          <h3><?= htmlspecialchars($product['name']) ?></h3>
          <p>€<?= number_format($product['price'], 2) ?></p>
        </div>
        <div class="wishlist-actions">
          <form action="remove_from_wishlist.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <button type="submit">Remove</button>
          </form>
          <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="hidden" name="from_wishlist" value="1">
            <button type="submit">Add to Cart</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

</body>
</html>



