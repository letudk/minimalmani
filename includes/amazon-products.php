<?php
/**
 * Amazon Affiliate Products Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================
// 1. AMAZON PRODUCT BOX - Custom Meta Fields & Shortcode
// ============================================

// Add Amazon Product meta box
function minimal_nails_add_product_meta_box() {
    add_meta_box(
        'amazon_product_meta',
        __('Amazon Product Information', 'minimal-nails'),
        'minimal_nails_product_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'minimal_nails_add_product_meta_box');

// Product meta box callback
function minimal_nails_product_meta_box_callback($post) {
    wp_nonce_field('minimal_nails_save_product_meta', 'minimal_nails_product_meta_nonce');
    
    $product_title = get_post_meta($post->ID, '_amazon_product_title', true);
    $product_asin = get_post_meta($post->ID, '_amazon_product_asin', true);
    $product_url = get_post_meta($post->ID, '_amazon_product_url', true);
    $product_price = get_post_meta($post->ID, '_amazon_product_price', true);
    $product_rating = get_post_meta($post->ID, '_amazon_product_rating', true);
    $product_image = get_post_meta($post->ID, '_amazon_product_image', true);
    $product_prime = get_post_meta($post->ID, '_amazon_product_prime', true);
    $product_description = get_post_meta($post->ID, '_amazon_product_description', true);
    $price_updated = get_post_meta($post->ID, '_amazon_price_updated', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="amazon_product_title"><?php _e('Product Title', 'minimal-nails'); ?></label></th>
            <td><input type="text" id="amazon_product_title" name="amazon_product_title" value="<?php echo esc_attr($product_title); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="amazon_product_asin"><?php _e('ASIN', 'minimal-nails'); ?></label></th>
            <td><input type="text" id="amazon_product_asin" name="amazon_product_asin" value="<?php echo esc_attr($product_asin); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="amazon_product_url"><?php _e('Amazon Affiliate URL', 'minimal-nails'); ?></label></th>
            <td><input type="url" id="amazon_product_url" name="amazon_product_url" value="<?php echo esc_url($product_url); ?>" class="large-text" /></td>
        </tr>
        <tr>
            <th><label for="amazon_product_price"><?php _e('Price', 'minimal-nails'); ?></label></th>
            <td><input type="text" id="amazon_product_price" name="amazon_product_price" value="<?php echo esc_attr($product_price); ?>" placeholder="e.g., $19.99" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="amazon_product_rating"><?php _e('Rating (1-5)', 'minimal-nails'); ?></label></th>
            <td><input type="number" id="amazon_product_rating" name="amazon_product_rating" value="<?php echo esc_attr($product_rating); ?>" min="0" max="5" step="0.1" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="amazon_product_image"><?php _e('Product Image URL', 'minimal-nails'); ?></label></th>
            <td>
                <input type="url" id="amazon_product_image" name="amazon_product_image" value="<?php echo esc_url($product_image); ?>" class="large-text" />
                <button type="button" class="button upload_image_button"><?php _e('Upload Image', 'minimal-nails'); ?></button>
            </td>
        </tr>
        <tr>
            <th><label for="amazon_product_prime"><?php _e('Prime Available', 'minimal-nails'); ?></label></th>
            <td><input type="checkbox" id="amazon_product_prime" name="amazon_product_prime" value="1" <?php checked($product_prime, '1'); ?> /></td>
        </tr>
        <tr>
            <th><label for="amazon_product_description"><?php _e('Product Description', 'minimal-nails'); ?></label></th>
            <td><textarea id="amazon_product_description" name="amazon_product_description" rows="3" class="large-text"><?php echo esc_textarea($product_description); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="amazon_price_updated"><?php _e('Price Last Updated', 'minimal-nails'); ?></label></th>
            <td>
                <input type="date" id="amazon_price_updated" name="amazon_price_updated" value="<?php echo esc_attr($price_updated); ?>" />
                <button type="button" class="button update_price_date"><?php _e('Set to Today', 'minimal-nails'); ?></button>
            </td>
        </tr>
    </table>
    <p><strong><?php _e('Shortcode:', 'minimal-nails'); ?></strong> <code>[amazon_product]</code> <?php _e('or', 'minimal-nails'); ?> <code>[amazon_product id="<?php echo $post->ID; ?>"]</code></p>
    <script>
    jQuery(document).ready(function($) {
        $('.update_price_date').on('click', function() {
            var today = new Date().toISOString().split('T')[0];
            $('#amazon_price_updated').val(today);
        });
    });
    </script>
    <?php
}

// Save product meta
function minimal_nails_save_product_meta($post_id) {
    if (!isset($_POST['minimal_nails_product_meta_nonce']) || !wp_verify_nonce($_POST['minimal_nails_product_meta_nonce'], 'minimal_nails_save_product_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $fields = array(
        'amazon_product_title',
        'amazon_product_asin',
        'amazon_product_url',
        'amazon_product_price',
        'amazon_product_rating',
        'amazon_product_image',
        'amazon_product_description',
        'amazon_price_updated'
    );
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    $prime = isset($_POST['amazon_product_prime']) ? '1' : '0';
    update_post_meta($post_id, '_amazon_product_prime', $prime);
}
add_action('save_post', 'minimal_nails_save_product_meta');

// Amazon Product Box Shortcode
function minimal_nails_amazon_product_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_the_ID(),
    ), $atts);
    
    $post_id = intval($atts['id']);
    
    $product_title = get_post_meta($post_id, '_amazon_product_title', true);
    $product_url = get_post_meta($post_id, '_amazon_product_url', true);
    $product_price = get_post_meta($post_id, '_amazon_product_price', true);
    $product_rating = get_post_meta($post_id, '_amazon_product_rating', true);
    $product_image = get_post_meta($post_id, '_amazon_product_image', true);
    $product_prime = get_post_meta($post_id, '_amazon_product_prime', true);
    $product_description = get_post_meta($post_id, '_amazon_product_description', true);
    $price_updated = get_post_meta($post_id, '_amazon_price_updated', true);
    
    if (empty($product_title) && empty($product_url)) {
        return '';
    }
    
    // Track click
    $track_url = add_query_arg(array(
        'ref' => 'amazon_product_' . $post_id,
        'post' => $post_id
    ), $product_url);
    
    ob_start();
    ?>
    <div class="amazon-product-box" itemscope itemtype="https://schema.org/Product">
        <div class="product-box-inner">
            <?php if ($product_image) : ?>
            <div class="product-image">
                <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank" onclick="minimalNailsTrackClick(<?php echo $post_id; ?>, '<?php echo esc_js($product_url); ?>')">
                    <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" itemprop="image" />
                </a>
            </div>
            <?php endif; ?>
            
            <div class="product-info">
                <h3 class="product-title" itemprop="name"><?php echo esc_html($product_title); ?></h3>
                
                <?php if ($product_rating) : ?>
                <div class="product-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                    <div class="stars">
                        <?php
                        $rating = floatval($product_rating);
                        $full_stars = floor($rating);
                        $half_star = ($rating - $full_stars) >= 0.5;
                        for ($i = 0; $i < 5; $i++) {
                            if ($i < $full_stars) {
                                echo '<span class="star filled">★</span>';
                            } elseif ($i == $full_stars && $half_star) {
                                echo '<span class="star half">★</span>';
                            } else {
                                echo '<span class="star">★</span>';
                            }
                        }
                        ?>
                    </div>
                    <meta itemprop="ratingValue" content="<?php echo esc_attr($rating); ?>" />
                    <meta itemprop="bestRating" content="5" />
                </div>
                <?php endif; ?>
                
                <?php if ($product_description) : ?>
                <p class="product-description" itemprop="description"><?php echo esc_html($product_description); ?></p>
                <?php endif; ?>
                
                <div class="product-footer">
                    <?php if ($product_price) : ?>
                    <div class="product-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                        <span class="price" itemprop="price"><?php echo esc_html($product_price); ?></span>
                        <?php if ($price_updated) : ?>
                        <span class="price-updated"><?php printf(__('Updated %s', 'minimal-nails'), date('M j, Y', strtotime($price_updated))); ?></span>
                        <?php endif; ?>
                        <meta itemprop="priceCurrency" content="USD" />
                        <meta itemprop="availability" content="https://schema.org/InStock" />
                    </div>
                    <?php endif; ?>
                    
                    <a href="<?php echo esc_url($track_url); ?>" class="amazon-button" rel="nofollow sponsored" target="_blank" onclick="minimalNailsTrackClick(<?php echo $post_id; ?>, '<?php echo esc_js($product_url); ?>')">
                        <?php _e('Check Price on Amazon', 'minimal-nails'); ?> <?php if ($product_prime) : ?><span class="prime-badge">Prime</span><?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('amazon_product', 'minimal_nails_amazon_product_shortcode');

// ============================================
// 2. PRODUCT COMPARISON TABLE
// ============================================

function minimal_nails_product_comparison_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'columns' => '3',
    ), $atts);
    
    // Parse table content
    $rows = explode("\n", trim($content));
    $header = array_shift($rows);
    $headers = array_map('trim', explode('|', $header));
    
    ob_start();
    ?>
    <div class="product-comparison-table">
        <table>
            <thead>
                <tr>
                    <?php foreach ($headers as $header) : ?>
                    <th><?php echo esc_html(trim($header, ' *')); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) : 
                    $cells = array_map('trim', explode('|', $row));
                    if (count($cells) == count($headers)) :
                ?>
                <tr>
                    <?php foreach ($cells as $index => $cell) : ?>
                    <td <?php echo $index === 0 ? 'class="product-name"' : ''; ?>>
                        <?php 
                        // Check if cell contains a link
                        if (preg_match('/\[([^\]]+)\]\(([^\)]+)\)/', $cell, $matches)) {
                            echo '<a href="' . esc_url($matches[2]) . '" rel="nofollow sponsored" target="_blank">' . esc_html($matches[1]) . '</a>';
                        } else {
                            echo esc_html($cell);
                        }
                        ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endif; endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('product_comparison', 'minimal_nails_product_comparison_shortcode');

// ============================================
// 3. BEST PRODUCTS SECTION (Multiple Products)
// ============================================

// Add Best Products meta box
function minimal_nails_add_best_products_meta_box() {
    add_meta_box(
        'best_products_meta',
        __('Best Products Section', 'minimal-nails'),
        'minimal_nails_best_products_meta_box_callback',
        'post',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'minimal_nails_add_best_products_meta_box');

function minimal_nails_best_products_meta_box_callback($post) {
    wp_nonce_field('minimal_nails_save_best_products', 'minimal_nails_best_products_nonce');
    
    $best_products = get_post_meta($post->ID, '_best_products', true);
    $best_products_title = get_post_meta($post->ID, '_best_products_title', true);
    $show_best_products = get_post_meta($post->ID, '_show_best_products', true);
    
    if (empty($best_products_title)) {
        $best_products_title = __('Best Products We Recommend', 'minimal-nails');
    }
    
    if (empty($best_products)) {
        $best_products = array();
    }
    ?>
    <p>
        <label>
            <input type="checkbox" name="show_best_products" value="1" <?php checked($show_best_products, '1'); ?> />
            <?php _e('Show Best Products Section', 'minimal-nails'); ?>
        </label>
    </p>
    <p>
        <label for="best_products_title"><?php _e('Section Title:', 'minimal-nails'); ?></label>
        <input type="text" id="best_products_title" name="best_products_title" value="<?php echo esc_attr($best_products_title); ?>" class="large-text" />
    </p>
    <div id="best-products-list">
        <?php
        if (!empty($best_products)) {
            foreach ($best_products as $index => $product) {
                minimal_nails_render_best_product_row($index, $product);
            }
        }
        ?>
    </div>
    <button type="button" class="button add-best-product"><?php _e('Add Product', 'minimal-nails'); ?></button>
    <script type="text/template" id="best-product-template">
        <?php minimal_nails_render_best_product_row('{{INDEX}}', array()); ?>
    </script>
    <script>
    jQuery(document).ready(function($) {
        var index = <?php echo count($best_products); ?>;
        $('.add-best-product').on('click', function() {
            var template = $('#best-product-template').html();
            template = template.replace(/\{\{INDEX\}\}/g, index);
            $('#best-products-list').append(template);
            index++;
        });
        $(document).on('click', '.remove-best-product', function() {
            $(this).closest('.best-product-row').remove();
        });
    });
    </script>
    <?php
}

function minimal_nails_render_best_product_row($index, $product = array()) {
    $product = wp_parse_args($product, array(
        'title' => '',
        'url' => '',
        'image' => '',
        'price' => '',
        'description' => ''
    ));
    ?>
    <div class="best-product-row" style="border: 1px solid #ddd; padding: 15px; margin-bottom: 10px;">
        <button type="button" class="button remove-best-product" style="float: right;"><?php _e('Remove', 'minimal-nails'); ?></button>
        <h4><?php _e('Product', 'minimal-nails'); ?> #<?php echo $index + 1; ?></h4>
        <table class="form-table">
            <tr>
                <th><label><?php _e('Product Title', 'minimal-nails'); ?></label></th>
                <td><input type="text" name="best_products[<?php echo $index; ?>][title]" value="<?php echo esc_attr($product['title']); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label><?php _e('Amazon URL', 'minimal-nails'); ?></label></th>
                <td><input type="url" name="best_products[<?php echo $index; ?>][url]" value="<?php echo esc_url($product['url']); ?>" class="large-text" /></td>
            </tr>
            <tr>
                <th><label><?php _e('Image URL', 'minimal-nails'); ?></label></th>
                <td><input type="url" name="best_products[<?php echo $index; ?>][image]" value="<?php echo esc_url($product['image']); ?>" class="large-text" /></td>
            </tr>
            <tr>
                <th><label><?php _e('Price', 'minimal-nails'); ?></label></th>
                <td><input type="text" name="best_products[<?php echo $index; ?>][price]" value="<?php echo esc_attr($product['price']); ?>" class="regular-text" /></td>
            </tr>
            <tr>
                <th><label><?php _e('Description', 'minimal-nails'); ?></label></th>
                <td><textarea name="best_products[<?php echo $index; ?>][description]" rows="2" class="large-text"><?php echo esc_textarea($product['description']); ?></textarea></td>
            </tr>
        </table>
    </div>
    <?php
}

function minimal_nails_save_best_products_meta($post_id) {
    if (!isset($_POST['minimal_nails_best_products_nonce']) || !wp_verify_nonce($_POST['minimal_nails_best_products_nonce'], 'minimal_nails_save_best_products')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $show = isset($_POST['show_best_products']) ? '1' : '0';
    update_post_meta($post_id, '_show_best_products', $show);
    
    if (isset($_POST['best_products_title'])) {
        update_post_meta($post_id, '_best_products_title', sanitize_text_field($_POST['best_products_title']));
    }
    
    if (isset($_POST['best_products']) && is_array($_POST['best_products'])) {
        $products = array();
        foreach ($_POST['best_products'] as $product) {
            $products[] = array(
                'title' => sanitize_text_field($product['title']),
                'url' => esc_url_raw($product['url']),
                'image' => esc_url_raw($product['image']),
                'price' => sanitize_text_field($product['price']),
                'description' => sanitize_textarea_field($product['description'])
            );
        }
        update_post_meta($post_id, '_best_products', $products);
    } else {
        delete_post_meta($post_id, '_best_products');
    }
}
add_action('save_post', 'minimal_nails_save_best_products_meta');

// Display Best Products Section
function minimal_nails_display_best_products($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $show = get_post_meta($post_id, '_show_best_products', true);
    if ($show != '1') {
        return '';
    }
    
    $products = get_post_meta($post_id, '_best_products', true);
    $title = get_post_meta($post_id, '_best_products_title', true);
    
    if (empty($products) || !is_array($products)) {
        return '';
    }
    
    if (empty($title)) {
        $title = __('Best Products We Recommend', 'minimal-nails');
    }
    
    ob_start();
    ?>
    <section class="best-products-section">
        <div class="container-narrow">
            <h2 class="section-title"><?php echo esc_html($title); ?></h2>
            <div class="best-products-grid">
                <?php foreach ($products as $index => $product) : 
                    if (empty($product['title']) || empty($product['url'])) continue;
                    $track_url = add_query_arg(array(
                        'ref' => 'best_product_' . $post_id . '_' . $index,
                        'post' => $post_id
                    ), $product['url']);
                ?>
                <div class="best-product-item">
                    <?php if (!empty($product['image'])) : ?>
                    <div class="product-image">
                        <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank" onclick="minimalNailsTrackClick(<?php echo $post_id; ?>, '<?php echo esc_js($product['url']); ?>')">
                            <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['title']); ?>" />
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="product-content">
                        <h3 class="product-title">
                            <a href="<?php echo esc_url($track_url); ?>" rel="nofollow sponsored" target="_blank" onclick="minimalNailsTrackClick(<?php echo $post_id; ?>, '<?php echo esc_js($product['url']); ?>')">
                                <?php echo esc_html($product['title']); ?>
                            </a>
                        </h3>
                        
                        <?php if (!empty($product['description'])) : ?>
                        <p class="product-description"><?php echo esc_html($product['description']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($product['price'])) : ?>
                        <div class="product-price"><?php echo esc_html($product['price']); ?></div>
                        <?php endif; ?>
                        
                        <a href="<?php echo esc_url($track_url); ?>" class="amazon-button-small" rel="nofollow sponsored" target="_blank" onclick="minimalNailsTrackClick(<?php echo $post_id; ?>, '<?php echo esc_js($product['url']); ?>')">
                            <?php _e('View on Amazon', 'minimal-nails'); ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// ============================================
// 4. CLICK TRACKING
// ============================================

function minimal_nails_track_click() {
    if (!isset($_GET['ref']) || !isset($_GET['post'])) {
        return;
    }
    
    $post_id = intval($_GET['post']);
    $ref = sanitize_text_field($_GET['ref']);
    
    // Get current clicks
    $clicks = get_post_meta($post_id, '_affiliate_clicks', true);
    if (!is_array($clicks)) {
        $clicks = array();
    }
    
    // Increment click count for this reference
    if (!isset($clicks[$ref])) {
        $clicks[$ref] = 0;
    }
    $clicks[$ref]++;
    
    // Update meta
    update_post_meta($post_id, '_affiliate_clicks', $clicks);
    
    // Get the actual URL
    $product_url = '';
    if (strpos($ref, 'amazon_product_') === 0) {
        $product_url = get_post_meta($post_id, '_amazon_product_url', true);
    } elseif (strpos($ref, 'best_product_') === 0) {
        $products = get_post_meta($post_id, '_best_products', true);
        $parts = explode('_', $ref);
        if (isset($parts[2]) && isset($products[$parts[2]]['url'])) {
            $product_url = $products[$parts[2]]['url'];
        }
    }
    
    // Redirect to actual URL
    if ($product_url) {
        wp_redirect($product_url, 302);
        exit;
    }
}
add_action('template_redirect', 'minimal_nails_track_click');

// JavaScript for click tracking (client-side backup)
function minimal_nails_tracking_script() {
    if (!is_single()) {
        return;
    }
    ?>
    <script>
    function minimalNailsTrackClick(postId, url) {
        // Send tracking data via AJAX
        if (typeof jQuery !== 'undefined') {
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'minimal_nails_track_click_ajax',
                    post_id: postId,
                    url: url
                }
            });
        }
    }
    </script>
    <?php
}
add_action('wp_footer', 'minimal_nails_tracking_script');

function minimal_nails_track_click_ajax() {
    $post_id = intval($_POST['post_id']);
    $url = esc_url_raw($_POST['url']);
    
    $total_clicks = get_post_meta($post_id, '_total_affiliate_clicks', true);
    $total_clicks = intval($total_clicks) + 1;
    update_post_meta($post_id, '_total_affiliate_clicks', $total_clicks);
    
    wp_send_json_success();
}
add_action('wp_ajax_minimal_nails_track_click_ajax', 'minimal_nails_track_click_ajax');
add_action('wp_ajax_nopriv_minimal_nails_track_click_ajax', 'minimal_nails_track_click_ajax');

