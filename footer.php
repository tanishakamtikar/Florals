<!-- Footer -->




<footer style="text-align:center; margin-top: 40px; padding: 20px; background: #f8f8f8">
  <p>&copy; <?= date('Y') ?> Florals. All rights reserved.</p>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<script>
  $(document).ready(function(){
    $('.slider').slick({
      autoplay: true,
      autoplaySpeed: 3000,
      dots: true,
      fade: true,
      arrows: false,
      speed: 1000
    });
  });
</script>

</body>
</html>
