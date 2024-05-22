<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$new_balance = intval($_POST['balance']); // Ensure the balance is an integer

$query = "UPDATE users SET balance = $new_balance WHERE id = $user_id";
if ($conn->query($query) === TRUE) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}
?>
