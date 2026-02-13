<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uniq_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    header("Location: ../index.php");
    exit;
}

$loggedUser = $_SESSION['uniq_id'];

$sql = "SELECT uniq_id, fname, lname, img FROM users WHERE uniq_id != ? ORDER BY fname ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedUser);
$stmt->execute();
$result = $stmt->get_result();

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'uniq_id' => $row['uniq_id'],
            'name' => $row['fname'] . ' ' . $row['lname'],
            'img' => $row['img'] ?: 's.jpg'
        ];
    }
}

echo json_encode(['status' => 'success', 'users' => $users]);