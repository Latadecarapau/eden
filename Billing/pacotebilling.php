<?php
session_start();
require '../db.php';

if (!isset($_SESSION['user_email'])) {
    header("Location: ../Login/Login.php");
    exit();
}

$user_email = $_SESSION['user_email'];
$result = $conn->query("SELECT firstname, lastname, telephone, email FROM users WHERE email='$user_email'");

if ($result->num_rows == 0) {
    echo "No user found with email " . $user_email;
    exit();
}

$user_details = $result->fetch_assoc();

$id_package = isset($_POST['id_package']) ? $_POST['id_package'] : '';
$package_name = isset($_POST['package_name']) ? $_POST['package_name'] : '';
$description = isset($_POST['description']) ? $_POST['description'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';

$errorMessages = [
    'package_name' => '',
    'description' => '',
    'price' => '',
    'check_out' => '',
    'check_in' => '',
    'num_guests' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $user_details['firstname'] . " " . $user_details['lastname'];
    $email = $user_details['email'];
    $telephone = $user_details['telephone'];
    $package_name = isset($_POST['package_name']) ? $_POST['package_name'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $valid = true;

    $current_date = new DateTime();
    $check_in_date = new DateTime($check_in);
    $check_out_date = new DateTime($check_out);

    if ($check_in_date < $current_date) {
        $errorMessages["check_in"] = "Check-in date cannot be earlier than today's date.";
        $valid = false;
    }

    if ($check_out_date <= $check_in_date) {
        $errorMessages["check_out"] = "Check-out date must be later than the check-in date.";
        $valid = false;
    }



    if ($valid) {
        if ($name && $email && $telephone && $package_name && $description && $price) {
            $stmt = $conn->prepare("INSERT INTO package_buys (name, email, telephone, package_name, description, price) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $name, $email, $telephone, $package_name, $description, $price);

            if ($stmt->execute()) {
                header("Location: ../Profile/Profile.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $errorMessages["form"] = "All fields are required.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOTEL RESERVA</title>
    <link rel="stylesheet" href="billing.css">
    <style>
        .hidden {
            display: none;
        }
    </style>


    <script>
        function toggleReservationDetails() {
            var checkbox = document.getElementById('associate_room');
            var reservationDetails = document.getElementById('reservation_details');
            var body = document.body;
            if (checkbox.checked) {
                reservationDetails.classList.remove('hidden');
            } else {
                reservationDetails.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const packageData = JSON.parse(sessionStorage.getItem('packageData'));
            if (packageData) {
                document.getElementById('package_name').value = packageData.name;
                document.getElementById('description').value = packageData.description;
                document.getElementById('price').value = packageData.price;
            }

            document.getElementById('type_of_room').addEventListener('change', function () {
                fetchRoomsByType(this.value);
            });

            document.getElementById('room_number').addEventListener('change', function () {
                fetchRoomDetails(this.value);
            });
        });

        function fetchRoomsByType(roomType) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `get_rooms_by_type.php?type_of_room=${roomType}`, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const rooms = JSON.parse(this.responseText);
                    const roomNumberSelect = document.getElementById('room_number');
                    roomNumberSelect.innerHTML = '<option value="">Select a room</option>';
                    rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.room_number;
                        option.textContent = room.room_number;
                        roomNumberSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }

        function fetchRoomDetails(roomNumber) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `get_room_details.php?room_number=${roomNumber}`, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    const room = JSON.parse(this.responseText);
                    document.getElementById('room_name').value = room.room_name;
                    document.getElementById('price').value = room.price;
                }
            };
            xhr.send();
        }

        function storeReservationData() {
            const reservationData = {
                name: document.getElementById('name').value,
                telephone: document.getElementById('telephone').value,
                email: document.getElementById('email').value,
                package_name: document.getElementById('package_name').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value
            };
            sessionStorage.setItem('reservationData', JSON.stringify(reservationData));
        }
    </script>

</head>

<body>
    <div class="reservation-form">
        <h2>Reserva de um Quarto</h2>
        <form action="payment.php" method="post" onsubmit="storeReservationData()">
            <fieldset>
                <legend>Informação Pessoal</legend>
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name"
                    value="<?php echo htmlspecialchars($user_details['firstname'] . ' ' . $user_details['lastname']); ?>"
                    readonly>

                <label for="telephone">Número de Telefone:</label>
                <input type="tel" id="telephone" name="telephone"
                    value="<?php echo htmlspecialchars($user_details['telephone']); ?>" readonly>

                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $user_details['email']; ?>" readonly>
            </fieldset>
            <fieldset>
                <legend>Pacotes</legend>
                <label for="package_name">Nome do pacote:</label>
                <input type="text" id="package_name" name="package_name"
                    value="<?php echo htmlspecialchars($package_name); ?>" readonly>
                <span class="error"><?php echo htmlspecialchars($errorMessages['package_name']); ?></span>

                <label for="description">Descrição:</label>
                <input type="text" id="description" name="description"
                    value="<?php echo htmlspecialchars($description); ?>" readonly>
                <span class="error"><?php echo htmlspecialchars($errorMessages['description']); ?></span>

                <label for="price">Preço:</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" readonly>
                <span class="error"><?php echo htmlspecialchars($errorMessages['price']); ?></span>

                <label for="associate_room"> Quer
                    associar complementar com um quarto? <input type="checkbox" id="associate_room"
                        name="associate_room" onclick="toggleReservationDetails()"></label>

            </fieldset>
            <fieldset id="reservation_details" class="hidden">
                <legend>Detalhes Da Reserva</legend>
                <label for="type_of_room">Tipo do Quarto:</label>
                <select id="type_of_room" name="type_of_room" required>
                    <option value="1">Suite</option>
                    <option value="2">Deluxe</option>
                    <option value="3">Family</option>
                </select>

                <label for="room_number">Número do Quarto:</label>
                <select id="room_number" name="room_number" required>
                    <option value="">Select a room</option>
                </select>

                <label for="room_name">Nome Do Quarto:</label>
                <input type="text" id="room_name" name="room_name" readonly>

                <label for="check_in">Check-in:</label>
                <input type="date" id="check_in" name="check_in" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['check_in']); ?></span>

                <label for="check_out">Check-out:</label>
                <input type="date" id="check_out" name="check_out" required>
                <span class="error"><?php echo $errorMessages['check_out']; ?></span>

                <label for="num_guests">Número de Pessoas:</label>
                <input type="number" id="num_guests" name="num_guests" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['num_guests']); ?></span>

                <label for="price">Preço:</label>
                <input type="text" id="price" name="price" readonly>
                <span class="error"><?php echo htmlspecialchars($errorMessages['price']); ?></span>
            </fieldset>
            <a href="../Services/Services.php" class="avoltar">Voltar</a>
            <button type="submit">Check out</button>
        </form>
    </div>

</body>

</html>