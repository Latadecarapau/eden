<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $code = $_POST['code'];
    
    // Check if the code is correct
    $stmt = $mysqli->prepare("SELECT id FROM password_resets WHERE email = ? AND code = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Code is correct, allow the user to reset the password
        header("Location: newpasswordform.php?email=" . urlencode($email));
        exit();
    } else {
        // Code is incorrect or expired, show an error message
        echo "Invalid or expired verification code.";
    }
}
?>
