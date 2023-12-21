<?php
if ( !class_exists('WBU_Checkout')) {
class WBU_Checkout {
    function init_hooks() {
        add_action('wp_ajax_nopriv_wbu_update_checkout', array($this, 'update_checkout') );
        add_action('wp_ajax_wbu_update_checkout', array($this, 'update_checkout') );

        add_filter('wbu_review_order_product_title', array($this, 'review_order_product_title'), 10, 2 );
        add_filter('woocommerce_cart_item_name', array($this, 'cart_item_name'), 11, 3 );
        add_filter('woocommerce_checkout_cart_item_quantity', array($this, 'checkout_item_qty'), 10, 3 );
    }
    
    function update_checkout() {
        $values = array();
        parse_str($_POST['post_data'], $values);

        $cart = (array) $values['cart'];

        foreach ( $cart as $cart_key => $cart_value ){
            if ( empty($cart_value['qty']) || !is_numeric($cart_value['qty']) ) {
                continue;
            }

            WC()->cart->set_quantity( $cart_key, $cart_value['qty'], false );
            WC()->cart->calculate_totals();
            woocommerce_cart_totals();
        }

        exit;
    }

    function cart_item_name($product_title, $cart_item, $cart_item_key) {
        $allowDelete = wbu()->option('checkout_allow_delete');

        if ( !is_checkout() || ( $allowDelete != 'yes' ) || apply_filters('wbu_override_review_order', false) ) {
            return $product_title;
        }

        $html  = $this->checkout_product_remove($cart_item, $cart_item_key, $product_title);
        $html .= $this->review_order_product_title($product_title);

        return $html;
    }

    function review_order_product_title($product_title) {
        return sprintf('<span class="product_name">%s</span>', $product_title);
    }
    
    function checkout_item_qty($html, $cart_item, $cart_item_key) {
        if ( !is_checkout() || apply_filters('wbu_override_review_order', false) ) {
            return $html;
        }

        return $this->checkout_product_quantity($html, $cart_item, $cart_item_key);
    }

    function checkout_product_remove($cart_item, $cart_item_key, $product_title = '') {
        return wbu()->get_template('checkout-product-remove.php', array(
            'product_id' => $cart_item['product_id'],
            'product' => $cart_item['data'],
            'product_title' => $product_title,
            'cart_item_key' => $cart_item_key,
        ));
    }

    function checkout_product_quantity($html, $cart_item, $cart_item_key) {
        $allowChangeQty = wbu()->option('checkout_allow_change_qty');
        
        if ( $allowChangeQty != 'yes') {
            return $html;
        }

        return wbu()->get_template('checkout-product.php', array(
            'product_id' => $cart_item['product_id'],
            'product' => $cart_item['data'],
            'displayUnitPrice' => wbu()->option('checkout_display_unit_price'),
            'allowChangeQty' => $allowChangeQty,
            'cart_item' => $cart_item,
            'cart_item_key' => $cart_item_key,
        ));
    }
}
}
