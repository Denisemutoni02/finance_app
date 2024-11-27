<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_month = date("Y-m");

// Fetch budgets and spending
$query = "
    SELECT b.category, b.budget_limit,
           COALESCE(SUM(e.amount), 0) AS total_spent
    FROM budgets b
    LEFT JOIN expenses e
    ON b.user_id = e.user_id AND b.category = e.category AND DATE_FORMAT(e.expense_date, '%Y-%m') = b.month
    WHERE b.user_id = ? AND b.month = ?
    GROUP BY b.category, b.budget_limit
";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$budgets = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();

// Prepare notifications
$notifications = [];
foreach ($budgets as $budget) {
    $percentage_spent = ($budget['total_spent'] / $budget['budget_limit']) * 100;

    if ($percentage_spent >= 100) {
        $notifications[] = "You have exceeded your budget for " . $budget['category'] . ".";
    } elseif ($percentage_spent >= 80) {
        $notifications[] = "You are nearing your budget limit for " . $budget['category'] . " (" . round($percentage_spent) . "% spent).";
    }
}
?>
