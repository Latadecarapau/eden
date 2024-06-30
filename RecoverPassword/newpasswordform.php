<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Set New Password</title>
</head>
<body>
  <section>
    <div class="form-box">
      <h2>Set New Password</h2>
      <form id="new-password-form" action="update_password.php" method="POST">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
        <div class="inputbox">
          <input type="password" name="new_password" required="required">
          <label>New Password</label>
          <ion-icon name="lock-closed-outline"></ion-icon>
        </div>
        <button type="submit">Update Password</button>
      </form>
      <div class="back-to-login">
        <p><a href="login.html">Back to Login</a></p>
      </div>
    </div>
  </section>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>
</html>
