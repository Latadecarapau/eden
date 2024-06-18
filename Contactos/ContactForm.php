<?php
session_start();
require '../vendor/PHPMailer/PHPMailer.php';
require '../vendor/PHPMailer/SMTP.php';
require '../vendor/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $message = $_POST['message'];

    $to = 'hoteleden2024kgm@gmail.com';
    $subject = 'Contact Form Submission';

    $emailMessage = "<html><body>";
    $emailMessage .= "<h2>Contact Form Submission</h2>";
    $emailMessage .= "<p><strong>Name:</strong> {$name}</p>";
    $emailMessage .= "<p><strong>Message:</strong><br>{$message}</p>";

    if (isset($_SESSION['username'])) {
        $userEmail = $_SESSION['user_email']; 
        $emailMessage .= "<p><strong>User Email:</strong> {$userEmail}</p>";
    }

    $emailMessage .= "</body></html>";

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@gmail.com';
        $mail->Password = 'your-email-password'; 
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587; 

        //Recipients
        $mail->setFrom('your-email@gmail.com', 'Hotel Eden');
        $mail->addAddress($to);
        if (isset($userEmail)) {
            $mail->addCC($userEmail); 
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $emailMessage;

        $mail->send();
        echo 'Message sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="ContactForm.css">
</head>

<body>
    <div class="contact-form">
        <h2>Contact-nos</h2>
        <form action="ContactForm.php" method="post">
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="message">Mensagem:</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <a href="../Home/Home.php">Voltar</a>
            <button type="submit">Enviar</button>
        </form>
    </div>
</body>

</html>