<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$host = "localhost";
$username = "root";
$password = "";
$database = "rir";
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
    $primeiroNome = $_POST["primeironome"];
    $apelido = $_POST["apelido"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $genero = $_POST["gender"];


    // Insert na database
    $sql = "INSERT INTO login (primeironome, apelido, Username, email, telefone, Password, genero) 
            VALUES ('$primeiroNome', '$apelido', '$username', '$email', '$telefone', '$password', '$genero')";

    if ($conn->query($sql) === TRUE) {

        header("Location: ../login/Login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Retrieve form data
    $primeiroNome = $_POST["primeironome"];
    $apelido = $_POST["apelido"];
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
        $mail->addAddress($email, $primeiroNome); // Add a recipient

        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Welcome to Our Website';
        $mail->Body = "Hello $primeiroNome $apelido,<br><br>Thank you for registering on our website. We're glad to have you as a member!<br><br>Best regards,<br>Hotel Eden Team";

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
    <title>Registar</title>
</head>

<body>
    <div class="container">
        <div class="title">Registo</div>
        <form form action="Registar.php" method="post">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Primeiro Nome</span>
                    <input type="text" id="primeironome" name="primeironome" placeholder="Escreva seu Primeiro Nome"
                        required />
                </div>
                <div class="input-box">
                    <span class="details">Apelido</span>
                    <input type="text" id="apelido" name="apelido" placeholder="Escreva seu Apelido" required />
                </div>


                <div class="input-box">
                    <span class="details">Username</span>
                    <input type="text" id="username" name="username" placeholder="Escreva seu Username" required />
                </div>


                <div class="input-box">
                    <span class="details">Email</span>
                    <input type="text" id="email" name="email" placeholder="Escreva seu Email" required />
                </div>


                <div class="input-box">
                    <span class="details">Número de telefone</span>
                    <input type="text" id="telefone" name="telefone" placeholder="Escreva seu Número de telefone"
                        required />
                </div>


                <div class="input-box">
                    <span class="details">Palavra-passe</span>
                    <input type="text" id="password" name="password" placeholder="Escreva sua Palavra-passe" required />
                </div>


                <div class="input-box">
                    <span class="details">Confirme Palavra-passe</span>
                    <input type="text" id="confirmPassword" name="confirmPassword"
                        placeholder="Confirme a sua Palavra-passe" required />
                </div>
            </div>



            <div class="gender-details">
                <input type="radio" name="gender" id="dot-1">
                <input type="radio" name="gender" id="dot-2">
                <input type="radio" name="gender" id="dot-3">
                <span class="gender-title">Género</span>
                <div class="category">
                    <label for="dot-1">
                        <span class="dot one"></span>
                        <span class="gender">Masculino</span>
                    </label>
                    <label for="dot-2">
                        <span class="dot two"></span>
                        <span class="gender">Feminino</span>
                    </label>
                    <label for="dot-3">
                        <span class="dot three"></span>
                        <span class="gender">Outros</span>
                    </label>
                </div>
            </div>
            <div class="button">
                <a href="../Login/Login.php" class="a">Voltar</a><input type="submit" value="Register" />
            </div>
        </form>
    </div>
</body>

</html>