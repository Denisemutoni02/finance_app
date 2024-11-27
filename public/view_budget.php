<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Budget</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Add your CSS file -->
    <style>
        /* Logo Styles */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            margin-bottom: 20px;
            justify-content: center; /* Center align the logo */
        }

        .logo svg {
            width: 40px;
            height: 40px;
            margin-right: 8px;
        }

        .logo .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .logo .logo-text span {
            color: #ffce56; /* Accent color */
        }
    </style>
</head>
<body>

<!-- Logo Section -->
<div class="logo">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="45" fill="#ffce56" stroke="#333" stroke-width="5"></circle>
        <path d="M70 50c0-12-9-20-20-20s-20 8-20 20c0 4 2 8 5 10h-5c-1 0-2 1-2 2v6h44v-6c0-1-1-2-2-2h-5c3-2 5-6 5-10z" fill="#fff" stroke="#333" stroke-width="3"></path>
        <rect x="45" y="30" width="10" height="5" fill="#333" rx="2"></rect>
    </svg>
    <div class="logo-text">Money<span>Wise</span></div>
</div>

<!-- Navigation -->
<nav style="background: #333; color: white; padding: 10px; text-align: center;">
    <a href="dashboard.php" style="color: white; margin: 0 10px; text-decoration: none;">Dashboard</a>
    <a href="view_budget.php" style="color: white; margin: 0 10px; text-decoration: none;">Budget</a>
    <a href="view_expenses.php" style="color: white; margin: 0 10px; text-decoration: none;">Expenses</a>
    <a href="view_report.php" style="color: white; margin: 0 10px; text-decoration: none;">Reports</a>
    <a href="../src/logout.php" style="color: white; margin: 0 10px; text-decoration: none;">Logout</a>
</nav>

<div class="container">
    <h1>My Budgets</h1>

    <?php
    // Include database connection
    require_once '../config/db.php';

    // Initialize variables
    $budgets = [];
    $notifications = [];

    // Fetch budgets from the database
    try {
        $query = "SELECT category, budget_limit, 
                         (SELECT COALESCE(SUM(amount), 0) 
                          FROM expenses 
                          WHERE expenses.category = budgets.category) AS total_spent 
                  FROM budgets";

        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $budgets = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $notifications[] = "No budgets found.";
        }
    } catch (Exception $e) {
        $notifications[] = "Error fetching budgets: " . $e->getMessage();
    }

    $conn->close();
    ?>

    <!-- Notifications Section -->
    <?php if (!empty($notifications)): ?>
        <div class="notifications">
            <?php foreach ($notifications as $notification): ?>
                <p style="color: red;"><?php echo htmlspecialchars($notification); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Budget Table -->
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Budget Limit</th>
                <th>Total Spent</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($budgets)): ?>
                <?php foreach ($budgets as $budget): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($budget['category']); ?></td>
                        <td><?php echo htmlspecialchars($budget['budget_limit']); ?></td>
                        <td><?php echo htmlspecialchars($budget['total_spent']); ?></td>
                        <td>
                            <?php
                            if ($budget['total_spent'] > $budget['budget_limit']) {
                                echo '<span style="color: red;">Over Budget</span>';
                            } else {
                                echo '<span style="color: green;">Within Budget</span>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No budget data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <button><a href="add_budget.php">Add budget</a></button>
</div>
</body>
</html>
