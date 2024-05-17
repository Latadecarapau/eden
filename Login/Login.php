<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "rir";
$port = 3306;


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];


    $sql = "SELECT * FROM login WHERE Username = '$username' AND Password = '$password'";
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
        
        session_start();
        // Assuming you've already validated the user's credentials
        $_SESSION['username'] = $username;
        header("Location: ../Home/Home.php");
        exit();

    } else {

        echo "Invalid username or password!";
    }
}


$conn->close();

?>



<html lang="en">

<head>
    <link rel="stylesheet" href="Login.css">
    <title>Login</title>
</head>

<body>
    <section>
        <div class="form-box">
            <div class="form-value">
                <form action="login.php" method="post">
                    <h2>Login</h2>
                    <div class="inputbox">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input id="username" name="username" type="text" required>
                        <label for="">Username</label>
                    </div>
                    <div class="inputbox">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input id="password" name="password" type="password" required>
                        <label for="">Password</label>
                    </div>
                    <div class="forget">
                        <label for=""><input type="checkbox">Remember Me <a href="#">Forget Password</a></label>

                    </div>
                    <button>Log in</button>
                    <div class="register">
                        <p>Don't have a account? <a href="../Registar/Registar.php">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>