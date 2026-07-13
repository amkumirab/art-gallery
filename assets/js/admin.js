/**
 * admin.js - Admin dashboard interactions
 *
 * Chapter 8 (JavaScript: for loop, arithmetic, conditionals)
 * Chapter 10 (jQuery: selectors, event .on, animation)
 */
$(function () {
    "use strict";

    // ---- Delete confirmation (Chapter 10: event handler) ----
    // Any link with class "delete-confirm" will prompt before navigating.
    $('.delete-confirm').on('click', function (e) {
        var message = 'Are you sure you want to delete this item?\n\nThis action cannot be undone.';
        if (!confirm(message)) {
            e.preventDefault();
        }
    });

    // ---- Animated stat counters on the dashboard (Chapter 8: loop + arithmetic) ----
    // Each .stat-number has a data-count attribute with its target value.
    $('.stat-number').each(function () {
        var $this   = $(this);
        var target  = parseInt($this.data('count'), 10) || 0;
        var current = 0;
        var step    = Math.max(1, Math.ceil(target / 30)); // finish in ~30 steps

        // Use an interval to count up (Chapter 8: setInterval)
        var timer = setInterval(function () {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            $this.text(current);
        }, 30);
    });

    // ---- Auto-dismiss flash messages after 5 seconds (Chapter 10: setTimeout + fadeOut) ----
    $('.flash').each(function () {
        var $flash = $(this);
        setTimeout(function () {
            $flash.fadeOut(500, function () {
                $flash.remove();
            });
        }, 5000);
    });

    // ---- Live image preview in artwork form (Chapter 10: change event) ----
    $('#image').on('change', function () {
        var file = this.files[0];
        if (!file) return;

        // Use FileReader to preview (Chapter 8: object + callback)
        var reader = new FileReader();
        reader.onload = function (e) {
            // Update or create the preview image
            var $preview = $('.upload-preview img');
            if ($preview.length === 0) {
                $('.upload-preview').append('<img alt="Preview">');
                $preview = $('.upload-preview img');
            }
            $preview.attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    });
});
