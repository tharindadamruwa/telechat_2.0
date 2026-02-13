<?php
session_start();
include_once __DIR__ . "/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($email) || empty($password)) {
        echo "All fields are required";
        exit;
    }

    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}'");
    if (mysqli_num_rows($sql) === 0) {
        echo "Email or password is incorrect";
        exit;
    }

    $user = mysqli_fetch_assoc($sql);

    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo "Email or password is incorrect";
        exit;
    }

    // Login success
    $_SESSION['uniq_id'] = $user['uniq_id'];
    setcookie("uniq_id", $uniq_id, time() + 60 * 60 * 24, "/");
    echo "success";
}