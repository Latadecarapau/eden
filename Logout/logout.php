<?php
session_start();
session_destroy(); // Destroy all session data
header("Location: ../Home/Home.php"); // Redirect to login page
exit();