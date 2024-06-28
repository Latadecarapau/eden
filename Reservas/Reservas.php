<?php
session_start();
$loggedIn = isset($_SESSION['username']);

require '../db.php';

$email = '';
if ($loggedIn) {
    $username = $_SESSION['username'];
    $sql = "SELECT email FROM users WHERE userName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    $stmt->close();
}

// Fetch reservations for the logged-in user
$reservations = [];
if ($email) {
    $sql = "SELECT id_reservation, name, email, telephone, type_of_room, room_number, check_in, check_out, num_guests, created_at FROM reservations WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
    $stmt->close();
}

$reservationrestaurant = [];
if ($email) {
    $sql = "SELECT id_restaurant, nome_completo, email, numero_telefone, numero_pessoas, data, hora, preferencia_mesa, pedido_especial FROM reservation_restaurant WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $reservationrestaurant[] = $row;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_restaurant'])) {
        $id_restaurant = $_POST['id_restaurant'];
        $sql = "DELETE FROM reservation_restaurant WHERE id_restaurant=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param("i", $id_restaurant);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['status' => 'success', 'message' => 'Your restaurant reservation was successfully canceled.']);
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_reservation'])) {
        $id_reservation = $_POST['id_reservation'];
        $sql = "DELETE FROM reservations WHERE id_reservation=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }
        $stmt->bind_param("i", $id_reservation);
        $stmt->execute();
        $stmt->close();

        echo json_encode(['status' => 'success', 'message' => 'Your reservation was successfully canceled.']);
        exit;
    }

    if ($created_at) {
        $createdAt = new DateTime($created_at);
        $currentDate = new DateTime();
        $dateDiff = $currentDate->diff($createdAt)->days;

        if ($dateDiff <= 3) {
            // Proceed with cancellation
            $sql = "DELETE FROM reservations WHERE id_reservation = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo "Error preparing statement: " . $conn->error;
                exit;
            }

            $stmt->bind_param("i", $id_reservation);
            if ($stmt->execute()) {
                echo "Reserva cancelada com sucesso.";
            } else {
                echo "Erro ao cancelar a reserva.";
            }
            $stmt->close();
        } else {
            echo "Reserva não pode ser cancelada depois de 3 dias.";
        }
    } else {
        echo "Reserva não foi encontrada.";
    }

}


$conn->close();
?>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="Reservas.css" />
    <title>Rooms</title>

</head>

<body>
    <div class="notification" id="notification"></div>
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
                <li><a href="../Services/Services.php">Pacotes</a></li>
                <li><a href="../Quartos/Quartos.php">Quartos</a></li>
                <li><a href="../Home/Home.php">Explore</a></li>
                <li><a href="../Contactos/ContactForm.php">Contactos</a></li>

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
    <section class="reservations-section">
        <div class="reservations-container">
            <h2>Minhas Reservas</h2>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Reserva ID</th>
                        <th>Data de Criação</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Tipo de Quarto</th>
                        <th>Número do Quarto</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Número de hospedes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <?php
                            // Calculate the date difference
                            $createdAt = new DateTime($reservation['created_at']);
                            $currentDate = new DateTime();
                            $dateDiff = $currentDate->diff($createdAt)->days;
                            $canCancel = $dateDiff <= 3;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['id_reservation']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['name']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['telephone']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['type_of_room']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['room_number']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['check_in']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['check_out']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['num_guests']); ?></td>
                                <td>
                                    <?php if ($canCancel): ?>
                                        <button class="cancel-btn"
                                            onclick="showModal(<?php echo $reservation['id_reservation']; ?>)">Cancel</button>
                                    <?php else: ?>
                                        <button class="cancel-btn" disabled>Cancelamento impossivel</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">Não encontramos reservas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <h2>Reservas de Restaurante</h2>
            <table class="reservations-table">
                <thead>
                    <tr>
                        <th>Reserva de mesa id</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Número de Pessoas</th>
                        <th>Data da Reserva</th>
                        <th>Hora da Reserva</th>
                        <th>Preferência de Mesa</th>
                        <th>Pedido Especial</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reservationrestaurant)): ?>
                        <?php foreach ($reservationrestaurant as $reservation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['id_restaurant']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['nome_completo']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['email']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['numero_telefone']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['numero_pessoas']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['data']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['hora']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['preferencia_mesa']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['pedido_especial']); ?></td>
                                <td>
                                    <button class="cancel-btn"
                                        onclick="showRestaurantModal(<?php echo $reservation['id_restaurant']; ?>)">Cancel</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10">Não encontramos reservas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Cancelar Reserva</h2>
            <p>Digite seu id de reserva:</p>
            <input type="text" id="reservationIdInput" />
            <button class="confirm-btn" onclick="confirmCancellation()">Confirm</button>
        </div>
    </div>
    <div id="restaurant-modal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="hideRestaurantModal()">&times;</span>
            <h2>Cancelar Reserva de Restaurante</h2>
            <form id="cancel-restaurant-form" method="POST" action="Reservas.php">
                <label for="id_restaurant">Digite seu id de reserva:</label>
                <input type="text" id="id_restaurant" name="id_restaurant" required>
                <button class="confirm-btn" type="submit">Confirm</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cancelRestaurantForm = document.getElementById('cancel-restaurant-form');

            cancelRestaurantForm.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(cancelRestaurantForm);

                fetch('Reservas.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        showNotification(data.message);
                        if (data.status === 'success') {
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        }
                        hideRestaurantModal();  // Close the modal
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });

        function showNotification(message) {
            const notification = document.getElementById('notification');
            notification.innerText = message;
            notification.style.display = 'block';
            notification.style.position = 'fixed';  // Position fixed
            notification.style.top = '20px';       // Top position
            notification.style.left = '50%';       // Center horizontally
            notification.style.transform = 'translateX(-50%)';  // Adjust center alignment
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        function showRestaurantModal(id) {
            document.getElementById('id_restaurant').value = id;
            document.getElementById('restaurant-modal').style.display = 'block';
        }

        function hideRestaurantModal() {
            document.getElementById('restaurant-modal').style.display = 'none';
        }

        window.onclick = function (event) {
            if (event.target == document.getElementById('restaurant-modal')) {
                hideRestaurantModal();
            }
        }
    </script>

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
                    <li><a href="#">Customer Support</a></li>
                    <li><a href="#">Inquiries & Reservations</a></li>
                    <li><a href="#">Feedback & Complaints</a></li>
                </ul>
            </div>
        </div>
        <div class="footer__credit">
            <p>&copy; 2023. Eden. All rights reserved.</p>
        </div>
    </footer>

</body>
<script src="Reservas.js"></script>


</html>