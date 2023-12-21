<?php

use Elementor\Core\Base\Document;

ob_start();

/**
 * Create content brand
 */
function brand_content() {
    $slide = '';

    $brands = get_terms( array(
		'taxonomy' => 'pwb-brand',
		'hide_empty' => false,
        ) 
    );
	
    foreach( $brands as $brand ) {
        $term_id = $brand->term_id ;
        $obj_post_id = get_term_meta( $term_id, 'pwb_brand_banner', true );
        $post_id = $obj_post_id != null ? $obj_post_id : '';

    	$slug = $brand->slug;
		$uri = '/brands/';
		$url_filter = site_url().'/'.$uri.$slug;
		
		$brand_logo = get_post_field( 'guid', $post_id );
		
        $slide .= '
            <div>
				<a href="'.$url_filter.'"> 
			   		<img src="'.$brand_logo.'" alt="Logo">
				</a>
            </div>
        ';
    } 

    echo '
        <div class="brand-content">
            <div class="slide-wrap">
                '.$slide.'
            </div>
        </div>
    ';
}

/**
 * Create a shortcode
 *
 * @return void
 */
function custom_brand() {
    ob_start();
    brand_content();
    return ob_get_clean();
}
// Register a new shortcode: [biz_custom_registration]
add_shortcode('woo_brand', 'custom_brand');

/**
 * Create content category
 */
function category_content() {
    $slide = '';

$categories = get_terms( array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    ) 
);

foreach( $categories as $category ) {
    $term_id = $category->term_id ;
    $obj_post_id = get_term_meta( $term_id, 'thumbnail_id', true );
    $post_id = $obj_post_id != null ? $obj_post_id : '';

    $slug = $category->slug;
    $uri = '/categories/';
    $url_filter = site_url().'/'.$uri.$slug;
    
    $category_logo = get_post_field( 'guid', $post_id );
    
    $slide .= '
        <div>
            <a href="'.$url_filter.'"> 
                   <img src="'.$category_logo.'" alt="Logo">
            </a>
        </div>
    ';
} 

echo '
    <div class="brand-content">
        <div class="slide-wrap">
            '.$slide.'
        </div>
    </div>
';
}

/**
 * Create a shortcode
 *
 * @return void
 */
function custom_category() {
    ob_start();
    category_content();
    return ob_get_clean();
}
// Register a new shortcode: [biz_custom_registration]
add_shortcode('woo_category', 'custom_category');



// create a shortcode recomment for you

function recommend_product() {
    $dir = str_replace('functions/' ,'',plugin_dir_url( __FILE__ ));
    $img_spinner = $dir .'assets/img/Iphone-spinner-2.png';
    $slide='';
    global $woocommerce;
    global $wpdb;  
    global $product;  
    
    $sale_price = '';
    $regular_price = '';
    $user_id = get_current_user_id();
   
    $data_products = $wpdb->get_row( " SELECT * FROM `revo_extend_products` WHERE `type` = 'recomendation' ");
    // echo json_encode(  $data_products);
    $data_select = $wpdb->get_row( " SELECT * FROM `ph0m31e_yith_wcwl` WHERE `user_id` =  '$user_id'");
    
    // foreach($data_select as $value){
    //     $user = $value->user_id;
    //     echo json_decode($user);
    // }
    // if(in_array($data_select,$data_products)){
    //     $status_hart = "style='color:red'";
    // }else{
    //     $status_hart = "";
    // }
    
    $get_products = json_decode($data_products->products);
    foreach($get_products as $product_id) {
        $product  = wc_get_product( $product_id );
        if(!empty($product)){
            $name     = $product->get_name();
            $price    = $product->get_price();
            $thumnail = $product->get_image();
            $product_url = get_permalink($product_id);
        
            $variant_ids = $product->get_children();
            if(!empty($variant_ids)){
                $variant_id = json_encode($variant_ids[0]);
                $variant    = wc_get_product($variant_id)->get_sku();

                $data_sale_price    = wc_get_product($variant_id)->get_sale_price();
                
                if (empty($data_sale_price )){
                    $regular_price = '$'.number_format(wc_get_product($variant_id)->get_regular_price(),2);
                    $sale_price = ''; 
                }   
                else{
                    $regular_price = '<del>'.'$'.number_format(wc_get_product($variant_id)->get_regular_price(),2).'</del>' ;
                    $sale_price = "$".number_format(wc_get_product($variant_id)->get_sale_price(),2);
                } 
            }

            // check active whistlist
            $whistlist_icon = 'fa-heart-o';
            $sqlStr = "SELECT * FROM `ph0m31e_yith_wcwl` WHERE `user_id` =  '$user_id' AND prod_id='$product_id'";
            $whistlist = $wpdb->get_row($sqlStr);
            if(count($whistlist)){
                $whistlist_icon = 'fa-heart';
            }

            $slide.= '
                <div class="content slide-detail-111-title-price-wislist-cart owl-custom-nav">
                    <div class="detail-111-title-price-wislist-cart">
                        <div class="thumbnail">
                            <a href="'.$product_url.'">'.$thumnail.'</a>
                        </div>
                        <div class="title-description">
                            <div class="rtin-title-1313">'.$variant.'</div>
                            <div class="description-rtin-1122">
                                <h3 class="title"> <a href="">'.$name.'</a></h3>
                            </div>
                        </div>
                        <div class="price-wislist-cart-12">
                            <div class="sale-price-get-price">
                                <div class="sale-price-discount">
                                    <span class="sale-price">'.$regular_price.'</span>
                                </div>
                                <div class="price-123">
                                    <span class="price-amount-1313">'.$sale_price.'</span>
                                </div>
                            </div>
                            <form action="" method="post">
                                    <input type="hidden" name="user_id" value="'.$user_id.'">
                                    <input type="hidden" name="product_id" value="'.$variant_id.'">
                                <div class="wislist-cart-1111">
                                    <div class="wislist-112">
                                        <div class="spiner_image">
                                            <a href=""><img src="'.$img_spinner.'" alt=""></a>   
                                        </div>
                                        <button type="button" name="btn_submit" class="btn-add-remove-wistlist" data-user-id="'.$user_id.'" data-product-id="'.$product_id.'">
                                            <a href=""><i class="wishlist-icon fa '.$whistlist_icon.'"></i></a>
                                        </button>
                                    </div>
                                    <div class="cart-112">
                                        <input type="hidden" name="session" value="'.$user_id.'">
                                        <input type="hidden" name="variant_id" value="'.$variant_id.'">
                                            <span><a href="'.$product_url.'"><i class="fa fa-cart-plus"></i></a name="btn_cart"></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            ';
        }else{
            echo "No product,  Please Enter product!-  "; 
        }
    } 

        
        echo '
            <div class="product-title-recomment">
                <h2 class="title-recomment">Recommendations For You</h2>
            </div>
            <div class="">
                <div class="product-slide-wrap product-slide-wrap-auto ">  
                    '.$slide.'
                </div>
            </div>
        ';
}
/**
 * Create a shortcode
 *
 * @return void
 */
