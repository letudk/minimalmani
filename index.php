<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <h1><?php echo esc_html(get_theme_mod('hero_title', 'Minimal Nail Ideas & Press-On Nail Guides')); ?></h1>
        <p class="hero-subtext"><?php echo esc_html(get_theme_mod('hero_subtitle', 'Clean, practical nail inspiration for everyday women.')); ?></p>
        
        <?php if (get_theme_mod('hero_image')) : ?>
        <div class="hero-image">
            <img src="<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('hero_image'), 'full')); ?>"
                alt="<?php echo esc_html(get_theme_mod('hero_title', 'Minimal Nail Ideas & Press-On Nail Guides')); ?>" width="800" height="400"   />
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Featured Categories -->
<section class="categories-section">
    <div class="container">
        <div class="category-grid">
            <?php
            $categories = get_categories(array('number' => 4));
            $icons = array('✨', '📖', '💅', '⭐');
            $i = 0;
            
            foreach ($categories as $category) :
            ?>
            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-box">
                <div class="category-icon"><?php echo $icons[$i % 4]; ?></div>
                <h3><?php echo esc_html($category->name); ?></h3>
            </a>
            <?php
            $i++;
            endforeach;
            ?>
        </div>
    </div>
</section>

<!-- Latest Articles -->
<section class="articles-section">
    <div class="container">
        <h2 class="section-title"><?php _e('Latest Articles', 'minimal-nails'); ?></h2>
        
        <div class="article-list">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
            ?>
            <article class="article-item">
                <?php if (has_post_thumbnail()) : ?>
                <div class="article-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="article-content">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) :
                    ?>
                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="article-category">
                        <?php echo esc_html($categories[0]->name); ?>
                    </a>
                    <?php endif; ?>
                    
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    
                    <p class="article-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                    
                    <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read more →', 'minimal-nails'); ?></a>
                </div>
            </article>
            <?php
                endwhile;
                
                // Pagination
                the_posts_pagination(array(
                    'mid_size'  => 2,
                    'prev_text' => __('← Previous', 'minimal-nails'),
                    'next_text' => __('Next →', 'minimal-nails'),
                ));
                
            else :
                echo '<p>' . __('No posts found.', 'minimal-nails') . '</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Trust Statement -->
<section class="trust-section">
    <div class="container">
        <div class="trust-content">
            "<?php echo esc_html(get_theme_mod('trust_statement', 'Researched, tested, and curated for everyday use')); ?>"
        </div>
    </div>
</section>

<?php get_footer(); ?>