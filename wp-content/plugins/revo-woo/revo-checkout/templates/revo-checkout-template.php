<?php
/*
* Template Name: Revo checkout
*/

function getValue(&$val, $default = '')
{
    return isset($val) ? $val : $default;
}

if(isset($_POST['order'])){
    $data = json_decode(urldecode(base64_decode($_POST['order'])), true);
}elseif (filter_has_var(INPUT_GET, 'order')) {
    $data = filter_has_var(INPUT_GET, 'order') ? json_decode(urldecode(base64_decode(filter_input(INPUT_GET, 'order'))), true) : [];
}

if (isset($data)):
    global $woocommerce;
    // Validate the cookie token
    $userId = wp_validate_auth_cookie($data['token'], 'logged_in');

    if (!$userId) {
        // echo "Invalid authentication cookie. Please try to login again!";
        return;
    }

    // Check user and authentication
    $user = get_userdata($userId);
    if ($user) {        
        wp_set_current_user($userId, $user->user_login);
        wp_set_auth_cookie($userId);

        // $url = filter_has_var(INPUT_SERVER, 'REQUEST_URI') ? filter_input(INPUT_SERVER, 'REQUEST_URI') : '';
        // header("Refresh: 0; url=$url");
    }
    $woocommerce->session->set('refresh_totals', true);
    $woocommerce->cart->empty_cart();

    // Get product info
    $billing = $data['billing'];
    $shipping = $data['shipping'];
    $products = $data['line_items'];
    foreach ($products as $product) {
        $productId = absint($product['product_id']);

        $quantity = $product['quantity'];
        $variationId = getValue($product['variation_id'], null);

        // Check the product variation
        if (!empty($variationId)) {
            $productVariable = new WC_Product_Variable($productId);
            $listVariations = $productVariable->get_available_variations();
            foreach ($listVariations as $vartiation => $value) {
                if ($variationId == $value['variation_id']) {
                    $attribute = $value['attributes'];
                    $woocommerce->cart->add_to_cart($productId, $quantity, $variationId, $attribute);
                }
            }
        } else {
            $woocommerce->cart->add_to_cart($productId, $quantity);
        }
    }


    if (!empty($data['coupon_lines'])) {
        $coupons = $data['coupon_lines'];
        foreach ($coupons as $coupon) {
            $woocommerce->cart->add_discount($coupon['code']);
        }
    }

    $shippingMethod = '';
    if (!empty($data['shipping_lines'])) {
        $shippingLines = $data['shipping_lines'];
        $shippingMethod = $shippingLines[0]['method_id'];
    }

      $url_woo = wc_get_checkout_url().'?webview=true';
      
      wp_redirect( $url_woo );
      exit;
endif;
?>
