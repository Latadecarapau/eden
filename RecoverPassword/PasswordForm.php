<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../db.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Generate a recovery code (for example, a random 6-digit number)
    $recoveryCode = rand(100000, 999999);

    // Store the recovery code in your database associated with the user's email
    saveRecoveryCode($email, $recoveryCode);

    // Send the recovery code via email
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = 'hoteleden2024kgm@gmail.com';               // SMTP username
        $mail->Password = 'hfvtkpxvzxlxgbsr';                  // SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        // Recipients
        $mail->setFrom('hoteleden2024kgm@gmail.com', 'Hotel Eden');
        $mail->addAddress($email);                                  // Add a recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Password Recovery Code';
        $mail->Body = "Your password recovery code is: <b>$recoveryCode</b>";
        $mail->AltBody = "Your password recovery code is: $recoveryCode";

        $mail->send();
        header("Location: verifycode.php?email=" . urlencode($email));
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function saveRecoveryCode($email, $code)
{
    require '../db.php';
    // Get the user id
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        // Insert the recovery code
        $stmt = $conn->prepare("INSERT INTO recovery_codes (user_id, recovery_code) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $code);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Email não Encontrado.";
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Password.css"> <!-- Ensure the CSS file is linked correctly -->
    <title>Forgot Password</title>
</head>

<body>
    <section>
        <div class="form-box">
            <h2>Recuperação da Pass</h2>
            <form id="email-form" action="PasswordForm.php" method="POST">
                <div class="inputbox">
                    <input type="email" name="email" required="required">
                    <label>Email</label>
                    <ion-icon name="mail-outline"></ion-icon>
                </div>
                <button type="submit">Enviar Código de verificação</button>
            </form>
            <div class="back-to-login">
                <p><a href="../Login/Login.php">Voltar Para o Login</a></p>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>