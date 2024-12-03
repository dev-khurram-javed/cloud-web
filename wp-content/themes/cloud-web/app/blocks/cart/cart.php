<?php 
    $render = function ($data) {
        if (!function_exists('WC')) { echo 'WC() function not exists'; return; }

        // echo '<pre>'; print_r(WC()->cart->get_cart()); echo '</pre>';
        
        $cart = (!empty(WC()->cart)) ? WC()->cart : '';
        $cart_nonce = wp_create_nonce('update-cart');
?>
    <div class="wrapper">
        <?php 
            echo do_shortcode( '[woocommerce_cart]' );
            if ( !empty($cart) ) : 
                $cart_items = $cart->get_cart();
        ?>
            <?php 
                foreach ( $cart_items as $cart_item_key => $cart_item ) : 
                    $product = $cart_item['data'];
                    echo $product->get_name();
                    echo $cart->get_product_subtotal( $product, $cart_item['quantity'] );
                    echo $product->get_price_html();
                    // woocommerce_get_price_html()
                    component('image', ['post_id' => $cart_item['product_id'], 'max_size' => 'medium']);
                    // echo get_the_post_thumbnail_url( $product->get_image_id() );
                    // echo $product->get_image();
                    // echo '<pre>'; print_r($product); echo '</pre>';
            ?>
                    <div class="update-cart">
                        <button class="dec js-qty-btn" data-type="dec">-</button>
                        <input class="js-qty" type="number" value="<?= $cart_item['quantity'] ?>" min="1" max="100" data-nonce="<?= $cart_nonce ?>" data-key="<?= $cart_item_key; ?>">
                        <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                        <button class="inc js-qty-btn" data-type="inc">+</button>
                    </div>
            <?php endforeach ?>
        <?php else: ?>
            <h2>No Items in Cart</h2>
        <?php endif ?>
        <?php //print_r(check_ajax_referer('update-cart', 'security')); ?>
    </div>
<?php
};

$fields = [
    
];

wp_register_custom_block([
    'title' => 'Cart',
    'icon' => 'grid-view',
    'description' => '',
    'fields' => $fields,
    'render_html' => $render,
    'classes' => 'spacing-top-large spacing-bottom-large'
]);