<?php
session_start();
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" type="text/css" href="style.css">
  <title>Florals</title>
  <!-- Add your CSS & FontAwesome links here -->
  <link rel="stylesheet" href="path/to/fontawesome.css" />
  <style>
  
     /* Basic styles for demo */
    .products-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 20px;
    }
    .product-card {
      border: 1px solid #ccc;
      padding: 15px;
      width: 200px;
      text-align: center;
      border-radius: 8px;
      box-shadow: 1px 1px 5px rgba(0,0,0,0.1);
    }
    .product-card img {
      max-width: 100%;
      height: auto;
      object-fit: cover;
    }
    .product-actions button {
      margin: 5px;
      padding: 8px 12px;
      cursor: pointer;
      border: none;
      border-radius: 4px;
      font-size: 14px;
    }
    .ajax-cart {
      background-color: #4CAF50;
      color: white;
    }
    .ajax-wishlist {
      background-color: #f44336;
      color: white;
    }
    #message {
      text-align: center;
      margin-top: 20px;
      color: green;
      font-weight: bold;
      opacity: 0;
      transition: opacity 0.5s ease;
    }
  </style>
</head>
<body>

<body>

<!-- ✅ Move message container to top -->
<div id="message" style="
  text-align:center;
  position: fixed;
  top: 0;
  left: 0;
  width: 25%;
  padding: 12px;
  background-color: #4CAF50;
  color: white;
  font-weight: bold;
  opacity: 0;
  transition: opacity 0.5s ease;
  z-index: 9999;
  justify-items: center;
"></div>

<?php include 'header.php'; ?>


<h2 style="text-align:center; margin-top: 100px; color: #b1415c;">Grab Your Flowers</h2>


<?php

$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");

?>

<div class="products-grid">
  <?php while ($product = $result->fetch_assoc()): ?>
    <div class="product-card">
      <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
      <h3><?= htmlspecialchars($product['name']) ?></h3>
      <p>€<?= number_format($product['price'], 2) ?></p>

      <div class="product-actions">
        <button class="ajax-cart" data-id="<?= $product['id'] ?>">
          <i class="fa-solid fa-cart-plus"></i> Add to Cart
        </button>

        <button class="ajax-wishlist" data-id="<?= $product['id'] ?>">
          <i class="fa-regular fa-heart"></i> Wishlist
        </button>
      </div>
    </div>
  <?php endwhile; ?>
</div>



<!-- Add this message container right here -->
<div id="message" style="text-align:center; margin-top: 20px; color: green;"></div>

<?php include 'footer.php'; ?>

<script>
document.querySelectorAll('.ajax-cart').forEach(button => {
  button.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    fetch('add_to_cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'  // <-- Add this header
      },
      body: 'product_id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
      showMessage(data.message);
    });
  });
});

document.querySelectorAll('.ajax-wishlist').forEach(button => {
  button.addEventListener('click', function () {
    const id = this.getAttribute('data-id');
    fetch('add_to_wishlist.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'  // <-- Add this header
      },
      body: 'product_id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
      showMessage(data.message);
    });
  });
});

function showMessage(msg) {
  const messageBox = document.getElementById('message');
  messageBox.textContent = msg;
  messageBox.style.opacity = '1';

  setTimeout(() => {
    messageBox.style.opacity = '0';
  }, 2500);
}

</script>


</body>
</html>



