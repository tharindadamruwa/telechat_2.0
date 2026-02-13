<?php
session_start();
include_once __DIR__ . "/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['lname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        echo "All fields are required";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address";
        exit;
    }

    // ✅ FIXED TABLE NAME HERE
    $check_email = mysqli_query(
        $conn,
        "SELECT email FROM users WHERE email = '{$email}'"
    );

    if (mysqli_num_rows($check_email) > 0) {
        echo "Email already exists";
        exit;
    }

    // Generate unique uniq_id
    do {
        $uniq_id = rand(100000000, 999999999);
        $check_uniq = mysqli_query(
            $conn,
            "SELECT uniq_id FROM users WHERE uniq_id = {$uniq_id}"
        );
    } while (mysqli_num_rows($check_uniq) > 0);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Image handling
    $img_name = "s.jpg";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $img_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array(strtolower($img_ext), $allowed)) {
            echo "Only JPG, PNG or WEBP images allowed";
            exit;
        }

        $new_img_name =
            $fname . "_" .
            $lname . "_" .
            $email . "_" .
            $uniq_id . "." . $img_ext;

        $new_img_name = str_replace(" ", "", $new_img_name);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], "../img/" . $new_img_name)) {
            echo "Image upload failed";
            exit;
        }

        $img_name = $new_img_name;
    }

    // ✅ FIXED TABLE NAME HERE
    $insert = mysqli_query($conn, "
        INSERT INTO users (uniq_id, fname, lname, email, password, img)
        VALUES ({$uniq_id}, '{$fname}', '{$lname}', '{$email}', '{$hashed_password}', '{$img_name}')
    ");

    if ($insert) {
        $_SESSION['uniq_id'] = $uniq_id;
        setcookie("uniq_id", $uniq_id, time() + 60 * 60 * 24, "/");
        echo "success";
    } else {
        echo "Signup failed";
    }
}