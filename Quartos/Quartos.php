<?php
session_start();
$loggedIn = isset($_SESSION['username']);

?>

<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <title>Rooms</title>
</head>

<body>
  <header class="header">
    <nav>
      <div class="nav__bar">
        <div class="logo">
          <a href="#"><img src="assetsrooms/logo.png" alt="logo" /></a>
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <i class="ri-menu-line"></i>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li><a href="../Home/Home.php">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="../Services/Services.php">Services</a></li>
        <li><a href="../Quartos/Quartos.php">Rooms</a></li>
        <li><a href="#explore">Explore</a></li>
        <li><a href="#contact">Contact</a></li>


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


  <section class="section__container room__container">
    <p class="section__subheader">Room´s for all</p>
    <h2 class="section__header">Make your reservation now .</h2>

    <!-- Search Bar -->
    <div class="search__bar">
      <input type="text" id="search-input" placeholder="Search rooms..." id="search-input" />
      <button class="btn " id="search-btn">Search</button>
    </div>

    <div class="room__grid">
      <div class="room__card">
        <div class="room__card__image">
          <img src="assetsrooms/room-1.jpg" alt="room" />
          <div class="room__card__icons">
            <span><i class="ri-heart-fill"></i></span>
            <span><i class="ri-paint-fill"></i></span>
            <span><i class="ri-shield-star-line"></i></span>
          </div>
        </div>
        <div class="room__card__details">
          <h4>Deluxe Ocean View</h4>
          <p>
            Bask in luxury with breathtaking ocean views from your private
            suite.
          </p>
          <h5>Starting from <span>$299/night</span></h5>
          <button class="btn book-now" data-logged-in="<?php echo $loggedIn ? 'true' : 'false'; ?>">Book Now</button>
        </div>
      </div>
      <div class="room__card">
        <div class="room__card__image">
          <img src="assetsrooms/room-2.jpg" alt="room" />
          <div class="room__card__icons">
            <span><i class="ri-heart-fill"></i></span>
            <span><i class="ri-paint-fill"></i></span>
            <span><i class="ri-shield-star-line"></i></span>
          </div>
        </div>
        <div class="room__card__details">
          <h4>Executive Cityscape Room</h4>
          <p>
            Experience urban elegance and modern comfort in the heart of the
            city.
          </p>
          <h5>Starting from <span>$199/night</span></h5>
          <button class="btn book-now" data-logged-in="<?php echo $loggedIn ? 'true' : 'false'; ?>">Book Now</button>
        </div>
      </div>
      <div class="room__card">
        <div class="room__card__image">
          <img src="assetsrooms/room-3.jpg" alt="room" />
          <div class="room__card__icons">
            <span><i class="ri-heart-fill"></i></span>
            <span><i class="ri-paint-fill"></i></span>
            <span><i class="ri-shield-star-line"></i></span>
          </div>
        </div>
        <div class="room__card__details">
          <h4>Family Garden Retreat</h4>
          <p>
            Spacious and inviting, perfect for creating cherished memories
            with loved ones.
          </p>
          <h5>Starting from <span>$249/night</span></h5>
          <button class="btn book-now" data-logged-in="<?php echo $loggedIn ? 'true' : 'false'; ?>">Book Now</button>
        </div>
      </div>
    </div>





  </section>

  <footer class="footer" id="contact">
    <div class="section__container footer__container">
      <div class="footer__col">
        <div class="logo">
          <a href="#home"><img src="assetsrooms/logo.png" alt="logo" /></a>
        </div>
        <p class="section__description">
          Discover a world of comfort, luxury, and adventure as you explore
          our curated selection of hotels, making every moment of your getaway
          truly extraordinary.
        </p>
        <button class="btn" data-logged-in="<?php echo $loggedIn ? 'true' : 'false'; ?>">Book Now</button>
      </div>
      <div class="footer__col">
        <h4>QUICK LINKS</h4>
        <ul class="footer__links">
          <li><a href="#">Browse Destinations</a></li>
          <li><a href="#">Special Offers & Packages</a></li>
          <li><a href="#">Room Types & Amenities</a></li>
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
        </ul>
      </div>
      <div class="footer__col">
        <h4>CONTACT US</h4>
        <ul class="footer__links">
          <li><a href="#">hoteleden2024kgm@gmail.com</a></li>
        </ul>
        <div class="footer__socials">
          <a href="#"><img src="assetsrooms/facebook.png" alt="facebook" /></a>
          <a href="#"><img src="assetsrooms/instagram.png" alt="instagram" /></a>
          <a href="#"><img src="assetsrooms/youtube.png" alt="youtube" /></a>
          <a href="#"><img src="assetsrooms/twitter.png" alt="twitter" /></a>
        </div>
      </div>
    </div>
    <div class="footer__bar">
      Copyright © 2024 Latadecarapau. All rights reserved.
    </div>
  </footer>

  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="Quartos.js"></script>
  <script src="searchbar.js"></script>
  <script src="Redirect.js"></script>
  
</body>

</html>