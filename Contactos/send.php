<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the user is logged in and email is available in the session
    if (!isset($_SESSION['user_email'])) {
        echo "User not logged in or email not available.";
        exit();
    }

    $userEmail = $_SESSION['user_email'];
    $name = isset($_POST["name"]) ? filter_var($_POST["name"], FILTER_SANITIZE_STRING) : '';
    $message = isset($_POST["message"]) ? filter_var($_POST["message"], FILTER_SANITIZE_STRING) : '';

    if (!empty($name) && !empty($message)) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hoteleden2024kgm@gmail.com'; // Your real SMTP username (must exist)
            $mail->Password = 'hfvtkpxvzxlxgbsr';        // Your real SMTP password
            $mail->SMTPSecure = 'ssl';                    // Enable SSL encryption
            $mail->Port = 465;                            // TCP port to connect to

            // Recipients
            $mail->setFrom('hoteleden2024@gmail.com', 'Contact Form'); // Use your real email as the sender
            $mail->addAddress('hoteleden2024kgm@gmail.com');           // Recipient's email address
            $mail->addReplyTo($userEmail, $name);                      // Reply-To address set to user's email

            // Content
            $mail->isHTML(true);                          // Set email format to HTML
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body = "<h2>Contact Form Submission</h2>
                              <p><strong>Name:</strong> {$name}</p>
                              <p><strong>Message:</strong> {$message}</p>";
            $mail->AltBody = "Name: {$name}\nMessage: {$message}";

            $mail->send();
            $_SESSION['success_message'] = "Seu form foi enviado com sucesso.";
            header('Location: ContactForm.php' . $_SERVER['PHP_SELF']);
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Preecha todos os campos";
    }
}