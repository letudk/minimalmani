<?php
/**
 * Affiliate Disclosure Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================
// AFFILIATE DISCLOSURE BANNER
// ============================================

// Add Affiliate Disclosure customizer setting
function minimal_nails_affiliate_disclosure_customizer($wp_customize) {
    $wp_customize->add_section('minimal_nails_affiliate', array(
        'title'    => __('Affiliate Disclosure', 'minimal-nails'),
        'priority' => 60,
    ));
    
    $wp_customize->add_setting('affiliate_disclosure_text', array(
        'default'           => 'This post contains affiliate links. We may earn a commission if you make a purchase through our links, at no additional cost to you.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('affiliate_disclosure_text', array(
        'label'       => __('Disclosure Text', 'minimal-nails'),
        'section'     => 'minimal_nails_affiliate',
        'type'        => 'textarea',
    ));
    
    $wp_customize->add_setting('show_affiliate_disclosure', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('show_affiliate_disclosure', array(
        'label'   => __('Show Affiliate Disclosure', 'minimal-nails'),
        'section' => 'minimal_nails_affiliate',
        'type'    => 'checkbox',
    ));
    
    $wp_customize->add_setting('affiliate_disclosure_position', array(
        'default'           => 'top',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('affiliate_disclosure_position', array(
        'label'   => __('Disclosure Position', 'minimal-nails'),
        'section' => 'minimal_nails_affiliate',
        'type'    => 'select',
        'choices' => array(
            'top'    => __('Top of Post', 'minimal-nails'),
            'bottom' => __('Bottom of Post', 'minimal-nails'),
            'both'   => __('Both', 'minimal-nails'),
        ),
    ));
}
add_action('customize_register', 'minimal_nails_affiliate_disclosure_customizer');

// Check if post has affiliate links
function minimal_nails_post_has_affiliate_links($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Check for Amazon product meta
    $product_url = get_post_meta($post_id, '_amazon_product_url', true);
    if (!empty($product_url)) {
        return true;
    }
    
    // Check for best products
    $best_products = get_post_meta($post_id, '_best_products', true);
    if (!empty($best_products) && is_array($best_products)) {
        foreach ($best_products as $product) {
            if (!empty($product['url'])) {
                return true;
            }
        }
    }
    
    // Check content for Amazon URLs
    $content = get_post_field('post_content', $post_id);
    if (preg_match('/amazon\.(com|co\.uk|de|fr|it|es|ca|com\.au)/i', $content)) {
        return true;
    }
    
    return false;
}

// Display Affiliate Disclosure
function minimal_nails_display_affiliate_disclosure($position = 'top') {
    $show = get_theme_mod('show_affiliate_disclosure', true);
    if (!$show) {
        return '';
    }
    
    $disclosure_position = get_theme_mod('affiliate_disclosure_position', 'top');
    
    if ($disclosure_position !== $position && $disclosure_position !== 'both') {
        return '';
    }
    
    if (!is_single() || !minimal_nails_post_has_affiliate_links()) {
        return '';
    }
    
    $text = get_theme_mod('affiliate_disclosure_text', 'This post contains affiliate links. We may earn a commission if you make a purchase through our links, at no additional cost to you.');
    
    ob_start();
    ?>
    <div class="affiliate-disclosure">
        <div class="disclosure-icon">ℹ️</div>
        <div class="disclosure-text">
            <strong><?php _e('Affiliate Disclosure:', 'minimal-nails'); ?></strong>
            <?php echo esc_html($text); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Add disclosure to content
function minimal_nails_add_disclosure_to_content($content) {
    if (!is_single() || !minimal_nails_post_has_affiliate_links()) {
        return $content;
    }
    
    $disclosure = minimal_nails_display_affiliate_disclosure('top');
    
    if ($disclosure) {
        $content = $disclosure . $content;
    }
    
    // Add to bottom
    $bottom_disclosure = minimal_nails_display_affiliate_disclosure('bottom');
    if ($bottom_disclosure && get_theme_mod('affiliate_disclosure_position', 'top') === 'both') {
        $content = $content . $bottom_disclosure;
    }
    
    return $content;
}
add_filter('the_content', 'minimal_nails_add_disclosure_to_content', 5);

