<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/styles.css"> <!-- Link to your CSS file -->
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
            font-size: 24px;
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

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .footer {
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            background-color: #f1f1f1;
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
        <h1>Register</h1>
        
        <!-- Error message placeholder -->
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </p>
        <?php endif; ?>

        <form action="../src/register_action.php" method="POST">
            <!-- Username -->
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required placeholder="Enter your username">
            
            <!-- Email -->
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">
            
            <!-- Password -->
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter a strong password">
            
            <!-- Confirm Password -->
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Re-enter your password">
            
            <!-- Submit Button -->
            <button type="submit" class="btn-primary">Register</button>

            <!-- Link to Login Page -->
            <div class="text-center mt-3">
                <a href="login.php">Already have an account? Login</a>
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
