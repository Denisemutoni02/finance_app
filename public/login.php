<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/styles.css">
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
            color: #ffce56;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Transition Styles */
        body {
            margin: 0;
            overflow-x: hidden;
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateX(0);
            animation: slide-in 0.8s ease forwards;
        }

        @keyframes slide-in {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes slide-out {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(-100%);
            }
        }

        .fade-out {
            animation: slide-out 0.8s ease forwards;
        }
    </style>
    <script>
        function transitionToRegister() {
            const container = document.querySelector('.container');
            container.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = 'register.php';
            }, 800);
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" fill="#ffce56" stroke="#333" stroke-width="5"></circle>
                <path d="M70 50c0-12-9-20-20-20s-20 8-20 20c0 4 2 8 5 10h-5c-1 0-2 1-2 2v6h44v-6c0-1-1-2-2-2h-5c3-2 5-6 5-10z" fill="#fff" stroke="#333" stroke-width="3"></path>
                <rect x="45" y="30" width="10" height="5" fill="#333" rx="2"></rect>
            </svg>
            <div class="logo-text">Money<span>Wise</span></div>
        </div>
    </nav>

    <div class="container">
        <h1>Login</h1>
        <form action="../src/login_action.php" method="POST">
            <label for="username">Username or Email:</label>
            <input type="text" id="username" name="username" required placeholder="Enter your username or email">
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required placeholder="Enter your password">
            
            <button type="submit" class="btn-primary">Login</button>

            <div class="text-center mt-3">
                <a href="javascript:void(0);" onclick="transitionToRegister()">Don't have an account? Register</a>
            </div>
        </form>
    </div>
</body>
</html>
