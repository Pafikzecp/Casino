<?php
session_start();
$loggedIn = isset($_SESSION['user_id']);
if (!$loggedIn) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

include 'database_connection.php'; // Make sure this file contains the necessary database connection code
$user_id = $_SESSION['user_id'];
$query = $pdo->prepare("SELECT balance FROM users WHERE id = :id");
$query->execute(['id' => $user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(['status' => 'success', 'balance' => $user['balance']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
}
?>
