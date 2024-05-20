<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$host = "localhost";
$username = "root";
$password = "";
$database = "eden";
$port = 3306;

// Connectar na database
$conn = new mysqli($host, $username, $password, $database);

// verificar a conecxão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




// verificar se o form está submitido e preenchido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);;
    $confirmPassword = $_POST["confirmPassword"];
    $gender = $_POST["gender"];


    // Insert na database
    $sql = "INSERT INTO users (firstname, lastname, username, email, telephone, password, gender) 
            VALUES ('$firstname', '$lastname', '$username', '$email', '$telephone', '$password', '$gender')";

    if ($conn->query($sql) === TRUE) {

        header("Location: ../login/Login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Retrieve form data
    $primeiroNome = $_POST["firstname"];
    $apelido = $_POST["lastname"];
    $email = $_POST["email"];

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output (optional)
        $mail->SMTPDebug = 2; // Enable verbose debug output1
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'localhost'; // Specify SMTP server
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'hoteleden2024kgm@gmail.com'; // SMTP username
        $mail->Password = 'hoteleden1730'; // SMTP password
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587; // TCP port to connect to

        // Sender information
        $mail->setFrom('hoteleden2024kgm@gmail.com', 'Hotel Eden');

        // Recipient information
        $mail->addAddress($email, $firstname); // Add a recipient

        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Welcome to Our Website';
        $mail->Body = "Hello $firstname $lastname,<br><br>Thank you for registering on our website. We're glad to have you as a member!<br><br>Best regards,<br>Hotel Eden Team";

        // Send email
        $mail->send();

        echo 'Welcome email sent successfully!';
    } catch (Exception $e) {
        echo "Failed to send welcome email. Error: {$mail->ErrorInfo}";
    }
}



// fechar conexão
$conn->close();

?>


<html lang="en">

<head>
    <link rel="stylesheet" href="Registar.css" />
    <title>Register</title>
</head>

<body>
    <div class="container">
        <div class="title">Register</div>
        <form form action="Registar.php" method="post">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">First name</span>
                    <input type="text" id="firstname" name="firstname" placeholder="Write your first name" required />
                </div>
                <div class="input-box">
                    <span class="details">Last name</span>
                    <input type="text" id="lastname" name="lastname" placeholder="Write your last name" required />
                </div>


                <div class="input-box">
                    <span class="details">Username</span>
                    <input type="text" id="username" name="username" placeholder="Write your Username" required />
                </div>


                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" id="email" name="email" placeholder="Write your Email" required />
                </div>

               

                <div class="input-boxnum">
                    <span class="details">Phone Number</span>
                    <div class="phone-container">
                        <select id="countryCode" name="countryCode" required>
                            <option value="+1">+1 (USA)</option>
                            <option value="+44">+44 (UK)</option>
                            <option value="+91">+91 (India)</option>
                            <option value="+61">+61 (Australia)</option>
                            <option value="+81">+81 (Japan)</option>
                            <option value="+351">+351 (Portugal)</option>
                            <!-- Add more country codes as needed -->
                        </select>
                        <input type="text" id="telephone" name="telephone" placeholder="Write your phone number" required />
                    </div>
                </div>

                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="text" id="password" name="password" placeholder="Write your password" required />
                </div>


                <div class="input-box">
                    <span class="details">Confirm Password</span>
                    <input type="text" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password"
                        required />
                </div>
            </div>



            <div class="gender-details">
                <input type="radio" name="gender" id="dot-1">
                <input type="radio" name="gender" id="dot-2">
                <input type="radio" name="gender" id="dot-3">
                <span class="gender-title">Gender</span>
                <div class="category">
                    <label for="dot-1">
                        <span class="dot one"></span>
                        <span class="gender">Male</span>
                    </label>
                    <label for="dot-2">
                        <span class="dot two"></span>
                        <span class="gender">Female</span>
                    </label>
                    <label for="dot-3">
                        <span class="dot three"></span>
                        <span class="gender">Other</span>
                    </label>
                </div>
            </div>
            <div class="button">
                <a href="../Login/Login.php" class="a">back</a><input type="submit" value="Register" />
            </div>
        </form>
    </div>
</body>

</html>