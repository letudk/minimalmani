<?php
/**
 * You May Also Like Products Section
 */

if (!defined('ABSPATH')) {
    exit;
}

// Display "You May Also Like" Products after related posts
function minimal_nails_display_you_may_also_like() {
    if (!is_single()) {
        return '';
    }
    
    $post_id = get_the_ID();
    
    // Get products from same category
    $categories = wp_get_post_categories($post_id);
    if (empty($categories)) {
        return '';
    }
    
    $products = get_posts(array(
        'category__in'   => $categories,
        'post__not_in'   => array($post_id),
        'posts_per_page' => 6,
        'meta_query'     => array(
            array(
                'key'     => '_amazon_product_url',
                'compare' => 'EXISTS'
            )
        )
    ));
    
    if (empty($products)) {
        return '';
    }
    
    ob_start();
    ?>
    <section class="you-may-also-like">
        <div class="container">
            <h2 class="section-title"><?php _e('You May Also Like', 'minimal-nails'); ?></h2>
            <div class="also-like-grid">
                <?php foreach ($products as $product) : 
                    setup_postdata($product);
                    $product_id = $product->ID;
                    
                    $product_title = get_post_meta($product_id, '_amazon_product_title', true);
                    $product_url = get_post_meta($product_id, '_amazon_product_url', true);
                    $product_image = get_post_meta($product_id, '_amazon_product_image', true);
                    $product_price = get_post_meta($product_id, '_amazon_product_price', true);
                    
                    if (empty($product_title) || empty($product_url)) {
                        continue;
                    }
                    
                    $track_url = add_query_arg(array(
                        'ref' => 'also_like_' . $product_id,
                        'post' => $product_id
                    ), $product_url);
                ?>
                <div class="also-like-item">
                    <?php if ($product_image) : ?>
                    <div class="also-like-image">
                        <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank">
                            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="also-like-content">
                        <h3 class="also-like-title">
                            <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank">
                                <?php echo esc_html($product_title); ?>
                            </a>
                        </h3>
                        <?php if ($product_price) : ?>
                        <div class="also-like-price"><?php echo esc_html($product_price); ?></div>
                        <?php endif; ?>
                        <a href="<?php echo esc_url($track_url); ?>" class="also-like-button" rel="nofollow sponsored" target="_blank">
                            <?php _e('View Product', 'minimal-nails'); ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}

