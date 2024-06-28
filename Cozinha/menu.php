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
                        <h1 class="text-white">Our Menus</h1>
                        <strong class="text-white">Perfect for all Breakfast, Lunch and Dinner</strong>
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
                        <h2 class="mb-lg-5 mb-4">Breakfast Menu</h2>
                    </div>
                    <!-- Add your menu items here -->
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