<?php
/**
 * This function created for dynamic function.
 * 
 * Version: 0.0.1
 * Created At: 20-08-2021
 * Updated At: 20-08-2021
 */

/**
 * Check Product Existing
 *
 * Param: product_id
 */
function check_product_exist( $product_id ) {

    $checking = get_posts(
        array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'p' => $product_id
        )
    );

    $count = count( $checking ); // Count proudct
    return $count; // Return result
}