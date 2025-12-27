<?php
/**
 * Product Carousel Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// Enqueue Slick Carousel
function minimal_nails_enqueue_product_carousel_assets() {
    wp_enqueue_style('slick-carousel', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1');
    wp_enqueue_style('slick-theme', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array('slick-carousel'), '1.8.1');
    wp_enqueue_script('slick-carousel', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);
}
add_action('wp_enqueue_scripts', 'minimal_nails_enqueue_product_carousel_assets');

// Product Carousel Shortcode
function minimal_nails_product_carousel_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'number' => 6,
        'title' => __('Featured Products', 'minimal-nails'),
    ), $atts);
    
    $args = array(
        'posts_per_page' => intval($atts['number']),
        'meta_query'     => array(
            array(
                'key'     => '_amazon_product_url',
                'compare' => 'EXISTS'
            )
        )
    );
    
    if (!empty($atts['category'])) {
        $args['category_name'] = sanitize_text_field($atts['category']);
    }
    
    $products = get_posts($args);
    
    if (empty($products)) {
        return '';
    }
    
    ob_start();
    ?>
    <section class="product-carousel-section">
        <?php if (!empty($atts['title'])) : ?>
        <div class="container">
            <h2 class="section-title"><?php echo esc_html($atts['title']); ?></h2>
        </div>
        <?php endif; ?>
        <div class="container">
            <div class="product-carousel">
                <?php foreach ($products as $product) : 
                    setup_postdata($product);
                    $product_id = $product->ID;
                    
                    $product_title = get_post_meta($product_id, '_amazon_product_title', true);
                    $product_url = get_post_meta($product_id, '_amazon_product_url', true);
                    $product_image = get_post_meta($product_id, '_amazon_product_image', true);
                    $product_price = get_post_meta($product_id, '_amazon_product_price', true);
                    $product_rating = get_post_meta($product_id, '_amazon_product_rating', true);
                    
                    if (empty($product_title) || empty($product_url)) {
                        continue;
                    }
                    
                    $track_url = add_query_arg(array(
                        'ref' => 'carousel_product_' . $product_id,
                        'post' => $product_id
                    ), $product_url);
                ?>
                <div class="carousel-product-item">
                    <?php if ($product_image) : ?>
                    <div class="carousel-product-image">
                        <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank">
                            <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="carousel-product-content">
                        <h3 class="carousel-product-title">
                            <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank">
                                <?php echo esc_html($product_title); ?>
                            </a>
                        </h3>
                        
                        <?php if ($product_rating) : ?>
                        <div class="carousel-product-rating">
                            <?php
                            $rating = floatval($product_rating);
                            $full_stars = floor($rating);
                            for ($i = 0; $i < 5; $i++) {
                                if ($i < $full_stars) {
                                    echo '<span class="star filled">★</span>';
                                } else {
                                    echo '<span class="star">★</span>';
                                }
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($product_price) : ?>
                        <div class="carousel-product-price"><?php echo esc_html($product_price); ?></div>
                        <?php endif; ?>
                        
                        <a href="<?php echo esc_url($track_url); ?>" class="carousel-product-button" rel="nofollow sponsored" target="_blank">
                            <?php _e('Shop Now', 'minimal-nails'); ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <script>
    jQuery(document).ready(function($) {
        $('.product-carousel').slick({
            dots: true,
            infinite: true,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
    </script>
    <?php
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('product_carousel', 'minimal_nails_product_carousel_shortcode');

