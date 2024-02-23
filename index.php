<?php
// Start the session to check if the user is already logged in
session_start();

// Check if the user is already logged in
if (isset($_SESSION['authenticated']) && $_SESSION['authenticated']) {
    // If the user is logged in, redirect them to the home page
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/auth.css">
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>

        <?php
        // Check if registration was successful
        if (isset($_GET['registration'])) {
            $message = 'Registration successful! Please log in.';
            echo "<p class='success-message'>$message</p>";
        }
        ?>

        <?php
        // Check if there are any errors passed via URL parameters
        if (isset($_GET['error'])) {
            $errorMessage = '';
            // Assign appropriate error message based on the error code
            switch ($_GET['error']) {
                case 'invalid_input':
                    $errorMessage = 'Invalid input';
                    break;
                case 'invalid_credentials':
                    $errorMessage = 'Invalid credentials';
                    break;
                default:
                    $errorMessage = 'An error occurred.';
                    break;
            }
            // Display the error message
            echo "<p class='error-message'>$errorMessage</p>";
        }
        ?>

        <form action="server/authenticate.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <div class="switch-links">
            <span>No account?</span>
            <a href="/register.php">Register</a>
        </div>
    </div>
</body>

</html>