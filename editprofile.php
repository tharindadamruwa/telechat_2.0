<?php
session_start();
include_once "php/config.php";

if (!isset($_SESSION['uniq_id'])) {
    if (isset($_COOKIE['uniq_id'])) {
      $_SESSION['uniq_id'] = $_COOKIE['uniq_id'];
      exit;
    } else {
      header("Location: https://telechat.rf.gd/index.php");
      exit;
    }
}

$my_id = $_SESSION['uniq_id'];
$query = mysqli_query($conn, "SELECT * FROM users WHERE uniq_id = '{$my_id}'");
$row = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TeleChat | Edit Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/editprofile.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
  <style>
    .error-text {
      color: #d93025;
      font-size: 0.9rem;
      margin-top: 10px;
      display: block;
    }
  </style>
</head>
<body>

<div class="signup-container">
  <div class="brand">
    <span>Tele</span><span class="brand-accent">Chat</span>
    <div class="brand-sub">Settings</div>
  </div>

  <h2>Edit Profile</h2>

  <form id="editProfileForm">
    <div class="avatar-section">
      <div class="avatar">
        <img src="img/<?php echo $row['img']; ?>" id="avatarPreview">
      </div>
      <label class="upload-btn">
        Change Profile Photo
        <input type="file" id="avatarInput" accept="image/*" hidden>
      </label>
    </div>

    <div class="crop-modal" id="cropModal">
      <div class="crop-box">
        <img id="cropImage">
        <button type="button" id="cropBtn">Crop & Save</button>
      </div>
    </div>

    <div class="row">
      <div class="input-group">
        <input type="text" id="firstName" value="<?php echo $row['fname']; ?>" required>
        <label>First Name</label>
      </div>
      <div class="input-group">
        <input type="text" id="lastName" value="<?php echo $row['lname']; ?>" required>
        <label>Last Name</label>
      </div>
    </div>

    <div class="input-group">
      <input type="email" id="email" value="<?php echo $row['email']; ?>" required>
      <label>Email</label>
    </div>

    <div class="input-group password-group">
      <input type="password" id="password" placeholder="Leave blank to keep current">
      <label>New Password</label>
      <span class="toggle-password" data-target="password">Show</span>
    </div>

    <button type="submit" id="submitBtn">
      <span class="btn-text">Update Profile</span>
      <span class="loader"></span>
    </button>
    
    <p class="error-text" id="errorText"></p>
    <div class="login-text"><a href="https://telechat.rf.gd/app.php">Cancel</a></div>
  </form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="src/editprofile.js"></script>
</body>
</html>