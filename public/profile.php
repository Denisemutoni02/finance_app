<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Simulate fetching user data (replace with actual database queries)
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : ''; 
$profile_picture = isset($_SESSION['profile_picture']) ? $_SESSION['profile_picture'] : 'uploads/default_profile.jpg'; 
$full_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'John Doe';
$created_at = isset($_SESSION['created_at']) ? $_SESSION['created_at'] : '2022-01-01';
$last_login = isset($_SESSION['last_login']) ? $_SESSION['last_login'] : '2022-01-01';
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
$address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
$bio = isset($_SESSION['bio']) ? $_SESSION['bio'] : '';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
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

        /* Navigation Bar */
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
    <h2>Update Profile</h2>

    <form method="POST" action="profile.php" enctype="multipart/form-data">
        <div class="profile-picture-container">
            <img id="profile_picture_preview" src="<?php echo $profile_picture; ?>" alt="Profile Picture" class="profile-pic">
            <input type="file" id="profile_picture_input" name="profile_picture" accept="image/*">
            <button type="button" id="crop_picture_button">Crop Picture</button>
        </div>

        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo $username; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo $email; ?>" required><br>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" value="<?php echo $phone; ?>"><br>

        <label for="address">Address:</label>
        <textarea name="address"><?php echo $address; ?></textarea><br>

        <label for="bio">Bio:</label>
        <textarea name="bio"><?php echo $bio; ?></textarea><br>

        <p><strong>Account Created:</strong> <?php echo $created_at; ?></p>
        <p><strong>Last Login:</strong> <?php echo $last_login; ?></p><br>

        <h3>Change Password</h3>
        <label for="old_password">Old Password:</label>
        <input type="password" name="old_password"><br>

        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" onkeyup="checkPasswordStrength()" required><br>
        <div id="password-strength-status"></div>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" name="confirm_password"><br>

        <button type="submit">Update Profile</button>
    </form>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    const input = document.getElementById('profile_picture_input');
    const preview = document.getElementById('profile_picture_preview');
    const cropper = new Cropper(preview, { aspectRatio: 1 });

    input.addEventListener('change', () => {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = () => {
            preview.src = reader.result;
            cropper.replace(reader.result);
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('crop_picture_button').addEventListener('click', () => {
        const croppedDataUrl = cropper.getCroppedCanvas().toDataURL();
        preview.src = croppedDataUrl;
    });

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
