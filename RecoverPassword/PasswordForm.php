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
            <h2>Esqueceste da palavra-pass?</h2>
            <form id="email-form" action="sendcode.php" method="POST">
                <div class="inputbox">
                    <input type="email" name="email" required="required">
                    <label>Email</label>
                    <ion-icon name="mail-outline"></ion-icon>
                </div>
                <button type="submit">Resetar palavra-pass</button>
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