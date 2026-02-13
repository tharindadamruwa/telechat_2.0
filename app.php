<?php
session_start();
if (!isset($_SESSION['uniq_id'])) {
    if (isset($_COOKIE['uniq_id'])) {
      $_SESSION['uniq_id'] = $_COOKIE['uniq_id'];
    } else {
      header("Location: https://telechat.rf.gd/index.php");
      exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TeleChat</title>
  <link rel="stylesheet" href="css/app.css"/>
</head>
<body>

<div class="chat-app">

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="brand-wrap">
        <div class="brand">
          Tele<span>Chat</span>
          <div class="brand-sub">Made by Tharinda</div>
        </div>
      </div>

      <div class="menu-wrapper">
        <button class="menu-btn-dots" id="menuDots">⋮</button>
        <div class="dropdown-menu" id="dropdownMenu">
          <a href="https://telechat.rf.gd/editprofile.php">Edit Profile</a>
          <a id="logoutBtn" >Logout</a>
        </div>
      </div>
    </div>

    <div class="user-list" id="userList"></div>
  </aside>

  <main class="chat-area">

    <div class="chat-header">
      <button class="menu-btn" id="menuBtn">☰</button>
      <div class="chat-user" id="chatUser">
        Select a chat
      </div>
    </div>

    <div class="chat-messages" id="chatMessages">
      <div class="empty-chat">Select a user to start chatting</div>
    </div>

    <button class="scroll-bottom-btn" id="scrollBtn">↓</button>

    <div class="chat-input">
      <input type="text" placeholder="Type a message..." disabled />
      <button disabled>Send</button>
    </div>

  </main>

</div>

<script>
  const MY_ID = "<?php echo $_SESSION['uniq_id']; ?>";
</script>
<script src="src/app.js"></script>

</body>
</html>