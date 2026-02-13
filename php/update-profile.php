<?php
session_start();
include_once "config.php";

if (!isset($_SESSION['uniq_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$my_id = $_SESSION['uniq_id'];
$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$pw = mysqli_real_escape_string($conn, $_POST['password']);
$password = password_hash($pw, PASSWORD_DEFAULT);
// Get existing image name
$sql = mysqli_query($conn, "SELECT img FROM users WHERE uniq_id = '{$my_id}'");
$row = mysqli_fetch_assoc($sql);
$current_img = $row['img'];

// Handle Image Replacement
if (isset($_FILES['image'])) {
    
    $img_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
 
    if ($current_img == "s.jpg") {
        $new_img_name =
            $fname . "_" .
            $lname . "_" .
            $email . "_" .
            $my_id . "." . $img_ext;
    } else {
        $new_img_name = $current_img; // Keep the old name
    }

    $tmp_name = $_FILES['image']['tmp_name'];
    $path = "../img/" . $new_img_name;

    // Delete old file if it's not the default
    if ($current_img != "s.jpg" && file_exists("../img/" . $current_img)) {
        // Only delete if we are actually replacing the file
        // (Though in this logic we use the same name, so it would overwrite anyway)
    }

    if (move_uploaded_file($tmp_name, $path)) {
        mysqli_query($conn, "UPDATE users SET img = '{$new_img_name}' WHERE uniq_id = '{$my_id}'");
    }
}

// Password update logic
$pass_query = "";
if (!empty($password)) {
    $pass_query = ", password = '{$password}'";
}

$update = mysqli_query($conn, "UPDATE users SET fname = '{$fname}', lname = '{$lname}', email = '{$email}' $pass_query WHERE uniq_id = '{$my_id}'");

if ($update) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed"]);
}
?>