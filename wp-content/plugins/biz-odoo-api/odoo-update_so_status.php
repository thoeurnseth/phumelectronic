<?php
    include('../../../wp-load.php');
        $so_id = $_GET['so_id'];
        // update_field($so_id, 'odoo_confirm_sale_order','success');

        $args = array(
            'post_type'     => 'shop_order',
            'post_status'   => array('wc-processing','wc-completed'),
            'meta_key'      => 'odoo_sale_order'
       );
       $loop = new WP_Query( $args );
        $status = array(
            'status' => '400',
        );
       while ( $loop->have_posts() ) : $loop->the_post();   
            $so_number = get_field('odoo_so_number');
            if($so_number != null) {
                if($so_number == $so_id) {
                    update_post_meta( get_the_id() ,'odoo_confirm_sale_order' ,'success'); 
                    $status = array(
                        'status' => '200',
                    );
                }   
            }
       endwhile;
       wp_reset_postdata();