function custom_recommend_product() {
    ob_start();
    recommend_product();
    return ob_get_clean();
}
// Register a new shortcode: [custom_recommend_product]
add_shortcode('woo_recommend_product', 'custom_recommend_product');

function promotion_product(){
    $dir = str_replace('functions/' ,'',plugin_dir_url( __FILE__ ));
    $img_spinner = $dir .'assets/img/Iphone-spinner-2.png';
    $slide='';
    $slide_promotion='';
    global $woocommerce;
    global $wpdb;  
    global $product;  
    $sale_price = '';
    $regular_price = '';
    $user_id = get_current_user_id();
    $data_products = $wpdb->get_row( " SELECT * FROM `revo_extend_products` WHERE `type` = 'special' ");
    // echo json_encode($data_products);
    $check_product = [];
    $get_products = json_decode($data_products->products);
    foreach($get_products as $product_id) {
        $cambo_exspire_date = get_post_meta($product_id,'cambo_exspire_date',true); 
        $current_date       = date('Y-m-d : H:i:s');
        $product            = wc_get_product( $product_id );
        if(!empty($product) &&  ($cambo_exspire_date > $current_date || $cambo_exspire_date == '')){
            $check_product[] = $product_id;
            $name     = $product->get_name();
            $price    = $product->get_price();
            $thumnail = $product->get_image();
            $product_url = get_permalink($product_id);
        
            $variant_ids = $product->get_children();
            if(!empty($variant_ids)){
                $variant_id = json_encode($variant_ids[0]);
                $variant    = wc_get_product($variant_id)->get_sku();
                // $start_date = '$'.number_format(wc_get_product($variant_id)->get_date_on_sale_from(),2);
                // $end_date   = '$'.number_format(wc_get_product($variant_id)->get_date_on_sale_to(),2);

                $data_sale_price    = wc_get_product($variant_id)->get_sale_price();
                
                if (empty($data_sale_price )){
                    $regular_price = '$'.number_format(wc_get_product($variant_id)->get_regular_price(),2);
                    $sale_price = ''; 
                }   
                else{
                    $regular_price = '<del>'.'$'.number_format(wc_get_product($variant_id)->get_regular_price(),2).'</del>' ;
                    $sale_price = "$".number_format(wc_get_product($variant_id)->get_sale_price(),2);
                } 
            }

            // check active whistlist
            $whistlist_icon = 'fa-heart-o';
            $sqlStr = "SELECT * FROM `ph0m31e_yith_wcwl` WHERE `user_id` =  '$user_id' AND prod_id='$product_id'";
            $whistlist = $wpdb->get_row($sqlStr);
            if(count($whistlist)){
                $whistlist_icon = 'fa-heart';
            }

            $slide_promotion.=' 
                <div>
                    <div class="content slide-detail-promotion owl-custom-nav">
                        <div class="detail-promotion111-title-price-wislist-cart" >
                            <div class="thumbnail">
                                <a href="'.$product_url.'">'.$thumnail.'</a>
                            </div>
                            <div class="title-description">
                                <div class="rtin-title-1313">'.$variant.'</div>
                                <div class="description-rtin-1122">
                                    <h3 class="title"> <a href="'.$product_url.'">'.$name.'</a></h3>
                                </div>
                            </div>
                            <div class="price-wislist-cart-12">
                                <div class="sale-price-get-price">
                                    <div class="sale-price-discount">
                                        <span class="sale-price">'.$regular_price.'</span>
                                    </div>
                                    <div class="price-123">
                                        <span class="price-amount-1313">'.$sale_price.'</span>
                                    </div>
                                </div>
                                <form action="" method="post">
                                    <input type="hidden" name="user_id" value="'.$user_id.'">
                                    <input type="hidden" name="product_id" value="'.$variant_id.'">
                                    <div class="wislist-cart-1111">
                                        <div class="wislist-112">
                                            <div class="spiner_image">
                                                <a href=""><img src="'.$img_spinner.'" alt=""></a>   
                                            </div>
                                            <button type="button" name="btn_submit" class="btn-add-remove-wistlist" data-user-id="'.$user_id.'" data-product-id="'.$product_id.'">
                                                <a href=""><i class="wishlist-icon fa '.$whistlist_icon.'"></i></a>
                                            </button>
                                        </div>
                                        <div class="cart-112">
                                            <input type="hidden" name="session" value="'.$user_id.'">
                                            <input type="hidden" name="variant_id" value="'.$variant_id.'">
                                                <span><a href="'.$product_url.'"><i class="fa fa-cart-plus"></i></a name="btn_cart"></span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            '; 
        }
    }
    
    if(count($check_product) > 0) {
        echo '
            <div class="product-title-recomment pomotion-product-title">
                <h3 class="title-recomment">Promotion</h3>  
                <div class="view-title-icon">
                    <a href="'.site_url().'/shop"> <a href="'.site_url().'/shop" class="view-all">View All</a><a href="'.site_url().'/shop"><i class="fa fa-chevron-right"></i></a> </a>
                </div>
            </div>
            <div class="product-promotion-display">
                <div class="product-slide-wrap product-slide-wrap-auto ">  
                    '.$slide_promotion.'
                </div>
            </div>
        ';
    }
}

