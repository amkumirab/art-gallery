/**
 * main.js - Global JavaScript (Chapter 8: fundamentals)
 * Loaded on every page via footer.php.
 */

// ---- Page-ready wrapper (Chapter 10: $(document).ready) ----
$(function () {

    // ---- Registration form: client-side password match check (Chapter 8: conditionals) ----
    var registerForm = $('#register-form, form[action*="register"]');
    if (registerForm.length) {
        registerForm.on('submit', function (e) {
            var pass  = $('#password').val();
            var confirm = $('#confirm_password').val();
            if (pass !== confirm) {
                e.preventDefault();
                alert('Passwords do not match. Please re-enter.');
            }
        });
    }

    // ---- Auto-dismiss flash messages after 5 seconds (Chapter 10: setTimeout) ----
    $('.flash').each(function () {
        var flash = $(this);
        window.setTimeout(function () {
            flash.fadeOut(500, function () { flash.remove(); });
        }, 5000);
    });

});
