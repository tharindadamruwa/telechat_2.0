<?php
session_start();
include "config.php";

if (!isset($_SESSION['uniq_id'])) {
    echo json_encode(["status" => "error"]);
    exit;
}

$outgoing_id = $_SESSION['uniq_id']; // This is ME
$incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']); // This is the OTHER person

// --- NEW: Mark messages sent FROM them TO me as READ ---
$update_sql = "UPDATE massage SET read_status = 1 
               WHERE incoming_msg_id = '{$outgoing_id}' 
               AND outgoing_msg_id = '{$incoming_id}' 
               AND read_status = 0";
mysqli_query($conn, $update_sql);

// Fetch messages
$sql = "SELECT * FROM massage
        WHERE (outgoing_msg_id = '{$outgoing_id}' AND incoming_msg_id = '{$incoming_id}')
           OR (outgoing_msg_id = '{$incoming_id}' AND incoming_msg_id = '{$outgoing_id}')
        ORDER BY msg_id ASC";

$query = mysqli_query($conn, $sql);
$messages = [];

while ($row = mysqli_fetch_assoc($query)) {
    $messages[] = $row;
}

echo json_encode([
    "status" => "success",
    "messages" => $messages
]);