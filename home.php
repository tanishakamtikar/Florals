

<?php session_start(); 
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Florals</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Slick Carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
  


</head>
<body>


    

<?php include 'header.php'; ?>

<!-- Page-specific content -->
<div class="slider">
  <div><img src="img/colorful-flower-bouquet-glass-vase-against-pink-background_36682-218609.jpg" alt="Flower 1"></div>
  <div><img src="img/beautiful-fresh-spring-flowers-pink-bouquet-flowers-vase-pink-background-copy-space_4740-6119.avif" alt="Flower 2"></div>
  <div><img src="img/bouquet-3175315_1280.jpg" alt="Flower 3"></div>
  <div><img src="img/painting-vase-flowers-with-pink-background-generative-ai_926199-717455.avif" alt="Flower 4"></div>
  
</div>

<!-- Hero Section -->
<section class="hero">
  <div class="hero-content">
    <h1></h1>
    
    
  </div>
</section>


<!-- Featured Collections -->
<section class="collections">
  <h2 class="section-title">Featured Collections</h2>
  <div class="grid">
    <a href="products.php" class="collection-card">
      <img src="img/lotus15.png" alt="Lotus">
      <h3>Specials</h3>
    </a>
    <a href="products.php" class="collection-card">
      <img src="img/mixed22.png" alt="Lillies">
      <h3>Occasional</h3>
    </a>
    <a href="products.php" class="collection-card">
      <img src="img/hydrangea14.png" alt="Hydrangea">
      <h3>Seasonal</h3>
    </a>
      <a href="products.php" class="collection-card">
      <img src="img/daffodil18.png" alt="Daffodils">
      <h3>All Arounds</h3>
    </a>
    <!-- Add more cards as needed -->
  </div>
</section>


<section class="collections">
  <h2 class="section-title">Occasional Collections</h2>
  <div class="grid">
    <a href="products.php" class="collection-card">
      <img src="img/redroses1.png" alt="red-roses">
      <h3>Birthday</h3>
    </a>
    <a href="products.php" class="collection-card">
      <img src="img/whiteorchids7.png" alt="Lillies">
      <h3>Wedding</h3>
    </a>
    <a href="products.php" class="collection-card">
      <img src="img/pinkroses6.png" alt="pink-roses">
      <h3>Anniversary</h3>
    </a>
      <a href="products.php" class="collection-card">
      <img src="img/poppy19.png" alt="white-orchids">
      <h3>Retirement</h3>
    </a>
    <!-- Add more cards as needed -->
  </div>
</section>


<section class="collections">
  <h2 class="section-title">Seasonal Collections</h2>
  <div class="grid">
    <a href="products.php" class="collection-card">
      <img src="img/tulips2.png" alt="tulip">
      <h3>Spring</h3>
    </a>
    <a href="products.php" class="collection-card">
      <img src="img/sunflowers5.png" alt="sunflower">
      <h3>Summer</h3>
    </a>
    <a href="products.php" class="collection-card">
      <img src="img/daffodil18.png" alt="daffodil">
      <h3>Fall</h3>
    </a>
      <a href="products.php" class="collection-card">
      <img src="img/iris17.png" alt="lilac">
      <h3>Winter</h3>
    </a>
    <!-- Add more cards as needed -->
  </div>
</section>


<!-- About Us -->
<section class="about-us">
  <div class="about-content">
    <h2>Bring Beauty Home</h2>
    <p>Discover elegant floral arrangements for every occasion and every season. We strive to make your day floraly!</p>
    <a href="about.php" class="btn-light">Learn More</a>
  </div>
  
</section>


<?php include 'footer.php'; ?>


  </section>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>


<script>
  $(document).ready(function(){
    $('.slider').slick({
      autoplay: true,
      autoplaySpeed: 1000,  // ↓ from 3000ms to 1500ms (1.5 sec per slide)
      dots: true,
      fade: true,
      arrows: false,
      speed: 500  // ↓ from 1000ms to 500ms (faster fade transition)
    });
  });
</script>


</body>
</html>

