<!-- <h2>My Coupons</h2> -->


<?php
    // $args = array(
    //     'posts_per_page'   => -1,
    //     'orderby'          => 'title',
    //     'order'            => 'asc',
    //     'post_type'        => 'shop_coupon',
    //     'post_status'      => 'publish',
    // );
        
    //  $coupons = get_posts( $args );

    //  foreach($coupons as $coupon_item):

    //     $code = $coupon_item->post_title;
    //     $coupon = new WC_Coupon($code);
    //     // echo $coupon;
    //      $coupon_post = get_post($coupon->id);
    //      $coupon_data = array(
    //         'id' => $coupon->id,
    //         'code' => $coupon->code,
    //         'type' => $coupon->type,
    //         'created_at' => $coupon_post->post_date_gmt,
    //         'updated_at' => $coupon_post->post_modified_gmt,
    //         'amount' => wc_format_decimal($coupon->coupon_amount, 2),
    //         'individual_use' => ( 'yes' === $coupon->individual_use ),
    //         'product_ids' => array_map('absint', (array) $coupon->product_ids),
    //         'exclude_product_ids' => array_map('absint', (array) $coupon->exclude_product_ids),
    //         'usage_limit' => (!empty($coupon->usage_limit) ) ? $coupon->usage_limit : null,
    //         'usage_count' => (int) $coupon->usage_count,
    //         'expiry_date' => (!empty($coupon->expiry_date) ) ? date('Y-m-d', $coupon->expiry_date) : null,
    //         'enable_free_shipping' => $coupon->enable_free_shipping(),
    //         'product_category_ids' => array_map('absint', (array) $coupon->product_categories),
    //         'exclude_product_category_ids' => array_map('absint', (array) $coupon->exclude_product_categories),
    //         'exclude_sale_items' => $coupon->exclude_sale_items(),
    //         'minimum_amount' => wc_format_decimal($coupon->minimum_amount, 2),
    //         'maximum_amount' => wc_format_decimal($coupon->maximum_amount, 2),
    //         'customer_emails' => $coupon->customer_email,
    //         'description' => $coupon_post->post_excerpt,
    //      );
    //      $usage_left = $coupon_data['usage_limit'] - $coupon_data['usage_count'];
    //  endforeach;
     
?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="coupon-title">
                <h2><i class="fa-regular fa-money-check-dollar"></i> My Coupon</h2>
            </div>
            <table>
                <tr>
                    <th hidden>Username or Email</th>
                    <th hidden>User_id</th>
                    <th>Coupon code</th>
                    <th>Coupon Amount</th>
                    <th>Discount type</th>
                    <th>usage limit per user</th>
                </tr>
                <?php
                    $user_id = get_current_user_id();
                    $arg = array(
                        'post_type'      => 'shop_coupon',
                        'posts_status'   => 'publish',
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'relation' => 'OR',
                                array(
                                    'key'     => 'inviter_id',
                                    'value'   => $user_id,
                                    'compare' => '='
                                ),
                                array(
                                    'key'     => 'inviter_id',
                                    'value'   => '',
                                    'compare' => '='
                                ),
                                array(
                                    'key'     => 'inviter_id',
                                    'compare' => 'NOT EXISTS'
                                )
                            ),
                            array(
                                'key'     => 'date_expires',
                                'value'   => date("Y-m-d"),
                                'compare' => '>'
                            )
                        )
                    );

                    $data_coupon = new WP_Query($arg);
                    if( $data_coupon ->have_posts() ) {
                        while($data_coupon ->have_posts()) {
                            $data_coupon->the_post();
                            $code        	= get_the_title();  
                            $description 	= get_the_excerpt();
                            $coupon_id   	= get_the_ID();
                            $usage_limit 	= get_field('usage_limit');
                            $usage_count 	= get_field('usage_count');
                            $discount_type 	= get_field('discount_type');
                            $usage_limit_per_user 	= get_field('usage_limit_per_user');
                            $expire_date		 	= get_field('date_expires');
                            $minimum_amount 	  	= get_field('minimum_amount');
                            $maximum_amount  	 	= get_field('maximum_amount');
                            $discount_amount 	  	= get_field('coupon_amount'); 
                            if($discount_type=='percent'){
                                $percenttage = 'Percentage discount';
                            }else{
                                $percenttage = 'Fixed cart discount';
                            }
                            $user_used_count = $wpdb->get_row(" SELECT COUNT(meta_value) as user_total_used FROM ph0m31e_postmeta WHERE post_id = $coupon_id AND meta_key = '_used_by' AND meta_value = $user_id");
                            $total_usage_by_user = $user_used_count->user_total_used;
                            // if($expire_date > date('Y-m-d')) {
                                if($usage_limit > $usage_count) {
                                    if($usage_limit_per_user > $total_usage_by_user) {
                                        ?>
                                            <tr>
                                                <th hidden> <?php echo $username ?></th>
                                                <th hidden> <?php echo $user_id ?></th>
                                                <th> <?php echo $code ?></th>
                                                <th> <?php echo $discount_amount ?></th>
                                                <th> <?php echo $percenttage ?></th>
                                                <th> <?php echo $usage_limit_per_user?></th>
                                            </tr>
                                        <?php
                                    }    
                                }        
                            // }              
                        }    
                    }      
                ?>
            </table>
        </div>
    </div>
</div>                 