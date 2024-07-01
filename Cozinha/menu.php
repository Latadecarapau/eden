<?php
require '../db.php';
session_start();

$loggedIn = isset($_SESSION['username']);
$user_email = $_SESSION['user_email'];
if (!isset($_SESSION['user_email'])) {
    header("Location: ../Login/login.php");
    exit();
}

$stmt = $conn->prepare("SELECT firstname, lastname, telephone, email FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No user found with email " . htmlspecialchars($user_email);
    exit();
}
$user_details = $result->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $user_details['firstname'] . " " . $user_details['lastname'];
    $email = $user_details['email'];
    $numero_telefone = $user_details['telephone'];
    $numero_pessoas = $_POST['people'] ?? '';
    $data = $_POST['date'] ?? '';
    $hora = $_POST['time'] ?? '';
    $preferencia_mesa = $_POST['table'] ?? '';
    $pedido_especial = $_POST['message'] ?? '';

    if ($nome_completo && $email && $numero_telefone && $numero_pessoas && $data && $hora && $preferencia_mesa && $pedido_especial) {
        $stmt = $conn->prepare("INSERT INTO reservation_restaurant (nome_completo, email, numero_telefone, numero_pessoas, data, hora, preferencia_mesa, pedido_especial) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nome_completo, $email, $numero_telefone, $numero_pessoas, $data, $hora, $preferencia_mesa, $pedido_especial);

        if ($stmt->execute()) {
            echo "<script>showNotification('Your reservation was successfully made.');</script>";
            echo "<script>setTimeout(function(){ window.location.reload(); }, 3000);</script>";
        } else {
            $reservation_error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $reservation_error = "All fields are required.";
    }
}
$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>EdenDining</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="Cozinha.css" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/tooplate-crispy-kitchen.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
       body {
    font-family: Arial, sans-serif;
}
.container {
    width: 90%;
    margin: auto;
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin: -15px;
}
.col {
    flex: 1 1 30%;
    padding: 15px;
    box-sizing: border-box;
}
.menu-thumb {
    margin-bottom: 30px;
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.menu-thumb img {
    width: 100%;
    height: auto;
}
.menu-info {
    padding: 15px;
}
.price-tag {
    font-size: 1.2em;
    font-weight: bold;
    background-color: #fff;
    padding: 5px 10px;
    border-radius: 3px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 0;
}
.reviews-text {
    font-size: 1em;
    margin: 0;
}
.reviews-stars {
    display: flex;
    margin-left: auto;
}
.reviews-stars .reviews-icon {
    color: #FFD700;
    margin-right: 2px;
}
.menu-info .details {
    display: flex;
    align-items: center;
    margin-top: 10px;
    justify-content: space-between;
}
    </style>
</head>

<body>
    <div class="notification" id="notification"></div>

    <nav class="navbar navbar-expand-lg bg-white shadow-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="index.html">EdenDining</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="../Home/Home.php">EdenHotel</a></li>
                    <li class="nav-item"><a class="nav-link" href="Mainkitchen.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">Sobre Nós</a></li>
                    <?php if ($loggedIn): ?>
                        <li class="nav-item user-profile">
                            <div class="dropdown">
                                <button class="dropbtn">
                                    <i class="ri-user-line"></i>
                                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                </button>
                                <div class="dropdown-content">
                                    <a href="../Profile/Profile.php">Profile</a>
                                    <a href="#">Settings</a>
                                    <a href="../Logout/logout.php">Logout</a>
                                </div>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="../Login/Login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="../Registar/Registar.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="d-none d-lg-block">
                <button type="button" class="custom-btn btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#BookingModal">Reservar Mesa</button>
            </div>
        </div>
    </nav>
    <main>
        <header class="site-header site-menu-header">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-12 mx-auto">
                        <h1 class="text-white">O que há no menu?</h1>
                        <strong class="text-white">Explore o seu paladar com as nossas comidas típicas e muito
                            mais</strong>
                    </div>
                </div>
            </div>
            <div class="overlay"></div>
        </header>

        <!-- Menu Section -->
        <section class="menu section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-lg-5 mb-4">Pequeno Almoço</h2>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="images/breakfast/brett-jordan-8xt8-HIFqc8-unsplash.jpg" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">Um Bom Começo</h4>
                            <p class="reviews-text mb-2">Consiste em dois pães torrados com salmão fumado, queijo mozarela, ovo mexido e majericão.</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>9,50</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">4.4/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">128 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="images/breakfast/lucas-swennen-1W_MyJSRLuQ-unsplash.jpg" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">Panquecas Cremosas</h4>
                            <p class="reviews-text mb-2">Prato servido de 4 panquecas, acompanhadas de amêndoa e avelã, cobertas com o nosso topping de doce de leite e com côco ralado.</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>11.50</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">64 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="images/breakfast/louis-hansel-dphM2U1xq0U-unsplash.jpg" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">Inicial Burguer</h4>
                            <p class="reviews-text mb-2">Hamburguer simples, constituido por um pão de sementes de centeio, alface, tomate e pepino. O hamburguer pode ser vegetariano ou de carne de vaca/frango.</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>12.50</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">32 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="menu section-padding bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-lg-5 mb-4">Pratos Típicos</h2>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="assetservice/sardi.png" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">Sardinhas da Terra</h4>
                            <p class="reviews-text mb-2">Uma dose de sardinhas grelhadas na brasa, acompanhadas de salada e batata cozida</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>19,99</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">4.7/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">485 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="assetservice/cast.png" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">Estufado de Castanha com Grão de Bico Vegetariano</h4>
                            <p class="reviews-text mb-2">Prato constituido por alho francês, abóbora, tomate, castanhas assadas e o grão-de-bico</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>15,99</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">4/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">204 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="menu section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-lg-5 mb-4">Outras Refeições</h2>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="assetservice/bito.png" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">O Bitoque</h4>
                            <p class="reviews-text mb-2">Carne grelhada acompanhada de arroz simples, batata frita, ovo estrelado e salada. A carne pode ser de vaca, de porco ou de frango.</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>15,99</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">4.4/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">102 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="assetservice/mari.png" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">Cataplana do Pescador</h4>
                            <p class="reviews-text mb-2">Mexilhão fresco, berbigão fresco, camarão e ameijoa servidos numa cataplana acompanhado do nosso tempero.</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>35,99</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">4/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">176 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="menu-thumb">
                        <img src="assetservice/banq.png" class="img-fluid menu-image" alt="">
                        <div class="menu-info">
                            <h4 class="mb-0">O Banquete</h4>
                            <p class="reviews-text mb-2">Comida para a familia toda (6 pessoas). Bacalhau com natas, frango assado no forno, bata cozinha, castanha assada, choriço, azeitonas, cogumelos, pão, os nossos molhos especiais da casa e fatia dourada.</p>
                            <div class="details">
                                <span class="price-tag"><small>€</small>109,99</span>
                                <div class="d-flex align-items-center">
                                    <h6 class="reviews-text mb-0 me-3">4,5/5</h6>
                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                    <p class="reviews-text mb-0 ms-4">128 Avaliações</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

        <!-- Modal for Reservation -->
        <div class="modal fade" id="BookingModal" tabindex="-1" aria-labelledby="BookingModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="BookingModal">Reserva</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex flex-column justify-content-center">
                        <?php if (isset($reservation_success)): ?>
                            <div class="alert alert-success"><?php echo $reservation_success; ?></div>
                        <?php elseif (isset($reservation_error)): ?>
                            <div class="alert alert-danger"><?php echo $reservation_error; ?></div>
                        <?php endif; ?>
                        <div class="booking">
                            <form action="menu.php" method="post" class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nome Completo</label>
                                    <input type="text" id="nome_completo" name="nome_completo" class="form-control"
                                        value="<?php echo htmlspecialchars($user_details['firstname'] . ' ' . $user_details['lastname']); ?>"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" id="email" name="email" class="form-control"
                                        value="<?php echo htmlspecialchars($user_details['email']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Telefone</label>
                                    <input type="tel" id="numero_telefone" name="numero_telefone" class="form-control"
                                        value="<?php echo htmlspecialchars($user_details['telephone']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="people" class="form-label">Número de Pessoas</label>
                                    <input type="number" class="form-control" id="people" name="people" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Data</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="time" class="form-label">Hora</label>
                                    <input type="time" class="form-control" id="time" name="time" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="table" class="form-label">Preferência da Mesa</label>
                                    <select class="form-select" id="table" name="table" required>
                                        <option value="Mesa Junto À Janela">Mesa Junto À Janela</option>
                                        <option value="Mesa Junto À Parede">Mesa Junto À Parede</option>
                                        <option value="Mesa Junto À Entrada">Mesa Junto À Entrada</option>
                                        <option value="Mesa na Esplanada">Mesa na Esplanada</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="message" class="form-label">Pedido Especial</label>
                                    <textarea class="form-control" id="message" name="message" rows="3"
                                        required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-danger">Reservar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer section-padding">

        <div class="container">

            <div class="row">

                <div class="col-12">
                    <h4 class="text-white mb-4 me-5">EdenDining</h4>
                </div>

                <div class="col-lg-4 col-md-7 col-xs-12 tooplate-mt30">
                    <h6 class="text-white mb-lg-4 mb-3">Localização</h6>

                    <p>Av. dos Náufragos 29, 2970-637 Sesimbra</p>

                    <a href="https://www.google.co.th/maps/place/Ribamar/@38.4433895,-9.1045032,161m/data=!3m1!1e3!4m6!3m5!1s0xd195221a531bec3:0xf09e9b3c1f8b1bd!8m2!3d38.4433413!4d-9.1046457!16s%2Fg%2F1tmcnhng?hl=en&entry=ttu"
                        class="custom-btn btn btn-dark mt-2">Direções</a>
                </div>

                <div class="col-lg-4 col-md-5 col-xs-12 tooplate-mt30">
                    <h6 class="text-white mb-lg-4 mb-3">Abertura</h6>

                    <p class="mb-2">Segunda - Domingo</p>

                    <p>9:00 AM - 22:00 PM</p>

                    <p>Tel: <a href="tel: 010-02-0340" class="tel-link">010-02-0340</a></p>
                </div>

                <div class="col-lg-4 col-md-6 col-xs-12 tooplate-mt30">
                    <h6 class="text-white mb-lg-4 mb-3">Social</h6>

                    <ul class="social-icon">
                        <li><a href="#" class="social-icon-link bi-facebook"></a></li>

                        <li><a href="#" class="social-icon-link bi-instagram"></a></li>

                        <li><a href="#" target="_blank" class="social-icon-link bi-twitter"></a></li>

                        <li><a href="#" class="social-icon-link bi-youtube"></a></li>
                    </ul>

                    <p class="copyright-text tooplate-mt60">2023. Eden. All rights reserved.
                        <br>Design: <a rel="nofollow" href="https://www.tooplate.com/" target="_blank">Tooplate</a>
                    </p>

                </div>

            </div><!-- row ending -->

        </div><!-- container ending -->

    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/tooplate-script.js"></script>
    <script>
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerText = message;
            document.body.appendChild(notification);
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
                document.body.removeChild(notification);
            }, 3000);
        }
    </script>
</body>

</html>