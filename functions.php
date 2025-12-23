<?php
/**
 * Minimal Nails Theme Functions
 */
// Disable Gutenberg and force Classic Editor
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);

// Theme Setup
function minimal_nails_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 50,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('responsive-embeds');
    
    // WebP Support
    add_theme_support('post-formats', array('image'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'minimal-nails'),
        'footer'  => __('Footer Menu', 'minimal-nails'),
    ));
}
add_action('after_setup_theme', 'minimal_nails_setup');

// Enqueue scripts and styles
function minimal_nails_scripts() {
    wp_enqueue_style('minimal-nails-style', get_stylesheet_uri(), array(), '1.1.0');
    wp_enqueue_script('minimal-nails-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '1.1.0', true);
     // Back to top script for single posts
    if (is_single()) {
        wp_enqueue_script('minimal-nails-back-to-top', get_template_directory_uri() . '/assets/js/back-to-top.js', array(), '1.1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'minimal_nails_scripts');

// WebP Image Support
function minimal_nails_webp_upload_mimes($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('mime_types', 'minimal_nails_webp_upload_mimes');

// Auto convert images to WebP on upload
function minimal_nails_convert_to_webp($metadata, $attachment_id) {
    if (!function_exists('wp_get_image_editor')) {
        return $metadata;
    }
    
    $file = get_attached_file($attachment_id);
    $editor = wp_get_image_editor($file);
    
    if (is_wp_error($editor)) {
        return $metadata;
    }
    
    // Get file info
    $pathinfo = pathinfo($file);
    $dirname = $pathinfo['dirname'];
    $filename = $pathinfo['filename'];
    
    // Save as WebP
    $webp_file = $dirname . '/' . $filename . '.webp';
    
    if (method_exists($editor, 'save')) {
        $saved = $editor->save($webp_file, 'image/webp');
        
        if (!is_wp_error($saved)) {
            // Add WebP version to metadata
            if (!isset($metadata['webp'])) {
                $metadata['webp'] = array();
            }
            $metadata['webp']['file'] = basename($webp_file);
        }
    }
    
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'minimal_nails_convert_to_webp', 10, 2);

// Get WebP image if available
function minimal_nails_get_image_url($attachment_id, $size = 'full') {
    $metadata = wp_get_attachment_metadata($attachment_id);
    
    // Check if WebP version exists
    if (isset($metadata['webp']['file'])) {
        $upload_dir = wp_upload_dir();
        $file_path = dirname(get_attached_file($attachment_id));
        return $upload_dir['baseurl'] . '/' . basename($file_path) . '/' . $metadata['webp']['file'];
    }
    
    // Return original image
    return wp_get_attachment_image_url($attachment_id, $size);
}

// Customizer Settings
function minimal_nails_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('minimal_nails_hero', array(
        'title'    => __('Hero Section', 'minimal-nails'),
        'priority' => 30,
    ));
    
    // Hero Title
    $wp_customize->add_setting('hero_title', array(
        'default'           => 'Minimal Nail Ideas & Press-On Nail Guides',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('hero_title', array(
        'label'   => __('Hero Title', 'minimal-nails'),
        'section' => 'minimal_nails_hero',
        'type'    => 'text',
    ));
    
    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => 'Clean, practical nail inspiration for everyday women. Curated designs that work with your lifestyle, not against it.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('hero_subtitle', array(
        'label'   => __('Hero Subtitle', 'minimal-nails'),
        'section' => 'minimal_nails_hero',
        'type'    => 'textarea',
    ));
    
    // Hero Image
    $wp_customize->add_setting('hero_image', array(
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'hero_image', array(
        'label'     => __('Hero Image', 'minimal-nails'),
        'section'   => 'minimal_nails_hero',
        'mime_type' => 'image',
    )));
    
    // Trust Statement
    $wp_customize->add_section('minimal_nails_trust', array(
        'title'    => __('Trust Statement', 'minimal-nails'),
        'priority' => 40,
    ));
    
    $wp_customize->add_setting('trust_statement', array(
        'default'           => 'Researched, tested, and curated for everyday use',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('trust_statement', array(
        'label'   => __('Trust Statement', 'minimal-nails'),
        'section' => 'minimal_nails_trust',
        'type'    => 'text',
    ));
}
add_action('customize_register', 'minimal_nails_customize_register');

// Custom excerpt length
function minimal_nails_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'minimal_nails_excerpt_length');

// Custom excerpt more
function minimal_nails_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'minimal_nails_excerpt_more');

// Widget areas
function minimal_nails_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'minimal-nails'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'minimal-nails'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'minimal_nails_widgets_init');

// Add category meta fields for FAQ and Pillar Post
function minimal_nails_category_meta_fields($term) {
    $term_id = $term->term_id;
    $pillar_post = get_term_meta($term_id, 'pillar_post', true);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="pillar_post"><?php _e('Pillar Post ID', 'minimal-nails'); ?></label>
        </th>
        <td>
            <input type="number" name="pillar_post" id="pillar_post" value="<?php echo esc_attr($pillar_post); ?>">
            <p class="description"><?php _e('Enter the post ID of the pillar/guide post for this category', 'minimal-nails'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('category_edit_form_fields', 'minimal_nails_category_meta_fields');

// Save category meta
function minimal_nails_save_category_meta($term_id) {
    if (isset($_POST['pillar_post'])) {
        update_term_meta($term_id, 'pillar_post', sanitize_text_field($_POST['pillar_post']));
    }
}
add_action('edited_category', 'minimal_nails_save_category_meta');
add_action('created_category', 'minimal_nails_save_category_meta');
// Calculate reading time
function minimal_nails_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute
    return $reading_time;
}

// Check if post has H2 headings
function minimal_nails_has_headings() {
    $content = get_the_content();
    return preg_match('/<h2[^>]*>.*?<\/h2>/i', $content);
}

// Generate Table of Contents
function minimal_nails_generate_toc() {
    $content = get_the_content();
    preg_match_all('/<h2[^>]*>(.*?)<\/h2>/i', $content, $matches);
    
    if (empty($matches[1])) {
        return '';
    }
    
    $toc = '<div class="table-of-contents">';
    $toc .= '<h2 class="toc-title">' . __('Table of Contents', 'minimal-nails') . '</h2>';
    $toc .= '<ol class="toc-list">';
    foreach ($matches[1] as $index => $heading) {
        $heading_text = strip_tags($heading);
        $heading_id = sanitize_title($heading_text);
        $toc .= '<li><a href="#' . $heading_id . '">' . $heading_text . '</a></li>';
    }
    $toc .= '</ol>';
    $toc .= '</div>';
    
    return $toc;
}

// Insert TOC before first H2
function minimal_nails_insert_toc_before_first_h2($content) {
    $toc = minimal_nails_generate_toc();
    
    if (empty($toc)) {
        return $content;
    }
    
    // Find first H2 and insert TOC before it
    $content = preg_replace('/<h2[^>]*>/i', $toc . '$0', $content, 1);
    
    return $content;
}

// Add IDs to H2 headings for TOC links
function minimal_nails_add_heading_ids($content) {
    if (!is_singular('post')) {
        return $content;
    }
    
    $content = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/i', function($matches) {
        $heading_text = strip_tags($matches[2]);
        $heading_id = sanitize_title($heading_text);
        
        // Check if ID already exists
        if (strpos($matches[1], 'id=') !== false) {
            return $matches[0];
        }
        
        return '<h2' . $matches[1] . ' id="' . $heading_id . '">' . $matches[2] . '</h2>';
    }, $content);
    
    return $content;
}
add_filter('the_content', 'minimal_nails_add_heading_ids');

// Get related posts by category
function minimal_nails_get_related_posts($post_id, $limit = 6) {
    $categories = wp_get_post_categories($post_id);
    
    if (empty($categories)) {
        return array();
    }
    
    $args = array(
        'category__in'   => $categories,
        'post__not_in'   => array($post_id),
        'posts_per_page' => $limit,
        'orderby'        => 'rand',
        'post_status'    => 'publish',
    );
    
    $query = new WP_Query($args);
    return $query->posts;
}

// Insert AdSense after 2nd H2 (optional)
function minimal_nails_insert_adsense($content) {
    // Skip if AdSense code not set
    $adsense_code = get_theme_mod('adsense_code', '');
    if (empty($adsense_code)) {
        return $content;
    }
    
    $h2_count = 0;
    $content = preg_replace_callback('/<h2[^>]*>.*?<\/h2>/i', function($matches) use (&$h2_count, $adsense_code) {
        $h2_count++;
        
        // Insert after 2nd H2
        if ($h2_count == 2) {
            return $matches[0] . '<div class="adsense-block">' . $adsense_code . '</div>';
        }
        
        return $matches[0];
    }, $content);
    
    return $content;
}

// Add AdSense customizer setting
function minimal_nails_adsense_customizer($wp_customize) {
    $wp_customize->add_section('minimal_nails_adsense', array(
        'title'    => __('AdSense Settings', 'minimal-nails'),
        'priority' => 50,
    ));
    
    $wp_customize->add_setting('adsense_code', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('adsense_code', array(
        'label'       => __('AdSense Code', 'minimal-nails'),
        'description' => __('Paste your AdSense code here. It will appear after the 2nd H2 in posts.', 'minimal-nails'),
        'section'     => 'minimal_nails_adsense',
        'type'        => 'textarea',
    ));
}
add_action('customize_register', 'minimal_nails_adsense_customizer');

// Add contact email to customizer
function minimal_nails_contact_customizer($wp_customize) {
    $wp_customize->add_setting('contact_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('contact_email', array(
        'label'       => __('Contact Email', 'minimal-nails'),
        'description' => __('Email address for 404 page contact button', 'minimal-nails'),
        'section'     => 'minimal_nails_trust',
        'type'        => 'email',
    ));
}
add_action('customize_register', 'minimal_nails_contact_customizer');