/**
 * quiz.js - Gamification quiz on artwork detail page
 *
 * Chapter 8 (JavaScript: arrays, objects, for loop, conditionals, functions)
 * Chapter 10 (jQuery: DOM creation, events, fadeIn animation)
 */
document.addEventListener("DOMContentLoaded", function() {
    "use strict";

    var $container = $('#quiz-container');
    var $result    = $('#quiz-result');

    // If we're not on a page with the quiz, do nothing
    if ($container.length === 0) return;

    // ---- Quiz questions (Chapter 8: array of objects) ----
    // General art knowledge questions shown on every artwork page.
    var questions = [
        {
            q: 'Which material is "Oil on canvas" referring to?',
            options: ['A type of frame', 'The paint and surface used', 'A style of painting', 'A famous gallery'],
            answer: 1
        },
        {
            q: 'The Renaissance period began in which country?',
            options: ['France', 'Germany', 'Italy', 'Spain'],
            answer: 2
        },
        {
            q: 'What does "medium" mean in the context of a painting?',
            options: ['The size of the painting', 'The art style used', 'The materials used to create it', 'The price range'],
            answer: 2
        },
        {
            q: 'Impressionism was a movement focused mainly on:',
            options: ['Religious themes', 'Light and color', 'Dark shadows', 'Abstract shapes'],
            answer: 1
        },
        {
            q: 'A "still life" painting typically depicts:',
            options: ['Landscapes', 'Portraits', 'Inanimate objects like fruit or flowers', 'Battle scenes'],
            answer: 2
        }
    ];

    // ---- State ----
    var currentQuestion = 0;
    var score = 0;
    var userAnswers = [];

    // ---- Render a question (Chapter 8: function, Chapter 10: DOM) ----
    function renderQuestion() {
        $result.empty();

        // Quiz complete?
        if (currentQuestion >= questions.length) {
            renderResult();
            return;
        }

        var qObj = questions[currentQuestion];
        var html = '<div class="quiz-question">';
        html += '<h3 style="margin-bottom:var(--space-sm);">Question ' + (currentQuestion + 1) + ' of ' + questions.length + '</h3>';
        html += '<p style="font-size:1.05rem; margin-bottom:var(--space-sm);">' + escapeHtml(qObj.q) + '</p>';
        html += '<div class="quiz-options">';

        // Loop through options (Chapter 8: for loop)
        var i;
        for (i = 0; i < qObj.options.length; i++) {
            html += '<button class="btn quiz-option" data-index="' + i + '" style="display:block; margin-bottom:0.5rem; width:100%; text-align:left;">';
            html += escapeHtml(qObj.options[i]);
            html += '</button>';
        }

        html += '</div>';
        html += '<p class="text-muted" style="margin-top:0.5rem; font-size:0.85rem;">Score: ' + score + ' / ' + questions.length + '</p>';
        html += '</div>';

        $container.hide().html(html).fadeIn(300);

        // Attach click handlers to options (Chapter 10: .on)
        $container.find('.quiz-option').on('click', function () {
            handleAnswer(parseInt($(this).data('index'), 10));
        });
    }

    // ---- Handle the user's answer (Chapter 8: conditional) ----
    function handleAnswer(selectedIndex) {
        var correctIndex = questions[currentQuestion].answer;
        userAnswers.push(selectedIndex);

        // Disable all options to prevent re-clicking
        $container.find('.quiz-option').prop('disabled', true).css('opacity', 0.7);

        // Highlight correct and incorrect answers (Chapter 8: conditional)
        var $options = $container.find('.quiz-option');
        $options.eq(correctIndex).css({
            'background-color': '#27ae60',
            'color': '#fff',
            'border-color': '#27ae60'
        });

        if (selectedIndex === correctIndex) {
            score++;
            $result.html('<p style="color:var(--color-success); margin-top:0.5rem;">&#10004; Correct!</p>');
        } else {
            $options.eq(selectedIndex).css({
                'background-color': '#c0392b',
                'color': '#fff',
                'border-color': '#c0392b'
            });
            $result.html('<p style="color:var(--color-danger); margin-top:0.5rem;">&#10006; Not quite. The correct answer is highlighted in green.</p>');
        }

        $result.hide().fadeIn(300);

        // Move to the next question after a delay
        setTimeout(function () {
            currentQuestion++;
            renderQuestion();
        }, 1500);
    }

    // ---- Show final result (Chapter 10: animation) ----
    function renderResult() {
        var percentage = Math.round((score / questions.length) * 100);
        var message;
        var badge;

        // Pick a message based on score (Chapter 8: if/else if/else)
        if (percentage === 100) {
            message = 'Perfect! You are a true art expert!';
            badge = '&#127942;'; // trophy
        } else if (percentage >= 60) {
            message = 'Great job! You know your art well.';
            badge = '&#11088;'; // star
        } else {
            message = 'Keep exploring art to improve your knowledge!';
            badge = '&#128218;'; // books
        }

        var html = '<div class="quiz-complete text-center" style="padding:var(--space-md);">';
        html += '<div style="font-size:3rem;">' + badge + '</div>';
        html += '<h2 style="margin:var(--space-xs) 0;">Quiz Complete!</h2>';
        html += '<p style="font-size:1.3rem; color:var(--color-accent-d); margin-bottom:var(--space-xs);">';
        html += 'Your score: ' + score + ' / ' + questions.length + ' (' + percentage + '%)';
        html += '</p>';
        html += '<p class="text-muted">' + message + '</p>';
        html += '<button class="btn btn-primary mt-sm" id="quiz-restart">Take Quiz Again</button>';
        html += '</div>';

        $container.hide().html(html).fadeIn(300);

        // Allow restart (Chapter 10: event)
        $('#quiz-restart').on('click', function () {
            currentQuestion = 0;
            score = 0;
            userAnswers = [];
            renderQuestion();
        });
    }

    // ---- Helper: escape HTML ----
    function escapeHtml(str) {
        if (str === null) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ---- Start the quiz on page load ----
    renderQuestion();
});
