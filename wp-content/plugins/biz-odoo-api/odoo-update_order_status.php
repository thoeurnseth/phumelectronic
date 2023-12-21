<?php
    include('../../../wp-load.php');
    
    $so_id          = $_POST['so_id'];
    biz_write_log($so_id,'333');
   //  $order_status   = $_POST['status'];

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

   $loop = new WP_Query( $args );
   while( $loop->have_posts() ) {
      $loop->the_post();
      $post_id = get_the_id();
   }
   global $wpdb;
   $wpdb->insert('ph0m31e_postmeta', array(
      'meta_key'   => 'packing_order',
      'meta_value' => maybe_serialize([1]),
      'post_id'    => $post_id,
   ));

   $wpdb->insert('ph0m31e_postmeta', array(
      'meta_key'   => 'delivery_order',
      'meta_value' => maybe_serialize([1]),
      'post_id'    => $post_id,
   ));

   date_default_timezone_set("Asia/Bangkok");
   date_default_timezone_get();
   $date_order  = date("Y-m-d : H:i:s");
   $date_packing_order  = get_field('date_packing_order',$post_id);
   $date_delivery_order = get_field('date_delivery_order',$post_id);
   if(empty($date_packing_order)){
      update_post_meta($post_id,'date_packing_order',$date_order);
   }
   if(empty($date_delivery_order)){
      update_post_meta($post_id,'date_delivery_order',$date_order);
   }




   //  $order_status   = $_POST['status'];
   //  $args = array(
   //      'post_type'     => 'shop_order',
   //      'post_status'   => array('wc-processing','wc-completed' ,'wc-pending'), 
   //      'meta_key'      => 'odoo_sale_order',
   //      'posts_per_page'=> 1,
   //      'meta_query' => array(
   //          array(
   //              'key'     => 'odoo_so_number',
   //              'value'   => $so_id,
   //              'compare' => '=',
   //          ),
   //      ),
   // );
   
   // $loop = new WP_Query( $args );
   // while( $loop->have_posts() ) {

   //    $loop->the_post();

   //    $order_id = get_the_id();
   //    $order = new WC_Order($order_id);
   //    if($order_status == 'completed') { 
   //       $order->update_status('completed', 'order_note'); 
   //       $order->update_status( 'wc-completed' );
   //       return array(
   //          'code'   => 200,
   //          'status' => 'success'
   //       );
   //    }
   //    if($order_status == 'processing') {
   //       $order->update_status('processing', 'order_note'); 
   //       $order->update_status( 'wc-processing' );
   //       return array(
   //          'code'   => 200,
   //          'status' => 'success'
   //       );
   //    }
   //    if($order_status == 'cancelled') { 
   //       $order->update_status('cancelled', 'order_note'); 
   //       $order->update_status( 'wc-cancelled' );
   //       return array(
   //          'code'   => 200,
   //          'status' => 'success'
   //       ); 
   //    }
         
   // }


   return array(
      'code'   => 200,
      'status' => 'success',
      'msg'    => 'not work'
   ); 
?>

