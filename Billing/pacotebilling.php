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
    'check_in' => '',
    'check_out' => '',
    'num_guests' => '',
    'room_number' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $user_details['firstname'] . " " . $user_details['lastname'];
    $email = $user_details['email'];
    $telephone = $user_details['telephone'];
    $package_name = $_POST['package_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $num_guests = $_POST['num_guests'];
    $room_number = $_POST['room_number'];
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


    if (empty($check_in)) {
        $errorMessages['check_in'] = 'Check-in date is required.';
        $valid = false;
    }
    if (empty($check_out)) {
        $errorMessages['check_out'] = 'Check-out date is required.';
        $valid = false;
    }
    if (empty($num_guests) || $num_guests < 1) {
        $errorMessages['num_guests'] = 'Valid number of guests is required.';
        $valid = false;
    }
    if (empty($room_number)) {
        $errorMessages['room_number'] = 'Room number is required.';
        $valid = false;
    }

    if ($valid) {
        $stmt = $conn->prepare("INSERT INTO package_buys (name, email, telephone, package_name, description, price, check_in, check_out, num_guests, room_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $name, $email, $telephone, $package_name, $description, $price, $check_in, $check_out, $num_guests, $room_number);

        if ($stmt->execute()) {
            header("Location: ../Profile/Profile.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
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
        });

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

        document.getElementById('type_of_room').addEventListener('change', function () {
            var typeOfRoom = this.value;
            if (typeOfRoom) {
                fetchRoomNumbers(typeOfRoom);
            }
        });

        function fetchRoomNumbers(typeOfRoom) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_rooms_by_type.php?type_of_room=' + typeOfRoom, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var rooms = JSON.parse(xhr.responseText);
                    var roomNumberSelect = document.getElementById('room_number');
                    roomNumberSelect.innerHTML = ''; // Clear previous options
                    rooms.forEach(function (room) {
                        var option = document.createElement('option');
                        option.value = room;
                        option.text = room;
                        roomNumberSelect.appendChild(option);
                    });
                }
            };
            xhr.send();
        }

        document.getElementById('room_number').addEventListener('change', function () {
            var roomNumber = this.value;
            if (roomNumber) {
                fetchRoomDetails(roomNumber);
            }
        });

        function fetchRoomDetails(roomNumber) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_room_details.php?room_number=' + roomNumber, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var roomDetails = JSON.parse(xhr.responseText);
                    if (!roomDetails.error) {
                        document.getElementById('price').value = roomDetails.price;
                        // You can add additional fields here as needed, for example:
                        // document.getElementById('capacity').value = roomDetails.capacity;
                    } else {
                        alert(roomDetails.error);
                    }
                }
            };
            xhr.send();
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
                <select id="type_of_room" name="type_of_room">
                    <option value="1">Suite</option>
                    <option value="2">Deluxe</option>
                    <option value="3">Family</option>
                </select>

                <label for="room_number">Número do Quarto:</label>
                <select id="room_number" name="room_number" required></select>

                <label for="room_name">Nome Do Quarto:</label>
                <input type="text" id="room_name" name="room_name" readonly>

                <label for="check_in">Check-in:</label>
                <input type="date" id="check_in" name="check_in" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['check_in']); ?></span>

                <label for="check_out">Check-out:</label>
                <input type="date" id="check_out" name="check_out" required>
                <span class="error"><?php echo $errorMessages['check_out']; ?></span>

                <label for="num_guests">Número de Pessoas:</label>
                <input type="number" id="num_guests" name="num_guests" min="1" required>
                <span class="error"><?php echo $errorMessages['num_guests']; ?></span>
            </fieldset>

            <a href="../Services/Services.php" class="avoltar">Voltar</a>
            <button type="submit">Check out</button>
        </form>
    </div>

</body>

</html>