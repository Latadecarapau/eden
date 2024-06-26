<?php
require '../db.php';
session_start();

$loggedIn = isset($_SESSION['username']);

if (!$loggedIn) {
    // Redirect to login with form data as query parameters
    $query = http_build_query($_POST);
    header("Location: login.php?$query");
    exit();
}

// Fetch user details if logged in
$user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
$user_query = $conn->prepare("SELECT firstname, lastname, email, telephone FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user_result = $user_query->get_result();
$user = $user_result->fetch_assoc();

$nome_completo = $user['firstname'] . ' ' . $user['lastname'];
$email = $user['email'];
$numero_telefone = $user['telephone'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['name'] ?? null;
    $email = $_POST['email'] ?? null;
    $numero_telefone = $_POST['phone'] ?? null;
    $numero_pessoas = $_POST['people'] ?? null;
    $data = $_POST['date'] ?? null;
    $hora = $_POST['time'] ?? null;
    $preferencia_mesa = $_POST['table'] ?? null;
    $pedido_especial = $_POST['message'] ?? null;


    if ($nome_completo && $email && $numero_telefone && $numero_pessoas && $data && $hora && $preferencia_mesa && $pedido_especial) {
        $stmt = $conn->prepare("INSERT INTO reservation_restaurant (nome_completo, email, numero_telefone, numero_pessoas, data, hora, preferencia_mesa, pedido_especial) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $nome_completo, $email, $numero_telefone, $numero_pessoas, $data, $hora, $preferencia_mesa, $pedido_especial);

        if ($stmt->execute()) {
            echo "New reservation created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
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

    <!--

Tooplate 2129 Crispy Kitchen

https://www.tooplate.com/view/2129-crispy-kitchen

-->
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a class="navbar-brand" href="index.html">
                EdenDining
            </a>

            <div class="d-lg-none">
                <button type="button" class="custom-btn btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#BookingModal">Reservation</button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../Home/Home.php">EdenHotel</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="Mainkitchen.php">Home</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="about.php">Sobre Nós</a>
                    </li>
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
                        <h1 class="text-white">Our Menus</h1>

                        <strong class="text-white">Perfect for all Breakfast, Lunch and Dinner</strong>
                    </div>

                </div>
            </div>

            <div class="overlay"></div>
        </header>

        <section class="menu section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-12">
                        <h2 class="mb-lg-5 mb-4">Breakfast Menu</h2>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/breakfast/brett-jordan-8xt8-HIFqc8-unsplash.jpg"
                                class="img-fluid menu-image" alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Fresh Start</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>24.50</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">4.4/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">128 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/breakfast/lucas-swennen-1W_MyJSRLuQ-unsplash.jpg"
                                class="img-fluid menu-image" alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Baked Creamy</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>16.50</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">64 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/breakfast/louis-hansel-dphM2U1xq0U-unsplash.jpg"
                                class="img-fluid menu-image" alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Burger Set</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>24.50</span>

                                <del class="ms-4"><small>$</small>36.50</del>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">32 Reviews</p>
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
                        <h2 class="mb-lg-5 mb-4">Lunch Menu</h2>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/lunch/louis-hansel-cH5IPjaAYyo-unsplash.jpg" class="img-fluid menu-image"
                                alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Super Steak Set</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>32.75</span>

                                <del class="ms-4"><small>$</small>55</del>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">4.2/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">66 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/lunch/louis-hansel-rheOvfxOlOA-unsplash.jpg" class="img-fluid menu-image"
                                alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Bread &amp; Steak Set</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>42.50</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">84 Reviews</p>
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
                        <h2 class="mb-lg-5 mb-4">Dinner Menu</h2>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/dinner/farhad-ibrahimzade-ZipYER3NLhY-unsplash.jpg"
                                class="img-fluid menu-image" alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Seafood Set</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>65.50</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">4.4/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">102 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/dinner/keriliwi-c3mFafsFz2w-unsplash.jpg" class="img-fluid menu-image"
                                alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Premium Steak</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>74.25</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">56 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <img src="images/dinner/farhad-ibrahimzade-isHUj3N0194-unsplash.jpg"
                                class="img-fluid menu-image" alt="">

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Salmon Set</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>$</small>60</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">76 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

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

                        <<li><a href="#" target="_blank" class="social-icon-link bi-twitter"></a></li>

                            <li><a href="#" class="social-icon-link bi-youtube"></a></li>
                    </ul>

                    <p class="copyright-text tooplate-mt60">2023. Eden. All rights reserved.
                        <br>Design: <a rel="nofollow" href="https://www.tooplate.com/" target="_blank">Tooplate</a>
                    </p>

                </div>

            </div><!-- row ending -->

        </div><!-- container ending -->

    </footer>

    <!-- Modal -->
    <div class="modal fade" id="BookingModal" tabindex="-1" aria-labelledby="BookingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="mb-0">Reserve a table</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column justify-content-center">
                    <div class="booking">
                        <form class="booking-form row" role="form" action="../Reservas/Reservas.php" method="post">
                            <div class="col-lg-6 col-12">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo htmlspecialchars($nome_completo); ?>" required>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo htmlspecialchars($nome_completo); ?>" required>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="phone" class="form-label">Número de telefone</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="<?php echo htmlspecialchars($numero_telefone); ?>" required>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="people" class="form-label">Número de pessoas</label>
                                <input type="text" name="people" id="people" class="form-control"
                                    placeholder="12 Pessoas"
                                    value="<?php echo htmlspecialchars($_GET['people'] ?? ''); ?>">
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="date" class="form-label">Data</label>
                                <input type="date" name="date" id="date"
                                    value="<?php echo htmlspecialchars($_GET['date'] ?? ''); ?>" class="form-control">
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="time" class="form-label">Horas a reservar</label>
                                <select class="form-select form-control" name="time" id="time">
                                    <option value="09:00:00" <?php echo (($_GET['time'] ?? '') == '09:00:00') ? 'selected' : ''; ?>>9:00 AM</option>
                                    <option value="10:00:00" <?php echo (($_GET['time'] ?? '') == '10:00:00') ? 'selected' : ''; ?>>10:00 AM</option>
                                    <option value="11:00:00" <?php echo (($_GET['time'] ?? '') == '11:00:00') ? 'selected' : ''; ?>>11:00 AM</option>
                                    <option value="12:00:00" <?php echo (($_GET['time'] ?? '') == '12:00:00') ? 'selected' : ''; ?>>12:00 PM</option>
                                    <option value="13:00:00" <?php echo (($_GET['time'] ?? '') == '13:00:00') ? 'selected' : ''; ?>>1:00 PM</option>
                                    <option value="14:00:00" <?php echo (($_GET['time'] ?? '') == '14:00:00') ? 'selected' : ''; ?>>2:00 PM</option>
                                    <option value="15:00:00" <?php echo (($_GET['time'] ?? '') == '15:00:00') ? 'selected' : ''; ?>>3:00 PM</option>
                                    <option value="16:00:00" <?php echo (($_GET['time'] ?? '') == '16:00:00') ? 'selected' : ''; ?>>4:00 PM</option>
                                    <option value="17:00:00" <?php echo (($_GET['time'] ?? '') == '17:00:00') ? 'selected' : ''; ?>>5:00 PM</option>
                                    <option value="18:00:00" <?php echo (($_GET['time'] ?? '') == '18:00:00') ? 'selected' : ''; ?>>6:00 PM</option>
                                    <option value="19:00:00" <?php echo (($_GET['time'] ?? '') == '19:00:00') ? 'selected' : ''; ?>>7:00 PM</option>
                                    <option value="20:00:00" <?php echo (($_GET['time'] ?? '') == '20:00:00') ? 'selected' : ''; ?>>8:00 PM</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="table" class="form-label">Preferência de mesa</label>
                                <input type="text" name="table" id="table" class="form-control" placeholder=""
                                    value="<?php echo htmlspecialchars($_GET['table'] ?? ''); ?>">
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Mensagem adicional</label>
                                <textarea class="form-control" rows="4" id="message" name="message"
                                    placeholder="Mensagem Adicional"><?php echo htmlspecialchars($_GET['message'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-lg-4 col-12 ms-auto">
                                <button type="submit" class="form-control">Enviar Reservas</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/custom.js"></script>

</body>

</html>