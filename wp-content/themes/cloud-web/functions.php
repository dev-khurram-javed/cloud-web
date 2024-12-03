<?php 
define("CORE_PATH", get_template_directory().'/core');
define("APP_PATH", get_template_directory().'/app');
define("PUBLIC_PATH", get_template_directory().'/public');
define("PUBLIC_SRC", get_template_directory_uri().'/public');

$components = [];
$acf_custom_fields = [];

add_action( 'init', function() {
    require_once CORE_PATH . '/common/index.php';
    require_once CORE_PATH . '/acf/index.php';
    require_once CORE_PATH . '/taxonomies/index.php';
    require_once CORE_PATH . '/post-types/index.php';
    require_once CORE_PATH . '/acf/core-fields.php';
    require_once CORE_PATH . '/components/index.php';
    require_once CORE_PATH . '/blocks/index.php';
    require_once CORE_PATH . '/option-pages/index.php';
    
    require_once APP_PATH . '/theme-functions.php';
});

// Theme Setup
function theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
}

add_action( 'after_setup_theme', 'theme_setup' );

add_action( 'wp_loaded', function() {
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
        function wc_activate_gutenberg_products($can_edit, $post_type) {
            $can_edit = ($post_type == 'product') ? true : $can_edit;
            
            return $can_edit;
        }
        add_filter('use_block_editor_for_post_type', 'wc_activate_gutenberg_products', 10, 2);
        
        // WC()->cart->add_to_cart('232', '1');
        // echo '<pre>'; print_r(WC()->cart->get_cart()); echo '</pre>';
        // echo '<pre>'; print_r(wc_get_formatted_cart_item_data( WC()->cart->get_cart()['289dff07669d7a23de0ef88d2f7129e7'] )); echo '</pre>';

        $product_id = 232; // Replace with the product ID you want to add
        $quantity = 1;     // Number of items to add

        // Check if WooCommerce cart is available
        // if (WC()->cart) {
        //     $added = WC()->cart->add_to_cart($product_id, $quantity);

        //     if ($added) {
        //         echo 'Product added to cart successfully!';
        //     } else {
        //         echo 'Failed to add product to cart.';
        //     }
        // } else {
        //     echo 'WooCommerce cart is not initialized.';
        // }

        // if (WC()->session) {
        //     echo '<pre>';
        //     print_r(WC()->session->get_session());
        //     echo '</pre>';
        // } else {
        //     echo 'Session not initialized.';
        // }
        
    }
});

add_action('wp_ajax_update_cart', 'handle_update_cart');
add_action('wp_ajax_nopriv_update_cart', 'handle_update_cart');

function handle_update_cart() {
    // Check nonce
    if (!isset($_SERVER['HTTP_X_WP_NONCE']) || !wp_verify_nonce($_SERVER['HTTP_X_WP_NONCE'], 'update-cart')) {
        wp_send_json_error(['message' => 'Nonce verification failed'], 403);
    }

    // Get and sanitize input
    $cart_item_key = sanitize_text_field($_POST['cart_item_key'] ?? '');
    $quantity = absint($_POST['quantity'] ?? 0);

    if (!$cart_item_key || $quantity <= 0) {
        wp_send_json_error(['message' => 'Invalid input']);
    }

    // Update cart
    if (WC()->cart->set_quantity($cart_item_key, $quantity)) {
        wp_send_json_success(['message' => 'Cart updated successfully']);
    } else {
        wp_send_json_error(['message' => 'Failed to update cart']);
    }
}
