<?php session_start(); 
include 'connection.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Florals</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Slick Carousel -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

  <style>
    
  </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="hero">
    <h1>About Us </h1>
  </div>

 
  <div class="about-section">
    <h2>Who We Are</h2>
    <p>
      At Florals, we believe flowers are more than just gifts—they're expressions of love, joy, and gratitude.
      For over a decade, we have been crafting floral experiences for weddings, anniversaries, and everyday surprises.
      Every bouquet is hand-arranged with care and delivered with love.
    </p>

    <p>
      Our passion is rooted in nature and nurtured by our customers smiles. Whether you're celebrating something big or simply brightening a day,
      Florals is here to make it beautiful.
    </p>

    <p>
       We harbor the gift of giving. Our flowers are carefully plucked and grown in the best conditions to ensure they are healthy 
       and far from chemical growth. Our organic flowers pose no threat to the environment and can further be recycled and used in home
       agriculture.  
    </p>

    <p>Florals is a boutique flower shop dedicated to bringing beauty, elegance, and joy through handcrafted floral arrangements.
       Whether it’s a romantic bouquet, a cheerful gift, or a touch of nature for your home, our fresh flowers and thoughtful designs 
       are perfect for any occasion. We believe in the power of flowers to brighten moments and create lasting memories.
    </p>

    <p>At Florals, we take pride in fostering meaningful connections—with our community, our growers, and the environment.
      We partner with local farms and artisans to support sustainable practices and fair trade, ensuring every purchase not
      only brings happiness to your doorstep but also uplifts those who help make it possible. From seed to stem, every detail is a
      testament to our commitment to quality, sustainability, and heartfelt service.
    </p>
       
  </div>


  <div class="about-section">
    <h2>What We Provide</h2>
    <p>
      At our flower website, we specialize in delivering fresh, handpicked flowers right to your doorstep. 
      Whether you're celebrating a special occasion or simply brightening someone's day, our curated collection of 
      floral arrangements offers something for every moment. From romantic roses to vibrant tulips and elegant lilies, our 
      flowers are sourced from trusted growers to ensure unmatched quality and freshness.
    </p>

    <p>
      We provide a seamless and user-friendly shopping experience, complete with detailed product images, descriptions, and 
      easy-to-navigate categories. Customers can browse our selection by occasion, flower type, or color palette, making it 
      simple to find the perfect bouquet. Our integrated wishlist and cart features allow you to save favorites and plan ahead 
      for birthdays, anniversaries, weddings, or just spontaneous gestures of kindness.
    </p>

    <p>
       Our website also offers personalized services, such as custom bouquet arrangements, occasional flowers, and same-day 
       delivery in select locations. We believe that every bouquet should tell a story, and we give our customers the tools to 
       add heartfelt notes, choose elegant wrapping styles, and select delivery timings that best suit their needs. 
       Whether you're sending love, sympathy, congratulations, or gratitude, our flowers help you say it beautifully. 
    </p>

    <p>
      Beyond our products, we are committed to providing top-tier customer support and a smooth, secure checkout process. 
      With a user profile section that includes easy login, registration, and managing your floral gifting is 
      more convenient than ever. Join our community of happy customers and let us help you turn everyday moments into blooming 
      memories
    </p>
  </div>

  
    
<div class="testimonials-carousel-section">
  <h2>What Our Customers Say</h2>

  <div class="carousel-container">
    <div class="carousel-slide active">
      <p>“Absolutely stunning arrangements! The roses I received for my anniversary were fresh and beautifully wrapped. Florals has my heart!”</p>
      <h4>- Malaiza S.</h4>
    </div>
    <div class="carousel-slide">
      <p>“Incredible service and even better flowers. I ordered last minute and they delivered on time with such grace. Highly recommend!”</p>
      <h4>- Anshul K.</h4>
    </div>
    <div class="carousel-slide">
      <p>“I used Florals for my wedding and every bouquet was picture-perfect. Thank you for making my day unforgettable.”</p>
      <h4>- Sethi T.</h4>
    </div>
    <div class="carousel-slide">
      <p>“These flowers are colorful and absolutely perfect. They brighten up everyones day. Totally recommend”</p>
      <h4>- Balaj S.</h4>
    </div>
  </div>
</div>
           



  <div class="contact-section">
    <h3>Contact Us</h3>
    <div class="contact-info">📍 521 Alta Terra Lane, Sicily, FW 98165</div>
    <div class="contact-info">📞 +39 3853753056</div>
    <div class="contact-info">📧 hello@floralboutique.com</div>

    <div class="social-icons">
      <a href="#"><i class="fab fa-facebook-f" title="Facebook"></i></a>
      <a href="#"><i class="fab fa-instagram" title="Instagram"></i></a>
      <a href="#"><i class="fab fa-twitter" title="Twitter"></i></a>
    </div>
  </div>

  <?php include 'footer.php'; ?>


  <script>
  let currentSlide = 0;
  const slides = document.querySelectorAll(".carousel-slide");

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle("active", i === index);
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  // Auto-slide every 5 seconds
  setInterval(nextSlide, 5000);

  // Initial display
  showSlide(currentSlide);
</script>  

</body>
</html>
