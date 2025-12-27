<?php get_header(); ?>

<article class="single-post-wrapper">
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Post Header -->
    <header class="post-header">
        <div class="container-narrow">
            <?php
            $categories = get_the_category();
            if (!empty($categories)) :
            ?>
            <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="post-category">
                <?php echo esc_html($categories[0]->name); ?>
            </a>
            <?php endif; ?>
            
            <h1 class="post-title"><?php the_title(); ?></h1>
            
            <div class="post-meta">
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date('F j, Y'); ?>
                </time>
                <span class="meta-separator">·</span>
                <span class="reading-time">
                    <?php echo minimal_nails_reading_time(); ?> <?php _e('min read', 'minimal-nails'); ?>
                </span>
            </div>
            
            <!-- Table of Contents -->
            <?php 
            // Get full content to check for headings and generate TOC
            $full_content = get_the_content();
            $full_content = apply_filters('the_content', $full_content);
            
            if (minimal_nails_has_headings($full_content)) : 
            ?>
            <div class="table-of-contents-wrapper">
                <?php echo minimal_nails_generate_toc($full_content); ?>
            </div>
            <?php endif; ?>
        </div>
    </header>

<section class="post-intro">
    <div class="container-narrow">
        <?php
        // Get raw content and extract intro
        $raw_content = get_the_content();
        $paragraphs = explode('</p>', $raw_content);
        $intro = '';

        if (!empty($paragraphs[0]) && trim(strip_tags($paragraphs[0]))) {
            $intro = $paragraphs[0] . '</p>';
        }

        echo wpautop($intro);
        ?>
    </div>
</section>

    <!-- Post Content -->
    <div class="post-content">
        <div class="container-narrow">
            <?php
            // Get full content and apply filters FIRST to add IDs to all H2 headings
            $full_processed_content = get_the_content();
            $full_processed_content = apply_filters('the_content', $full_processed_content);
            
            // Now split the processed content for display
            $processed_paragraphs = explode('</p>', $full_processed_content);
            $remaining_content = '';
            
            // Skip first 3 paragraphs (used for intro)
            for ($i = 3; $i < count($processed_paragraphs); $i++) {
                $remaining_content .= $processed_paragraphs[$i];
            }
            
            // Add AdSense after 2nd H2 (optional) - this works with IDs already added
            $remaining_content = minimal_nails_insert_adsense($remaining_content);
            
            echo $remaining_content;
            ?>
        </div>
    </div>

    <!-- Best Products Section -->
    <?php echo minimal_nails_display_best_products(); ?>
    
    <!-- Related Posts -->
    <?php
    $related_posts = minimal_nails_get_related_posts(get_the_ID(), 6);
    if (!empty($related_posts)) :
    ?>
    <section class="related-posts">
        <div class="container">
            <h2 class="section-title"><?php _e('Related Articles', 'minimal-nails'); ?></h2>
            
            <div class="related-grid">
                <?php foreach ($related_posts as $related) : ?>
                <article class="related-item">
                    <?php if (has_post_thumbnail($related->ID)) : ?>
                    <div class="related-thumbnail">
                        <a href="<?php echo get_permalink($related->ID); ?>">
                            <?php echo get_the_post_thumbnail($related->ID, 'medium'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="related-content">
                        <?php
                        $post_cats = get_the_category($related->ID);
                        if (!empty($post_cats)) :
                        ?>
                        <a href="<?php echo esc_url(get_category_link($post_cats[0]->term_id)); ?>" class="related-category">
                            <?php echo esc_html($post_cats[0]->name); ?>
                        </a>
                        <?php endif; ?>
                        
                        <h3 class="related-title">
                            <a href="<?php echo get_permalink($related->ID); ?>">
                                <?php echo get_the_title($related->ID); ?>
                            </a>
                        </h3>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- You May Also Like Products -->
    <?php echo minimal_nails_display_you_may_also_like(); ?>

    <!-- Conclusion / CTA -->
    <section class="post-conclusion">
        <div class="container-narrow">
            <div class="conclusion-box">
                <p class="conclusion-text">
                    <?php _e('Looking for more inspiration?', 'minimal-nails'); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/category/minimal-nail-ideas/')); ?>" class="conclusion-cta">
                    <?php _e('Explore more minimal nail ideas →', 'minimal-nails'); ?>
                </a>
            </div>
        </div>
    </section>

    <?php endwhile; ?>
</article>

<?php get_footer(); ?>