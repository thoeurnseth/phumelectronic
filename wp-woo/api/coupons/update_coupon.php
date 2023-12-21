<?php

/**
 * This function created for update coupon in WooCommerce
 * Param: target, action, data, product_id
 * 
 * Version: 0.0.1
 * Created At: 11-10-2022
 */

$data = file_get_contents('php://input');
if(!empty($data)):

    $data = json_decode( stripslashes( $data ) ); // Remove back slash
    $response = $woocommerce->post('coupons/'.$_GET['id'], $data); // Execute Query
    
    if($response):
        // update_post_meta($_GET['id'], 'date_expires', date('Y-m-d', strtotime($data->date_expires)));
        $error::success( $response );
    endif;

    $error::inter_server_error();
else:
    $error::bad_request();
endif;