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

// Initialize variables
$expenses_by_category = [];
$expenses_by_month = [];

try {
    // Query to fetch spending by category for the current month
    $query_category = "
        SELECT category, SUM(amount) AS total_spent
        FROM expenses
        WHERE MONTH(expense_date) = MONTH(CURRENT_DATE())
        AND YEAR(expense_date) = YEAR(CURRENT_DATE())
        GROUP BY category
    ";
    $result_category = $conn->query($query_category);
    if ($result_category && $result_category->num_rows > 0) {
        $expenses_by_category = $result_category->fetch_all(MYSQLI_ASSOC);
    }

    // Query to fetch monthly spending trends
    $query_month = "
        SELECT DATE_FORMAT(expense_date, '%Y-%m') AS month, SUM(amount) AS total_spent
        FROM expenses
        GROUP BY DATE_FORMAT(expense_date, '%Y-%m')
        ORDER BY month ASC
    ";
    $result_month = $conn->query($query_month);
    if ($result_month && $result_month->num_rows > 0) {
        $expenses_by_month = $result_month->fetch_all(MYSQLI_ASSOC);
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error fetching report data: " . htmlspecialchars($e->getMessage()) . "</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Add your CSS file -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js for visualizations -->
</head>
<body>

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
    <h1>Expense Report</h1>

    <!-- Export Buttons -->
    <div class="export-buttons">
        <a href="../src/export_csv.php" class="button" style="margin-right: 10px; text-decoration: none; padding: 10px; background-color: #4CAF50; color: white; border-radius: 5px;">Export as CSV</a>
        <a href="../src/export_pdf.php" class="button" style="text-decoration: none; padding: 10px; background-color: #2196F3; color: white; border-radius: 5px;">Export as PDF</a>
    </div>

    <!-- Summary by Category -->
    <h2>Spending by Category (Current Month)</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Total Spent</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($expenses_by_category)): ?>
                <?php foreach ($expenses_by_category as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['category']); ?></td>
                        <td><?php echo htmlspecialchars($category['total_spent']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No data available for the current month.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Chart: Spending by Category -->
    <canvas id="categoryChart" width="400" height="200"></canvas>

    <!-- Monthly Trends -->
    <h2>Monthly Spending Trends</h2>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Total Spent</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($expenses_by_month)): ?>
                <?php foreach ($expenses_by_month as $month): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($month['month']); ?></td>
                        <td><?php echo htmlspecialchars($month['total_spent']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">No spending trends available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Chart: Monthly Trends -->
    <canvas id="monthlyChart" width="400" height="200"></canvas>
</div>

<script>
    // Data for Category Chart
    const categoryLabels = <?php echo json_encode(array_column($expenses_by_category, 'category')); ?>;
    const categoryData = <?php echo json_encode(array_column($expenses_by_category, 'total_spent')); ?>;

    const ctxCategory = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Spending by Category',
                data: categoryData,
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0']
            }]
        }
    });

    // Data for Monthly Chart
    const monthlyLabels = <?php echo json_encode(array_column($expenses_by_month, 'month')); ?>;
    const monthlyData = <?php echo json_encode(array_column($expenses_by_month, 'total_spent')); ?>;

    const ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctxMonthly, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Monthly Spending Trends',
                data: monthlyData,
                borderColor: '#36a2eb',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                fill: true
            }]
        }
    });
</script>

</body>
</html>
