<?php
session_start();
include "config.php";

if (!isset($_SESSION['uniq_id'])) {
    echo json_encode(["status" => "error"]);
    exit;
}

$outgoing_id = $_SESSION['uniq_id'];
$incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
$msg = mysqli_real_escape_string($conn, $_POST['msg']);

if (!empty($msg)) {
    // We add read_status = 0 (unread)
    $sql = "INSERT INTO massage (incoming_msg_id, outgoing_msg_id, msg, read_status)
            VALUES ('{$incoming_id}', '{$outgoing_id}', '{$msg}', 0)";

    mysqli_query($conn, $sql);
}

echo json_encode(["status" => "success"]);