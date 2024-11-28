<?php
session_start();
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Mark all notifications as read
$query = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();

echo json_encode(['success' => true]);

$stmt->close();
$conn->close();
?>
