<?php
session_start();
$loggedIn = isset($_SESSION['username']);
require '../db.php';
$sql = "SELECT id_package, package_name, price_package, images, description FROM packages";
$result = $conn->query($sql);
?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="Services.css" />
    <title>Services</title>
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
            <button class="btn nav__btn">Checkout</button>
        </nav>
    </header>
    <section class="section__container service__container">
        <p class="section__subheader">Pacotes para todos</p>
        <h2 class="section__header">Compre seu pacote agora.</h2>

        <div class="service__grid">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='service__card'>";
                    echo "<div class='service__card__image'>";
                    if ($row["images"]) {
                        $imageData = base64_encode($row["images"]);
                        echo "<img src='data:image/jpeg;base64," . $imageData . "' alt='" . $row["package_name"] . "' />";
                    }
                    echo "<div class='service__card__icons'>";
                    echo "<span><i class='ri-heart-fill'></i></span>";
                    echo "<span><i class='ri-paint-fill'></i></span>";
                    echo "<span><i class='ri-shield-star-line'></i></span>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='service__card__details'>";
                    echo "<h4>" . $row["package_name"] . "</h4>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "<h5>Starting from <span>$" . $row["price_package"] . "</span></h5>";
                    echo "<button class='btn book-now' data-id='" . $row["id_package"] . "' data-name='" . $row["package_name"] . "' data-price='" . $row["price_package"] . "' data-description='" . $row["description"] . "' data-logged-in='" . ($loggedIn ? 'true' : 'false') . "'>Book Now</button>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No packages available.";
            }
            $conn->close();
            ?>
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
                <a href="../Contactos/ContactForm.php"><button class="btn">CONTACT-NOS</button></a>
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
        document.addEventListener('DOMContentLoaded', function () {
            const bookNowButtons = document.querySelectorAll('.book-now');

            bookNowButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const loggedIn = button.getAttribute('data-logged-in') === 'true';
                    if (loggedIn) {
                        const packageData = {
                            id: button.getAttribute('data-id'),
                            name: button.getAttribute('data-name'),
                            price: button.getAttribute('data-price'),
                            description: button.getAttribute('data-description')
                        };
                        sessionStorage.setItem('packageData', JSON.stringify(packageData));
                        window.location.href = '../Billing/PacoteBilling.php';
                    } else {
                        window.location.href = '../Login/Login.php';
                    }
                });
            });
        });
    </script>
</body>

</html>