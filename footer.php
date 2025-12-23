<footer class="site-footer">
    <div class="container">
        <nav class="footer-navigation">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'container'      => false,
                'fallback_cb'    => false,
            ));
            ?>
        </nav>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All content researched and curated for everyday use.', 'minimal-nails'); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

