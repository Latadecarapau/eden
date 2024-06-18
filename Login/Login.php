<?php
// Login.php
session_start();

require '../db.php';
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '../Home/Home.php';
            header("Location: " . $redirect);
            exit();
        } else {
            $error_message = '
            <div class="toastify on bg-warning toastify-center toastify-top" aria-live="polite" duration="2" style="transform: translate(0px, 0px); top: 15px;">
                 <strong> Error! </strong> Password incorreta.</b>
             </div>';
        }
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $error_message = '
            <div class="toastify on bg-warning toastify-center toastify-top" aria-live="polite" duration="2" style="transform: translate(0px, 0px); top: 15px;">
                 <strong> Error! </strong> username incorreto.</b>
             </div>';
        } else {
            $error_message = '
            <div class="toastify on bg-warning toastify-center toastify-top" aria-live="polite" duration="2" style="transform: translate(0px, 0px); top: 15px;">
                 <strong> Error! </strong> Password e username incorretos.</b>
             </div>';
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="Login.css">
    <title>Login</title>
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Fonts css load -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link id="fontsLink" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css">
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

</head>

<body>
    <?php
    if (!empty($error_message)) {
        echo  $error_message;
    }
    ?>
    <section>
        <div class="form-box">
            <div class="form-value">

                <form
                    action="Login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>"
                    method="post">
                    <h2>Login</h2>

                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input id="username" name="username" type="text" required>
                        <label for="username">Username</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input id="password" name="password" type="password" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="forget">
                        <label for=""><input type="checkbox"> Lembre-se | <a href="#">Esqueceu-se da
                                Password?</a></label>
                    </div>
                    <button type="submit">Log in</button>
                    <div class="register">
                        <p>Não tem conta? <a href="../Registar/Registar.php">Registar</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!-- prismjs plugin -->
    <script src="assets/libs/prismjs/prism.js"></script>

    <!-- notifications init -->
    <script src="assets/js/pages/notifications.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</body>


</html>