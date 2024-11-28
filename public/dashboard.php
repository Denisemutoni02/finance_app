<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Database connection (adjust this part according to your setup)
require_once '../config/db.php';

// Get user_id from session
$user_id = $_SESSION['user_id'];
$current_month = date("Y-m"); // Get current month for filtering data

// Fetch total expenses for the current month
$query_expenses = "SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = ? AND DATE_FORMAT(expense_date, '%Y-%m') = ?";
$stmt = $conn->prepare($query_expenses);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$total_expenses = $result->fetch_assoc()['total_expenses'] ?: 0; // Default to 0 if no data found

// Fetch total budget for the current month
$query_budget = "SELECT SUM(budget_limit) AS total_budget FROM budgets WHERE user_id = ? AND month = ?";
$stmt = $conn->prepare($query_budget);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$total_budget = $result->fetch_assoc()['total_budget'] ?: 0; // Default to 0 if no data found

// Calculate remaining budget
$remaining_budget = $total_budget - $total_expenses;

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Font Awesome for hamburger icon -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js library for graphs -->
</head>
<style>
    /* Main Wrapper: Flex container */
    .main-wrapper {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background: #333;
        padding-top: 20px;
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        overflow-y: auto;
        transition: transform 0.3s ease;
    }

    .menu {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
    }

    .menu-item {
        text-align: center;
        padding: 10px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .menu-item:hover {
        background-color: #ddd;
        transform: scale(1.05);
    }

    .menu-link {
        text-decoration: none;
        color: #333;
    }

    .menu-link i {
        font-size: 30px;
        color: var(--primary-color);
    }

    .menu-link p {
        margin-top: 10px;
        font-weight: bold;
    }

    /* Right Content Section */
    .content {
        margin-left: 250px;
        padding: 20px;
        width: 100%;
    }

    .user-profile {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-pic {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-right: 10px;
    }

    /* Notifications */
    .notification-bell {
        position: fixed;
        top: 20px;
        right: 20px;
        font-size: 30px;
        cursor: pointer;
    }

    .notification-count {
        position: absolute;
        top: -5px;
        right: -5px;
        background: red;
        color: white;
        border-radius: 50%;
        padding: 3px 8px;
        font-size: 12px;
    }

    .notification-dropdown {
        display: none;
        position: fixed;
        top: 60px;
        right: 20px;
        background-color: #2c3e50;
        color: white;
        width: 250px;
        padding: 10px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .notification-dropdown.show {
        display: block;
    }

    .notification-dropdown ul {
        list-style-type: none;
        padding: 0;
    }

    .notification-dropdown ul li {
        padding: 10px;
        border-bottom: 1px solid #34495e;
    }

    /* Stats Layout - Horizontal Box */
    .dashboard-stats-horizontal {
        display: flex;
        gap: 20px;
        justify-content: space-between;
        margin-top: 20px;
    }

    .stat-item {
        flex: 1;
        padding: 20px;
        background: #f4f4f9;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .stat-item h2 {
        margin-bottom: 10px;
    }

    .remaining-budget {
        color: green;
        font-weight: bold;
    }
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

<!-- Loader while fetching data -->
<div id="loader" class="loader">
    <i class="fa fa-spinner fa-spin"></i> Loading...
</div>

<!-- Main Wrapper: Flex container for the left menu and right content -->
<div class="main-wrapper">
    
     <!-- Notification Bell (Top-right corner) -->
     <div class="notification-bell" onclick="toggleNotifications()">
        <i class="fa fa-bell"></i>
        <span class="notification-count">3</span> <!-- Example notification count -->
    </div>

    <!-- Left Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="hamburger-menu" id="hamburgerMenu">
            <i class="fas fa-bars" onclick="toggleMenu()"></i> <!-- Hamburger icon -->
        </div>

        <!-- Menu Section -->
        <div class="menu" id="menu">
            <div class="menu-item">
                <a href="dashboard.php" class="menu-link">
                    <i class="fa fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="view_budget.php" class="menu-link">
                    <i class="fa fa-dollar-sign"></i>
                    <p>Budget</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="view_expenses.php" class="menu-link">
                    <i class="fa fa-wallet"></i>
                    <p>Expenses</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="view_report.php" class="menu-link">
                    <i class="fa fa-chart-pie"></i>
                    <p>Reports</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="profile.php" class="menu-link">
                    <i class="fa fa-user"></i>
                    <p>Profile</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="settings.php" class="menu-link">
                    <i class="fa fa-cogs"></i>
                    <p>Settings</p>
                </a>
            </div>
            <div class="menu-item">
                <a href="../src/logout.php" class="menu-link">
                    <i class="fa fa-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Right Content -->
    <div class="content">
        <!-- Profile & Welcome Message -->
        <div class="container">
            <div class="user-profile">
                <!-- <img src="path_to_profile_picture.jpg" alt="Profile Picture" class="profile-pic"> -->
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            </div>
        </div>

        <!-- Display Total Spending and Remaining Budget in Horizontal Boxes -->
        <div class="dashboard-stats-horizontal">
            <div class="stat-item" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <h2>Total Spending</h2>
                <p>UGX <?php echo number_format($total_expenses, 2); ?></p>
            </div>
            <div class="stat-item" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <h2>Total Budget</h2>
                <p>UGX <?php echo number_format($total_budget, 2); ?></p>
            </div>
            <div class="stat-item" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <h2>Remaining Budget</h2>
                <p class="remaining-budget">UGX <?php echo number_format($remaining_budget, 2); ?></p>
            </div>
        </div>

        <?php
// Check if the total budget is greater than zero to avoid division by zero
if ($total_budget > 0) {
    $overrun_percentage = ($total_expenses - $total_budget) / $total_budget * 100;

    // Determine alert class based on severity
    if ($overrun_percentage > 50) {
        $alert_class = 'alert-severe'; // Severe overrun
    } elseif ($overrun_percentage > 20) {
        $alert_class = 'alert-moderate'; // Moderate overrun
    } else {
        $alert_class = 'alert-minor'; // Minor overrun
}
} else {
    $overrun_percentage = 0; // Default to 0 if no budget is set
    $alert_class = 'alert-minor'; // Default alert class
}
?>

<!-- Budget Overrun Alert -->
<?php if ($total_expenses > $total_budget): ?>
    <div class="alert <?php echo $alert_class; ?>">
        <strong>Warning!</strong> Your spending has exceeded the budget by <?php echo round($overrun_percentage); ?>%.
    </div>
<?php endif; ?>



         <!-- Graphs Section -->
         <div class="charts-container">
            <div class="chart">
                <h3>Budget vs Spending (Pie Chart)</h3>
                <canvas id="pieChart" width="300" height="200"></canvas>
            </div>
            <div class="chart">
                <h3>Monthly Spending (Bar Graph)</h3>
                <canvas id="barChart" width="300" height="200"></canvas>
            </div>
            <div class="chart">
                <h3>Spending Over Time (Line Graph)</h3>
                <canvas id="lineChart" width="300" height="200"></canvas>
            </div>

             
        </div>
          <!-- Floating Action Button (FAB) for adding expense -->
      <a href="add_expense.php" class="fab">
            <i class="fa fa-plus-circle"></i>
        </a>
    </div>

      

</div>

<style>
    .charts-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: space-between;
        margin-top: 20px;
    }

    .chart {
        flex: 1 1 30%;
        background: #f4f4f9;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    color: white;
    font-weight: bold;
    text-align: center;
}

.alert-minor {
    background-color: #f39c12; /* Orange - minor warning */
}

.alert-moderate {
    background-color: #e74c3c; /* Red - moderate warning */
}

.alert-severe {
    background-color: #c0392b; /* Dark red - severe warning */
}

</style>

<script>
    // Sample data for the graphs
    const categoryData = ['Food', 'Transport', 'Utilities', 'Others'];
    const spendingData = [50000, 30000, 20000, 15000];

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: categoryData,
            datasets: [{
                data: spendingData,
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56'],
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
        }
    });

    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: categoryData,
            datasets: [{
                label: 'Monthly Spending',
                data: spendingData,
                backgroundColor: '#36a2eb',
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true },
            },
        }
    });

    // Line Chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: categoryData,
            datasets: [{
                label: 'Spending Trend',
                data: spendingData,
                borderColor: '#ff6384',
                fill: false,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true },
            },
        }
    });
</script>

</body>
</html>
