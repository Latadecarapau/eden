<?php
session_start();
$loggedIn = isset($_SESSION['username']);

require '../db.php';

if ($loggedIn) {
    $username = $_SESSION['username'];
    $sql = "SELECT userName, firstname, lastname, email, telephone FROM users WHERE userName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($username, $firstname, $lastname, $email, $telephone);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newFirstName = $_POST['firstname'];
    $newLastName = $_POST['lastname'];
    $newEmail = $_POST['email'];
    $newPhoneNumber = $_POST['telephone'];

    // Server-side validation
    if (empty($newFirstName) || empty($newLastName) || empty($newEmail) || empty($newPhoneNumber)) {
        echo "All fields are required.";
        exit();
    }

    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    if (!preg_match('/^[0-9]+$/', $newPhoneNumber)) {
        echo "Phone number can only contain digits.";
        exit();
    }

    // Update profile details
    $sql = "UPDATE users SET firstname=?, lastname=?, email=?, telephone=? WHERE userName=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $newFirstName, $newLastName, $newEmail, $newPhoneNumber, $username);
    $stmt->execute();
    $stmt->close();

    // Refresh the page to show updated details
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
$conn->close();
?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="Profile.css"/>
    <title>Rooms</title>
</head>

<body>
    <header class="header">
        <nav>
            <div class="nav__bar">
                <div class="logo">
                    <a href="#"><img src="assetservice/logo.png" alt="logo" /></a>
                </div>
                <div class="nav__menu__btn" id="menu-btn">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
            <ul class="nav__links" id="nav-links">
                <li><a href="../Home/Home.php">Home</a></li>
                <li><a href="#about">Sobre</a></li>
                <li><a href="#service">Serviços</a></li>
                <li><a href="../Quartos/Quartos.php">Quartos</a></li>
                <li><a href="#explore">Explorar</a></li>
                <li><a href="#contact">Contactos</a></li>

                <?php if ($loggedIn): ?>
                    <li>
                        <div class="user-profile">
                            <div class="dropdown">
                                <button class="dropbtn">
                                    <i class="ri-user-line"></i>
                                    <span><?php echo $_SESSION['username']; ?></span>
                                </button>
                                <div class="dropdown-content">
                                    <a href="#">Profile</a>
                                    <a href="#">Settings</a>
                                    <a href="../Logout/logout.php">Logout</a>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="../Login/Login.php">Login</a></li>
                    <li><a href="../Registar/Registar.php">Register</a></li>
                <?php endif; ?>
            </ul>
            <button class="btn nav__btn">Book Now</button>
        </nav>
    </header>

    <section class="profile-section">
        <div class="profile-container">
            <div class="profile-picture">
                <img src="assetservice/pfp-of-l-by-me-photoshopped-v0-s3j3a7fc403b1.png" alt="Profile Picture">

                <button type="submit" class="btn">Upload new image</button>

                <button class="btn btn-secondary"><a href="../Reservas/Reservas.php">See Reservations</a></button>
            </div>
            <div class="profile-details">
                <h2>Account Details</h2>
                <form id="profileForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">

                    <label for="firstname">First name</label>
                    <input type="text" id="firstname" name="firstname"
                        value="<?php echo htmlspecialchars($firstname); ?>">

                    <label for="lastname">Last name</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>">

                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

                    <label for="telephone">Phone number</label>
                    <input type="text" id="telephone" name="telephone"
                        value="<?php echo htmlspecialchars($telephone); ?>" pattern="[0-9]+">

                    <button type="submit">Save changes</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="footer" id="contact">
        <div class="section__container footer__container">
            <div class="footer__col">
                <div class="logo">
                    <a href="#home"><img src="assetservice/logo.png" alt="logo" /></a>
                </div>
                <p class="section__description">
                    Discover a world of comfort, luxury, and adventure as you explore
                    our curated selection of hotels, making every moment of your getaway
                    truly extraordinary.
                </p>
                <button class="btn">Book Now</button>
            </div>
            <div class="footer__col">
                <h4>QUICK LINKS</h4>
                <ul class="footer__links">
                    <li><a href="#">Browse Destinations</a></li>
                    <li><a href="#">Special Offers & Packages</a></li>
                    <li><a href="#">Room Types & Amenities</a></li>
                    <li><a href="#">Customer Reviews & Ratings</a></li>
                    <li><a href="#">Travel Tips & Guides</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>OUR SERVICES</h4>
                <ul class="footer__links">
                    <li><a href="#">Concierge Assistance</a></li>
                    <li><a href="#">Flexible Booking Options</a></li>
                    <li><a href="#">Airport Transfers</a></li>
                    <li><a href="#">Wellness & Recreation</a></li>
                </ul>
            </div>
            <div class="footer__col">
                <h4>CONTACT US</h4>
                <ul class="footer__links">
                    <li><a href="#">Customer Support</a></li>
                    <li><a href="#">Inquiries & Reservations</a></li>
                    <li><a href="#">Feedback & Complaints</a></li>
                </ul>
            </div>
        </div>
        <div class="footer__credit">
            <p>&copy; 2023. Eden. All rights reserved.</p>
        </div>
    </footer>

    <!-- JavaScript for custom validation messages -->
    <script>
        document.getElementById('profileForm').addEventListener('submit', function (event) {
            let isValid = true;
            let errorMsg = '';

            // Check if firstname is empty
            const firstname = document.getElementById('firstname').value;
            if (!firstname) {
                isValid = false;
                errorMsg += 'First name is required.\n';
            }

            // Check if lastname is empty
            const lastname = document.getElementById('lastname').value;
            if (!lastname) {
                isValid = false;
                errorMsg += 'Last name is required.\n';
            }

            // Check if email is valid
            const email = document.getElementById('email').value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                isValid = false;
                errorMsg += 'Invalid email format.\n';
            }

            // Check if phone number is valid
            const telephone = document.getElementById('telephone').value;
            const phonePattern = /^[0-9]+$/;
            if (!phonePattern.test(telephone)) {
                isValid = false;
                errorMsg += 'Phone number can only contain digits.\n';
            }

            if (!isValid) {
                alert(errorMsg);
                event.preventDefault();
            }
        });
    </script>
</body>

</html>