<?php
require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $code = $_POST['code'];


  // Verify the recovery code
  $stmt = $conn->prepare("SELECT user_id FROM recovery_codes WHERE recovery_code = ? AND EXISTS (SELECT id FROM users WHERE id = user_id AND email = ?)");
  $stmt->bind_param("ss", $code, $email);
  $stmt->execute();
  $stmt->bind_result($user_id);
  $stmt->fetch();
  $stmt->close();

  if ($user_id) {
    // Redirect to reset password page with user ID as a parameter
    header("Location: newpasswordform.php?user_id=" . $user_id);
    exit();
  } else {
    echo "Invalid verification code.";
  }

  // Close the connection
  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="Password.css">
  <title>Verify Code</title>
</head>

<body>
  <section>
    <div class="form-box">
      <h2>Verificar Código</h2>
      <form id="code-form" action="verifycode.php" method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
        <div class="inputbox">
          <input type="text" name="code" required="required">
          <label>Verification Code</label>
          <ion-icon name="key-outline"></ion-icon>
        </div>
        <button type="submit">Verificar Codigo</button>
      </form>
      <div class="back-to-login">
        <p><a href="../Login/Login.php">Voltar para Login</a></p>
      </div>
    </div>
  </section>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>