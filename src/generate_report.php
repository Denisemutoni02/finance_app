<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch total expenses grouped by category for the current month
$current_month = date("Y-m");
$query = "
    SELECT category, SUM(amount) AS total_spent
    FROM expenses
    WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?
    GROUP BY category
    ORDER BY total_spent DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$expenses_by_category = $result->fetch_all(MYSQLI_ASSOC);

// Fetch total expenses for each month
$query = "
    SELECT DATE_FORMAT(expense_date, '%Y-%m') AS month, SUM(amount) AS total_spent
    FROM expenses
    WHERE user_id = ?
    GROUP BY month
    ORDER BY month ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$expenses_by_month = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
