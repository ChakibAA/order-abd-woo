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


add_action('wp_ajax_save_data_order_abd', 'save_data_order_abd');
add_action('wp_ajax_nopriv_save_data_order_abd', 'save_data_order_abd');

function save_data_order_abd()
{
    if (isset($_POST['data'])) {
        $data = json_encode($_POST['data']);

        $new_order_abd_post = array(
            'post_title' => generate_order_abd_title(),
            'post_content' => 'Your post content goes here.',
            'post_type' => 'order_abd',
            'post_status' => 'publish',
        );


        $new_post_id = wp_insert_post($new_order_abd_post);

        if ($new_post_id) {

            update_post_meta($new_post_id, 'order_info', sanitize_text_field($data));


            echo "New 'Order_abd'  created with ID: $new_post_id";

        } else {

            echo "Failed to create a new 'Order_abd'.";
        }

    }
    wp_die();

}

function generate_order_abd_title()
{
    global $wpdb;

    $query = "SELECT MAX(CAST(post_title AS SIGNED)) FROM $wpdb->posts WHERE post_type = 'order_abd'";

    $max_number = $wpdb->get_var($query);

    // If there are no existing posts, start from 1
    $new_number = $max_number ? $max_number + 1 : 1;

    $new_title = $new_number;

    return $new_title;
}


// Add a custom meta box for JSON input
function add_data_meta_box($post)
{
    add_meta_box(
        'order_info',
        'Order Information',
        'order_info_callback',
        'order_abd',
        // Votre nom de type de publication personnalisé
        'normal',
        'high'
    );
}

function order_info_callback($post)
{
    // Récupérez les données existantes
    $order_info = get_post_meta($post->ID, 'order_info', true);

    $array = json_decode($order_info, true);

    echo '<label for="order_info">Order Information:</label>';

    foreach ($array as $key => $value) {
        echo '<label for="order_info">' . $key . '</label>';

        echo '<textarea id="order_info" name="order_info" rows="4" style="width: 100%;">' . $value . '</textarea>';

    }

}

add_action('add_meta_boxes', 'add_data_meta_box');