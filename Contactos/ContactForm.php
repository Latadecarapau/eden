<?php
session_start();
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
        <h2>Contacta-nos</h2>
        <form action="send.php" method="post">
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

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success-message" id="successMessage"><?php echo $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                successMessage.style.display = 'block';
                setTimeout(function () {
                    successMessage.style.display = 'none';
                }, 3000); // Hide after 3 seconds
            }
        });
    </script>
</body>


</html>