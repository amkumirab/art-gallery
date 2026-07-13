<?php
/**
 * register.php - User registration page
 *
 * Chapter 5 (HTML forms: fieldset, label-for, input types, validation)
 * Chapter 12 ($_POST superglobal, $_SESSION for flash messages)
 * Chapter 13 (User class: OOP)
 */
require_once __DIR__ . '/includes/helpers.php';

// If already logged in, go home
if (is_logged_in()) {
    redirect(BASE_URL . '/index.php');
}

$errors = [];
$old    = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect $_POST data (Chapter 12)
    $old['username'] = trim($_POST['username'] ?? '');
    $old['email']    = trim($_POST['email'] ?? '');

    $user = new User();
    $result = $user->register($_POST);

    if ($result['success']) {
        // Auto-login the new user
        $user->login($old['username'], $_POST['password']);
        set_flash('success', 'Welcome to ' . SITE_NAME . '! Your account has been created.');
        redirect(BASE_URL . '/index.php');
    } else {
        $errors = $result['errors'];
    }
}

$page_title = 'Register';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <div class="auth-card">
        <h1>Create Account</h1>
        <p class="subtitle">Join the gallery to save favorites and leave reviews.</p>

        <?php if (!empty($errors)): ?>
            <div class="flash flash-error">
                <ul style="margin:0; padding-left:1.2rem;">
                    <?php foreach ($errors as $e): ?>
                        <li><?= sanitize($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/register.php" novalidate>
            <fieldset>
                <legend>Your Details</legend>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                           value="<?= sanitize($old['username']) ?>"
                           required minlength="3" maxlength="50"
                           placeholder="Choose a username">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email"
                           value="<?= sanitize($old['email']) ?>"
                           required maxlength="100"
                           placeholder="you@example.com">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           required minlength="6"
                           placeholder="At least 6 characters">
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                           required minlength="6"
                           placeholder="Re-enter your password">
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Register</button>
                </div>
            </fieldset>
        </form>

        <p class="auth-switch">
            Already have an account? <a href="<?= BASE_URL ?>/login.php">Login here</a>
        </p>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
