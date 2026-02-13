<?php
session_start();
if (isset($_SESSION['uniq_id'])) {
      header("Location: https://telechat.rf.gd/app.php");
      exit;
} else {
      if (isset($_COOKIE['uniq_id'])) {
        $_SESSION['uniq_id'] = $_COOKIE['uniq_id'];
        header("Location: https://telechat.rf.gd/app.php");
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TeleChat | Login</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

  <div class="login-container">

    <!-- Brand -->
    <div class="brand">
  <span>Tele</span><span class="brand-accent">Chat</span>
  <div class="brand-sub">Made by Tharinda</div>
</div>

    <h2>Sign In</h2>

    <form id="loginForm">
      <div class="input-group">
        <input type="email" id="email" required />
        <label>Email</label>
      </div>

      <div class="input-group">
        <input type="password" id="password" required />
        <label>Password</label>
        <span class="toggle-password" onclick="togglePassword()">Show</span>
      </div>
      <p class="error-text" id="errorText"></p>
      <button type="submit">
      <span class="btn-text">Login</span>
      <span class="loader"></span>
    </button>
    </form>

    <!-- Sign up link -->
    <p class="signup-text">
      Don’t have an account?
      <a href="https://telechat.rf.gd/signup.php">Sign up</a>
    </p>

  </div>

  <script src="src/script.js"></script>
</body>
</html>