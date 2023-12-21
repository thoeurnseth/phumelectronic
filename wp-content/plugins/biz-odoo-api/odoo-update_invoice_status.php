<?php
    include('../../../wp-load.php');

    $so_id = $_POST['so_id'];
    $args = array(
        'post_type'     => 'shop_order',
        'post_status'   => array('wc-pending' ,'wc-completed' ,'wc-processing'),
        'posts_per_page'=> 1,
        'meta_query' => array(
            array(
                'key'     => 'odoo_so_number',
                'value'   => $so_id,
                'compare' => '=',
            ),
        ),
   );
   $loop = new WP_Query( $args ); 
   
   while ( $loop->have_posts() ) {
    
        $loop->the_post();   

        $order_id = get_the_id();
        if($order_id != null) { 
            $order = wc_get_order( $order_id );
            update_post_meta( $order_id ,'online_payment' ,'paid');
            $order->update_status('completed', 'order_note');
            $result = array(
                'code'  => 200,
                'status'=> 'success' 
            );
        }
        echo json_encode($result);
   } 

   $args = array(
    'post_type'     => 'shop_order',
    'post_status'   => array('wc-processing','wc-completed' ,'wc-pending'), 
    'meta_key'      => 'odoo_sale_order',
    'posts_per_page'=> 1,
    'meta_query' => array(
          array(
             'key'     => 'odoo_so_number',
             'value'   => $so_id,
             'compare' => '=',
          ),
       ),
 );

//  auto tick arrived and confirm_received when create invoice  from odoo
 biz_write_log($order_id,'order_id');

 global $wpdb;
 $wpdb->insert('ph0m31e_postmeta', array(
    'meta_key'   => 'arrived',
    'meta_value' => maybe_serialize([1]),
    'post_id'    => $order_id,
 ));

 $wpdb->insert('ph0m31e_postmeta', array(
    'meta_key'   => 'confirm_received',
    'meta_value' => maybe_serialize([1]),
    'post_id'    => $order_id,
 ));
 
 date_default_timezone_set("Asia/Bangkok");
 date_default_timezone_get();
 $date_order  = date("Y-m-d : H:i:s");
 $date_arrived          = get_field('date_arrived',$order_id);
 $date_confirm_received = get_field('date_confirm_received',$order_id);
 if(empty($date_arrived)){
    update_post_meta($order_id,'date_arrived',$date_order);
 }
 if(empty($date_confirm_received)){
    update_post_meta($order_id,'date_confirm_received',$date_order);
 }


//  global $wpdb;
//  $wpdb->update('ph0m31e_postmeta',
//              array(
//                 'meta_value' => maybe_serialize([1])
//             ),
//             array(
//                 'post_id'=>$order_id,
//                 'meta_key'=>'arrived'
//             ));
//  $wpdb->update('ph0m31e_postmeta',
//              array(
//                 'meta_value' => maybe_serialize([1])
//             ),
//             array(
//                 'post_id'=>$order_id,
//                 'meta_key'=>'confirm_received'
//             ));
?>

