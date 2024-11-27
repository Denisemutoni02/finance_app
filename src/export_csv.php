<?php
require_once '../config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$current_month = date("Y-m");

// Fetch expenses for the current month
$query = "
    SELECT expense_date, category, amount, description
    FROM expenses
    WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?
    ORDER BY expense_date ASC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();

// Set headers to download the CSV file
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="expenses_' . $current_month . '.csv"');

$output = fopen('php://output', 'w');

// Add the headers
fputcsv($output, ['Date', 'Category', 'Amount', 'Description']);

// Add the data rows
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
$stmt->close();
$conn->close();
?>
