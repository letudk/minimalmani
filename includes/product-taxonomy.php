<?php
/**
 * Product Categories Taxonomy
 */

if (!defined('ABSPATH')) {
    exit;
}

// Register Product Categories Taxonomy
function minimal_nails_register_product_taxonomy() {
    $labels = array(
        'name'              => _x('Product Categories', 'taxonomy general name', 'minimal-nails'),
        'singular_name'     => _x('Product Category', 'taxonomy singular name', 'minimal-nails'),
        'search_items'      => __('Search Product Categories', 'minimal-nails'),
        'all_items'         => __('All Product Categories', 'minimal-nails'),
        'parent_item'       => __('Parent Product Category', 'minimal-nails'),
        'parent_item_colon' => __('Parent Product Category:', 'minimal-nails'),
        'edit_item'         => __('Edit Product Category', 'minimal-nails'),
        'update_item'       => __('Update Product Category', 'minimal-nails'),
        'add_new_item'      => __('Add New Product Category', 'minimal-nails'),
        'new_item_name'     => __('New Product Category Name', 'minimal-nails'),
        'menu_name'         => __('Product Categories', 'minimal-nails'),
    );
    
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'product-category'),
    );
    
    register_taxonomy('product_category', array('post'), $args);
}
add_action('init', 'minimal_nails_register_product_taxonomy', 0);

