<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="header-content">
            <div class="site-branding">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title">
                        <?php bloginfo('name'); ?>
                    </a>
                    <?php
                }
                ?>
            </div>
            

            
            <nav class="main-navigation" id="main-nav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
                ?>
            </nav>
            <div class="header-actions">
                <button class="search-toggle-btn" id="search-toggle" aria-label="Search">
                    <span class="search-icon">🔍</span>
                </button>
                <button class="mobile-menu-btn" id="mobile-menu-toggle" aria-label="Menu">☰</button>
            </div>
        </div>
    </div>
    
    <!-- Search Overlay -->
    <div class="search-overlay" id="search-overlay">
        <div class="search-overlay-content">
            <button class="search-close" id="search-close" aria-label="Close search">×</button>
            <?php get_search_form(); ?>
        </div>
    </div>
</header>

