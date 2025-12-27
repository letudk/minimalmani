<?php
/**
 * Schema Markup / Rich Snippets
 */

if (!defined('ABSPATH')) {
    exit;
}

// Add Product Schema to single posts
function minimal_nails_add_product_schema() {
    if (!is_single()) {
        return;
    }
    
    $product_title = get_post_meta(get_the_ID(), '_amazon_product_title', true);
    $product_url = get_post_meta(get_the_ID(), '_amazon_product_url', true);
    $product_price = get_post_meta(get_the_ID(), '_amazon_product_price', true);
    $product_rating = get_post_meta(get_the_ID(), '_amazon_product_rating', true);
    $product_image = get_post_meta(get_the_ID(), '_amazon_product_image', true);
    $product_description = get_post_meta(get_the_ID(), '_amazon_product_description', true);
    
    if (empty($product_title) || empty($product_url)) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product_title,
        'image' => $product_image ? array($product_image) : array(),
        'description' => $product_description ?: get_the_excerpt(),
    );
    
    if ($product_rating) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => floatval($product_rating),
            'bestRating' => '5',
            'worstRating' => '1'
        );
    }
    
    if ($product_price) {
        $price_clean = preg_replace('/[^0-9.]/', '', $product_price);
        $schema['offers'] = array(
            '@type' => 'Offer',
            'url' => $product_url,
            'priceCurrency' => 'USD',
            'price' => $price_clean,
            'availability' => 'https://schema.org/InStock'
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'minimal_nails_add_product_schema');

// Add Review Schema if rating exists
function minimal_nails_add_review_schema() {
    if (!is_single()) {
        return;
    }
    
    $product_rating = get_post_meta(get_the_ID(), '_amazon_product_rating', true);
    if (empty($product_rating)) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'Review',
        'itemReviewed' => array(
            '@type' => 'Product',
            'name' => get_post_meta(get_the_ID(), '_amazon_product_title', true)
        ),
        'reviewRating' => array(
            '@type' => 'Rating',
            'ratingValue' => floatval($product_rating),
            'bestRating' => '5'
        ),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author()
        ),
        'reviewBody' => get_the_excerpt()
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'minimal_nails_add_review_schema', 5);

