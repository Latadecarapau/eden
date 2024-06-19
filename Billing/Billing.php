<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

require '../db.php';

$user_email = $_SESSION['user_email'];
$result = $conn->query("SELECT firstname, lastname, telephone, email FROM users WHERE email='$user_email'");

if ($result->num_rows == 0) {
    echo "No user found with email " . $user_email;
    exit();
}

$user_details = $result->fetch_assoc();



var_dump($_GET);
$roomType = $_GET['roomType'];
$roomNumber = $_GET['roomNumber'];
$roomCapacity = $_GET['roomCapacity'];
$roomPrice = $_GET['roomPrice'];

if (!$roomType || !$roomNumber || !$roomCapacity ||  !$room_Price) {
    echo "Missing required room details.";
    exit();
}

$errorMessages = array(
    "name" => "",
    "telephone" => "",
    "type_of_room" => "",
    "room_number" => "",
    "check_in" => "",
    "check_out" => "",
    "num_guests" => "",
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $user_details['firstname'] . " " . $user_details['lastname'];
    $email = $user_details['email'];
    $telephone = $user_details['telephone'];
    $type_of_room = $_POST['type_of_room'] ?? null;
    $room_number = $_POST['room_number'] ?? null;
    $check_in = $_POST['check_in'] ?? null;
    $check_out = $_POST['check_out'] ?? null;
    $num_guests = $_POST['num_guests'] ?? null;

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

    if ($num_guests > $room_details['capacity']) {
        $errorMessages["num_guests"] = "Number of guests exceeds the room capacity of " . $room_details['capacity'] . ".";
        $valid = false;
    }

    if ($valid) {
        if ($name && $email && $telephone && $type_of_room && $room_number && $check_in && $check_out && $num_guests) {
            $stmt = $conn->prepare("INSERT INTO reservations (name, email, telephone, type_of_room, room_number, check_in, check_out, num_guests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssi", $name, $email, $telephone, $type_of_room, $room_number, $check_in, $check_out, $num_guests);

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
</head>

<body>
    <div class="reservation-form">
        <h2>Reserva de um Quarto</h2>
        <form action="Billing.php" method="post">
            <fieldset>
                <legend>Informação Pessoal</legend>
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name"
                    value="<?php echo htmlspecialchars($user_details['firstname'] . ' ' . $user_details['lastname']); ?>"
                    readonly>
                <span class="error"><?php echo htmlspecialchars($errorMessages['name']); ?></span>

                <label for="telephone">Número de Telefone:</label>
                <input type="tel" id="telephone" name="telephone"
                    value="<?php echo htmlspecialchars($user_details['telephone']); ?>" readonly>
                <span class="error"><?php echo htmlspecialchars($errorMessages['telephone']); ?></span>

                <label for="email">Email:</label>
                <input type="text" id="email" name="email"
                    value="<?php echo htmlspecialchars($user_details['email']); ?>" readonly>
            </fieldset>
            <fieldset>
                <legend>Detalhes Da Reserva</legend>
                <label for="type_of_room">Tipo do Quarto:</label>
                <select id="type_of_room" name="type_of_room">
                    <option value="Suite" <?php echo $roomType === 'Suite' ? 'selected' : ''; ?>>Suite</option>
                    <option value="Deluxe" <?php echo $roomType === 'Deluxe' ? 'selected' : ''; ?>>Deluxe</option>
                    <option value="Family" <?php echo $roomType === 'Family' ? 'selected' : ''; ?>>Family</option>
                </select>
                <span class="error"><?php echo htmlspecialchars($errorMessages['type_of_room']); ?></span>

                <label for="room_number">Número do Quarto:</label>
                <input type="text" id="room_number" name="room_number"
                    value="<?php echo htmlspecialchars($roomNumber); ?>" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['room_number']); ?></span>

                <label for="check_in">Check-in:</label>
                <input type="date" id="check_in" name="check_in" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['check_in']); ?></span>

                <label for="check_out">Check-out:</label>
                <input type="date" id="check_out" name="check_out" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['check_out']); ?></span>

                <label for="num_guests">Numero de Pessoas:</label>
                <input type="number" id="num_guests" name="num_guests" min="1"
                    max="<?php echo htmlspecialchars($roomCapacity); ?>" required>
                <span class="error"><?php echo htmlspecialchars($errorMessages['num_guests']); ?></span>
            </fieldset>
            <a href="../Quartos/Quartos.php" class="avoltar">Voltar</a>
            <button type="submit">Check out</button>
        </form>
    </div>
</body>

</html>