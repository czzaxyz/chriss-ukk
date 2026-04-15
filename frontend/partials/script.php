<!-- Latest jQuery -->
<script src="frontend/templates/assets/js/jquery-1.12.4.min.js"></script>

<!-- Latest compiled and minified Bootstrap -->
<script src="frontend/templates/assets/bootstrap/js/bootstrap.min.js"></script>

<!-- Modernizer JS -->
<script src="frontend/templates/assets/js/modernizr-2.8.3.min.js"></script>

<!-- jQuery simple mobilemenu -->
<script src="frontend/templates/assets/js/jquery-simple-mobilemenu.js"></script>

<!-- Owl carousel min js -->
<script src="frontend/templates/assets/owlcarousel/js/owl.carousel.min.js"></script>

<!-- Magnific-popup js -->
<script src="frontend/templates/assets/js/jquery.magnific-popup.min.js"></script>

<!-- CountTo js -->
<script src="frontend/templates/assets/js/jquery.inview.min.js"></script>

<!-- Scrolltopcontrol js -->
<script src="frontend/templates/assets/js/scrolltopcontrol.js"></script>

<!-- WOW - Reveal Animations When You Scroll -->
<script src="frontend/templates/assets/js/wow.min.js"></script>

<!-- Scripts js -->
<script src="frontend/templates/assets/js/scripts.js"></script>

<script>
    // Initialize WOW.js
    new WOW().init();
    
    // Owl Carousel for Testimonials
    $('#testimonial-slider').owlCarousel({
        items: 1,
        loop: true,
        margin: 30,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 3000,
        smartSpeed: 1000
    });
</script>

</body>
</html>