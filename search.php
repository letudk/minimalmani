<?php get_header(); ?>

<!-- Search Hero -->
<section class="search-hero">
    <div class="container">
        <div class="search-hero-content">
            <h1 class="search-hero-title">
                <?php
                if (have_posts()) {
                    printf(__('Search Results for: %s', 'minimal-nails'), '<span class="search-query">' . get_search_query() . '</span>');
                } else {
                    printf(__('No Results for: %s', 'minimal-nails'), '<span class="search-query">' . get_search_query() . '</span>');
                }
                ?>
            </h1>
            <?php if (have_posts()) : ?>
            <p class="search-results-count">
                <?php
                global $wp_query;
                printf(
                    _n('%d result found', '%d results found', $wp_query->found_posts, 'minimal-nails'),
                    $wp_query->found_posts
                );
                ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Search Form Section -->
<section class="search-form-section">
    <div class="container">
        <div class="search-form-wrapper">
            <?php get_search_form(); ?>
        </div>
    </div>
</section>

<!-- Search Results -->
<?php if (have_posts()) : ?>
<section class="search-results">
    <div class="container">
        <div class="article-list">
            <?php while (have_posts()) : the_post(); ?>
            <article class="article-item" id="post-<?php the_ID(); ?>">
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
                    
                    <h2 class="article-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <div class="article-meta">
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                        <span class="meta-separator">·</span>
                        <span class="reading-time">
                            <?php echo minimal_nails_reading_time(); ?> <?php _e('min read', 'minimal-nails'); ?>
                        </span>
                    </div>
                    
                    <p class="article-excerpt">
                        <?php
                        // Highlight search terms in excerpt
                        $excerpt = get_the_excerpt();
                        $search_query = get_search_query();
                        if (!empty($search_query)) {
                            $excerpt = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark class="search-highlight">$1</mark>', $excerpt);
                        }
                        echo $excerpt;
                        ?>
                    </p>
                    
                    <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read more →', 'minimal-nails'); ?></a>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination -->
        <div class="archive-pagination">
            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('← Previous', 'minimal-nails'),
                'next_text' => __('Next →', 'minimal-nails'),
            ));
            ?>
        </div>
    </div>
</section>
<?php else : ?>
<!-- No Results -->
<section class="no-search-results">
    <div class="container">
        <div class="no-results-content">
            <div class="no-results-icon">🔍</div>
            <h2 class="no-results-title"><?php _e('No Results Found', 'minimal-nails'); ?></h2>
            <p class="no-results-text">
                <?php _e('Sorry, we couldn\'t find any posts matching your search. Please try different keywords.', 'minimal-nails'); ?>
            </p>
            
            <!-- Popular Categories -->
            <?php
            $categories = get_categories(array(
                'number' => 6,
                'orderby' => 'count',
                'order' => 'DESC',
                'hide_empty' => true,
            ));
            
            if (!empty($categories)) :
            ?>
            <div class="popular-categories">
                <h3 class="popular-categories-title"><?php _e('Popular Categories', 'minimal-nails'); ?></h3>
                <div class="popular-categories-list">
                    <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-tag">
                        <?php echo esc_html($category->name); ?>
                        <span class="category-count">(<?php echo $category->count; ?>)</span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Recent Posts -->
            <?php
            $recent_posts = new WP_Query(array(
                'posts_per_page' => 6,
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
            ));
            
            if ($recent_posts->have_posts()) :
            ?>
            <div class="recent-posts-section">
                <h3 class="recent-posts-title"><?php _e('Recent Posts', 'minimal-nails'); ?></h3>
                <div class="recent-posts-grid">
                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                    <article class="recent-post-item">
                        <?php if (has_post_thumbnail()) : ?>
                        <div class="recent-post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <div class="recent-post-content">
                            <?php
                            $post_cats = get_the_category();
                            if (!empty($post_cats)) :
                            ?>
                            <a href="<?php echo esc_url(get_category_link($post_cats[0]->term_id)); ?>" class="recent-post-category">
                                <?php echo esc_html($post_cats[0]->name); ?>
                            </a>
                            <?php endif; ?>
                            
                            <h4 class="recent-post-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                        </div>
                    </article>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

