<?php
session_start();
include '../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['balance' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT balance FROM users WHERE id = $user_id";
$result = $conn->query($query);

if ($result) {
    $balance = $result->fetch_assoc()['balance'];
    echo json_encode(['balance' => $balance]);
} else {
    echo json_encode(['balance' => 0]);
}
?>
