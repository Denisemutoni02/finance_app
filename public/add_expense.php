<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
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

        /* Form Container */
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Dark Mode Support */
        .dark-mode {
            background-color: #121212;
            color: white;
        }

        .dark-mode .container {
            background-color: #1e1e1e;
            color: white;
        }

        .dark-mode button {
            background-color: #444;
        }

    </style>
</head>
<body class="<?php echo $dark_mode ? 'dark-mode' : ''; ?>">

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
        <h1>Add Expense</h1>
        <form action="../src/add_expense.php" method="POST">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="Tuition">Tuition</option>
                <option value="Food">Food</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Transportation">Transportation</option>
                <option value="Other">Other</option>
            </select>

            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="0.01" required>

            <label for="expense_date">Date:</label>
            <input type="date" id="expense_date" name="expense_date" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <button type="submit">Add Expense</button>
        </form>
    </div>
</body>
</html>
