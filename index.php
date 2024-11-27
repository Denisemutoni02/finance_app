<?php
// Start session
session_start();

// Redirect logged-in users to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: public/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyWise</title>
    <link rel="stylesheet" href="assets/styles4.css"> <!-- Update path to your CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Font Awesome for icons -->
    <style>
        /* Body background image */
        body {
            background-image: url('image.jpg'); /* Path to your background image */
            background-size: cover; /* Ensure the image covers the entire background */
            background-position: center center; /* Center the background image */
            background-attachment: fixed; /* Keep the background fixed during scroll */
        }

        /* Styles for the logo */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            margin-bottom: 20px;
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
            color: #ffce56; /* Accent color for "Wise" */
        }

        /* Navigation bar tweaks */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 80px 20px;
            color: #fff;
            background-color: rgba(0, 0, 0, 0.5); /* Optional dark overlay */
        }

        .cta a {
            margin: 10px;
            text-decoration: none;
            color: white;
        }
    </style>

    
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="logo">
            <!-- MoneyWise Logo -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                <!-- Outer Circle (Coin) -->
                <circle cx="50" cy="50" r="45" fill="#ffce56" stroke="#333" stroke-width="5"></circle>

                <!-- Piggy Bank Shape -->
                <path d="M70 50c0-12-9-20-20-20s-20 8-20 20c0 4 2 8 5 10h-5c-1 0-2 1-2 2v6h44v-6c0-1-1-2-2-2h-5c3-2 5-6 5-10z" fill="#fff" stroke="#333" stroke-width="3"></path>
                
                <!-- Coin Slot -->
                <rect x="45" y="30" width="10" height="5" fill="#333" rx="2"></rect>
            </svg>
            <div class="logo-text">Money<span>Wise</span></div>
        </div>
        <div class="nav-links">
            <a href="public/login.php" class="btn">Login</a>
            <a href="public/register.php" class="btn btn-secondary">Sign Up</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <div class="container">
            <h1>Welcome to MoneyWise</h1>
            <p>Your personal finance management tool to track expenses, set budgets, and achieve financial freedom.</p>
            <h2 class="slogan">Spend Smart. Live Bold.</h2> <!-- Slogan added here -->
            <style>
                .container h1{
                color: #fff;
                }
                .container p{
                    color: #fff;
                }
            </style>

            <style>
                .hero .slogan {
                font-size: 24px;
                font-weight: bold;
             color: #ffce56; /* Accent color */
             margin-top: 20px;
                }
             </style>

            <div class="cta">
                <a href="public/register.php" class="btn">Get Started</a>
                <a href="#features" class="btn btn-secondary">Learn More</a>
            </div>
        </div>
    </header>



   <!-- Features Section -->
<section id="features" class="features">
    <div class="container">
        <h2>Why Use MoneyWise?</h2>
        <p>MoneyWise helps you stay on top of your finances with tools designed to make budgeting, tracking, and analyzing your spending easy and efficient.</p>
        
        <div class="features-grid">
            <div class="feature">
                <i class="fa fa-wallet"></i>
                <h3>Track Expenses</h3>
                <p>Easily record and categorize your daily spending with just a few clicks.</p>
            </div>
            <div class="feature">
                <i class="fa fa-dollar-sign"></i>
                <h3>Set Budgets</h3>
                <p>Set monthly limits for each category and monitor your spending to stay within budget.</p>
            </div>
            <div class="feature">
                <i class="fa fa-chart-pie"></i>
                <h3>View Reports</h3>
                <p>Analyze your financial data with visualized charts and detailed reports.</p>
            </div>
            <div class="feature">
                <i class="fa fa-lock"></i>
                <h3>Secure</h3>
                <p>Your data is protected with advanced encryption to keep it safe and private.</p>
            </div>
        </div>

       
    </div>
</section>


    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <h2>Start Managing Your Finances Today</h2>
            <a href="public/register.php" class="btn btn-primary">Sign Up Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> MoneyWise. All rights reserved.</p>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </footer>

    <!-- External Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
</body>
</html>
