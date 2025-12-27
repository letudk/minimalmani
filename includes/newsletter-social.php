<?php
/**
 * Newsletter and Social Share Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================
// EMAIL NEWSLETTER
// ============================================

// Newsletter Signup Shortcode
function minimal_nails_newsletter_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => __('Get Nail Ideas Delivered', 'minimal-nails'),
        'description' => __('Subscribe to get the latest nail design ideas and tips', 'minimal-nails'),
        'placeholder' => __('Enter your email', 'minimal-nails'),
        'button' => __('Subscribe', 'minimal-nails'),
    ), $atts);
    
    ob_start();
    ?>
    <div class="newsletter-signup">
        <h3 class="newsletter-title"><?php echo esc_html($atts['title']); ?></h3>
        <?php if ($atts['description']) : ?>
        <p class="newsletter-description"><?php echo esc_html($atts['description']); ?></p>
        <?php endif; ?>
        <form class="newsletter-form" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" method="post">
            <input type="hidden" name="action" value="minimal_nails_subscribe_newsletter" />
            <?php wp_nonce_field('newsletter_subscribe', 'newsletter_nonce'); ?>
            <div class="newsletter-input-group">
                <input type="email" name="email" placeholder="<?php echo esc_attr($atts['placeholder']); ?>" required />
                <button type="submit" class="newsletter-button"><?php echo esc_html($atts['button']); ?></button>
            </div>
            <p class="newsletter-privacy"><?php _e('We respect your privacy. Unsubscribe at any time.', 'minimal-nails'); ?></p>
        </form>
        <div class="newsletter-message" style="display: none;"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('newsletter', 'minimal_nails_newsletter_shortcode');

// Newsletter AJAX Handler
function minimal_nails_subscribe_newsletter() {
    check_ajax_referer('newsletter_subscribe', 'newsletter_nonce');
    
    $email = sanitize_email($_POST['email']);
    
    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Invalid email address', 'minimal-nails')));
    }
    
    // Store email in options (you can integrate with MailChimp, ConvertKit, etc.)
    $subscribers = get_option('minimal_nails_newsletter_subscribers', array());
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('minimal_nails_newsletter_subscribers', $subscribers);
    }
    
    wp_send_json_success(array('message' => __('Thank you for subscribing!', 'minimal-nails')));
}
add_action('wp_ajax_minimal_nails_subscribe_newsletter', 'minimal_nails_subscribe_newsletter');
add_action('wp_ajax_nopriv_minimal_nails_subscribe_newsletter', 'minimal_nails_subscribe_newsletter');

// Newsletter form JavaScript
function minimal_nails_newsletter_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var messageDiv = form.siblings('.newsletter-message');
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        messageDiv.removeClass('error').addClass('success').text(response.data.message).show();
                        form[0].reset();
                    } else {
                        messageDiv.removeClass('success').addClass('error').text(response.data.message).show();
                    }
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'minimal_nails_newsletter_script');

// ============================================
// SOCIAL SHARE
// ============================================

// Social Share Buttons Shortcode
function minimal_nails_social_share_shortcode($atts) {
    $atts = shortcode_atts(array(
        'platforms' => 'facebook,twitter,pinterest,email',
        'title' => __('Share this post', 'minimal-nails'),
    ), $atts);
    
    if (!is_single()) {
        return '';
    }
    
    $platforms = array_map('trim', explode(',', $atts['platforms']));
    $permalink = get_permalink();
    $title = get_the_title();
    $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    
    // Get product URL if exists
    $product_url = get_post_meta(get_the_ID(), '_amazon_product_url', true);
    $share_text = $title;
    if ($product_url) {
        $share_text .= ' - ' . $product_url;
    }
    
    ob_start();
    ?>
    <div class="social-share-buttons">
        <?php if (!empty($atts['title'])) : ?>
        <h4 class="share-title"><?php echo esc_html($atts['title']); ?></h4>
        <?php endif; ?>
        <div class="share-buttons-list">
            <?php if (in_array('facebook', $platforms)) : ?>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($permalink); ?>" target="_blank" class="share-button share-facebook" rel="noopener">
                <span class="share-icon">📘</span> Facebook
            </a>
            <?php endif; ?>
            
            <?php if (in_array('twitter', $platforms)) : ?>
            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($permalink); ?>&text=<?php echo urlencode($title); ?>" target="_blank" class="share-button share-twitter" rel="noopener">
                <span class="share-icon">🐦</span> Twitter
            </a>
            <?php endif; ?>
            
            <?php if (in_array('pinterest', $platforms)) : ?>
            <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode($permalink); ?>&media=<?php echo urlencode($image); ?>&description=<?php echo urlencode($title); ?>" target="_blank" class="share-button share-pinterest" rel="noopener">
                <span class="share-icon">📌</span> Pinterest
            </a>
            <?php endif; ?>
            
            <?php if (in_array('email', $platforms)) : ?>
            <a href="mailto:?subject=<?php echo urlencode($title); ?>&body=<?php echo urlencode($permalink); ?>" class="share-button share-email">
                <span class="share-icon">✉️</span> Email
            </a>
            <?php endif; ?>
            
            <?php if (in_array('whatsapp', $platforms)) : ?>
            <a href="https://wa.me/?text=<?php echo urlencode($share_text . ' ' . $permalink); ?>" target="_blank" class="share-button share-whatsapp" rel="noopener">
                <span class="share-icon">💬</span> WhatsApp
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('social_share', 'minimal_nails_social_share_shortcode');

// Auto add social share after post content
function minimal_nails_add_social_share_to_content($content) {
    if (!is_single()) {
        return $content;
    }
    
    $show_share = get_theme_mod('show_social_share', true);
    if (!$show_share) {
        return $content;
    }
    
    $share_buttons = minimal_nails_social_share_shortcode(array());
    return $content . $share_buttons;
}
add_filter('the_content', 'minimal_nails_add_social_share_to_content', 20);

// Add social share customizer setting
function minimal_nails_social_share_customizer($wp_customize) {
    $wp_customize->add_setting('show_social_share', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    
    $wp_customize->add_control('show_social_share', array(
        'label'   => __('Show Social Share Buttons', 'minimal-nails'),
        'section' => 'minimal_nails_trust',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'minimal_nails_social_share_customizer');

