/**
 * favorite.js - Toggle favorite via AJAX on the artwork detail page
 *
 * Chapter 8 (conditionals, events)
 * Chapter 10 (jQuery: AJAX POST, CSS class toggle, animation)
 */
document.addEventListener("DOMContentLoaded", function() {
    "use strict";

    var $btn = $('#fav-btn');

    // If we're not on a page with the favorite button, do nothing
    if ($btn.length === 0) return;

    $btn.on('click', function () {
        var artworkId = $(this).data('artwork-id');

        // AJAX POST to the favorite API (Chapter 10: $.post)
        $.post(BASE_URL + '/api/favorite.php', { artwork_id: artworkId })
            .done(function (response) {
                if (response.status === 'added') {
                    // Change button to "favorited" state
                    $btn.addClass('is-favorited');
                    $btn.find('.fav-text').text('Saved to Favorites');
                    showToast('Added to your favorites!');
                } else if (response.status === 'removed') {
                    // Change button back to normal state
                    $btn.removeClass('is-favorited');
                    $btn.find('.fav-text').text('Add to Favorites');
                    showToast('Removed from favorites.');
                }
            })
            .fail(function () {
                showToast('Something went wrong. Please try again.');
            });
    });

    // ---- Show a toast notification (Chapter 10: animation) ----
    function showToast(message) {
        // Remove any existing toast
        $('.toast').remove();

        // Create and show the toast (Chapter 10: dynamic DOM)
        var $toast = $('<div class="toast">' + message + '</div>');
        $('body').append($toast);

        // Animate in (Chapter 10: CSS transition handles the slide)
        setTimeout(function () {
            $toast.addClass('show');
        }, 10);

        // Auto-dismiss after 3 seconds
        setTimeout(function () {
            $toast.removeClass('show');
            setTimeout(function () {
                $toast.remove();
            }, 500);
        }, 3000);
    }
});
