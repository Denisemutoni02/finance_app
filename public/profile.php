<?php 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Simulate fetching user data (replace with actual database queries)
$username = $_SESSION['username'] ?? '';
$email = $_SESSION['email'] ?? ''; 
$profile_picture = $_SESSION['profile_picture'] ?? 'uploads/default_profile.jpg'; // Default image if none exists
$phone = $_SESSION['phone'] ?? '';
$address = $_SESSION['address'] ?? '';
$bio = $_SESSION['bio'] ?? '';

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated user info
    $new_username = htmlspecialchars($_POST['username']);
    $new_email = htmlspecialchars($_POST['email']);
    $new_phone = htmlspecialchars($_POST['phone']);
    $new_address = htmlspecialchars($_POST['address']);
    $new_bio = htmlspecialchars($_POST['bio']);

    // Update session data (replace with database update queries)
    $_SESSION['username'] = $new_username;
    $_SESSION['email'] = $new_email;
    $_SESSION['phone'] = $new_phone;
    $_SESSION['address'] = $new_address;
    $_SESSION['bio'] = $new_bio;

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file = $_FILES['profile_picture'];
        $file_name = uniqid() . "_" . basename($file['name']); 
        $file_tmp_name = $file['tmp_name'];
        $file_type = $file['type'];

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $upload_dir = 'uploads/';
        $upload_file = $upload_dir . $file_name;

        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($file_tmp_name, $upload_file)) {
                $_SESSION['profile_picture'] = $upload_file;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../assets/styles3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
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

        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Profile Picture */
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }

        .profile-picture-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Main Content */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .profile-pic {
                width: 120px;
                height: 120px;
            }
        }
    </style>
</head>
<body>

<!-- Navigation -->
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

<!-- Main Content -->
<div class="container">
    <h2>Profile</h2>

    <form method="POST" action="profile.php" enctype="multipart/form-data">
      

        <!-- Form Fields -->
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">

        <label for="address">Address:</label>
        <textarea name="address"><?php echo htmlspecialchars($address); ?></textarea>

        <label for="bio">Bio:</label>
        <textarea name="bio"><?php echo htmlspecialchars($bio); ?></textarea>

        <!-- Password Change Section -->
        <h3>Change Password</h3>
        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password">

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" onkeyup="checkPasswordStrength()" required>
        <div id="password-strength-status"></div>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password">

        <button type="submit">Update Profile</button>
    </form>
</div>

<!-- Scripts -->
<script>
    function previewProfilePicture(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('profile_picture_preview');
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    function checkPasswordStrength() {
        const password = document.getElementById('new_password').value;
        const status = document.getElementById('password-strength-status');
        const regexWeak = /^(?=.*[a-z]).{6,}$/;
        const regexMedium = /^(?=.*[a-z])(?=.*[A-Z]).{6,}$/;
        const regexStrong = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/;

        if (password.match(regexStrong)) {
            status.textContent = "Strong";
            status.style.color = "green";
        } else if (password.match(regexMedium)) {
            status.textContent = "Medium";
            status.style.color = "orange";
        } else if (password.match(regexWeak)) {
            status.textContent = "Weak";
            status.style.color = "red";
        } else {
            status.textContent = "";
        }
    }
</script>

</body>
</html>
