<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../db.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verify if the form is submitted and filled
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get data from the form via POST
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $telephone = $_POST["telephone"];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $confirmPassword = $_POST["confirmPassword"];
    $gender = $_POST["gender"];
    $phone_area = $_POST["phone_area"];

    // Insert into the database
    $sql = "INSERT INTO users (firstname, lastname, username, email, phone_area, telephone, password, gender) 
                VALUES ('$firstname', '$lastname', '$username', '$email','$phone_area','$telephone', '$password', '$gender')";

    if ($conn->query($sql) === TRUE) {
        // Send welcome email
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hoteleden2024kgm@gmail.com'; // Your email
            $mail->Password = 'hfvtkpxvzxlxgbsr'; // Your email password
            $mail->SMTPSecure = 'ssl';   // Enable SSL encryption
            $mail->Port = 465;     

            // Recipients
            $mail->setFrom('hoteleden2024kgm@gmail.com', 'Hotel Eden');
            $mail->addAddress($email); // Add recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Bem-Vindo Ao Hotel Eden!!! :)';
            $mail->Body    = "Caro $firstname $lastname,<br><br>Estamos Gratos por ter aderido ao Hotel Eden Aproveite as suas ferias connosco.<br><br>Melhores Comprimentos e estadia,<br>Hotel Eden Team";
            $mail->AltBody = "Caro $firstname $lastname,\n\nEstamos Gratos por ter aderido ao Hotel Eden Aproveite as suas ferias connosco.\n\nMelhores Comprimentos e estadia,\nHotel Eden Team";

            $mail->send();
        } catch (Exception $e) {
            // Handle the error if the email fails to send
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect to login page
        header("Location: ../login/Login.php");
        exit();
    } else {
        throw new Exception("Error: " . $conn->error);
    }
}

// Close connection
$conn->close();
?>

<html lang="en">

<head>
    <link rel="stylesheet" href="Registar.css" />
    <title>Register</title>
</head>

<script>
    function validateForm(event) {
        //get nos elemementoss do form
        var email = document.getElementById("email").value;
        var phone = document.getElementById("telephone").value;
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        var genderMale = document.getElementById("dot-1").checked;
        var genderFemale = document.getElementById("dot-2").checked;
        var genderOther = document.getElementById("dot-3").checked;

        // Email validação
        if (!email.includes("@")) {
            alert("Email must contain '@'");
            event.preventDefault();
            return false;
        }

        // Tlefenone validação
        if (/[a-zA-Z]/.test(phone)) {
            alert("Phone number must not contain letters");
            event.preventDefault();
            return false;
        }

        // Password confirmation validação
        if (password !== confirmPassword) {
            alert("Passwords do not match");
            event.preventDefault();
            return false;
        }

        // Gender validação
        if (!genderMale && !genderFemale && !genderOther) {
            alert("Please select a gender");
            event.preventDefault();
            return false;
        }

        return true;
    }
</script>

<body>
    <div class="container">
        <div class="title">Register</div>
        <form action="Registar.php" method="post" onsubmit="return validateForm(event)">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Primeiro Nome</span>
                    <input type="text" id="firstname" name="firstname" placeholder="Write your first name" required />
                </div>
                <div class="input-box">
                    <span class="details">Ultimo Nome</span>
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

                <div class="input-box">
                    <span class="details">Telefone</span>
                    <div class="phone-container">
                        <select id="phone_area" name="phone_area" required>
                            <option id="1" value="+1-USA">+1 (USA)</option>
                            <option id="2" value="+44-UK">+44 (UK)</option>
                            <option id="3" value="+91-INDIA">+91 (India)</option>
                            <option id="4" value="+61-AUS">+61 (Australia)</option>
                            <option id="5" value="+81-JAP">+81 (Japan)</option>
                            <option id="6" value="+351-PT">+351 (Portugal)</option>
                            <!-- Add more country codes as needed -->
                        </select>
                        <input type="text" id="telephone" name="telephone" placeholder="" required />
                    </div>
                </div>

                <div class="input-box">
                    <span class="details">Password</span>
                    <input type="text" id="password" name="password" placeholder="Write your password" required />
                </div>

                <div class="input-box">
                    <span class="details">Confirme a Password</span>
                    <input type="text" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password"
                        required />
                </div>
            </div>

            <div class="gender-details">
                <input type="radio" name="gender" id="dot-1" value="Masculino">
                <input type="radio" name="gender" id="dot-2" value="Feminino">
                <input type="radio" name="gender" id="dot-3" value="outros">
                <span class="gender-title">Genero</span>
                <div class="category">
                    <label for="dot-1">
                        <span class="dot one"></span>
                        <span class="gender">Mascúlino</span>
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