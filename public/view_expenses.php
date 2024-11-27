<?php
// Include database connection
require_once '../config/db.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Initialize variables for filters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build the SQL query with filters
$query = "SELECT expense_date, category, amount, description FROM expenses WHERE user_id = ?";

// Apply category filter
if ($category) {
    $query .= " AND category = ?";
}

// Apply date filters
if ($start_date) {
    $query .= " AND expense_date >= ?";
}
if ($end_date) {
    $query .= " AND expense_date <= ?";
}

// Apply search filter (keyword in description)
if ($search) {
    $query .= " AND description LIKE ?";
}

$query .= " ORDER BY expense_date ASC"; // Optional: Order by date

$stmt = $conn->prepare($query);

// Bind parameters
if ($category && $start_date && $end_date && $search) {
    $stmt->bind_param("issss", $user_id, $category, $start_date, $end_date, "%$search%");
} elseif ($category && $start_date && $end_date) {
    $stmt->bind_param("isss", $user_id, $category, $start_date, $end_date);
} elseif ($category && $search) {
    $stmt->bind_param("iss", $user_id, $category, "%$search%");
} elseif ($category) {
    $stmt->bind_param("is", $user_id, $category);
} else {
    $stmt->bind_param("s", $user_id);
}

// Execute the query and get the results
$stmt->execute();
$result = $stmt->get_result();

// Fetch all the data
$expenses = [];
if ($result && $result->num_rows > 0) {
    $expenses = $result->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Expenses</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Add your CSS file -->

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>
<body class="<?php echo $dark_mode ? 'dark-mode' : ''; ?>">

<!-- Logo Above Header -->
<div class="logo-container" style="text-align: center; margin-bottom: 20px;">
    <div class="logo" style="display: flex; align-items: center; justify-content: center; color: white;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 40px; height: 40px; margin-right: 8px;">
            <!-- Outer Circle (Coin) -->
            <circle cx="50" cy="50" r="45" fill="#ffce56" stroke="#333" stroke-width="5"></circle>
            <!-- Piggy Bank Shape -->
            <path d="M70 50c0-12-9-20-20-20s-20 8-20 20c0 4 2 8 5 10h-5c-1 0-2 1-2 2v6h44v-6c0-1-1-2-2-2h-5c3-2 5-6 5-10z" fill="#fff" stroke="#333" stroke-width="3"></path>
            <!-- Coin Slot -->
            <rect x="45" y="30" width="10" height="5" fill="#333" rx="2"></rect>
        </svg>
        <div style="font-size: 20px; font-weight: bold; color: black;">Money<span style="color: #ffce56;">Wise</span></div>
    </div>
</div>

<!-- Navigation Bar -->
<nav style="background: #333; color: white; padding: 10px; text-align: center;">
    <a href="dashboard.php" style="color: white; margin: 0 10px; text-decoration: none;">Dashboard</a>
    <a href="view_budget.php" style="color: white; margin: 0 10px; text-decoration: none;">Budget</a>
    <a href="view_expenses.php" style="color: white; margin: 0 10px; text-decoration: none;">Expenses</a>
    <a href="view_report.php" style="color: white; margin: 0 10px; text-decoration: none;">Reports</a>
    <a href="../src/logout.php" style="color: white; margin: 0 10px; text-decoration: none;">Logout</a>
</nav>

<div class="container">
    <h1>My Expenses</h1>

    <!-- Filters Form -->
    <form method="GET" action="view_expenses.php">
        <label for="category">Category:</label>
        <select id="category" name="category">
            <option value="">All</option>
            <option value="Tuition" <?php echo ($category == 'Tuition') ? 'selected' : ''; ?>>Tuition</option>
            <option value="Food" <?php echo ($category == 'Food') ? 'selected' : ''; ?>>Food</option>
            <option value="Entertainment" <?php echo ($category == 'Entertainment') ? 'selected' : ''; ?>>Entertainment</option>
            <option value="Transportation" <?php echo ($category == 'Transportation') ? 'selected' : ''; ?>>Transportation</option>
            <option value="Other" <?php echo ($category == 'Other') ? 'selected' : ''; ?>>Other</option>
        </select>

        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">

        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Keyword..." value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">Filter</button>
    </form>

    <!-- Expenses Table -->
    <table id="expensesTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($expenses)): ?>
                <?php foreach ($expenses as $expense): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($expense['expense_date']); ?></td>
                        <td><?php echo htmlspecialchars($expense['category']); ?></td>
                        <td><?php echo htmlspecialchars($expense['amount']); ?></td>
                        <td><?php echo htmlspecialchars($expense['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No expenses found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button><a href="add_expense.php">Add expense</a></button>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#expensesTable').DataTable();
    });
</script>

</body>
</html>
