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
  <meta charset="UTF-8">
  <title>TeleChat | Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/signup.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
</head>
<body>

<div class="signup-container">

  <div class="brand">
    <span>Tele</span><span class="brand-accent">Chat</span>
    <div class="brand-sub">Made by Tharinda</div>
  </div>

  <h2>Create Account</h2>

  <form id="signupForm">

    <!-- Avatar Upload -->
    <div class="avatar-section">
      <div class="avatar" id="avatarWrapper">
        <img src="img/s.jpg" id="avatarPreview">
      </div>

      <label class="upload-btn">
        Upload Profile Photo
        <input type="file" id="avatarInput" accept="image/*" hidden>
      </label>

      <!-- cropped base64 -->
      <input style="display: none;" type="input" id="croppedImage">
    </div>

    <!-- Crop Modal -->
    <div class="crop-modal" id="cropModal">
      <div class="crop-box">
        <img id="cropImage">
        <button type="button" id="cropBtn">Crop</button>
      </div>
    </div>

    <div class="row">
      <div class="input-group">
        <input type="text" id="firstName" required>
        <label>First Name</label>
      </div>

      <div class="input-group">
        <input type="text" id="lastName" required>
        <label>Last Name</label>
      </div>
    </div>

    <div class="input-group">
      <input type="email" id="email" required>
      <label>Email</label>
    </div>

    <div class="input-group password-group">
      <input type="password" id="password" required>
      <label>Password</label>
      <span class="toggle-password" data-target="password">Show</span>
    </div>

    <div class="input-group password-group">
      <input type="password" id="confirmPassword" required>
      <label>Confirm Password</label>
      <span class="toggle-password" data-target="confirmPassword">Show</span>
    </div>

    <button id="submit">
      <span class="btn-text">Sign Up</span>
      <span class="loader"></span>
    </button>

    <p class="error-text" id="errorText"></p>

  </form>

  <p class="login-text">
    Already have an account?
    <a href="https://telechat.rf.gd/index.php">Login</a>
  </p>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="src/signup.js"></script>
</body>
</html>