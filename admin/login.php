<?php
/**
 * admin/login.php - Separate admin login page
 * Checks that user role = 'admin' (Chapter 12: $_SESSION, Chapter 13: User class)
 */
require_once __DIR__ . '/../includes/helpers.php';

// If already admin, go to dashboard
if (is_admin()) {
    redirect(BASE_URL . '/admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = new User();
    if ($user->login($username, $password)) {
        // Check if the user is an admin
        if ($_SESSION['role'] === 'admin') {
            redirect(BASE_URL . '/admin/index.php');
        } else {
            // Not admin — log them out and show error
            $user->logout();
            $error = 'Access denied. Admin privileges required.';
        }
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login &middot; <?= sanitize(SITE_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forms.css">
</head>
<body style="background:#f5f5f3; display:flex; align-items:center; justify-content:center; min-height:100vh;">
    <div class="auth-card">
        <h1>Admin Login</h1>
        <p class="subtitle">Access the administration panel.</p>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= sanitize($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/admin/login.php" novalidate>
            <fieldset>
                <legend>Administrator</legend>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus placeholder="Admin username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Admin password">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </fieldset>
        </form>
        <p class="auth-switch">
            <a href="<?= BASE_URL ?>/index.php">&larr; Back to site</a>
        </p>
    </div>
</body>
</html>
