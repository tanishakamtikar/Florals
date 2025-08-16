<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Florals</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="style.css">

  <!-- ✅ Correct Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Slick carousel styles if you're using them -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
</head>


<!-- Navbar -->
<div class="navbar">
  <a href="home.php" class="logo">🌸Florals</a>
  <div class="nav-links">
    <a href="home.php">HOME</a>
    <a href="about.php">ABOUT</a>
    <a href="products.php">SHOP</a>
    <a href="order.php">ORDERS</a>
  </div>
  <div class="icons">

    <a href="wishlist.php" class="icon-btn">
      <i class="fa-regular fa-heart"></i>
      <span class="count"><?= isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0 ?></span>
    </a>

    <a href="cart.php" class="icon-btn">
      <i class="fa-solid fa-cart-shopping"></i>
      <span class="count"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></span>
    </a>

    <div class="user-dropdown icon-btn" id="userDropdown">
    <i class="fa-regular fa-user" id="userIcon"></i>
    <div class="dropdown-menu" id="dropdownMenu">

    <?php if (isset($_SESSION['user_id'])): ?>
      <span style="padding: 9px; display:block;">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></span>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </div>
</div>

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const userDropdown = document.getElementById('userDropdown');

    // When user clicks on the dropdown (icon or area)
    userDropdown.addEventListener('click', function(event) {
      event.stopPropagation(); // Prevent click event from bubbling up to document
      this.classList.toggle('active'); // Toggle showing/hiding dropdown
    });

    // When user clicks anywhere else in the document
    document.addEventListener('click', function() {
      userDropdown.classList.remove('active'); // Hide dropdown
    });
  });
</script>

<script>
function addToWishlist(productId) {
    fetch('add_to_wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'product_id=' + encodeURIComponent(productId)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message); // Or use a toast notification
        } else {
            alert('Failed to add to wishlist: ' + data.message);
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Something went wrong.');
    });
}
</script>


</body>
</html>