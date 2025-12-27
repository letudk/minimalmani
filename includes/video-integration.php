<?php
/**
 * Video Integration Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

// YouTube Video Shortcode (enhanced)
function minimal_nails_youtube_video_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
        'url' => '',
        'title' => '',
        'width' => '100%',
        'aspect' => '16:9',
    ), $atts);
    
    $video_id = '';
    
    if (!empty($atts['id'])) {
        $video_id = sanitize_text_field($atts['id']);
    } elseif (!empty($atts['url'])) {
        // Extract YouTube video ID from URL
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $atts['url'], $matches);
        if (!empty($matches[1])) {
            $video_id = $matches[1];
        }
    }
    
    if (empty($video_id)) {
        return '';
    }
    
    $aspect_ratio = str_replace(':', ' / ', $atts['aspect']);
    
    ob_start();
    ?>
    <div class="youtube-video-wrapper" style="position: relative; padding-bottom: calc(<?php echo esc_attr($aspect_ratio); ?> * 100%); height: 0; overflow: hidden; max-width: 100%; margin: 30px 0;">
        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen></iframe>
    </div>
    <?php if (!empty($atts['title'])) : ?>
    <p class="video-title" style="text-align: center; margin-top: 10px; font-style: italic; color: var(--text-medium);">
        <?php echo esc_html($atts['title']); ?>
    </p>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
add_shortcode('youtube_video', 'minimal_nails_youtube_video_shortcode');

// Video Gallery Shortcode
function minimal_nails_video_gallery_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => __('Video Tutorials', 'minimal-nails'),
    ), $atts);
    
    // Parse video IDs from content (one per line)
    $video_ids = array_filter(array_map('trim', explode("\n", $content)));
    
    if (empty($video_ids)) {
        return '';
    }
    
    ob_start();
    ?>
    <section class="video-gallery-section">
        <?php if (!empty($atts['title'])) : ?>
        <div class="container">
            <h2 class="section-title"><?php echo esc_html($atts['title']); ?></h2>
        </div>
        <?php endif; ?>
        <div class="container">
            <div class="video-gallery-grid">
                <?php foreach ($video_ids as $video_id) : 
                    $video_id = trim($video_id);
                    if (empty($video_id)) continue;
                ?>
                <div class="video-gallery-item">
                    <div class="youtube-video-wrapper" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                        <iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                                src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('video_gallery', 'minimal_nails_video_gallery_shortcode');

