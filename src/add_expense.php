<?php
require_once '../config/db.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $category = trim($_POST['category']);
    $amount = trim($_POST['amount']);
    $expense_date = trim($_POST['expense_date']);
    $description = trim($_POST['description']);

    // Validate inputs
    if (empty($category) || empty($amount) || empty($expense_date)) {
        die("All fields except description are required.");
    }
    

    // Insert expense into the database
    $query = "INSERT INTO expenses (user_id, category, amount, expense_date, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isdss", $user_id, $category, $amount, $expense_date, $description);

    if ($stmt->execute()) {
        echo "Expense added successfully. <a href='../public/dashboard.php'>Go back to dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
