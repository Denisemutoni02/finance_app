<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Link to your external CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- Font Awesome -->
    <style>
        /* Logo Styles */
        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
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

        /* Navigation Bar Styles */
        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1>Login</h1>

        <!-- Display error messages -->
        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials'): ?>
            <p style="color: red;">Invalid username or password. Please try again.</p>
        <?php endif; ?>

        <form action="../src/login_action.php" method="POST">
            <div class="form-group">
                <label for="username">Username or Email:</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username or email">
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn-primary">Login</button>

            <!-- Link to Registration Page -->
            <div class="text-center mt-3">
                <a href="register.php">Don't have an account? Register</a>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date("Y"); ?> MoneyWise. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
