<?php

require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user_id = $_POST['user_id'];
  $newPassword = $_POST['new_password'];



  // Update the password
  $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
  $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
  $stmt->bind_param("si", $hashedPassword, $user_id);
  $stmt->execute();
  $stmt->close();

  // Delete the recovery code
  $stmt = $conn->prepare("DELETE FROM recovery_codes WHERE user_id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $stmt->close();

  echo "Password has been changed successfully.";

  // Close the connection
  $conn->close();
  // Redirect to login page
  header("Location: ../Login/Login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="password.css">
  <title>Set New Password</title>
</head>

<body>
  <section>
    <div class="form-box">
      <h2>Set New Password</h2>
      <form id="reset-form" action="newpasswordform.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($_GET['user_id']); ?>">
        <div class="inputbox">
          <input type="password" name="new_password" required="required">
          <label>Nova Password</label>
          <ion-icon name="lock-closed-outline"></ion-icon>
        </div>
        <button type="submit">Change Password</button>
      </form>
      <div class="back-to-login">
        <p><a href="../Login/Login.php">Back to Login</a></p>
      </div>
    </div>
  </section>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>