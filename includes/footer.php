<?php
/**
 * footer.php - Shared site footer + closing HTML + global JS
 * Chapter 3 (semantic <footer>), Chapter 10 (jQuery CDN).
 */
?>
</main><!-- /.main-content -->

<footer class="site-footer">
    <div class="container">
    <p>&copy; <?= date('Y') ?> <?= sanitize(SITE_NAME) ?>. All rights reserved.</p>        <p><a href="<?= BASE_URL ?>/admin/login.php">Admin</a></p>
    </div>
</footer>

<!-- Expose PHP BASE_URL to JavaScript -->
<script>var BASE_URL = "<?= BASE_URL ?>";</script>

<!-- jQuery (Chapter 10: include via CDN with local failsafe) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    window.jQuery || document.write('<script src="<?= BASE_URL ?>/assets/js/jquery-3.7.1.min.js"><\/script>');
</script>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
