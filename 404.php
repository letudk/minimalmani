<?php get_header(); ?>

<div class="error-404-page">
    <!-- 404 Hero -->
    <section class="error-hero">
        <div class="container">
            <div class="error-content">
                <div class="error-number">404</div>
                <h1 class="error-title"><?php _e('Page Not Found', 'minimal-nails'); ?></h1>
                <p class="error-description">
                    <?php _e('Sorry, the page you\'re looking for doesn\'t exist or has been moved.', 'minimal-nails'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="error-home-btn">
                    <?php _e('← Back to Homepage', 'minimal-nails'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section class="error-search">
        <div class="container">
            <h2 class="search-title"><?php _e('Try Searching', 'minimal-nails'); ?></h2>
            <div class="search-form-wrapper">
                <?php get_search_form(); ?>
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="error-categories">
        <div class="container">
            <h2 class="section-title"><?php _e('Browse Popular Categories', 'minimal-nails'); ?></h2>
            <div class="category-grid">
                <?php
                $categories = get_categories(array(
                    'number' => 4,
                    'orderby' => 'count',
                    'order' => 'DESC',
                ));
                
                $icons = array('✨', '💅', '📖', '⭐');
                $i = 0;
                
                foreach ($categories as $category) :
                ?>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-box">
                    <div class="category-icon"><?php echo $icons[$i % 4]; ?></div>
                    <h3><?php echo esc_html($category->name); ?></h3>
                    <span class="category-count">
                        <?php printf(_n('%s article', '%s articles', $category->count, 'minimal-nails'), $category->count); ?>
                    </span>
                </a>
                <?php
                $i++;
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- Recent Posts -->
    <section class="error-recent">
        <div class="container">
            <h2 class="section-title"><?php _e('Recent Articles', 'minimal-nails'); ?></h2>
            <div class="recent-grid">
                <?php
                $recent_posts = new WP_Query(array(
                    'posts_per_page' => 6,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                
                if ($recent_posts->have_posts()) :
                    while ($recent_posts->have_posts()) : $recent_posts->the_post();
                ?>
                <article class="recent-item">
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="recent-thumbnail">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="recent-content">
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) :
                        ?>
                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="recent-category">
                            <?php echo esc_html($categories[0]->name); ?>
                        </a>
                        <?php endif; ?>
                        
                        <h3 class="recent-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                    </div>
                </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Help Section -->
    <section class="error-help">
        <div class="container">
            <div class="help-box">
                <h2><?php _e('Still Can\'t Find What You\'re Looking For?', 'minimal-nails'); ?></h2>
                <p><?php _e('Get in touch with us and we\'ll help you find the perfect nail inspiration.', 'minimal-nails'); ?></p>
                <?php if (get_theme_mod('contact_email')) : ?>
                <a href="mailto:<?php echo esc_attr(get_theme_mod('contact_email')); ?>" class="help-cta">
                    <?php _e('Contact Us', 'minimal-nails'); ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>
