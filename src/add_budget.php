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
    $budget_limit = trim($_POST['budget_limit']);
    $month = trim($_POST['month']);

    // Validate inputs
    if (empty($category) || empty($budget_limit) || empty($month)) {
        die("All fields are required.");
    }

    // Check if budget already exists for the category and month
    $query = "SELECT * FROM budgets WHERE user_id = ? AND category = ? AND month = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $user_id, $category, $month);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("Budget for this category and month already exists.");
    }
    

    // Insert the budget into the database
    $query = "INSERT INTO budgets (user_id, category, budget_limit, month) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $category, $budget_limit, $month);

    if ($stmt->execute()) {
        echo "Budget added successfully. <a href='../public/dashboard.php'>Go back to dashboard</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
