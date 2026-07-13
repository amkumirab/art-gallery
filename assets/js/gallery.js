/**
 * gallery.js - Live search & filter for the gallery page
 *
 * Chapter 8 (JavaScript: functions, objects, arrays, conditionals)
 * Chapter 10 (jQuery: selectors, AJAX $.get, DOM creation, events)
 */
$(function () {
    "use strict";

    // ---- Cache DOM elements (Chapter 10: selectors) ----
    var $grid      = $('#artwork-grid');
    var $search    = $('#search-input');
    var $category  = $('#filter-category');
    var $artist    = $('#filter-artist');
    var $sort      = $('#filter-sort');

    // If we're not on the gallery page, do nothing
    if ($grid.length === 0) return;

    // ---- Timer for debouncing the search input ----
    var debounceTimer;

    // ---- Main function: fetch artworks from the API via AJAX ----
    function loadArtworks() {
        // Build query string from filters (Chapter 8: object + string concat)
        var params = {
            search:      $search.val(),
            category_id: $category.val(),
            artist_id:   $artist.val(),
            sort:        $sort.val()
        };

        // Show loading state
        $grid.html('<div class="loading">Loading artworks...</div>');

        // AJAX GET request to the PHP API (Chapter 10: $.get)
        $.get(BASE_URL + '/api/artworks.php', params)
            .done(function (data) {
                renderArtworks(data);
            })
            .fail(function () {
                $grid.html('<p class="empty-state">Error loading artworks. Please try again.</p>');
            });
    }

    // ---- Render artwork cards into the grid (Chapter 8: loop, Chapter 10: DOM creation) ----
    function renderArtworks(artworks) {
    // حل مشکل String بودن ریسپانس دیتای جی‌کوئری
    if (typeof artworks === 'string') {
        try {
            artworks = JSON.parse(artworks);
        } catch (e) {
            console.error("Error parsing artworks JSON:", e);
        }
    }

    // Empty state
    if (!artworks || artworks.length === 0) {
        $grid.html(
            '<div class="empty-state">' +
            '<h3>No artworks found</h3>' +
            '<p>Try adjusting your search or filters.</p>' +
            '</div>'
        );
        return;
    }
    

        // Clear the grid
        $grid.empty();

        // Loop through artworks and build each card (Chapter 8: for loop)
        var i;
        for (i = 0; i < artworks.length; i++) {
            var art = artworks[i];

            // Build the artist name
            var artistName = (art.artist_first || '') + ' ' + (art.artist_last || '');
            artistName = artistName.trim() || 'Unknown Artist';

            // Build price string
            var priceStr = art.price ? '$' + Number(art.price).toLocaleString() : '—';

            // Build featured badge (Chapter 8: conditional)
            var badge = art.is_featured ? '<span class="featured-badge">Featured</span>' : '';

            // Build year
            var yearStr = art.year ? art.year : '';

            // Create the card element (Chapter 10: dynamic DOM creation)
            var card = $(
                '<article class="artwork-card" style="display:none;">' +
                '  <a href="' + BASE_URL + '/artwork.php?id=' + art.id + '" class="card-image">' +
                '    ' + badge +
                '    <img src="' + BASE_URL + '/assets/uploads/' + escapeHtml(art.image_filename) + '" alt="' + escapeHtml(art.title) + '">' +
                '  </a>' +
                '  <div class="card-body">' +
                '    <h3><a href="' + BASE_URL + '/artwork.php?id=' + art.id + '">' + escapeHtml(art.title) + '</a></h3>' +
                '    <p class="artist-name">' + escapeHtml(artistName) + '</p>' +
                '    <div class="card-meta">' +
                '      <span class="text-muted">' + yearStr + '</span>' +
                '      <span class="price">' + priceStr + '</span>' +
                '    </div>' +
                '  </div>' +
                '</article>'
            );

            // Set a fallback image if the real one is missing (Chapter 10: attr, onerror)
            card.find('img').on('error', function () {
                $(this).attr('src', BASE_URL + '/assets/uploads/no-image.jpg');
            });

            // Append and fade in (Chapter 10: append, fadeIn)
            $grid.append(card);
            card.fadeIn(300);
        }
    }

    // ---- Helper: escape HTML to prevent XSS in JS-generated content ----
    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ---- Event listeners (Chapter 10: .on) ----

    // Search input: debounce so we don't fire on every keystroke
    $search.on('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(loadArtworks, 350);
    });

    // Dropdown filters: fire immediately on change
    $category.on('change', loadArtworks);
    $artist.on('change', loadArtworks);
    $sort.on('change', loadArtworks);

    // ---- Initial load: fetch artworks on page ready ----
    loadArtworks();
});
