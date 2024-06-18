<?php
session_start();
$loggedIn = isset($_SESSION['username']);
?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="Services.css" />
    <title>services</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- Vendor Script -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <!-- Fonts css load -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link id="fontsLink" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css">
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css">
</head>

<body>
    <header class="header">
        <nav>
            <div class="nav__bar">
                <div class="logo">
                    <a href="#"><img src="assetservice/logo.png" alt="logo" /></a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li><a href="../Home/Home.php">Home</a></li>
                <li><a href="../AboutUs/About.php">Sobre Nós</a></li>
                <li><a href="../Quartos/Quartos.php">Quartos</a></li>
                <li><a href="../Home/explore">Explore</a></li>
                <li><a href="../Contactos/ContactForm.php">Contact-nos</a></li>

                <?php if ($loggedIn): ?>
                    <li>
                        <div class="user-profile">
                            <div class="dropdown">
                                <button class="dropbtn">
                                    <i class="ri-user-line"></i>
                                    <span><?php echo $_SESSION['username']; ?></span>
                                </button>
                                <div class="dropdown-content">
                                    <a href="#">Profile</a>
                                    <a href="#">Settings</a>
                                    <a href="../Logout/logout.php">Logout</a>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="../Login/Login.php">Login</a></li>
                    <li><a href="../Registar/Registar.php">Register</a></li>
                <?php endif; ?>
            </ul>
            <button class="btn nav__btn">Book Now</button>
        </nav>
    </header>
    <section class="section__container service__container">
        <p class="section__subheader">Pacotes para todos </p>
        <h2 class="section__header">Compre seu pacote agora .</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Pesquise Pacotes..." id="search-input" />
            <button class="btn " id="search-btn">Procurar</button>
        </div>

        <div class="service__grid">
            <div class="service__card">
                <div class="service__card__image">
                    <img src="assetsservice/service-1.jpg" alt="service" />
                    <div class="service__card__icons">
                        <span><i class="ri-heart-fill"></i></span>
                        <span><i class="ri-paint-fill"></i></span>
                        <span><i class="ri-shield-star-line"></i></span>
                    </div>
                </div>
                <div class="service__card__details">
                    <h4>Pacote tour</h4>
                    <p>
                        Bask in luxury with breathtaking ocean views from your private suite.
                    </p>
                    <h5>Starting from <span>$299/night</span></h5>
                    <a href="../Billing/Billing.php"> <button class="btn book-now"
                            data-logged-in="<?php echo $loggedIn ? 'true' : 'false'; ?>">Book Now</button></a>
                </div>
            </div>
            <div class="service__card">
                <div class="service__card__image">
                    <img src="assetsservices/service-2.jpg" alt="service" />
                    <div class="service__card__icons">
                        <span><i class="ri-heart-fill"></i></span>
                        <span><i class="ri-paint-fill"></i></span>
                        <span><i class="ri-shield-star-line"></i></span>
                    </div>
                </div>
                <div class="service__card__details">
                    <h4>Executive Cityscape service</h4>
                    <p>
                        Experience urban elegance and modern comfort in the heart of the city.
                    </p>
                    <h5>Starting from <span>$199/night</span></h5>
                    <a href="../Billing/Billing.php"> <button class="btn book-now"
                            data-logged-in="<?php echo $loggedIn ? 'true' : 'false'; ?>">Book Now</button></a>
                </div>
            </div>
            <div class="service__card">
                <div class="service__card__image">
                    <img src="assetsservices/service-3.jpg" alt="service" />
                    <div class="service__card__icons">
                        <span><i class="ri-heart-fill"></i></span>
                        <span><i class="ri-paint-fill"></i></span>
                        <span><i class="ri-shield-star-line"></i></span>
                    </div>
                </div>
                <div class="service__card__details">
                    <h4>Family Garden Retreat</h4>
                    <p>
                        Spacious and inviting, perfect for creating cherished memories
                        with loved ones.
                    </p>
                    <h5>Starting from <span>$249/night</span></h5>
                    <a href="../Billing/Billing.php"><button class="btn book-now"
                            data-logged-in="<?php echo $loggedIn ? 'true' : 'false'; ?>">Book Now</button></a>
                </div>
            </div>
        </div>





    </section>

    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/6.4.5/swiper-bundle.min.js'></script>
    <script src="Services.js"></script>

    <footer class="footer" id="contact">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="logo">
                    <a href="#home"><img src="assetservice/logo.png" alt="logo" /></a>
                </div>
                <p class="section__description">
                    Discover a world of comfort, luxury, and adventure as you explore
                    our curated selection of hotels, making every moment of your getaway
                    truly extraordinary.
                </p>
                <button class="btn">Book Now</button>
            </div>
            <div class="footer__col">
                <h4>QUICK LINKS</h4>
                <ul class="footer__links">
                    <li><a href="#">Browse Destinations</a></li>
                    <li><a href="#">Special Offers & Packages</a></li>
                    <li><a href="#">service Types & Amenities</a></li>
                    <li><a href="#">Customer Reviews & Ratings</a></li>
                    <li><a href="#">Travel Tips & Guides</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>OUR SERVICES</h4>
                <ul class="footer__links">
                    <li><a href="#">Concierge Assistance</a></li>
                    <li><a href="#">Flexible Booking Options</a></li>
                    <li><a href="#">Airport Transfers</a></li>
                    <li><a href="#">Wellness & Recreation</a></li>
                    <li><a href="#">Tour Guides & Extra Packages</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>CONTACT US</h4>
                <ul class="footer__links">
                    <li><a href="mailto:hoteleden2024kgm@gmail.com">hoteleden2024kgm@gmail.com</a></li>
                </ul>
                <div class="footer__socials">
                    <a href="#"><img src="assetservice/facebook.png" alt="facebook" /></a>
                    <a href="#"><img src="assetservice/instagram.png" alt="instagram" /></a>
                    <a href="#"><img src="assetservice/youtube.png" alt="youtube" /></a>
                    <a href="#"><img src="assetservice/twitter.png" alt="twitter" /></a>
                </div>
            </div>
        </div>
        <div class="footer__bar">
            Copyright © 2024 Latadecarapau. All rights reserved.
        </div>
    </footer>

    <script src="https://unpkg.com/scrollreveal"></script>
    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>