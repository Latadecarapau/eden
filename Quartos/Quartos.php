<?php
require '../db.php';
session_start();
$loggedIn = isset($_SESSION['username']);


$query = "SELECT * FROM exhibit_rooms";
$result = mysqli_query($conn, $query);
$rooms = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="Quartos.css" />
  <title>Rooms</title>
  <style>
    .room__card {
      border: 1px solid #ddd;
      margin: 10px;
      padding: 10px;
      width: 400px;
      display: inline-block;
      vertical-align: top;
    }

    .slide {
      width: 100%;
      display: none;
    }

    .slide.active {
      display: block;
    }
  </style>
  <script>
    async function fetchRoomData() {
      try {
        const response = await fetch('fetch_rooms.php');
        const rooms = await response.json();

        if (rooms && rooms.length > 0) {
          const container = document.getElementById('roomsContainer');
          container.innerHTML = ''; // Clear any existing content

          rooms.forEach((room, index) => {
            const roomCard = document.createElement('div');
            roomCard.className = 'room__card';
            const imagesHTML = room.images.map((img, i) => `
              <img src="${img}" alt="room" class="slide ${i === 0 ? 'active' : ''}" />
            `).join('');
            roomCard.innerHTML = `
              <div class="room__card__image">
                <div class="slider slider-${index + 1}">
                  <div class="slides slides-${index + 1}">
                    ${imagesHTML}
                  </div>
                  <button class="prev" onclick="moveSlide(-1, 'slides-${index + 1}')">&#10094;</button>
                  <button class="next" onclick="moveSlide(1, 'slides-${index + 1}')">&#10095;</button>
                </div>
                <div class="room__card__icons">
                  <span><i class="ri-heart-fill"></i></span>
                  <span><i class="ri-paint-fill"></i></span>
                  <span><i class="ri-shield-star-line"></i></span>
                </div>
              </div>
              <div class="room__card__details">
                <h4>${room.room_name}</h4>
                <p>${room.Description}</p>
                <p><strong>Tipo de quarto:</strong> ${room.id_room}</p>
                <p><strong>Número do Quarto:</strong> ${room.room_number}</p>
                <p><strong>Capacidade:</strong> ${room.capacity}</p>
                <h5>Preço <span>$${room.price}/Noite</span></h5>
                   <button class="btn book-now"data-logged-in="${<?php echo $loggedIn ? 'true' : 'false'; ?>}" data-id="${room.id_room}" data-number="${room.room_number}" data-capacity="${room.capacity}" onclick="handleBookNow(this)">Book Now</button>
              </div>
            `;
            container.appendChild(roomCard);
          });
        }
      } catch (error) {
        console.error('Error fetching room data:', error);
      }
    }

    function moveSlide(n, sliderClass) {
      const slides = document.querySelectorAll(`.${sliderClass} .slide`);
      let activeIndex = -1;

      slides.forEach((slide, index) => {
        if (slide.classList.contains('active')) {
          activeIndex = index;
          slide.classList.remove('active');
        }
      });

      activeIndex = (activeIndex + n + slides.length) % slides.length;
      slides[activeIndex].classList.add('active');
    }
    function handleBookNow(button) {
      const loggedIn = button.getAttribute('data-logged-in') === 'true';

      if (loggedIn) {
        const roomData = {
          id: button.getAttribute('data-id'),
          number: button.getAttribute('data-number'),
          name: button.getAttribute('data-name'),
          capacity: button.getAttribute('data-capacity')
        };
        sessionStorage.setItem('roomData', JSON.stringify(roomData));
        // Redirect to booking page or perform booking action
        window.location.href = '../Billing/Billing.php';
      } else {
        // Redirect to login page
        window.location.href = '../Login/Login.php';
      }
    }

    window.onload = fetchRoomData;
  </script>

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
        <li><a href="../AboutUs/About.php">Sobre Nós</a></li>
        <li><a href="../Services/Services.php">Pacotes</a></li>
        <li><a href="../Home/Home.php">Explore</a></li>
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
                  <a href="../Profile/Profile.php">Profile</a>
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



  <div id="roomsContainer">


  </div>

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


  <script>
    function bookNow(button) {
      const roomType = button.getAttribute('data-room-type');
      const roomPrice = button.getAttribute('data-room-price');
      const roomNumber = button.getAttribute('data-room-number');
      const roomCapacity = button.getAttribute('data-room-capacity');
      const loggedIn = button.getAttribute('data-logged-in');

      if (loggedIn === 'true') {
        // Redirect to the billing page with the room information
        window.location.href = `../Billing/Billing.php?room_Type=${roomType}&room_price=${roomPrice}&room_number=${roomNumber}&room_capacity=${roomCapacity}`;
      }
    }

    // Attach event listeners to the book-now buttons
    document.querySelectorAll('.btn.book-now').forEach(button => {
      button.addEventListener('click', () => bookNow(button));
    });
  </script>
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="Quartos.js"></script>
  <script src="searchbar.js"></script>
  <!-- <script src="Redirect.js"></script> -->

</body>

</html>