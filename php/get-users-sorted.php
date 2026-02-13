<?php
session_start();
include "config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['uniq_id'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$my_id = $_SESSION['uniq_id'];

$sql = "
SELECT 
    u.uniq_id,
    u.fname,
    u.lname,
    u.img,
    m.msg_id,
    m.incoming_msg_id,
    m.outgoing_msg_id,
    m.read_status
FROM users u
LEFT JOIN massage m ON m.msg_id = (
    SELECT msg_id FROM massage
    WHERE 
        (incoming_msg_id = u.uniq_id AND outgoing_msg_id = ?)
        OR
        (incoming_msg_id = ? AND outgoing_msg_id = u.uniq_id)
    ORDER BY msg_id DESC
    LIMIT 1
)
WHERE u.uniq_id != ?
ORDER BY m.msg_id DESC, u.fname ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $my_id, $my_id, $my_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];

while ($row = $result->fetch_assoc()) {
    $unread = false;

    // Show dot if message was sent TO me AND it is still unread (0)
    if (
        $row['msg_id'] &&
        $row['incoming_msg_id'] == $my_id &&
        $row['read_status'] == 0
    ) {
        $unread = true;
    }

    $users[] = [
        'uniq_id' => $row['uniq_id'],
        'name' => $row['fname'] . ' ' . $row['lname'],
        'img' => $row['img'] ?: 's.jpg',
        'unread' => $unread
    ];
}

echo json_encode(['status' => 'success', 'users' => $users]);