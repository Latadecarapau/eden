<?php



require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Retrieve form data
$primeiroNome = $_POST["primeironome"];
$apelido = $_POST["apelido"];
$email = $_POST["email"]; 

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output (optional)
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com'; // Specify SMTP server
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'hoteleden2024kgm@gmail.com'; // SMTP username
    $mail->Password = 'hoteleden1730'; // SMTP password
    $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587; // TCP port to connect to

    // Sender information
    $mail->setFrom('hoteleden@gmail.com', 'Hotel Eden');

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
