<?php
require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    // Update the password in the database
    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    $stmt->execute();

    // Delete the password reset entry
    $stmt = $mysqli->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Redirect to the login page with a success message
    header("Location: ../Login/Login.php?message=Password successfully updated.");
    exit();
}
?>