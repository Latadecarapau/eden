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

// Retrieve posted room details
$id_room = isset($_POST['id_room']) ? $_POST['id_room'] : '';
$room_number = isset($_POST['room_number']) ? $_POST['room_number'] : '';
$room_name = isset($_POST['room_name']) ? $_POST['room_name'] : '';
$capacity = isset($_POST['capacity']) ? $_POST['capacity'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';

// Define error messages (for demonstration purposes)
$errorMessages = [
    'type_of_room' => '',
    'room_number' => '',
    'check_in' => '',
    'check_out' => '',
    'num_guests' => '',
    'price' => '',
    'room_name' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $user_details['firstname'] . " " . $user_details['lastname'];
    $email = $user_details['email'];
    $telephone = $user_details['telephone'];
    $type_of_room = isset($_POST['type_of_room']) ? $_POST['type_of_room'] : null;
    $room_number = isset($_POST['room_number']) ? $_POST['room_number'] : null;
    $room_name = isset($_POST['price']) ? $_POST['room_name'] : null;
    $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : null;
    $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : null;
    $num_guests = isset($_POST['num_guests']) ? $_POST['num_guests'] : null;
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

    if ($num_guests > $capacity) {
        $errorMessages["num_guests"] = "Number of guests exceeds the room capacity of " . $capacity . ".";
        $valid = false;
    }

    if ($valid) {
        if ($name && $email && $telephone && $type_of_room && $room_number && $check_in && $check_out && $num_guests && $price) {
            $stmt = $conn->prepare("INSERT INTO reservations (name, email, telephone, type_of_room, room_number, check_in, check_out, num_guests, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $name, $email, $telephone, $type_of_room, $room_number, $check_in, $check_out, $num_guests, $price);

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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roomData = JSON.parse(sessionStorage.getItem('roomData'));
            if (roomData) {
                document.getElementById('type_of_room').value = roomData.id;
                document.getElementById('room_number').value = roomData.number;
                document.getElementById('room_name').value = roomData.name;
                document.getElementById('price').value = roomData.price;
                const numGuestsInput = document.getElementById('num_guests');
                numGuestsInput.max = roomData.capacity;
                // Ensure the value does not exceed the capacity
                if (numGuestsInput.value > roomData.capacity) {
                    numGuestsInput.value = roomData.capacity;
                }
            }
        });

        function storeReservationData() {
            const reservationData = {
                name: document.getElementById('name').value,
                telephone: document.getElementById('telephone').value,
                email: document.getElementById('email').value,
                type_of_room: document.getElementById('type_of_room').value,
                room_number: document.getElementById('room_number').value,
                check_in: document.getElementById('check_in').value,
                check_out: document.getElementById('check_out').value,
                num_guests: document.getElementById('num_guests').value,
                price: document.getElementById('price').value,
                room_name: document.getElementById('room_name').value
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
                <legend>Detalhes Da Reserva</legend>
                <label for="type_of_room">Tipo do Quarto:</label>
                <select id="type_of_room" name="type_of_room">
                    <option value="1">Suite</option>
                    <option value="2">Deluxe</option>
                    <option value="3">Family</option>
                </select>
                <span class="error"><?php echo htmlspecialchars($errorMessages['type_of_room']); ?></span>

                <label for="room_number">Número do Quarto:</label>
                <input type="text" id="room_number" name="room_number"
                    value="<?php echo htmlspecialchars($room_number); ?>" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['room_number']); ?></span>

                <label for="room_name">Nome Do Quarto:</label>
                <input type="text" id="room_name" name="room_name" value="<?php echo htmlspecialchars($room_name); ?>" readonly>
                <span class="error"><?php echo $errorMessages['room_name']; ?></span>

                <label for="check_in">Check-in:</label>
                <input type="date" id="check_in" name="check_in" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['check_in']); ?></span>

                <label for="check_out">Check-out:</label>
                <input type="date" id="check_out" name="check_out" required>
                <span class="error"><?php echo $errorMessages['check_out']; ?></span>

                <label for="num_guests">Numero de Pessoas:</label>
                <input type="number" id="num_guests" name="num_guests" min="1" required>
                <span class="error"><?php echo $errorMessages['num_guests']; ?></span>

                <label for="price">Preço:</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" readonly>
                <span class="error"><?php echo $errorMessages['price']; ?></span>
            </fieldset>

            <a href="../Quartos/Quartos.php" class="avoltar">Voltar</a>
            <button type="submit">Check out</button>
        </form>
    </div>
</body>

</html>