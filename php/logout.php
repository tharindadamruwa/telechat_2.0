<?php
session_start();
include "config.php";

if (isset($_SESSION['uniq_id'])) {
    // Optional: If you have a status column in your DB, update it here 
    // to "Offline" before destroying the session.
    
    session_unset();
    session_destroy();
    setcookie("uniq_id", null, -time() + 60 * 60 * 24, "/");
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
}
?>