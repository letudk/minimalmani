<?php
/**
 * Custom Widgets
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================
// RELATED PRODUCTS WIDGET
// ============================================

class Minimal_Nails_Related_Products_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'minimal_nails_related_products',
            __('Related Products', 'minimal-nails'),
            array('description' => __('Display related Amazon products', 'minimal-nails'))
        );
    }
    
    public function widget($args, $instance) {
        if (!is_single()) {
            return;
        }
        
        $title = !empty($instance['title']) ? $instance['title'] : __('Related Products', 'minimal-nails');
        $number = !empty($instance['number']) ? absint($instance['number']) : 3;
        
        // Get posts with products from same category
        $categories = wp_get_post_categories(get_the_ID());
        if (empty($categories)) {
            return;
        }
        
        $related_posts = get_posts(array(
            'category__in'   => $categories,
            'post__not_in'   => array(get_the_ID()),
            'posts_per_page' => $number,
            'meta_query'     => array(
                array(
                    'key'     => '_amazon_product_url',
                    'compare' => 'EXISTS'
                )
            )
        ));
        
        if (empty($related_posts)) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . esc_html($title) . $args['after_title'];
        }
        
        echo '<div class="related-products-widget">';
        foreach ($related_posts as $post) {
            setup_postdata($post);
            
            $product_title = get_post_meta($post->ID, '_amazon_product_title', true);
            $product_url = get_post_meta($post->ID, '_amazon_product_url', true);
            $product_image = get_post_meta($post->ID, '_amazon_product_image', true);
            $product_price = get_post_meta($post->ID, '_amazon_product_price', true);
            
            if (empty($product_title) || empty($product_url)) {
                continue;
            }
            
            $track_url = add_query_arg(array(
                'ref' => 'widget_product_' . $post->ID,
                'post' => $post->ID
            ), $product_url);
            ?>
            <div class="widget-product-item">
                <?php if ($product_image) : ?>
                <div class="widget-product-image">
                    <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank">
                        <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                    </a>
                </div>
                <?php endif; ?>
                <div class="widget-product-info">
                    <h4 class="widget-product-title">
                        <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank">
                            <?php echo esc_html($product_title); ?>
                        </a>
                    </h4>
                    <?php if ($product_price) : ?>
                    <div class="widget-product-price"><?php echo esc_html($product_price); ?></div>
                    <?php endif; ?>
                    <a href="<?php echo esc_url($track_url); ?>" class="widget-product-link" rel="nofollow sponsored" target="_blank">
                        <?php _e('View on Amazon', 'minimal-nails'); ?>
                    </a>
                </div>
            </div>
            <?php
        }
        echo '</div>';
        
        wp_reset_postdata();
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Related Products', 'minimal-nails');
        $number = !empty($instance['number']) ? absint($instance['number']) : 3;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'minimal-nails'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php _e('Number of products:', 'minimal-nails'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 3;
        return $instance;
    }
}

function minimal_nails_register_widgets() {
    register_widget('Minimal_Nails_Related_Products_Widget');
}
add_action('widgets_init', 'minimal_nails_register_widgets');

