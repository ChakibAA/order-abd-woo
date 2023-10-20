<?php


function register_order_abd_post_type()
{

    $labels = array(
        'name' => 'Order_abds',
        'singular_name' => 'Order_abd',
        'menu_name' => 'Order_abds',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Order_abd',
        'edit_item' => 'Edit Order_abd',
        'new_item' => 'New Order_abd',
        'view_item' => 'View Order_abd',
        'search_items' => 'Search Order_abds',
        'not_found' => 'No Order_abds found',
        'not_found_in_trash' => 'No Order_abds found in trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-cart',
        // Customize with a dashicon
        'menu_position' => 5,
        // Adjust the menu position
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'comments'),
    );
    register_post_type('order_abd', $args);
}

add_action('init', 'register_order_abd_post_type');