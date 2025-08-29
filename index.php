<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <title>Online Tax System | Home Page</title>
    
    <!-- CSS here -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/slicknav.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/progressbar_barfiller.css">
    <link rel="stylesheet" href="assets/css/gijgo.css">
    <link rel="stylesheet" href="assets/css/animate.min.css"> <!-- Animate.css -->
    <link rel="stylesheet" href="assets/css/animated-headline.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/nice-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <?php include_once('includes/header.php'); ?>
    
    <main>
        <!-- Split Screen Section Start -->
        <section class="hero-section">
            <!-- Left Side with Solid Background Color and Text -->
            <div class="hero-left">
                <div class="hero-content">
                    <h1 class="hero-title wow fadeInLeft" data-wow-delay="0.2s">
                        Welcome to Online <br> Tax Management System
                    </h1>
                    <p class="hero-description wow fadeInLeft" data-wow-delay="0.4s">
                        Transform Your Tax Journey With Our Next Generation Platform, Offering Centralized User Registration, Smart Tax Applications Management, And Quick Documents Verification. Enjoy Effortless Tax Compliance, Precise Calculation, And Secured Payment Processing. All Backed By Advanced Reporting And Analytics Capabilities.
                    </p>
                    <a href="signup.php" class="btn hero-btn wow fadeInLeft" data-wow-delay="0.7s">
                        Join Us For Free
                    </a>
                </div>
            </div>

            <!-- Right Side with Carousel -->
            <div class="hero-right">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/img/hero/bg5.jpg" class="d-block w-100" alt="Image 1">
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Streamline Your Tax Filing Process with Simple, Quick Applications!</h3>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/hero/bg4.jpg" class="d-block w-100" alt="Image 2">
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Accurate Tax Calculations, Automated Correction, and Real-Time Alerts!</h3>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/hero/bg1.jpg" class="d-block w-100" alt="Image 3">
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Efficient Tax Management: Monitor Your Account and Track Rates Instantly!</h3>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/hero/bg3.jpg" class="d-block w-100" alt="Image 4">
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Automated Reporting and Excellent Notification Tracking for Your Tax Progress!</h3>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/hero/bg6.jpg" class="d-block w-100" alt="Image 5">
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Dedicated Full-Time Administrative Support for Your Tax Queries and Account Management!</h3>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/hero/bg2.png" class="d-block w-100" alt="Image 6">
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Access Real-Time Tax Rates, Updates, and More on one Click!</h3><br>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Split Screen Section End -->
    </main>

    <?php include_once('includes/footer.php'); ?>

    <!-- JS here -->
    <script src="assets/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="assets/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script>
        // Initialize the carousel to change automatically every 2 seconds
        $('#carouselExampleIndicators').carousel({
            interval: 2000 // Change slides every 2 seconds
        });
    </script>
</body>
</html>
