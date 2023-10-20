<?php
/*

    Plugin Name: Order abd Woocommerce
    Description: Detect abandoned woo Orders
    Version: 1.0
    Author: Chakib
    Author URI: https://www.linkedin.com/in/chakib-ammar-aouchiche-a25150220/
    License: GPL-2.0+
    License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

if (!defined('ABSPATH')) {
    exit;
}


function is_woocommerce_active_order_abd_woo()
{
    return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
}

function woocommerce_inactive_notice_order_abd_woo()
{
    ?>
    <div id="message" class="error">
        <p>

            <?php
            deactivate_plugins(plugin_basename(__FILE__));
            print_r(__('<b>WooCommerce</b> plugin must be active for <b>Shipping zone Algeria Woocomerce</b> to work. '));
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
            ?>
        </p>
    </div>
    <?php
}

if (!is_woocommerce_active_order_abd_woo()) {
    add_action('admin_notices', 'woocommerce_inactive_notice_order_abd_woo');

    return;
}



include_once(plugin_dir_path(__FILE__) . 'order-abd.type.php');




function my_enqueue_scripts_order_abd_woo()
{
    wp_register_script('form-data-script', plugins_url('/js/main-script.js', __FILE__), array('jquery'), '1.0.0', true);
    wp_localize_script('form-data-script', 'php_data', array('ajax_url' => admin_url('admin-ajax.php')));

    check_checkout_page();

}

add_action('wp_enqueue_scripts', 'my_enqueue_scripts_order_abd_woo');


// Check if page is checkout
function check_checkout_page()
{
    if (is_checkout()) {

        do_action('get_from_data_hook');

    }
}


// Enqueue JS
add_action('get_from_data_hook', 'get_from_data');

function get_from_data()
{
    wp_enqueue_script('form-data-script');
}



// Save ata
add_action('wp_ajax_save_data_order_abd', 'save_data_order_abd');
add_action('wp_ajax_nopriv_save_data_order_abd', 'save_data_order_abd');


function save_data_order_abd()
{
    // if (isset($_POST['data'])) {
    $new_post = array(
        'post_title' => 'New Post Title',
        'post_content' => 'Post content goes here.',
        'post_type' => 'Order_abds',
        // Your custom post type
        'post_status' => 'publish',
        // You can use 'draft' if you want to save it as a draft
    );

    $post_id = wp_insert_post($new_post);

    update_post_meta($post_id, '_json_data', sanitize_text_field($_POST['data']));
    // }
    echo "chakib";
}



// Add a custom meta box for JSON input
function add_json_meta_box()
{
    add_meta_box('json_data_meta_box', 'JSON Data', 'render_json_meta_box', 'order_abd', 'normal', 'high');
}

// Render the JSON meta box
function render_json_meta_box($post)
{
    $json_data = get_post_meta($post->ID, '_json_data', true);
    ?>
    <textarea name="json_data" rows="10" cols="50"><?php echo esc_textarea($json_data); ?></textarea>
    <?php
}

add_action('add_meta_boxes', 'add_json_meta_box');