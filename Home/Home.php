<?php
session_start();
$loggedIn = isset($_SESSION['username']);

require '../db.php';

$sql_reservations = "SELECT COUNT(*) as total_reservations FROM reservations";
$result_reservations = $conn->query($sql_reservations);
$total_reservations = $result_reservations->fetch_assoc()['total_reservations'];

$sql_rooms = "SELECT COUNT(*) as total_rooms FROM exhibit_rooms WHERE is_available = 1";
$result_rooms = $conn->query($sql_rooms);
$total_rooms = $result_rooms->fetch_assoc()['total_rooms'];

?>

<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="Home.css" />
  <title>Eden Hotel</title>
</head>

<body>



  <header class="header">
    <nav>
      <div class="nav__bar">
        <div class="logo">
          <img src="assetshome/logo.png" alt="logo" />
        </div>
        <div class="nav__menu__btn" id="menu-btn">
          <i class="ri-menu-line"></i>
        </div>
      </div>
      <ul class="nav__links" id="nav-links">
        <li><a href="#home">Home</a></li>
        <li><a href="../AboutUs/About.php">Sobre Nós</a></li>
        <li><a href="../Services/Services.php">Pacotes</a></li>
        <li><a href="../Quartos/Quartos.php">Quartos</a></li>
        <li><a href="#explore">Restaurante</a></li>
        <li><a href="#contact">Contactos</a></li>


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
    <div class="section__container header__container" id="home">
      <p>Simples - Unico - Amigável</p>
      <h1>Sinta-se em casa<br />No Nosso <span>Hotel Eden</span>.</h1>
    </div>
    </ul>

  </header>

  <section class="section__container booking__container">
    <form action="/" class="booking__form">
      <div class="input__group">
        <span><i class="ri-calendar-2-fill"></i></span>
        <div>
          <label for="check-in">CHECK-IN</label>
          <input type="text" placeholder="Digite Aqui" />
        </div>
      </div>
      <div class="input__group">
        <span><i class="ri-calendar-2-fill"></i></span>
        <div>
          <label for="check-out">CHECK-OUT</label>
          <input type="text" placeholder="Digite Aqui" />
        </div>
      </div>
      <div class="input__group">
        <span><i class="ri-user-fill"></i></span>
        <div>
          <label for="check-out">Hospedes</label>
          <input type="text" placeholder="Digite Aqui" />
        </div>
      </div>
      <div class="input__group input__btn">
        <button class="btn">CHECK OUT</button>
      </div>
    </form>
  </section>

  <section class="section__container about__container" id="about">
    <div class="about__image">
      <img src="assetshome/about.jpg" alt="about" />
    </div>
    <div class="about__content">
      <p class="section__subheader">SOBRE NÓS</p>
      <h2 class="section__header">As suas melhores férias começam aqui!</h2>
      <p class="section__description">
        Com foco em acomodações de qualidade, experiências personalizadas e reserva fácil, a nossa plataforma está
        dedicada a garantir que cada viajante inicie as suas férias de sonho com confiança e entusiasmo.
      </p>
      <div class="about__btn">
        <button class="btn">Veja Mais</button>
      </div>
    </div>
  </section>

  <section class="section__container room__container">
    <p class="section__subheader">Os Nossos Quartos destacados </p>
    <h2 class="section__header">Quartos de mais refinada qualidade.</h2>
    <div class="room__grid">
      <div class="room__card">
        <div class="room__card__image">
          <img src="assetshome/room-1.jpg" alt="room" />
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
          <a href="../Quartos/Quartos.php"><button class="btn">Veja Mais</button></a>
        </div>
      </div>
      <div class="room__card">
        <div class="room__card__image">
          <img src="assetshome/room-2.jpg" alt="room" />
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
          <a href="../Quartos/Quartos.php"><button class="btn">Veja Mais</button></a>
        </div>
      </div>
      <div class="room__card">
        <div class="room__card__image">
          <img src="assetshome/room-3.jpg" alt="room" />
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
          <a href="../Quartos/Quartos.php"><button class="btn">Veja Mais</button></a>
        </div>
      </div>
    </div>
  </section>

  <section class="service" id="service">
    <div class="section__container service__container">
      <div class="service__content">
        <p class="section__subheader">Pacotes</p>
        <h2 class="section__header">Pacotes do preços irresistiveis.</h2>
        <ul class="service__list">
          <li>
            <span><i class="ri-shield-star-line"></i></span>
            Serviço topo de gama
          </li>
          <li>
            <span><i class="ri-24-hours-line"></i></span>
            24 horas de serviço ao quarto
          </li>
          <li>
            <span><i class="ri-headphone-line"></i></span>
            Spa
          </li>
          <li>
            <span><i class="ri-map-2-line"></i></span>
            Guia turisticos
          </li>
          <li>
            <span><i class="ri-map-2-line"></i></span>
            Atividades outdoors
          </li>
        </ul>
      </div>
    </div>
  </section>

  <section class="section__container banner__container">
    <div class="banner__content">
      <div class="banner__card">
        <h4><?php echo $total_rooms; ?>+</h4>
        <p>Quartos disponiveis</p>
      </div>
      <div class="banner__card">
        <h4><?php echo $total_reservations; ?>+</h4>
        <p>Reservas Feitas</p>
      </div>
      <div class="banner__card">
        <h4>600+</h4>
        <p>Clientes Satisfeitos</p>
      </div>
    </div>
  </section>


  <section class="explore" id="explore">
    <p class="section__subheader">EXPLORE</p>
    <h2 class="section__header">Conhece os sabores de EdenHotel</h2>
    <div class="explore__bg">
      <div class="explore__content">
        <p class="section__description" id="current-date-time"></p>
        <h4>Dê uma olhadela na nossa ementa</h4>
        <a href="../Cozinha/menu.php"><button class="btn">Ver Menus</button></a>
      </div>
    </div>
  </section>

  <footer class="footer" id="contact">
    <div class="section__container footer__container">
      <div class="footer__col">
        <div class="logo">
          <a href="#home"><img src="assetshome/logo.png" alt="logo" /></a>
        </div>
        <p class="section__description">
         Descubra seu Hotel de sonho e paradisiaco aqui
         com EdenHotel
        </p>
        <a href="../Contactos/ContactForm.php"><button class="btn">CONTACTA-NOS</button></a>
      </div>
      <div class="footer__col">
        <h4>QUICK LINKS</h4>
        <ul class="footer__links">
          <li><a href="../Cozinha/menu.php">Cozinha e Ménus</a></li>
          <li><a href="../Services/Services.php">Ofertas especiais e pacotes</a></li>
          <li><a href="../Quartos/Quartos.PHP">Tipos de Quartos e reservas</a></li>
          <li><a href="#">Sobre Nós</a></li>
        </ul>
      </div>
      <div class="footer__col">
        <h4>Os nossos Serviços</h4>
        <ul class="footer__links">
          <li><a href="#">Assistencia Rapida</a></li>
          <li><a href="#">Opções e booking Flexiveis</a></li>
          <li><a href="#">Tour Guides amplas</a></li>
          <li><a href="#">Comida local e nacional</a></li>
        </ul>
      </div>
      <div class="footer__col">
        <h4>NOSSO EMAIL</h4>
        <ul class="footer__links">
          <li><a href="#">hoteleden2024kgm@gmail.com</a></li>
        </ul>
        <div class="footer__socials">
          <a href="#"><img src="assetshome/facebook.png" alt="facebook" /></a>
          <a href="#"><img src="assetshome/instagram.png" alt="instagram" /></a>
          <a href="#"><img src="assetshome/youtube.png" alt="youtube" /></a>
          <a href="#"><img src="assetshome/twitter.png" alt="twitter" /></a>
        </div>
      </div>
    </div>
    <div class="footer__bar">
      Copyright © 2024 Latadecarapau. All rights reserved.
    </div>
  </footer>
  <script>
    function updateDateTime() {
      const dateTimeElement = document.getElementById('current-date-time');
      const currentDateTime = new Date();
      const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      };
      dateTimeElement.textContent = currentDateTime.toLocaleDateString('PT-PT', options);
    }

    document.addEventListener('DOMContentLoaded', function () {
      updateDateTime();
      setInterval(updateDateTime, 1000);
    });
  </script>

  <script src="https://unpkg.com/scrollreveal"></script>
  <script src="Home.js"></script>
</body>

</html>