<?php
session_start();
require '../db.php';
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}



$user_email = $_SESSION['user_email'];
$result = $conn->query("SELECT firstname, lastname, telephone, email FROM users WHERE email='$user_email'");

if ($result->num_rows == 0) {
    echo "No user found with email " . $user_email;
    exit();
}

$user_details = $result->fetch_assoc();

$errorMessages = array(
    "name" => "",
    "telephone" => "",
    "type_of_room" => "",
    "room_number" => "",
    "check_in" => "",
    "check_out" => "",
    "num_guests" => ""
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $user_details['firstname'] . " " . $user_details['lastname'];
    $email = $user_details['email'];
    $telephone = $user_details['telephone'];
    $type_of_room = isset($_POST['type_of_room']) ? $_POST['type_of_room'] : null;
    $room_number = isset($_POST['room_number']) ? $_POST['room_number'] : null;
    $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : null;
    $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : null;
    $num_guests = isset($_POST['num_guests']) ? $_POST['num_guests'] : null;


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
        if ($name && $email && $telephone && $type_of_room && $room_number && $check_in && $check_out && $num_guests) {
            $stmt = $conn->prepare("INSERT INTO reservations (name, email, telephone, type_of_room, room_number, check_in, check_out, num_guests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $name, $email, $telephone, $type_of_room, $room_number, $check_in, $check_out, $num_guests);

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
                num_guests: document.getElementById('num_guests').value
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
                    value="<?php echo $user_details['firstname'] . ' ' . $user_details['lastname']; ?>" readonly>
                <span class="error"><?php echo $errorMessages['name']; ?></span>

                <label for="telephone">Número de Telefone:</label>
                <input type="tel" id="telephone" name="telephone" value="<?php echo $user_details['telephone']; ?>"
                    readonly>
                <span class="error"><?php echo $errorMessages['telephone']; ?></span>

                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $user_details['email']; ?>" readonly>

            </fieldset>
            <fieldset>
                <legend>Detalhes Da Reserva</legend>
                <label for="type_of_room">Tipo do Quarto:</label>
                <select id="type_of_room" name="type_of_room" id="id_room">
                    <option value="1">Suite</option>
                    <option value="2">Deluxe</option>
                    <option value="3">Family</option>
                </select>
                <span class="error"><?php echo $errorMessages['type_of_room']; ?></span>

                <label for="room_number">Número do Quarto:</label>
                <input type="text" id="room_number" name="room_number" required>
                <span class="error"><?php echo $errorMessages['room_number']; ?></span>

                <input type="date" id="check_in" name="check_in" required>
                <span class="error"><?php echo $errorMessages['check_in']; ?></span>

                <label for="check_out">Check-out:</label>
                <input type="date" id="check_out" name="check_out" required>
                <span class="error"><?php echo $errorMessages['check_out']; ?></span>
                <label for="num_guests">Numero de Pessoas:</label>
                <input type="number" id="num_guests" name="num_guests" min="1" required>
                <span class="error"><?php echo $errorMessages['num_guests']; ?></span>
            </fieldset>

            <!--  <fieldset>
                <legend>Faturação</legend>
                <label for="card_name">Nome do cartão:</label>
                <input type="text" id="card_name" name="card_name" required>
                <span class="error"><//?php echo $errorMessages['card_name']; ?></span>

                <label for="card_number">Número do cartão:</label>
                <input type="text" id="card_number" name="card_number" required>
                <span class="error"><//?php echo $errorMessages['card_number']; ?></span>

                <label for="card_expiry">Data de Expiração:</label>
                <input type="month" id="card_expiry" name="card_expiry" required>
                <span class="error"><//?php echo $errorMessages['card_expiry']; ?></span>

                <label for="card_cvc">CVC:</label>
                <input type="text" id="card_cvc" name="card_cvc" required>
                <span class="error"><//?php echo $errorMessages['card_cvc']; ?></span>
            </fieldset> -->
            <a href="../Quartos/Quartos.php" class="avoltar">Voltar</a>
            <button type="submit">Check out</button>
        </form>
    </div>
</body>

</html>