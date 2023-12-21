<?php

/**
 * This function created for create products in WooCommerce
 * Param: target, action, data
 * 
 * Version: 0.0.1
 * Date: 11-10-2022
 */



$data = file_get_contents('php://input');
if(!empty($data)):

    $data = json_decode( stripslashes( $data ) ); // Remove back slash
    $response = $woocommerce->post('coupons', $data); // Execute Query
    $post_id  = $response->id;
    $coupon_amount = $response->amount;
    $amount =  substr($coupon_amount,0,2);
    if($response)
        // convert date expire
        update_post_meta($post_id, 'date_expires', date('Y-m-d', strtotime($data->date_expires. ' +1 day')));
        update_post_meta($post_id, 'coupon_amount', $amount);

        $error::success( $response );

    $error::inter_server_error();
else:
    $error::bad_request();
endif;