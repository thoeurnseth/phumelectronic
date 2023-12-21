<?php

/**
 * This function created for create products in WooCommerce
 * 
 * Param: target, action, data
 * 
 * Version: 0.0.1
 * Created At: 20-08-2021
 * Updated At: 20-08-2021
 */

if( isset($_POST['data']) && !empty($_POST['data']) && 
    $_POST['target'] == "products" && $_POST['action'] == "create_product" ) {

    $product  = $_POST['data'];
    $product  = json_decode( stripslashes( $product ) ); // Remove back slash
    $response = $woocommerce->post('products', $product); // Execute create product

    /**
     * Create Product
     */
    if( $response ) {
        $error::created( $response ); // Create
    }

    $error::inter_server_error(); // Create Error
}
else {
    $error::bad_request(); // Bad Request
}