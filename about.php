<?php
/**
 * about.php - About the gallery (Professional & Realistic Version)
 */
$page_title = 'About Us';
$active_nav = 'about';
include __DIR__ . '/includes/helpers.php';
include __DIR__ . '/includes/header.php';
?>

<div class=\"container\">
    <div class=\"section-title\"><h1>About Aurelia Art Gallery</h1></div>

    <div style=\"max-width:800px; margin:0 auto;\">
        <section style=\"margin-bottom:var(--space-lg);\">
            <h2>Our Vision</h2>
            <p>
                Founded with a passion for classical and contemporary masterpieces, Aurelia Art Gallery serves as a premier digital and physical exhibition space. We are committed to bridging the gap between world-renowned artists and passionate collectors, providing an immersive platform where fine art lives forever.
            </p>
            <p>
                Our curated collections range from high-Renaissance timeless paintings to modern avant-garde movements. Every piece exhibited in our gallery undergoes a rigorous authentication and preservation process to ensure the highest artistic standards.
            </p>
        </section>

        <section style=\"margin-bottom:var(--space-lg);\">
            <h2>Visitor Information & Legal Location</h2>
            <p>We welcome art enthusiasts, scholars, and collectors to visit our physical headquarters located in the historic heart of Sicily:</p>
            
            <table class=\"admin-table\" style=\"margin-top: var(--space-sm); background: var(--color-surface);\">
                <tr>
                    <td style=\"padding: var(--space-sm); font-weight: 600; width: 30%;\">Address</td>
                    <td style=\"padding: var(--space-sm);\">Piazza del Duomo, 98122 Messina ME, Italy</td>
                </tr>
                <tr>
                    <td style=\"padding: var(--space-sm); font-weight: 600;\">Opening Hours</td>
                    <td style=\"padding: var(--space-sm);\">Tuesday – Sunday: 10:00 AM – 7:00 PM (Closed on Mondays)</td>
                </tr>
                <tr>
                    <td style=\"padding: var(--space-sm); font-weight: 600;\">Admission</td>
                    <td style=\"padding: var(--space-sm);\">Free entry for general exhibitions. Guided tours require pre-booking.</td>
                </tr>
            </table>
        </section>

        <section style=\"margin-bottom:var(--space-lg);\">
            <h2>Contact & Inquiries</h2>
            <p>For artwork acquisitions, exhibition submissions, or private viewing appointments, please reach out to our administration desk:</p>
            
            <div style=\"display: flex; flex-wrap: wrap; gap: var(--space-sm); margin-top: var(--space-sm);\">
                <article style=\"flex:1 1 220px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius); padding:var(--space-sm);\">
                    <h3>&#128231; Email Us</h3>
                    <p class=\"text-muted\" style=\"font-size:0.95rem; margin-top:0.5rem;\">
                        <strong>General:</strong> info@aureliaartgallery.com<br>
                        <strong>Curator:</strong> curatorial@aureliaartgallery.com
                    </p>
                </article>
                <article style=\"flex:1 1 220px; background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius); padding:var(--space-sm);\">
                    <h3>&#128222; Call Us</h3>
                    <p class=\"text-muted\" style=\"font-size:0.95rem; margin-top:0.5rem;\">
                        <strong>Front Desk:</strong> +39 090 123 4567<br>
                        <strong>Fax Office:</strong> +39 090 123 4568
                    </p>
                </article>
            </div>
        </section>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>