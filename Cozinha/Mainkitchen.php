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
    <link rel="stylesheet" href="Cozinha.css" />

    <meta name="description" content="">
    <meta name="author" content="">

    <title>EdenDining</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

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
                        <a class="nav-link active" href="../Home/Home.php">EdenHotel</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="about.php">Sobre Nós</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="menu.php">Menu</a>
                    </li>
                    <?php if ($loggedIn): ?>
                        <li class="nav-item">
                            <div class="user-profile">
                                <div class="dropdown">
                                    <button class="dropbtn">
                                        <i class="ri-user-line"></i>
                                        <span><?php echo $_SESSION['username']; ?></span>
                                    </button>
                                    <div class="dropdown-content">
                                        <a class="nav-link" href="../Profile/Profile.php">Profile</a>
                                        <a class="nav-link" href="#">Settings</a>
                                        <a class="nav-link" href="../Logout/logout.php">Logout</a>
                                    </div>
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

        <section class="hero">
            <div class="container">
                <div class="row">

                    <div class="col-lg-5 col-12 m-auto">
                        <div class="heroText">

                            <h1 class="text-white mb-lg-5 mb-3">Bife Delicioso</h1>

                            <div class="c-reviews my-3 d-flex flex-wrap align-items-center">
                                <div class="d-flex flex-wrap align-items-center">
                                    <h4 class="text-white mb-0 me-3">4.4/5</h4>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>
                                </div>

                                <p class="text-white w-100">De <strong>1,206+</strong> Avaliações</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 col-12">
                        <div id="carouselExampleCaptions" class="carousel carousel-fade hero-carousel slide"
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="carousel-image-wrap">
                                        <img src="images/slide/jay-wennington-N_Y88TWmGwA-unsplash.jpg"
                                            class="img-fluid carousel-image" alt="">
                                    </div>

                                    <div class="carousel-caption">
                                        <span class="text-white">

                                        </span>

                                        <h4 class="hero-text">Tudo um pouco e do melhor </h4>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="carousel-image-wrap">
                                        <img src="images/slide/jason-leung-O67LZfeyYBk-unsplash.jpg"
                                            class="img-fluid carousel-image" alt="">
                                    </div>

                                    <div class="carousel-caption">
                                        <div class="d-flex align-items-center">
                                            <h4 class="hero-text">Bife</h4>

                                            <span class="price-tag ms-4"><small>€</small>26.50</span>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center">
                                            <h5 class="reviews-text mb-0 me-3">3.8/5</h5>

                                            <div class="reviews-stars">
                                                <i class="bi-star-fill reviews-icon"></i>
                                                <i class="bi-star-fill reviews-icon"></i>
                                                <i class="bi-star-fill reviews-icon"></i>
                                                <i class="bi-star reviews-icon"></i>
                                                <i class="bi-star reviews-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="carousel-item">
                                    <div class="carousel-image-wrap">
                                        <img src="images/slide/ivan-torres-MQUqbmszGGM-unsplash.jpg"
                                            class="img-fluid carousel-image" alt="">
                                    </div>

                                    <div class="carousel-caption">
                                        <div class="d-flex align-items-center">
                                            <h4 class="hero-text">Sausage Pasta</h4>

                                            <span class="price-tag ms-4"><small>€</small>18.25</span>
                                        </div>

                                        <div class="d-flex flex-wrap align-items-center">
                                            <h5 class="reviews-text mb-0 me-3">4.2/5</h5>

                                            <div class="reviews-stars">
                                                <i class="bi-star-fill reviews-icon"></i>
                                                <i class="bi-star-fill reviews-icon"></i>
                                                <i class="bi-star-fill reviews-icon"></i>
                                                <i class="bi-star-fill reviews-icon"></i>
                                                <i class="bi-star reviews-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>

                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="video-wrap">
                <video autoplay="" loop="" muted="" class="custom-video" poster="">
                    <source src="video/production_ID_3769033.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

            <div class="overlay"></div>
        </section>

        <section class="menu section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-12">
                        <h2 class="text-center mb-lg-5 mb-4">Special Menus</h2>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <div class="menu-image-wrap">
                                <img src="images/breakfast/brett-jordan-8xt8-HIFqc8-unsplash.jpg"
                                    class="img-fluid menu-image" alt="">

                                <span class="menu-tag bg-warning">Breakfast</span>
                            </div>

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Morning Fresh</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>€</small>12.50</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">4.3/5</h6>

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
                            <div class="menu-image-wrap">
                                <img src="images/lunch/farhad-ibrahimzade-MGKqxm6u2bc-unsplash.jpg"
                                    class="img-fluid menu-image" alt="">

                                <span class="menu-tag bg-warning">Lunch</span>
                            </div>

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Tooplate Soup</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>€</small>24.50</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">50 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <div class="menu-image-wrap">
                                <img src="images/dinner/keriliwi-c3mFafsFz2w-unsplash.jpg" class="img-fluid menu-image"
                                    alt="">

                                <span class="menu-tag bg-warning">Dinner</span>
                            </div>

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Premium Steak</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>€</small>45</span>

                                <del class="ms-4"><small>€</small>150</del>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">86 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <div class="menu-image-wrap">
                                <img src="images/dinner/farhad-ibrahimzade-ZipYER3NLhY-unsplash.jpg"
                                    class="img-fluid menu-image" alt="">

                                <span class="menu-tag bg-warning">Dinner</span>
                            </div>

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Seafood Set</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>€</small>86</span>

                                <del class="ms-4"><small>€</small>124</del>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">3/5</h6>

                                    <div class="reviews-stars">
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star-fill reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                        <i class="bi-star reviews-icon"></i>
                                    </div>

                                    <p class="reviews-text mb-0 ms-4">44 Reviews</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="menu-thumb">
                            <div class="menu-image-wrap">
                                <img src="images/breakfast/louis-hansel-dphM2U1xq0U-unsplash.jpg"
                                    class="img-fluid menu-image" alt="">

                                <span class="menu-tag bg-warning">Breakfast</span>
                            </div>

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Burger Set</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>€</small>20.50</span>

                                <div class="d-flex flex-wrap align-items-center w-100 mt-2">
                                    <h6 class="reviews-text mb-0 me-3">4.3/5</h6>

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
                            <div class="menu-image-wrap">
                                <img src="images/lunch/farhad-ibrahimzade-D5c9ZciQy_I-unsplash.jpg"
                                    class="img-fluid menu-image" alt="">

                                <span class="menu-tag bg-warning">Lunch</span>
                            </div>

                            <div class="menu-info d-flex flex-wrap align-items-center">
                                <h4 class="mb-0">Healthy Soup</h4>

                                <span class="price-tag bg-white shadow-lg ms-4"><small>€</small>34.20</span>

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

                </div>
            </div>
        </section>

        <section class="BgImage"></section>

        <section class="news section-padding">
            <div class="container">
                <div class="row">

                    <h2 class="text-center mb-lg-5 mb-4">News &amp; Events</h2>

                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="news-thumb mb-4">
                            <a href="news-detail.html">
                                <img src="images/news/pablo-merchan-montes-Orz90t6o0e4-unsplash.jpg"
                                    class="img-fluid news-image" alt="">
                            </a>

                            <div class="news-text-info news-text-info-large">
                                <span class="category-tag bg-danger">Featured</span>

                                <h5 class="news-title mt-2">
                                    <a href="news-detail.html" class="news-title-link">Healthy Lifestyle and happy
                                        living tips</a>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="news-thumb mb-4">
                            <a href="news-detail.html">
                                <img src="images/news/stefan-johnson-xIFbDeGcy44-unsplash.jpg"
                                    class="img-fluid news-image" alt="">
                            </a>

                            <div class="news-text-info news-text-info-large">
                                <span class="category-tag bg-danger">Featured</span>

                                <h5 class="news-title mt-2">
                                    <a href="news-detail.html" class="news-title-link">How to make a healthy meal</a>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="news-thumb mb-lg-0 mb-lg-4 mb-0">
                            <a href="news-detail.html">
                                <img src="images/news/gilles-lambert-S_LhjpfIdm4-unsplash.jpg"
                                    class="img-fluid news-image" alt="">
                            </a>

                            <div class="news-text-info">
                                <span class="category-tag me-3 bg-info">Promotions</span>

                                <strong>8 April 2022</strong>

                                <h5 class="news-title mt-2">
                                    <a href="news-detail.html" class="news-title-link">Is Coconut good for you?</a>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12">
                        <div class="news-thumb mb-lg-0 mb-lg-4 mb-2">
                            <a href="news-detail.html">
                                <img src="images/news/caroline-attwood-bpPTlXWTOvg-unsplash.jpg"
                                    class="img-fluid news-image" alt="">
                            </a>

                            <div class="news-text-info">
                                <span class="category-tag">News</span>

                                <h5 class="news-title mt-2">
                                    <a href="news-detail.html" class="news-title-link">Salmon Steak Noodle</a>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="news-thumb mb-4">
                            <a href="news-detail.html">
                                <img src="images/news/louis-hansel-GiIiRV0FjwU-unsplash.jpg"
                                    class="img-fluid news-image" alt="">
                            </a>

                            <div class="news-text-info">
                                <span class="category-tag me-3 bg-info">Meeting</span>

                                <strong>30 April 2022</strong>

                                <h5 class="news-title mt-2">
                                    <a href="news-detail.html" class="news-title-link">Making a healthy salad</a>
                                </h5>
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
                        <form class="booking-form row" role="form" action="ver_reservas.php" method="post">
                            <div class="col-lg-6 col-12">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Seu Nome"
                                    value="<?php echo htmlspecialchars($_GET['name'] ?? ''); ?>" required>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*" class="form-control"
                                    placeholder="seu@email.com"
                                    value="<?php echo htmlspecialchars($_GET['email'] ?? ''); ?>" required>
                            </div>
                            <div class="col-lg-6 col-12">
                                <label for="phone" class="form-label">Número de telefone</label>
                                <input type="telephone" name="phone" id="phone" class="form-control"
                                    placeholder="123-456-780"
                                    value="<?php echo htmlspecialchars($_GET['phone'] ?? ''); ?>">
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