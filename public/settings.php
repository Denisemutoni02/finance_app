<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user settings from session
$dark_mode = isset($_SESSION['dark_mode']) ? $_SESSION['dark_mode'] : 0;
$email_notifications = isset($_SESSION['email_notifications']) ? $_SESSION['email_notifications'] : 1;
$language = isset($_SESSION['language']) ? $_SESSION['language'] : 'en';

// Set email variable with a fallback (if not provided in session)
$email = isset($_SESSION['email']) ? $_SESSION['email'] : 'Not provided';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        /* Logo Styles */
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
            color: #ffce56; /* Accent color */
        }

        /* General Styles for the logo's container */
        .logo-container {
            text-align: center; /* Ensures the logo is centered */
            margin-bottom: 20px;
        }

        .settings-wrapper {
            margin-top: 10px; /* Space adjustment to compensate for the logo */
            max-width: 800px; /* Set a max-width for the settings container */
            margin: 0 auto; /* Center the settings container horizontally */
            padding: 20px;
            text-align: center; /* Center-align text */
        }

        .settings-wrapper .profile-overview,
        .settings-wrapper .setting-item {
            text-align: center; /* Center the content of profile overview and settings items */
        }

        .dark-mode .logo .logo-text {
            color: white;
        }

        /* Adjust form elements to center-align */
        .setting-item {
            margin-bottom: 15px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: green;
            color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: none;
        }

        /* Dark Mode Styles */
        .dark-mode {
            background: #121212;
            color: white;
        }

        .dark-mode .settings-wrapper {
            background: #1e1e1e;
        }

        .dark-mode .btn {
            background: #444;
        }
    </style>
</head>
<body class="<?php echo $dark_mode ? 'dark-mode' : ''; ?>">

<!-- Logo Section -->
<div class="logo-container">
    <div class="logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" fill="#ffce56" stroke="#333" stroke-width="5"></circle>
            <path d="M70 50c0-12-9-20-20-20s-20 8-20 20c0 4 2 8 5 10h-5c-1 0-2 1-2 2v6h44v-6c0-1-1-2-2-2h-5c3-2 5-6 5-10z" fill="#fff" stroke="#333" stroke-width="3"></path>
            <rect x="45" y="30" width="10" height="5" fill="#333" rx="2"></rect>
        </svg>
        <div class="logo-text">Money<span>Wise</span></div>
    </div>
</div>

<!-- Settings Wrapper inside a container -->
<div class="container">
    <div class="settings-wrapper">
        <nav class="breadcrumb">
            <a href="dashboard.php">Home</a> &gt; <a href="settings.php">Settings</a>
        </nav>

        <div class="profile-overview">
            <img src="path_to_profile_picture.jpg" alt="Profile Picture" class="profile-pic">
            <h2><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
            <p>Email: <?php echo htmlspecialchars($email); ?></p> <!-- Safely display email -->
        </div>

        <h2>Update Settings</h2>

        <!-- Dark Mode Toggle -->
        <div class="setting-item">
            <label for="dark_mode">Dark Mode</label>
            <button id="darkModeToggle" class="btn btn-primary">
                <?php echo $dark_mode ? 'Disable Dark Mode' : 'Enable Dark Mode'; ?>
            </button>
        </div>

        <!-- Language Preferences -->
        <div class="setting-item">
            <label for="language">Language</label>
            <select id="language" name="language">
                <option value="en" <?php echo $language === 'en' ? 'selected' : ''; ?>>English</option>
                <option value="es" <?php echo $language === 'es' ? 'selected' : ''; ?>>Español</option>
                <option value="fr" <?php echo $language === 'fr' ? 'selected' : ''; ?>>Français</option>
            </select>
        </div>

        <!-- Email Notifications -->
        <div class="setting-item">
            <label for="email_notifications">Email Notifications</label>
            <input type="checkbox" id="email_notifications" name="email_notifications" <?php echo $email_notifications ? 'checked' : ''; ?>>
        </div>

        <!-- Reset Settings Button -->
        <div class="setting-item">
            <button id="resetSettings" class="btn btn-danger">Reset to Default</button>
        </div>

        <!-- Success Toast -->
        <div id="toast" class="toast">Settings updated successfully!</div>
    </div>
</div>

<script>
// Your existing JavaScript logic remains the same
</script>

</body>
</html>
