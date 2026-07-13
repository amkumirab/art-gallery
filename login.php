<?php
/**
 * login.php - User login page
 *
 * Chapter 5 (forms), Chapter 12 ($_POST, $_SESSION),
 * Chapter 13 (User class: OOP).
 */
require_once __DIR__ . '/includes/helpers.php';

if (is_logged_in()) {
    redirect(BASE_URL . '/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = new User();
    if ($user->login($username, $password)) {
        set_flash('success', 'Welcome back, ' . sanitize($_SESSION['username']) . '!');
        redirect(BASE_URL . '/index.php');
    } else {
        $error = 'Invalid username or password.';
    }
}

$page_title = 'Login';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <div class="auth-card">
        <h1>Welcome Back</h1>
        <p class="subtitle">Login to access your favorites and reviews.</p>

        <?php if ($error): ?>
            <div class="flash flash-error"><?= sanitize($error) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/login.php" novalidate>
            <fieldset>
                <legend>Login</legend>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           required autofocus placeholder="Your username">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           required placeholder="Your password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </fieldset>
        </form>

        <p class="auth-switch">
            New here? <a href="<?= BASE_URL ?>/register.php">Create an account</a>
        </p>
        <p class="auth-switch text-muted" style="font-size:0.8rem;">
            Demo user: <strong>demo</strong> / <strong>demo123</strong>
        </p>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
