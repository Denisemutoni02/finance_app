<?php
require_once '../config/db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_month = date("Y-m");

// Fetch total expenses for the current month
$query_expenses = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?";
$stmt = $conn->prepare($query_expenses);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$total_expenses = $result->fetch_assoc()['total_expenses'] ?: 0;

// Fetch total budgets for the current month
$query_budget = "SELECT SUM(budget_limit) AS total_budget FROM budgets WHERE user_id = ? AND month = ?";
$stmt = $conn->prepare($query_budget);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$total_budget = $result->fetch_assoc()['total_budget'] ?: 0;

// Calculate remaining budget
$remaining_budget = $total_budget - $total_expenses;

$stmt->close();
$conn->close();
?>
