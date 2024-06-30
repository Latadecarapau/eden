<?php

require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $code = rand(100000, 999999);
        
        $stmt = $mysqli->prepare("INSERT INTO password_resets (email, code, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        
        mail($email, "Your Password Reset Code", "Your verification code is: $code");
        
        header("Location: verifycode.php?email=" . urlencode($email));
        exit();
    } else {
        header("Location: ../Register/Registar.php");
        exit();
    }
}
?>