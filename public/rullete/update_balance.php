<?php
session_start();
header('Content-Type: application/json');

include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];

// Get the new balance from the request
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['balance'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid balance']);
    exit();
}

$newBalance = $data['balance'];

// Update the balance
$query = "UPDATE users SET balance = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $newBalance, $userId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update balance']);
}

$stmt->close();
$conn->close();
?>
