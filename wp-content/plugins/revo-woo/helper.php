<?php
	
	global $wpdb;

	session_start();

	if (!function_exists('revo_url')) {

	    function revo_url(){
	    	return plugin_dir_url( __FILE__ );
	    }

	}

	if (!function_exists('get_page')) {

	    function get_page($page_name){
	    	return plugin_dir_path( __FILE__ ).'page/'.$page_name;
	    }

	}

	if (!function_exists('get_logo')) {

	    function get_logo($type = 'color'){
	    	return revo_url() . ( $type == 'color' ? 'assets/logo/logo-revo.png' : 'assets/logo/logo-bw.png' );
	    }

	}

	if (!function_exists('check_exist_database')) {

	    function check_exist_database($tablename){
	    	global $wpdb;
			if ($wpdb) {
				$exit_tabel =" SHOW TABLES LIKE '$tablename' ";
				if(count($wpdb->get_results($exit_tabel)) == 0) {
					return true;
				}
			}
			
			return false;
	    }

	}

	if (!function_exists('createThumbnail')) {

	    function createThumbnail($src, $dest, $targetWidth, $targetHeight = null) {

		    // 1. Load the image from the given $src
		    // - see if the file actually exists
		    // - check if it's of a valid image type
		    // - load the image resource

		    // get the type of the image
		    // we need the type to determine the correct loader
		    $type = exif_imagetype($src);

		    // if no valid type or no handler found -> exit
		    if (!$type || !IMAGE_HANDLERS[$type]) {
		        return null;
		    }

		    // load the image with the correct loader
		    $image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);

		    // no image found at supplied location -> exit
		    if (!$image) {
		        return null;
		    }


		    // 2. Create a thumbnail and resize the loaded $image
		    // - get the image dimensions
		    // - define the output size appropriately
		    // - create a thumbnail based on that size
		    // - set alpha transparency for GIFs and PNGs
		    // - draw the final thumbnail

		    // get original image width and height
		    $width = imagesx($image);
		    $height = imagesy($image);

		    // maintain aspect ratio when no height set
		    if ($targetHeight == null) {

		        // get width to height ratio
		        $ratio = $width / $height;

		        // if is portrait
		        // use ratio to scale height to fit in square
		        if ($width > $height) {
		            $targetHeight = floor($targetWidth / $ratio);
		        }
		        // if is landscape
		        // use ratio to scale width to fit in square
		        else {
		            $targetHeight = $targetWidth;
		            $targetWidth = floor($targetWidth * $ratio);
		        }
		    }

		    // create duplicate image based on calculated target size
		    $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

		    // set transparency options for GIFs and PNGs
		    if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

		        // make image transparent
		        imagecolortransparent(
		            $thumbnail,
		            imagecolorallocate($thumbnail, 0, 0, 0)
		        );

		        // additional settings for PNGs
		        if ($type == IMAGETYPE_PNG) {
		            imagealphablending($thumbnail, false);
		            imagesavealpha($thumbnail, true);
		        }
		    }

		    // copy entire source image to duplicate image and resize
		    imagecopyresampled(
		        $thumbnail,
		        $image,
		        0, 0, 0, 0,
		        $targetWidth, $targetHeight,
		        $width, $height
		    );


		    // 3. Save the $thumbnail to disk
		    // - call the correct save method
		    // - set the correct quality level

		    // save the duplicate version of the image to disk
		    return call_user_func(
		        IMAGE_HANDLERS[$type]['save'],
		        $thumbnail,
		        $dest,
		        IMAGE_HANDLERS[$type]['quality']
		    );

	    }

	}

	if (!function_exists('compress')) {

	    function compress($source, $destination, $quality) {

		    $info = getimagesize($source);
		    if ($info['mime'] == 'image/jpeg') 
		        $image = imagecreatefromjpeg($source);

		    elseif ($info['mime'] == 'image/gif') 
		        $image = imagecreatefromgif($source);

		    elseif ($info['mime'] == 'image/png') 
		        $image = imagecreatefrompng($source);

		    imagejpeg($image, $destination, $quality);

		    return $destination;
		}

	}

	if (!function_exists('get_product_varian')) {
		function get_product_varian(){
		    $all_product =[];
		    $args = array(
		    			'limit'  => -1, // All products
    					'status' => 'publish'
    				);
		    $products = wc_get_products( $args );
			
		    foreach ($products as $key => $value) {
				$product = wc_get_product($value->get_id());
				$current_products = $product->get_children();
                if(!empty($current_products)) { 
                    $item_code = wc_get_product(json_encode($current_products[0]))->get_sku();
                }
                else { 
                    $item_code = '';
                }
                # code...
                array_push($all_product,[
                    'id'   => $value->get_id(),
                    'text' => $value->get_title() ,
                    'sku'  => $item_code
                ]);
		    }
		    return json_encode($all_product);
		}
	}

	if (!function_exists('get_products_id')) {
		function get_products_id($args){
		    $all_product =[];
		    $products = wc_get_products( $args );
		    foreach ($products as $key => $value) {
		        // array_push($all_product,[
		        //     'id' => $value->get_id(),
		        // ]);
		        $all_product[] = $value->get_id();
		    }
		    return $all_product;
		}
	}

	if (!function_exists('get_product_varian_detail')) {
		function get_product_varian_detail($xid){
		    $products = wc_get_products(['include' => [$xid]]);
		    return $products;
		}
	}

	if (!function_exists('get_categorys')) {
		function get_categorys(){
		    $categories = get_terms( ['taxonomy' => 'product_cat'] );
		    $all_categories =[];
		    foreach ($categories as $key => $value) {
		        # code...
		        array_push($all_categories,[
		            'id' => $value->term_id,
		            'text' =>$value->name
		        ]);
		    }
		    return json_encode($all_categories);
		}
	}

	// @get coupon
	if (!function_exists('get_coupons')) {
		function get_coupons(){
			$all_coupons = array(
				'post_type' 	 => 'shop_coupon',
				'posts_status' 	 => 'publish',
				'posts_per_page' => -1 ,
			);
		
			$query = new WP_Query($all_coupons);
			$all_coupon = [];
			if($query->have_posts())
			{
				while($query->have_posts())
				{
					$query->the_post();
					array_push($all_coupon,[
						'id'   => get_the_id(),
						'text' => get_the_title()
					]);
				}
				return json_encode($all_coupon);
			}
		}
	}

	if (!function_exists('get_categorys_detail')) {
		function get_categorys_detail($id){
		    $categorie = get_terms( ['term_taxonomy_id' => $id] );    
		    return $categorie;
		}

	}

	if (!function_exists('current_url')) {
		function current_url(){
		    global $wp;
			return add_query_arg($wp->query_vars);
		}

	}


	if (!function_exists('formatted_date')) {

	    function formatted_date($timestamp, $format = "d/m/Y - H:i"){

	        return date($format, strtotime($timestamp));

	    }

	}

	if (!function_exists('cek_is_active')) {

	    function cek_is_active($data){

	        return '<span class="badge '.($data == 1 ? 'badge-success' : 'badge-danger').' p-2">'.($data == 1 ? 'Active' : 'Non Active').'</span>';

	    }

	}

	if (!function_exists('cek_type')) {

	    function cek_type($type){
	    	$data['image'] = '';
	    	$data['text'] = '';
	    	if ($type == 'special') {
	    		$data['image'] = revo_url().'/assets/extend/images/example_special.jpg';
	    		$data['text'] = 'Pannel 1 ( Default : Special )';
	    	}

	    	if ($type == 'our_best_seller') {
	    		$data['image'] = revo_url().'/assets/extend/images/example_bestseller.jpg';
	    		$data['text'] = 'Pannel 2 ( Default : Our Best Seller )';
	    	}

	    	if ($type == 'recomendation') {
	    		$data['image'] = revo_url().'/assets/extend/images/example_recomend.jpg';
	    		$data['text'] = 'Pannel 2 ( Default : Recomendation )';
	    	}

	    	$data['text'] = '<span class="badge badge-primary p-2">'.$data['text'].'</span>';
	        return $data;

	    }

	}

	if (!function_exists('cek_flash_sale_end')) {

	    function cek_flash_sale_end(){

	    	global $wpdb;

	    	$date = date('Y-m-d H:i:s');

	        $get = $wpdb->get_results("SELECT id FROM `revo_flash_sale` WHERE is_deleted = 0 AND start < '".$date."' AND end < '".$date."' AND is_active = 1", OBJECT);

	        foreach ($get as $key => $value) {
	        	$query = $wpdb->update( 
	              'revo_flash_sale', ['is_active' =>  '0'], 
	              array( 'id' => $value->id) 
	            );
	        }

	    }

	}

	if (!function_exists('buttonQuestion')) {

	    function buttonQuestion(){

	    	return '<a href="javascript:void(0)" data-toggle="modal" data-target="#question" class="badge py-1 px-2  badge-secondary rounded"><i class="fa fa-question font-size-15 font-weight-normal"></i></a>';

	    }

	}

	if (!function_exists('get_user')) {

	    function get_user($email){

	    	$user = get_user_by('email', $email);

	    	return $user;

	    }

	}

	if (!function_exists('get_authorization_header')) {
		function get_authorization_header() {
			if ( ! empty( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
				return wp_unslash( $_SERVER['HTTP_AUTHORIZATION'] ); // WPCS: sanitization ok.
			}

			if ( function_exists( 'getallheaders' ) ) {
				$headers = getallheaders();
				// Check for the authoization header case-insensitively.
				foreach ( $headers as $key => $value ) {
					if ( 'authorization' === strtolower( $key ) ) {
						return $value;
					}
				}
			}

			return '';
		}
	}

	if (!function_exists('security_0auth')) {

	    function security_0auth(){
	    	include_once plugin_dir_path(__FILE__). 'Revo_authentication.php';
	    }

	}

	if (!function_exists('query_revo_mobile_variable')) {

	    function query_revo_mobile_variable($slug, $order_by = 'created_at'){
	    	global $wpdb;
	    	return $wpdb->get_results("SELECT * FROM `revo_mobile_variable` WHERE `slug` IN ($slug) AND is_deleted = 0 ORDER BY $order_by DESC", OBJECT);
	    }

	}

	if (!function_exists('query_hit_products')) {

	    function query_hit_products($id,$user_id){
	    	global $wpdb;
	    	// return $wpdb->get_row("SELECT count(id) as is_wistlist FROM `revo_hit_products` WHERE products = '$id' AND user_id = '$user_id' AND type = 'wistlist'", OBJECT);
			return $wpdb->get_row("SELECT COUNT(ID) as is_wishlist FROM `ph0m31e_yith_wcwl` WHERE `prod_id` = '$id' AND `user_id` = '$user_id'", OBJECT);
		}

	}

	if (!function_exists('query_all_hit_products')) {

	    function query_all_hit_products($user_id){
	    	global $wpdb;
	    	// return $wpdb->get_results("SELECT * FROM `revo_hit_products` WHERE user_id = '$user_id' AND type = 'wistlist'", OBJECT);
			return $wpdb->get_results("SELECT * FROM `ph0m31e_yith_wcwl` WHERE user_id = '$user_id' AND type = 'wishlist'", OBJECT);
	    }

	}

	if (!function_exists('insert_update_MV')) {

	    function insert_update_MV($where, $id,  $desc){
	    	global $wpdb;

	        $query_data = $where;
	        $query_data['description'] = $desc;

	        $success = 0;
	        if ($id != 0) {
	          $where['id'] = $id;
	          $wpdb->update('revo_mobile_variable',$query_data,$where);
	          if (@$wpdb->show_errors == false) {
	            $success = 1;
	          }
	        }else{
	          $wpdb->insert('revo_mobile_variable',$query_data);
	          if (@$wpdb->insert_id > 0) {
	            $success = 1;
	          }
	        }

	        return $success;
	    }

	}

	if (!function_exists('access_key')) {

	   	function access_key(){
		    global $wpdb;
		    $query = "SELECT * FROM `revo_access_key` ORDER BY created_at DESC limit 1";
		    return $wpdb->get_row($query, OBJECT);
		 }

	}

	if (!function_exists('get_products_woocomerce')) {

	   	function get_products_woocomerce($layout, $api, $request){
	        $params = array('order'=> 'desc', 'orderby' => 'date');
	        if (isset($layout['category'])) {
	            $params['category'] = $layout['category'];
	        }
	        if (isset($layout['tag'])) {
	            $params['tag'] = $layout['tag'];
	        }
	        if (isset($layout['feature'])) {
	            $params['feature'] = $layout['feature'];
	        }

	        $request->set_query_params($params);

	        $response = $api->get_items($request);
	        return $response->get_data();
	    }

	}

	if (!function_exists('send_FCM')) {

	   	function send_FCM($token,$notification,$extend){

            $token = json_encode($token);

	   		$data = access_key();
	   		$server_key = $data->firebase_servey_key;
		    
		    if ($server_key) {
		    	$body = array(
		                  "notification" => $notification,
		                  "registration_ids" => $token,
		                  "data" => $extend
	                );

	          	$notification = json_encode($notification);
	          	// $body = '{ "notification": { "title": "coba title", "body": "coba body" }, "to" : "cAayzcaKRjykBzw_3LxnEN:APA91bFJi8zxwtA1rRRXkQ1P5NM2vo-6ZiMLa6zRFcATw6eImYZLun7EK79Rs7ro9ojjDMwkcTIU3Vj4SLQiqL4tXixuqUU_ZStEpEaiG5tWyWVmDceIghMaK-jJBsMfkT7s2MH5N2Gy" }';
	          	$curl = curl_init();
	          	curl_setopt_array($curl, array(
					CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 30,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_HTTPHEADER => array(
						"Content-Type: application/json",
						"Authorization: key=$server_key"
					),
	              	CURLOPT_POSTFIELDS =>'{
						"notification": '.$notification.'
						"registration_ids": '.$token.'
					}',
	          	));

	          	$response = curl_exec($curl);

	          	$err = curl_error($curl);

	          	curl_close($curl);

	          	if ($err) {
	          		return 'error';
	          	}
                // biz_write_log($request ,'A Log FCM 16-12-2022');
	          	return json_decode($response);
		    }

		    return 'error';
		}

	}

	if (!function_exists('cek_raw')) {

	   	function cek_raw($key = ''){
		    $json = file_get_contents('php://input');
        	$params = json_decode($json);

        	
        	if ($params AND $key) {
        		if (@$params->$key) {
        			$text = $params->$key;

     //    			// Strip HTML Tags
					// $clear = strip_tags($text);
					// // Clean up things like &amp;
					// $clear = html_entity_decode($clear);
					// // Strip out any url-encoded stuff
					// $clear = urldecode($clear);
					// // Replace Multiple spaces with single space
					// $clear = preg_replace('/ +/', ' ', $clear);
					// // Trim the string of leading/trailing space
					// $clear = trim($clear);

					return $text;
        		}
        	}

        	return '';
		 }

	}

	if (!function_exists('get_user_token')) {

	   	function get_user_token($where = ''){
		    global $wpdb;
		    $query = "SELECT token FROM `revo_token_firebase` $where GROUP BY token ORDER BY created_at DESC";
		    return $wpdb->get_results($query, OBJECT);
		 }
	}

	if (!function_exists('rv_total_sales')) {

	   	function rv_total_sales( $product ) {
			$product_id = $product->get_id();
			if ( ! $product ) { return 0; }

			$total_sales = is_a( $product, 'WC_Product_Variation' ) ? get_post_meta( $product_id, 'total_sales', true ) : $product->get_total_sales();
			
			return $total_sales;
		}
	}

	if (!function_exists('load_Revo_Flutter_Mobile_App_Public')) {

		function load_Revo_Flutter_Mobile_App_Public(){
			require (plugin_dir_path( __FILE__ ).'Revo_Flutter_Mobile_App_Public.php');
			$revo_loader = new Revo_Flutter_Mobile_App_Public();
			return $revo_loader;
		}

	}

	if (!function_exists('get_popular_categories')) {

		function get_popular_categories(){
			global $wpdb;
			$data_categories = $wpdb->get_results("SELECT title,categories FROM revo_popular_categories WHERE is_deleted = 0 ORDER BY created_at DESC", OBJECT);

			return $data_categories;
		}

	}

	if (!function_exists('data_default_MV')) {

	    function data_default_MV($type){
	    	
	    	if ($type == 'splashscreen') {
	    		$data = array(
	    				'slug' => 'splashscreen', 
	    				'title' => '', 
		            	'image' => get_logo(), 
		            	'description' => 'Welcome', 
		        	);
	    	}
		    
	    	if ($type == 'intro_page_status') {
	    		$data = array(
	    				'slug' => 'intro_page_status', 
	    				'title' => '', 
		            	'image' => get_logo(), 
		            	'description' => 'show', 
		        	);
	    	}

	    	if ($type == 'kontak_wa') {
	    		$data = array(
	    				'slug' => 'kontak', 
			            'title' => 'wa', 
			            'image' => '',
			            'description' => '', 
		        	);
	    	}

	    	if ($type == 'kontak_phone') {
	    		$data = array(
	    				'slug' => 'kontak', 
			            'title' => 'phone', 
			            'image' => '',
			            'description' => '', 
		        	);
	    	}

	    	if ($type == 'kontak_sms') {
	    		$data = array(
	    				'slug' => 'sms', 
            			'title' => 'link sms', 
			            'image' => '',
			            'description' => '', 
		        	);
	    	}

	    	if ($type == 'about') {
	    		$data = array(
	    				'slug' => 'about', 
            			'title' => 'link about', 
			            'image' => '',
			            'description' => '', 
		        	);
	    	}

	    	if ($type == 'privacy_policy') {
	    		$data = array(
	    				'slug' => 'privacy_policy', 
            			'title' => 'link Privacy Policy', 
			            'image' => '',
			            'description' => '', 
		        	);
	    	}

	    	if ($type == 'cs') {
	    		$data = array(
	    				'slug' => 'cs', 
            			'title' => 'customer service', 
			            'image' => '',
			            'description' => '', 
		        	);
	    	}

	    	if ($type == 'logo') {
	    		$data = array(
	    				'slug' => 'logo', 
            			'title' => 'Mobile Revo Apps', 
            			'image' => get_logo(), 
			            'description' => '', 
		        	);
	    	}

	    	if ($type == 'intro_page_1') {
	    		$data = array(
	    				'slug' => 'intro_page', 
		              	'title' => 'Manage Everything', 
		              	'image' => revo_url().'assets/extend/images/revo-woo-onboarding-01.jpg', 
		              	'description' => 'Completely manage your store from the dashboard, including onboarding/intro changes, sliding banners, posters, home, and many more.', 
		        	);
	    	}

	    	if ($type == 'intro_page_2') {
	    		$data = array(
	    				'slug' => 'intro_page', 
			            'title' => 'Support All Payments', 
			            'image' => revo_url().'assets/extend/images/revo-woo-onboarding-02.jpg', 
			            'description' => 'Pay for the transaction using all the payment methods you want. Including paypal, razorpay, bank transfer, BCA, Mandiri, gopay, or ovo.', 
		        	);
	    	}

	    	if ($type == 'intro_page_3') {
	    		$data = array(
	    				'slug' => 'intro_page', 
			            'title' => "Support All Shipping Methods", 
			            'image' => revo_url().'assets/extend/images/revo-woo-onboarding-03.jpg', 
			            'description' => "The shipping method according to your choice, which is suitable for your business. All can be arranged easily.", 
		        	);
	    	}

	    	if ($type == 'empty_images_1') {
	    		$data = array(
	    				'slug' => 'empty_image', 
			            'title' => "404_images", 
			            'image' => revo_url().'assets/extend/images/404.png', 
			            'description' => "450 x 350px", 
		        	);
	    	}
	    	if ($type == 'empty_images_2') {
	    		$data = array(
	    				'slug' => 'empty_image', 
			            'title' => "thanks_order", 
			            'image' => revo_url().'assets/extend/images/thanks_order.png', 
			            'description' => "600 x 420px", 
		        	);
	    	}
	    	if ($type == 'empty_images_3') {
	    		$data = array(
	    				'slug' => 'empty_image', 
			            'title' => "empty_transaksi", 
			            'image' => revo_url().'assets/extend/images/no_transaksi.png', 
			            'description' => "260 x 300px", 
		        	);
	    	}
	    	if ($type == 'empty_images_4') {
	    		$data = array(
	    				'slug' => 'empty_image', 
			            'title' => "search_empty", 
			            'image' => revo_url().'assets/extend/images/search_empty.png', 
			            'description' => "260 x 300px", 
		        	);
	    	}

	    	if ($type == 'empty_images_5') {
	    		$data = array(
	    				'slug' => 'empty_image', 
			            'title' => "login_required", 
			            'image' => revo_url().'assets/extend/images/404.png', 
			            'description' => "260 x 300px", 
		        	);
	    	}
	    	return $data;
	    }

	}

?>