function custom_promotion_product() {
    ob_start();
    promotion_product();
    return ob_get_clean();
}

// Register a new shortcode: [custom_promotion_product]
add_shortcode('woo_promotion_product', 'custom_promotion_product');

function add_remove_wistlist_web() {
    global $wpdb;
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';

    // Code for save wistlist
    // if(isset($_POST['btn_submit'])){
    //     $user_id = $_POST['user_id'];
    //     $product_id = $_POST['product_id']; 

        $user_wishlist = $wpdb->get_row("SELECT ID FROM `ph0m31e_yith_wcwl_lists` WHERE `user_id` = $user_id");

        if(count($user_wishlist->ID)>0){
            $wpdb->insert('ph0m31e_yith_wcwl',
                array(
                        'prod_id' 	        => $product_id,
                        'quantity' 			=> 1 ,
                        'user_id'			=> $user_id ,
                        'wishlist_id'		=> $user_wishlist->ID ,
                        'original_price' 	=> $price,
                        'type'				=> 'wishlist',
                        'original_currency' => 'USD',
                        'dateadded' 		=> date('Y-m-d H:m:s') ,
                        'on_sale' 			=> 0 
                )                           
            );
        } 
        else{
            $wpdb->insert('ph0m31e_yith_wcwl_lists', 
                array(  
                    'user_id' 			=> $user_id,
                    'wishlist_slug' 	=> '',
                    'wishlist_token' 	=> uniqid(), //wishlist token is not using for feature app & web but database required
                    'wishlist_privacy' 	=> '',
                    'is_default' 		=> 1,
                    'dateadded' 		=> date('Y-m-d H:m:s'),  
                )
            );
            $latest_wishlist_id = $wpdb->get_row("SELECT ID FROM `ph0m31e_yith_wcwl_lists` ORDER BY id DESC");
                $wpdb->insert('ph0m31e_yith_wcwl', 
                    array(
                        'prod_id' 			=> $product_id,   
                        'prod_id' 			=> $product_id,   
                        'quantity' 			=> 1,
                        'user_id'			=> $user_id,
                        'wishlist_id'		=> $latest_wishlist_id->ID,
                        'position'			=> 0,
                        'original_price' 	=> $price,
                        'type'				=> 'wishlist',
                        'original_currency' => 'USD',
                        'dateadded' 		=> date('Y-m-d H:m:s'),
                        'on_sale' 			=> 0
                    )   
                );    
        }   
    // } 

    wp_send_json_success([
        'user_id'=> $user_id ,
        'product_id'=> $product_id ,
    ]);
}

add_action('wp_ajax_add_remove_wistlist_web', 'add_remove_wistlist_web');
add_action('wp_ajax_nopriv_add_remove_wistlist_web', 'add_remove_wistlist_web');
