<?php

use AC\Helper\Arrays;

	function rest_home(){
		$rest_slider = rest_slider('get');
		$rest_categories = rest_categories('get');

		$result['main_slider'] = $rest_slider;
		$result['mini_categories'] = $rest_categories;
		$result['mini_banner'] = rest_mini_banner('result');
		$result['general_settings'] = rest_get_general_settings('result');
		$get_intro = rest_get_intro_page('result');
		$result = array_merge($result,$get_intro);


		$revo_loader = load_Revo_Flutter_Mobile_App_Public();
		$result['products_flash_sale'] = rest_product_flash_sale('result',$revo_loader);
		$result['products_special'] = rest_additional_products('result','special',$revo_loader);
		$result['products_our_best_seller'] = rest_additional_products('result','our_best_seller',$revo_loader);
		// $result['products_recomendation'] = rest_additional_products('result','recomendation',$revo_loader);

		echo json_encode($result);
		exit();
	}

	//Add Multi Customer
	function add_multi_customer() {
		if(!empty($_GET['ck'] && !empty($_GET['cs']))) {
			if($_GET['ck'] == CONSUMER_KEY && $_GET['cs'] == CONSUMER_SECRET) {

				$username     = cek_raw('username');
				$first_name   = cek_raw('first_name');
				$last_name    = cek_raw('last_name');
				$email        = cek_raw('email');
				$password     = cek_raw('password');
				$user_odoo_id = cek_raw('user_odoo_id'); 
				$user_type    = cek_raw('user_type');
				$phone    	  = cek_raw('phone');

				if(
					!empty($username) && !empty($first_name) && !empty($last_name) 
					&& !empty($email) && !empty($password) && !empty($user_odoo_id)
					&& !empty($user_type) && !empty($phone)
				) { 
					$user_data = array (
						'user_login'  =>  $username,
						'user_email'  =>  $email,
						'user_pass'   =>  $password,
						'first_name'  =>  $first_name,
						'last_name'   =>  $last_name,
						'role'        => 'customer'
					);
				
					$user_id = wp_insert_user( $user_data ) ;
					if(!empty($user_id)) {
						//Update User Meta
						update_user_meta( $user_id, 'first_name', $first_name );
						update_user_meta( $user_id, 'last_name', $last_name );
						update_user_meta( $user_id, 'last_name', $last_name );
						update_user_meta( $user_id, 'phone_number', $phone );
						update_user_meta( $user_id, 'email', $email );
						update_user_meta( $user_id, 'user_type', $user_type );
						add_user_meta( $user_id, 'odoo_id', $user_odoo_id, true );
						update_user_meta( $user_id, 'register_status', 1 );

						$result = array(
							'code'   => 200,
							'status' => 'User Registerd'
						);
					}
				}
				else {
					$result = array(
						'code'   => 500,
						'status' => 'Field requierd'
					);
				}

			}
			else {
				$result = array(
					'code' => 401,
					'status' => 'Unauthorized'
				);
			}
		}
		else {
			$result = array(
				'code' => 401,
				'status' => 'Unauthorized'
			);
		}
		return $result;
	}

	function rest_product_details()
	{
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		// $req = (Object) array_merge($_POST,$_GET,$_);//Get list coupon
		$revo_loader = load_Revo_Flutter_Mobile_App_Public();
		$search = cek_raw('product_id') ?? get_page_by_path( cek_raw('slug'), OBJECT, 'product' );
		$product = wc_get_product($search);
		//return ['products'=>$revo_loader->get_products(),'id'=>cek_raw('product_id')];
		return $revo_loader->reformat_product_result($product);
	}

	function rest_product_lists()
	{
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$revo_loader = load_Revo_Flutter_Mobile_App_Public();
		$args = [
			'limit'=> cek_raw('perPage') ?? 1,
			'page'=> cek_raw('page') ?? 10,
			'featured' => cek_raw('featured'),
			'category' => cek_raw('category'),
			'orderby' => cek_raw('orderby') ?? 'date',
			'order'  => cek_raw('order') ?? 'ASC',
		];

		if ($parent = cek_raw('parent')) {
			$args['parent'] = $parent;
		}
		if ($include = cek_raw('include')) {
			$args['include'] = $include;
		}
		if ($search = cek_raw('search')) {
			$args['like_name'] = $search;
		}
		
		$products = wc_get_products( $args );
		$results = array();
		foreach ($products as $i => $product) {
			array_push($results,$revo_loader->reformat_product_result($product));
		}

		echo json_encode($results);
		exit;
	}

	//Get Recommend Product Home Page
	function rest_recommend_product() {
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie'); 
		$revo_loader = load_Revo_Flutter_Mobile_App_Public();
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		global $wpdb;

		$offset = $_GET['offset'];
		$limit  = $_GET['limit'];

		$products = $wpdb->get_results("SELECT * FROM revo_extend_products WHERE is_deleted = 0 AND is_active = 1 AND type = 'recomendation'  ORDER BY id DESC", OBJECT);
		$arr_products = $products[0]->products;
		$data_product = array_slice(json_decode($arr_products), $offset, $limit);  
	
		$result = [];
		$list_products = [];
		foreach ($products as $key => $value) { 
		  if (!empty($value->products)) { 

			$_POST['include'] = json_encode($data_product);
			$_POST['limit'] = 1000;
			$list_products = $revo_loader->get_products();
	
			foreach($list_products as $k => $v) {
			  $prod_id = $v['id']; 
			  if(!empty($user_id)) {
				$favorite_id = $wpdb->get_results("SELECT ID FROM ph0m31e_yith_wcwl WHERE prod_id = '$prod_id' AND user_id = '$user_id'");
				if(count($favorite_id) > 0) {
				   foreach($favorite_id as $fav) { 
					if(count($fav->ID) > 0) { 	
					  $favorite_status = true;
					}else { 
					  $favorite_status = false;
					}
				  }
				}else {
				  $favorite_status = false;
				}
	
			  $list_products[$k]["favorite_status"] = $favorite_status;
			  }
			  $list_products[$k]["favorite_status"] = $favorite_status;
			}
		  }
		  array_push($result, [
			'title' => $value->title,
			'description' => $value->description,
			'products' => $list_products,
			'categories' => json_decode($value->category_id),
		  ]);
		}
	
		if ($type == 'rest') {
		  echo json_encode($result);
		  exit();
		}else{
		  return $result;
		}
	}

	function rest_additional_products($type = 'rest',$product_type,$revo_loader){

	require (plugin_dir_path( __FILE__ ).'../helper.php');
	$cookie = cek_raw('cookie'); 
	$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
	global $wpdb;
	// $user_id = 186;

	$where = '';

	if ($product_type == 'special') {
		$where = "AND type = 'special'";
	}elseif ($product_type == 'our_best_seller') {
		$where = "AND type = 'our_best_seller'";
	}

	$products = $wpdb->get_results("SELECT * FROM revo_extend_products WHERE is_deleted = 0 AND is_active = 1 $where  ORDER BY id DESC", OBJECT);

	$result = [];
	$list_products = [];
	foreach ($products as $key => $value) {

		if (!empty($value->products)) {
		$_POST['include'] = $value->products;
		if ($product_type=='special' || $product_type=='our_best_seller') {
			$_POST['limit'] = 1000;
		}
		$list_products = $revo_loader->get_products();

		foreach($list_products as $k => $v) {
			$prod_id = $v['id']; 
			if(!empty($user_id)) {  
			$favorite_id = $wpdb->get_results("SELECT ID FROM ph0m31e_yith_wcwl WHERE prod_id = '$prod_id' AND user_id = '$user_id'");
			foreach($favorite_id as $fav) { 
				if(count($fav->ID) > 0) { 
				$favorite_status = true;
				} 
				else {
				$favorite_status = false;
				}
			}
			$list_products[$k]["favorite_status"] = $favorite_status;
			}
			$list_products[$k]["favorite_status"] = $favorite_status;
			
		}
		}


		array_push($result, [
		'title' => $value->title,
		'description' => $value->description,
		'products' => $list_products,
		'categories' => json_decode($value->category_id),
		]);

	}

	if ($type == 'rest') {
		echo json_encode($result);
		exit();
	}else{
		return $result;
	}
	}

	function rest_product_flash_sale($type = 'rest',$revo_loader){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		cek_flash_sale_end();
		$date = date('Y-m-d H:i:s');
		$data_flash_sale = $wpdb->get_results("SELECT * FROM `revo_flash_sale` WHERE is_deleted = 0 AND start <= '".$date."' AND end >= '".$date."' AND is_active = 1  ORDER BY id DESC LIMIT 1", OBJECT);

		$result = [];
		$list_products = [];
		foreach ($data_flash_sale as $key => $value) {
			if (!empty($value->products)) {
				$_POST['include'] = $value->products;
				$list_products = $revo_loader->get_products();
			}
			array_push($result, [
				'id' => (int) $value->id,
				'title' => $value->title,
				'start' => $value->start,
				'end' => $value->end,
				'image' => $value->image,
				'products' => $list_products,
			]);
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function index_home(){
		$rest_slider = rest_slider('get');
		$rest_categories = rest_categories('get');

		$result['slider'] = $rest_slider;
		$result['categories'] = $rest_categories;

		echo json_encode($result);
		exit();
	}

	function rest_slider($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$data_banner = $wpdb->get_results("SELECT * FROM revo_mobile_slider WHERE is_deleted = 0 ORDER BY order_by DESC", OBJECT);

		$result = [];
		foreach ($data_banner as $key => $value) { 

			$start_date        = date_create($value->start_date);
			$start_date_format = date_format($start_date ,'Ymd');
			$end_date          = date_create($value->end_date);
			$end_date_format   = date_format($end_date ,'Ymd');
			$current_date      = date('Ymd');

			if(($current_date >= $start_date_format && $current_date <= $end_date_format) 
				|| ($value->start_date == "0000-00-00" && $value->start_date == "0000-00-00")) { 

				array_push($result, [
					'product' 		=> (int) $value->product_id,
					'name'			=> $value->product_name,
					'type'			=> $value->is_type,
					'title_slider' 	=> $value->title,
					'image' 		=> $value->images_url,
				]);
			}

		}

		if (empty($result)) {
			for ($i=0; $i < 3; $i++) { 
				array_push($result, [
					'product' => (int) '0',
					'title_slider' => '',
					'image' => revo_url().'assets/extend/images/default_banner.png',
				]);
			}
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_mini_banner($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$where = '';
		if ($_GET['blog_banner']) {
			$where = "AND type = 'Blog Banner' ";
		}
		$data_banner = $wpdb->get_results("SELECT * FROM revo_list_mini_banner WHERE is_deleted = 0 $where ORDER BY order_by ASC", OBJECT);
		// $data_banner = $wpdb->get_results("SELECT * FROM `revo_list_mini_banner` WHERE is_deleted = 0 AND `end_date` >= `start_date` AND `start_date` <= `end_date` ORDER BY `order_by` ASC");
		$result = [];
		if ($_GET['blog_banner']) {

			foreach ($data_banner as $key => $value) {
				if ($value->type == 'Blog Banner') {
					$result[] = [
							'product' => (int) $value->product_id,
							'title_slider' => ($value->title != NULL ? $value->title : ''),
							'type' => $value->type,
							'image' => $value->image,
						];
				}else{
					$result[] = array(
							'product' => (int) '0',
							'title_slider' => '',
							'type' => 'Blog Banner',
							'image' => revo_url().'assets/extend/images/defalt_mini_banner.png',
						);
				}

				break;
			}

		}else{
			$result_1 = [];
			$type_1 = 'Special Promo';
			$result_2 = [];
			$type_2 = 'Love These Items';
			
			foreach ($data_banner as $key => $value) {
				$start_date = date_create($value->start_date);
				$start_date_format = date_format($start_date ,'Ymd');
				$end_date   = date_create($value->end_date);
				$end_date_format = date_format($end_date ,'Ymd');
				$current_date      = date('Ymd');
				// $image_item = $value->image;
				if(($current_date >= $start_date_format && $current_date <= $end_date_format) 
				|| ($value->start_date == "0000-00-00" && $value->end_date == "0000-00-00")) {
					if ($value->type == $type_2) {
						array_push($result_2, [
							'product' => (int) $value->product_id,
							'link_type'=>$value->is_type,
							'title_slider' =>$value->product_name,
							'type' => $value->type,
							'image' => $value->image
						]);
					}
				}
				if ($value->type == $type_1) {
					array_push($result_1, [
						'product' => (int) $value->product_id,
						'link_type'=>$value->is_type,
						'title_slider' =>$value->product_name,
						'type' => $value->type,
						'image' => $value->image
					]);
				}
			}

			if (count($result_1) < 4) {
				$total_result_1 = 4 - count($result_1);
				for ($i=0; $i < $total_result_1; $i++) { 
					array_push($result_1, [
						'product' => (int) '0',
						'title_slider' => '',
						'type' => $type_1,
						'image' => revo_url().'assets/extend/images/defalt_mini_banner.png',
					]);
				}
			}

			if (count($result_2) < 4) {
				$total_result_2 = 4 - count($result_2);
				for ($i=0; $i < $total_result_2; $i++) {
					array_push($result_2, [
						'product' => (int) '0',
						'title_slider' => '',
						'type' => $type_2,
						'image' => revo_url().'assets/extend/images/defalt_mini_banner.png',
					]);
				}
			}

			$result = array_merge($result_1,$result_2);
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_categories($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$data_banner = $wpdb->get_results("SELECT * FROM revo_list_categories WHERE is_deleted = 0 ORDER BY order_by ASC", OBJECT);
		// $data_banner = $wpdb->get_results("SELECT * FROM revo_list_categories WHERE is_deleted = 0 ORDER BY order_by ASC ", OBJECT);

		$result = [];

		if ($_GET['show_popular']) {
			array_push($result, [
				'categories' => (int) '9911',
				'title_categories' => 'Popular Categories',
				'image' => revo_url().'assets/extend/images/popular.png',
			]);
		}

		foreach ($data_banner as $key => $value) {
			array_push($result, [
				'categories' => (int) $value->category_id,
				'title_categories' => $value->category_name,
				'image' => $value->image,
			]);
		}

		if (empty($result)) {
			for ($i=0; $i < 5; $i++) { 
				array_push($result, [
					'categories' => (int) '0',
					'title_categories' => 'Dummy Categories',
					'image' => revo_url().'assets/extend/images/default_categories.png',
				]);
			}
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_categories_list($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$result = [];

		$taxonomy     = 'product_cat';
        $orderby      = 'name';  
        $show_count   = 1;      // 1 for yes, 0 for no
        $pad_counts   = 0;      // 1 for yes, 0 for no
        $hierarchical = 1;      // 1 for yes, 0 for no  
        $title        = '';  
        $empty        = 0;

        $args = array(
             'taxonomy'     => $taxonomy,
             //'orderby'      => $orderby,
             'show_count'   => $show_count,
             'pad_counts'   => $pad_counts,
             'hierarchical' => $hierarchical,
             'title'     => $title,
             'hide_empty'   => $empty,
             'menu_order' => 'asc',
             'parent' => 0
        );

        if (cek_raw('page')) {
        	$args['offset'] = cek_raw('page');
        } 

        if (cek_raw('limit')) {
        	$args['number'] = cek_raw('limit');
        }

        if (!cek_raw('parent')) {
        	$data_categories = get_popular_categories();
			if (!empty($data_categories)) {
				array_push($result, [
					'id' => (int) '9911',
					'title' => 'Popular Categories',
					'description' => '',
					'parent' => 0,
					'count' => 0,
					'image' => revo_url().'assets/extend/images/popular.png',
				]);
			}

       	 	$categories = get_categories( $args );
	 		foreach ($categories as $key => $value) {
	 			if ($value->name != 'Uncategorized') {
	 				$image_id = get_term_meta( $value->term_id, 'thumbnail_id', true );
		            $image = '';

		            if ( $image_id ) {
		                $image = wp_get_attachment_url( $image_id );
		            }

		            $terms = get_terms([
				        'taxonomy'    => 'product_cat',
				        'hide_empty'  => false,
				        'parent'      => $value->term_id
				    ]);


		            array_push($result, [
						'id' => $value->term_id,
		                'title' => wp_specialchars_decode($value->name),
		                'description' => $value->description,
		                'parent' => $value->parent,
		                'count' => count($terms),
		                'image' => $image,
					]);
	 			}
	        }
        }else{
        	$categories = get_terms([
					        'taxonomy'    => 'product_cat',
					        'hide_empty'  => false,
					        'parent'      => cek_raw('parent')
					    ]);

        	foreach ($categories as $key => $value) {
        		$image_id = get_term_meta( $value->term_id, 'thumbnail_id', true );
	            $image = '';

	            if ( $image_id ) {
	                $image = wp_get_attachment_url( $image_id );
	            }


        		array_push($result, [
							'id' => $value->term_id,
			                'title' => wp_specialchars_decode($value->name),
			                'description' => $value->description,
			                'parent' => $value->parent,
			                'count' => 0,
			                'image' => $image,
						]);
        	}

        }

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function popular_categories($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$data_categories = get_popular_categories();
		$result = [];
		if (!empty($data_categories)) {
			foreach ($data_categories as $key) {
				$categories = json_decode($key->categories);
				$list = [];
				if (is_array($categories)) {
					for ($i=0; $i < count($categories); $i++) { 
						//$image = wp_get_attachment_url(get_term_meta($categories[$i], 'thumbnail_id',true));
						$image = wp_get_attachment_url(get_term_meta($categories[$i], 'metro_icon',true));					
						$list[] = array(
									'id' => $categories[$i], 
									'name' => get_terms( 'product_cat', ['include' => $categories[$i], 'hide_empty' => false] , true)[0]->name, 
									'image' => ($image == false ? revo_url().'assets/extend/images/defalt_mini_banner.png' : $image)
								);
					}
					if (!empty($list)) {
						$result[] = array(
									'title' => $key->title,
									'categories' => $list,
								);
					}
				}
			} 
		}

		if ($type == 'rest') {
			echo json_encode($result);
			//echo json_encode($categories);
			exit();
		}else{
			return $result;
		}
	}

	function rest_flash_sale($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		cek_flash_sale_end();
		$date = date('Y-m-d H:i:s');
		$data_flash_sale = $wpdb->get_results("SELECT * FROM `revo_flash_sale` WHERE is_deleted = 0 AND start <= '".$date."' AND end >= '".$date."' AND is_active = 1  ORDER BY id DESC LIMIT 1", OBJECT);

		$result = [];
		$list_products = [];
		foreach ($data_flash_sale as $key => $value) {
			if (!empty($value->products)) {
				$get_products = json_decode($value->products);
				if (is_array($get_products)) {
					$list_products = implode(",",$get_products);
				}
			}
			array_push($result, [
				'id' => (int) $value->id,
				'title' => $value->title,
				'start' => $value->start,
				'end' => $value->end,
				'image' => $value->image,
				'products' => implode(",",json_decode($value->products)),
			]);
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_extend_products($type = 'rest'){
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$where = '';

		$typeGet = '';
		if (isset($_GET['type'])) {
			$typeGet = $_GET['type'];
			
			if ($typeGet == 'special') {
				$where = "AND type = 'special'";
			}

			if ($typeGet == 'our_best_seller') {
				$where = "AND type = 'our_best_seller'";
			}

			if ($typeGet == 'recomendation') {
				$where = "AND type = 'recomendation'";
			}
		}

		$products = $wpdb->get_results("SELECT * FROM `revo_extend_products` WHERE is_deleted = 0 AND is_active = 1 $where  ORDER BY id DESC", OBJECT);

		$result = [];
		$list_products = "";
		if (!empty($products)) {
			foreach ($products as $key => $value) {
				if (!empty($value->products)) {
					$get_products = json_decode($value->products);
					if (is_array($get_products)) {
						$list_products = implode(",",$get_products);
					}
				}
				array_push($result, [
					'title' => $value->title,
					'description' => $value->description,
					'products' => $list_products,
				]);

			}
		}else{
			array_push($result, [
				'title' => $typeGet,
				'description' => "",
				'products' => "",
			]);
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_get_barcode($type = 'rest'){
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$code = cek_raw('code');

		if (!empty($code)) {
			$table_name = $wpdb->prefix . 'postmeta';

			$get = $wpdb->get_row("SELECT * FROM `$table_name` WHERE `meta_value` LIKE '$code'", OBJECT);
			if (!empty($get)) {
				$result['id'] = (int)$get->post_id;
			}else{
				$result = ['status' => 'error','message' => 'code not found !'];
			}
		}else{
			$result = ['status' => 'error','message' => 'code required !'];
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_hit_products($type = 'rest'){
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$cookie = cek_raw('cookie');

		$result = ['status' => 'error','message' => 'Login required !'];
		if ($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			if (!$user_id) {
				$result = ['status' => 'error','message' => 'User Tidak ditemukan !'];
			}else{
				$id_product = cek_raw('product_id');
				$ip_address = cek_raw('ip_address');
				
				$result = ['status' => 'error','message' => 'Tidak dapat Hit Products !'];

				if (!empty($id_product) AND !empty($ip_address)) {
					
					$date = date('Y-m-d');

					$products = $wpdb->get_results("SELECT * FROM `revo_hit_products` WHERE products = '$id_product' AND type = 'hit' AND ip_address = '$ip_address' AND user_id = '$user_id' AND created_at LIKE '%$date%'", OBJECT);

					if (empty($products)) {
						
						$wpdb->insert('revo_hit_products',                  
				        [
				            'products' => $id_product,
				            'ip_address' => $ip_address,
				            'user_id' => $user_id,
				        ]);

						if (empty($wpdb->show_errors())) {
							
							$result = ['status' => 'success','message' => 'Berhasil Hit Products !'];

						}else{

							$result = ['status' => 'error','message' => 'Server Error 500 !'];

						}

					}else{

						$result = ['status' => 'error','message' => 'Hit Product Hanya Bisa dilakukan sekali sehari !'];

					}

				}
			}
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_insert_review($type = 'rest'){
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$cookie = cek_raw('cookie');

		$result = ['status' => 'error','message' => 'Login required !'];
		if ($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			if (!$user_id) {
				$result = ['status' => 'error','message' => 'User Tidak ditemukan !'];
			}else{
				$user = get_userdata($user_id);

				$comment_id = wp_insert_comment( array(
				    'comment_post_ID'      => cek_raw('product_id'), // <=== The product ID where the review will show up
				    'comment_author'       => $user->first_name.' '.$user->last_name,
				    'comment_author_email' => $user->user_email, // <== Important
				    'comment_author_url'   => '',
				    'comment_content'      => cek_raw('comments'),
				    'comment_type'         => '',
				    'comment_parent'       => 0,
				    'user_id'              => $user_id, // <== Important
				    'comment_author_IP'    => '',
				    'comment_agent'        => '',
				    'comment_date'         => date('Y-m-d H:i:s'),
				    'comment_approved'     => 1,
				) );

				// HERE inserting the rating (an integer from 1 to 5)
				update_comment_meta( $comment_id, 'rating', cek_raw('rating') );

				$result = ['status' => 'success','message' => 'insert rating success !'];
			}
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_get_hit_products($type = 'rest'){
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$cookie = cek_raw('cookie');

		if ($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			if (!$user_id) {
				$result = ['status' => 'error','message' => 'User Tidak ditemukan !'];
			}else{

				$products = $wpdb->get_results("SELECT * FROM `revo_hit_products` WHERE user_id = '$user_id' AND type = 'hit' GROUP BY products ORDER BY created_at DESC", OBJECT);
    
				$list_products = '';

				if (!empty($products)) {
				    $list_products = [];
					foreach ($products as $key => $value) {
						$list_products[] = $value->products;
					}
                    if(!empty($list_products)){
                        $list_products = implode(",",$list_products);
                    }
				}else{
					
					// $where = array(
					// 			'limit' => 10,
					// 			'orderby' => 'rand',
					// 		);

					// $list_products = get_products_id($where);

					// $list_products = implode(",",$list_products);

				}

				$result = [
							'status' => 'success',
							'products' => $list_products,
						];
			}
		}else{
			$result = ['status' => 'error','message' => 'Login required !'];
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_intro_page_status($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$get = query_revo_mobile_variable('"intro_page_status"','sort');
		$status = $_GET['status'];
		if (empty($get)) {
			$wpdb->insert('revo_mobile_variable', array(
				'slug' => 'intro_page_status',
				'title' => '',
				'image' => query_revo_mobile_variable('"splashscreen"')[0]->image,
				'description' => $status
			));
		}else {
			$wpdb->query(
				$wpdb
				->prepare("
					UPDATE revo_mobile_variable 
					SET description='$status' 
					WHERE slug='intro_page_status'
				")
			);
		}
		return $status;
	}

	function rest_get_general_settings($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$result['wa'] = data_default_MV('kontak_wa');
		$result['sms'] = data_default_MV('kontak_sms');
		$result['phone'] = data_default_MV('kontak_phone');
		$result['about'] = data_default_MV('about');
		$result['privacy_policy'] = data_default_MV('privacy_policy');
		$result['cs'] = data_default_MV('cs');
		$result['logo'] = data_default_MV('logo');
		$result['barcode_active'] = false;

		if ( is_plugin_active( 'yith-woocommerce-barcodes-premium/init.php' ) ) {
			$result['barcode_active'] = true;			
		}
		

	    $get = query_revo_mobile_variable('"kontak","about","cs","privacy_policy","logo","empty_image"','sort');

		if (!empty($get)) {
			foreach ($get as $key) {

				if ($key->slug == 'kontak') {
					$result[$key->title] = [
											  'slug' => $key->slug, 
											  "title" => $key->title,
											  "image" => $key->image,
											  "description" => $key->description
											];
				}else{
					if ($key->slug == 'empty_image') {
						$result[$key->slug][] = [
											  'slug' => $key->slug, 
											  "title" => $key->title,
											  "image" => $key->image,
											  "description" => $key->description
											];
					}else{
						$result[$key->slug] = [
											  'slug' => $key->slug, 
											  "title" => $key->title,
											  "image" => $key->image,
											  "description" => $key->description
											];
					}
				}
			}

			$result["link_playstore"] = [
							  'slug' => "playstore", 
							  "title" => "link playstore",
							  "image" => "",
							  "description" => "https://play.google.com/store"
							];
			$currency = get_woocommerce_currency_symbol();

			$result["currency"] = [
							  'slug' => "currency", 
							  "title" => get_option('woocommerce_currency'),
							  "image" => wp_specialchars_decode(get_woocommerce_currency_symbol($currency)),
							  "description" => wp_specialchars_decode($currency)
							];

			$result["format_currency"] = [
							  'slug' => wc_get_price_decimals(), 
							  "title" => wc_get_price_decimal_separator(),
							  "image" => wc_get_price_thousand_separator(),
							  "description" => "Slug : Number of decimals , title : Decimal separator, image : Thousand separator"
							];
		}

		if (empty($result['empty_image'])) {
			$result['empty_image'][] = data_default_MV('empty_images_1');
			$result['empty_image'][] = data_default_MV('empty_images_2');
			$result['empty_image'][] = data_default_MV('empty_images_3');
			$result['empty_image'][] = data_default_MV('empty_images_4');
			$result['empty_image'][] = data_default_MV('empty_images_5');
		}

		if ($intro_page) {
			for ($i=1; $i < 4; $i++) { 
				$result['intro'][] = data_default_MV('intro_page_'.$i);
			}
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}


	function rest_get_intro_page($type = 'rest'){
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$get = query_revo_mobile_variable('"intro_page","splashscreen"','sort');

		$result['splashscreen'] = data_default_MV('splashscreen');
		$result['intro_page_status'] = query_revo_mobile_variable('"intro_page_status"','sort')[0]->description;

	    	$intro_page = true;
		if (!empty($get)) {
			foreach ($get as $key) {

				if ($key->slug == 'splashscreen') {
					$result['splashscreen'] =  [
						'slug' => $key->slug,
						"title" => '',
						"image" => $key->image,
						"description" => $key->description
					];
				}

				if ($key->slug == 'intro_page') {
					$result['intro'][] = [
						'slug' => $key->slug,
						"title" => $key->title,
						"image" => $key->image,
						"description" => $key->description
					];

				    $intro_page = false;
				}
			}
		}

		if ($intro_page) {
			for ($i=1; $i < 4; $i++) { 
				$result['intro'][] = data_default_MV('intro_page_'.$i);
			}
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_add_remove_wistlist($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$result['product_id'] = cek_raw('product_id');
		$user_id = wp_validate_auth_cookie(cek_raw('cookie'), 'logged_in');
		// $result['product_id'] = 11066;
		// $user_id = 186;
		$product = wc_get_product($result['product_id']);
		$product_price = $product->get_price();

		if ($user_id) {
			if (!empty($result['product_id'])) {
				$get = query_hit_products($result['product_id'],$user_id);
				if (@cek_raw('check')) {
					if ($get->is_wishlist == 0) { 
						$result['type'] = 'check';
						$result['message'] = false;
					}else{
						$result['type'] = 'check';
						$result['message'] = true;
					}
				}
				else{
					if ($get->is_wishlist == 0) { 						
						$user_wishlist = $wpdb->get_row("SELECT ID FROM `ph0m31e_yith_wcwl_lists` WHERE `user_id` = $user_id");
						if(count($user_wishlist->ID) > 0) { 
							$wpdb->insert('ph0m31e_yith_wcwl', 
								array(
									'prod_id' 			=> $result['product_id'] ,
									'quantity' 			=> 1 ,
									'user_id'			=> $user_id ,
									'wishlist_id'		=> $user_wishlist->ID ,
									'original_price' 	=> $product_price ,
									'type'				=> 'wishlist',
									'original_currency' => 'USD',
									'dateadded' 		=> date('Y-m-d H:m:s') ,
									'on_sale' 			=> 0
								) 
							); 
						}
						else { 
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
									'prod_id' 			=> $result['product_id'],
									'quantity' 			=> 1,
									'user_id'			=> $user_id,
									'wishlist_id'		=> $latest_wishlist_id->ID,
									'position'			=> 0,
									'original_price' 	=> $product_price,
									'type'				=> 'wishlist',
									'original_currency' => 'USD',
									'dateadded' 		=> date('Y-m-d H:m:s'),
									'on_sale' 			=> 0
								) 
							); 
						}


						if (empty($wpdb->show_errors())) {
							$result['type'] = 'add';
							$result['message'] = 'success';
						}else{
							$result['type'] = 'add';
							$result['message'] = 'error';
						}
					}else{
						$product_id = $result['product_id'];
						// $wpdb->query($wpdb->prepare("DELETE FROM `revo_hit_products` WHERE products = '$product_id' AND user_id = '$user_id' AND type = 'wistlist'"));
						$wpdb->query($wpdb->prepare("DELETE FROM `ph0m31e_yith_wcwl` WHERE prod_id = '$product_id' AND user_id = '$user_id' AND type = 'wishlist'"));

						if (empty($wpdb->show_errors())) {
							$result['type'] = 'remove';
							$result['message'] = 'success';
						}else{
							$result['type'] = 'remove';
							$result['message'] = 'error';
						}
					}
				}
			}else{
				$result['type'] = 'Empty Product id !';
				$result['message'] = 'error';
			}
		}else{
			$result['type'] = 'Users tidak ditemukan !';
			$result['message'] = 'error';
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}
	
	function rest_list_wistlist($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$list_products = '';

		$cookie = cek_raw('cookie');
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		// $user_id = 186;

		if ($user_id) {
			$get = query_all_hit_products($user_id);
			if (!empty($get)) {
				$list_products = [];
				foreach ($get as $key) {
					// $list_products[] = $key->products;
					$list_products[] = $key->prod_id;
				}
				if (is_array($list_products)) {
					$list_products = implode(",",$list_products);
				}
			}
		}

		$result = [
					'products' => $list_products
				];

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_key_firebase($type = 'rest'){
		
		$key = access_key();
		$result = array(
					 	"serverKey" => 'AAAALwNKHLc:APA91bGY_AkY01vJ_aGszm7yIjLaNbaAM1ivPlfigeFscdSVuUx3drCRGxyIRgLTe7nLB-5_5rF_ShlmqVXCUmrSd_uaJdcEV43MLxUeFrzmKCzyZzBB7AUlziIGxIH0phtw5VNqgY2Z',
					 	"apiKey" => 'AIzaSyCYkikCSaf91MbO6f3xEkUgFRDqHeNZgNE',
		              	"authDomain" => 'revo-woo.firebaseapp.com',
		              	"databaseURL" => 'https://revo-woo.firebaseio.com',
		              	"projectId" => 'revo-woo',
		              	"storageBucket" => 'revo-woo.appspot.com',
		              	"messagingSenderId" => '201918651575',
		              	"appId" => '1:201918651575:web:dda924debfb0121cf3c132',
		              	"measurementId" => 'G-HNR4L3Z0JE',
				);

		if (isset($key->firebase_servey_key)) {
			$result['serverKey'] = $key->firebase_servey_key;
		}

		if (isset($key->firebase_api_key)) {
			$result['apiKey'] = $key->firebase_api_key;
		}

		if (isset($key->firebase_auth_domain)) {
			$result['authDomain'] = $key->firebase_auth_domain;
		}

		if (isset($key->firebase_database_url)) {
			$result['authDomain'] = $key->firebase_database_url;
		}

		if (isset($key->firebase_database_url)) {
			$result['databaseURL'] = $key->firebase_database_url;
		}

		if (isset($key->firebase_project_id)) {
			$result['projectId'] = $key->firebase_project_id;
		}

		if (isset($key->firebase_storage_bucket)) {
			$result['storageBucket'] = $key->firebase_storage_bucket;
		}

		if (isset($key->firebase_messaging_sender_id)) {
			$result['messagingSenderId'] = $key->firebase_messaging_sender_id;
		}

		if (isset($key->firebase_app_id)) {
			$result['appId'] = $key->firebase_app_id;
		}

		if (isset($key->firebase_measurement_id)) {
			$result['measurementId'] = $key->firebase_measurement_id;
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_token_user_firebase($type = 'rest'){

		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$data['token'] = cek_raw('token');
		$cookie = cek_raw('cookie');

		$result = ['status' => 'error','message' => 'Gagal Input Token !'];
		$insert = true;

		if (!empty($data['token'])) {
			if ($cookie) {
				$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
				if ($user_id) {
					$data['user_id'] = $user_id;
					$get = get_user_token(" WHERE user_id = '$user_id'  ");
					if (!empty($get)) {
						$insert = false;
						$wpdb->update('revo_token_firebase',$data,['user_id' => $user_id]);
						if (@$wpdb->show_errors == false) {
				            $result = ['status' => 'success','message' => 'Update Token Berhasil !'];
				        }
					}
				}

			}

			if ($insert) {
				$wpdb->insert('revo_token_firebase',$data);
				if (@$wpdb->show_errors == false) {
		            $result = ['status' => 'success','message' => 'Insert Token Berhasil !'];
		        }
			}
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_check_variation($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$product_id = cek_raw('product_id');
		$variation = cek_raw('variation');
		$result = ['status' => 'error','variation_id' => 0];

		if (!empty($product_id)) {
			if ($variation) {
				$data = [];
				$variation_ = $variation[0];
				foreach ($variation_ as $key => $value) {
					// $key->column_name = str_replace(" ", "-", strtolower($key->column_name));
					// $data["attribute_".$key->column_name] .= $key->value;
					$key = str_replace(" ", "-", strtolower($key));
					$data["attribute_pa_".$key] .= strtolower($value);
				}

				if ($data) {
					$data_store = new WC_Product_Data_Store_CPT();
				    $product_object = wc_get_product($product_id);
				    $variation_id = $data_store->find_matching_product_variation($product_object, $data);

				    if ($variation_id) {
				    	$revo_loader = load_Revo_Flutter_Mobile_App_Public();
		
				    	$variableProduct = wc_get_product($variation_id);
				    	$result['status'] = 'success';
				    	$result['data'] = $revo_loader->reformat_product_result($variableProduct);
				    	$result['variation_id'] = $variation_id;
				    }
				}
			}
		}
            
		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_list_orders($type = 'rest'){

		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$cookie = cek_raw('cookie');
		$page = cek_raw('page');
		$limit = cek_raw('limit');
		$order_by = cek_raw('order_by');
		$order_id = cek_raw('order_id');
		$status = cek_raw('status');
		$search = cek_raw('search');

		
		$result = [];
		if ($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');

			$revo_loader = load_Revo_Flutter_Mobile_App_Public();
			if ($order_id) {
				$customer_orders = wc_get_order($order_id);
				if ($customer_orders) {
					$get  = $revo_loader->get_formatted_item_data($customer_orders);
					if (isset($get["line_items"])) {
						for ($i=0; $i < count($get["line_items"]); $i++) { 
								$image_id = wc_get_product($get["line_items"][$i]["product_id"])->get_image_id();
								$get["line_items"][$i]['image'] = wp_get_attachment_image_url( $image_id, 'full' );
						}
					}

					if ($get['customer_id'] == $user_id) {

						// Diplay payment title base on aba choosen method
						$payment_method_title = '';
						$payment_description = '';
						foreach($get['meta_data'] as $meta){
							if($meta->key == 'aba_choosen_method'){
								if($meta->value == 'aba_payway_aim_card') {
									$payment_method_title = 'Credit/Debit Card';
									$payment_description = 'Successfully payment through Credit/Debit Card';
								}else if($meta->value == 'aba_payway_aim'){
									$payment_method_title = 'ABA PAY';
									$payment_description = 'Successfully payment through ABA PAY';
								}
								break;
							}
						}
						if(!empty($payment_method_title)){
							$get['payment_method_title'] = $payment_method_title;
							$get['payment_description'] = $payment_description;
						}


						$result[] = $get;
					}
				}
			}else{

				$where = array(
			                'meta_key' => '_customer_user',
			                'orderby' => 'date',
			                'order' => ($order_by ? $order_by : "DESC"),
			                'customer_id' => $user_id,
			                'page' => ($page ? $page : "-1"),
			                'limit' => ($limit ? $limit : "10"),
			                'parent' => 0
			            );

				if ($status) {
					// Order status. Options: pending, processing, on-hold, completed, cancelled, refunded, failed,trash. Default is pending.
					$where['status'] = $status;
				}

				$customer_orders = wc_get_orders($where);
				
				foreach ($customer_orders as $key => $value) {
	                $get  = $revo_loader->get_formatted_item_data( $value );

	                if ($get) {
	                	if ($search) {
	                		$show = false;
		                	if (isset($get["line_items"])) {
		                		for ($i=0; $i < count($get["line_items"]); $i++) { 
		                			$product_name = strtolower($get["line_items"][$i]["name"]);
		                			if (strpos($product_name, $search) !== FALSE) {
									   	$show = true;
									   	$image_id = wc_get_product($get["line_items"][$i]["product_id"])->get_image_id();
										$get["line_items"][$i]['image'] = wp_get_attachment_image_url( $image_id, 'full' );
									}
		                		}
		                	}
		                	if ($show) {
		                		$result[] = $get;
		                	}
		                }else{
		                	if (isset($get["line_items"])) {
		                		for ($i=0; $i < count($get["line_items"]); $i++) { 
								   	$show = true;
								   	//$image_id = wc_get_product($get["line_items"][$i]["product_id"])->get_image_id();
									$prodObj = wc_get_product($get["line_items"][$i]["product_id"]);
								   	$image_id = !empty($prodObj) ? $prodObj->get_image_id() : 0;
									$get["line_items"][$i]['image'] = wp_get_attachment_image_url( $image_id, 'full' );
		                		}
		                	}
	                		$result[] = $get;
		                }
	                }
	            }

	            
				// print_r($image_url);
				// die();
			}
		}
            

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_list_review($type = 'rest'){

		$result = ['status' => 'error','message' => 'Login required !'];

		$cookie = cek_raw('cookie');
		$limit = cek_raw('limit');
		$post_id = cek_raw('post_id');
		$limit = cek_raw('limit');
		$page = cek_raw('page');

		$args = array( 
            'number'      => $limit, 
            'status'      => 'approve', 
            'post_status' => 'publish', 
            'post_type'   => 'product',
        );

        if ($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			$args['user_id'] = $user_id;
		}

		if ($post_id) {
			$args['post_id'] = $post_id;
		}

		if ($limit) {
			$args['number'] = $limit;
		}

		if ($page) {
			$args['offset'] = $page;
		}

		$comments = get_comments( $args );

		$result = [];
		foreach ($comments as $key) {
			$product = wc_get_product($key->comment_post_ID);
			$result[] = array(
							'product_id' => $key->comment_post_ID, 
							'title_product' => $product->get_name(), 
							'image_product' => wp_get_attachment_image_url( $product->get_image_id(), 'full' ), 
							'content' => $key->comment_content, 
							'star' => get_comment_meta( $key->comment_ID, 'rating', true ), 
							'comment_author' => $key->comment_author, 
							'user_id' => $key->user_id,
							'comment_date' => $key->comment_date,  
						);
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function rest_list_notification($type = 'rest'){

		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$result = ['status' => 'error','message' => 'Login required !'];

		$cookie = cek_raw('cookie');

		if ($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			// $data_notification = $wpdb->get_results("SELECT * FROM revo_notification WHERE user_id = '$user_id' AND type = 'order'  AND is_read = 0 ORDER BY created_at DESC", OBJECT);
			$data_notification = $wpdb->get_results("SELECT * FROM revo_notification WHERE user_id = '$user_id' AND type = 'order' ORDER BY created_at DESC", OBJECT);
			$revo_loader = load_Revo_Flutter_Mobile_App_Public();
			$result = [];
			foreach ($data_notification as $key => $value) {
				$order_id = (int) $value->target_id;
				$imageProduct="";
				if ($order_id && $imageProduct=="") {
					$customer_orders = wc_get_order($order_id);
					if ($customer_orders) {
						$get  = $revo_loader->get_formatted_item_data($customer_orders);
						if (isset($get["line_items"])) {
							for ($i=0; $i < count($get["line_items"]); $i++) { 
								//$image_id = wc_get_product($get["line_items"][$i]["product_id"])->get_image_id();
								$productObj = wc_get_product($get["line_items"][$i]["product_id"]);
								$image_id   = !empty($productObj) ? $productObj->get_image_id():0;
								$imageProduct = wp_get_attachment_image_url( $image_id, 'full' ) ?? get_logo();

							}
						}
					}
				}

				array_push($result, [
					'user_id' => (int) $value->user_id,
					'order_id' => (int) $value->target_id,
					'status' => $value->message,
					'is_read'=> $value->is_read,
					'image' => (string)$imageProduct,
					'created_at' => $value->created_at,
					'created_at' => $value->created_at
				]);
			}
			
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}
	
	function rest_read_notification($type = 'rest'){

		require (plugin_dir_path( __FILE__ ).'../helper.php');

		$result = ['status' => 'error','message' => 'Login required !'];

		$cookie = cek_raw('cookie');
		$id = cek_raw('id');

		if ($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');

			$data['is_read'] = 1;
			$wpdb->update('revo_notification',$data,[
							'id' => $id,
							'user_id' => $user_id,
						]);
			if (@$wpdb->show_errors == false) {
	            $result = ['status' => 'success','message' => 'Berhasil Dibaca !'];
	        }
		}

		if ($type == 'rest') {
			echo json_encode($result);
			exit();
		}else{
			return $result;
		}
	}

	function notif_new_order($order_id){

		require (plugin_dir_path( __FILE__ ).'../helper.php');
		
		$order = wc_get_order($order_id);

		$order_number = $order->get_order_number();
		$user_id = $order->get_user_id();
		$title = 'ORDER: #' . $order_number;
        	$message = 'YOUR ORDER ' . strtoupper($order->status);

		$wpdb->insert('revo_notification',
						[
							'type' => "order",
							'target_id' => $order_number,
							'message' => $order->status,
							'user_id' => $user_id,
						]);

		$get = get_user_token(" WHERE user_id = '$user_id' ");

		if (!empty($get)) {
			foreach ($get as $key) {
				$token = $key->token;
				$notification = array(
							'title' => $title, 
							'body' => $message, 
							'icon' => get_logo(), 
						);
							// 'click_action' => '', 
				$extend['id'] = $order_number;
				$extend['type'] = "order";

				send_FCM($token,$notification,$extend);
			}
		}
	}

	function rest_cart_by_user($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');

		$url = site_url().'/wp-json/wc/store/cart';
		$cart = curlInit($url,"GET",$cookie);

		

		$revo_loader = load_Revo_Flutter_Mobile_App_Public();
		$result = [];
		$cart_count = 0;
		foreach ($cart->items as $cart_item ) {
			$search = $cart_item->product_id ?? get_page_by_path( cek_raw('slug'), OBJECT, 'product' );
			$product = wc_get_product($search);
			$product_cart = $revo_loader->reformat_product_result($product);
			$product_cart['cart_quantity'] = (int)$cart_item->quantity;
			$cart_count = $cart_count + (int)$cart_item->quantity;
			$product_cart['key'] = $cart_item->key;
			$product_cart['variant_id'] = 0;
			$result[] = $product_cart;
			
		}

		// echo json_encode($result);
		$data = ['status' => 'success','count'=>$cart_count,'products' => $result];
		if($type = 'rest'){
			echo json_encode($data);
			exit();
		}
		$result = ['status' => 'fail','message' => 'Intternal Server Error !'];
		return json_encode($result);
	}

	function update_cart_by_user($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$result = ['status' => 'fail','message' => 'Intternal Server Error !'];
		$cookie = cek_raw('cookie');
		$key = cek_raw('key');
		$qty = cek_raw('qty');
		$cart = '';
		if(is_array($key) == true){
			for($i = 0; $i < count($key); $i++){
				$url = site_url().'/wp-json/wc/store/cart/update-item?key='.$key[$i].'&quantity='.(int)$qty .'';
				$cart = curlInit($url,"POST",$cookie);
			}
		}else{
			$url = site_url().'/wp-json/wc/store/cart/update-item?key='.$key.'&quantity='.(int)$qty .'';
			$cart = curlInit($url,"POST",$cookie);
		}
		


		$cart_count = 0;
		foreach ($cart->items as $cart_item ) {
			$cart_count = $cart_count + (int)$cart_item->quantity;
		}

		if($type = 'rest'){
			$result = ['status' => 'success','count' => $cart_count];
			echo json_encode($result);
			exit();
		}
		return $result;
	} 

	function add_cart_by_user($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$result = ['status' => 'fail','message' => 'Intternal Server Error !'];
		$cookie = cek_raw('cookie');
		$product_id = cek_raw('product_id');
		$qty = cek_raw('quantity');

		$url = site_url().'/wp-json/wc/store/cart/add-item';
		$cart = addCartCurlInit($url,"POST",$cookie,$product_id,$qty);

		// Check cart error when user no address
		if(property_exists($cart, 'code')){
			if($cart->code == 'woocommerce_rest_add_to_cart_error'){
				$error_code = "other";
				$message = $cart->message;
				if(strpos($message, "stock") != false){
					$error_code = "product_out_of_stock";
				}else if(strpos($message, "address") != false){
					$error_code = "user_no_address";
				}
				$data = ['status' => 'missing_params','count' => 0, "code" => $error_code, "message" => $message];
				biz_write_log($data, "add_cart_by_user");
				echo json_encode($data);
				exit();
			}
		}

		$cart_count = 0;
		foreach ($cart->items as $cart_item ) {
			$cart_count = $cart_count + (int)$cart_item->quantity;
		}

		if($type = 'rest'){
			$data = ['status' => 'success','count' => $cart_count];
			echo json_encode($data);
			exit();
		}
		return $result;
	}

	function clear_cart_by_user($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');

		if($type = 'rest'){
			
			echo json_encode(wc_clear_cart_by_user($user_id));
			exit();
		}
		return "Hello";
	}

	
	function multi_cart_add_by_user($type = 'rest'){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$products = cek_raw('products');
		$cookie = cek_raw('cookie');
		$result = ['status' => 'fail','message' => 'Intternal Server Error !'];
		$cart = '';
		
		for($i = 0; $i < count($products); $i++){
			$url = site_url().'/wp-json/wc/store/cart/add-item';
			$cart = addCartCurlInit($url,"POST",$cookie,$products[$i]->product_id,(int)$products[$i]->quantity);
		}

		$cart_count = 0;
		foreach ($cart->items as $cart_item ) {
			$cart_count = $cart_count + (int)$cart_item->quantity;
		}

		if($type = 'rest'){
			$result = ['status' => 'success','count' => $cart_count];
			echo json_encode($result);
			exit();
		}
		return "Hello";
	}

	function curlInit($url,$method,$cookie){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => $method,
		CURLOPT_HTTPHEADER => array(
			'Cookie: '.$cookie.''
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);
		
	}

	function addCartCurlInit($url,$method,$cookie,$product_id,$quantity){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => site_url().'/wp-json/wc/store/cart/add-item',
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{
			"id":"'.$product_id.'",
			"quantity":'.$quantity.',
			"variation": [
				{
					"attribute": "Options",
					"value": "Normal"
				}
			]
		}',
		CURLOPT_HTTPHEADER => array(
			'Cookie: '.$cookie.'',
			'Content-Type: application/json'
		),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return json_decode($response);
		
	}

	add_filter('woocommerce_rest_prepare_product_object', 'custom_product_response', 10, 3);
	function custom_product_response($response, $post, $request){
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		// $user_id = 186;

		$response->data["favorite_status"] = false;
		if(!empty($user_id)){
			global $wpdb;
			$wishlist_data = "SELECT * FROM ph0m31e_yith_wcwl WHERE user_id=".$user_id." AND prod_id=".$post->id;
			$results = $wpdb->get_results($wishlist_data); 
			$response->data["favorite_status"] = count($results) ? true : false;
		}

		return $response;
	}

	function delete_account() {
		global $wpdb;
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		// $user_id = 484;
	    wp_update_user( [  
		  'ID'             => $user_id, 
		  'user_pass'      => $user_id.'_delete',
		  'user_email'     => $user_id.'_delete',
		  'user_nicename'  => $user_id.'_delete',
		  'nickname'       => $user_id.'_delete',
		  'first_name'     => $user_id.'_delete',
		  'last_name'      => $user_id.'_delete',	  
		] );

		$wpdb->update($wpdb->users, 
		array(
			'user_login' => $user_id.'_delete',
		), 
		array('ID' => $user_id));
	  
		if ( !empty( $user_id ) ) {
		  echo json_encode(['status'=>'success']) ;
		} else {
		  echo json_encode(['status'=>'fail','message'=>'Intternal Server Error !']);
		}
	}

	function get_popular_search() {
		global $wpdb;

		$data_search = $wpdb->get_results(" SELECT * FROM revo_count_search ORDER BY search_count DESC LIMIT 15 ");

		foreach($data_search as $value) {
			$data_json[] = $value->search_text;
		}
	
		echo json_encode([
			'search_history' => $data_json
		]);
    }

	function get_search_text() {
		global $wpdb;

		$key_word = strtolower($_POST['key_search']);

		if(!empty($key_word)) {
			$get_data_search = $wpdb->get_results(" SELECT * FROM revo_count_search ");
			foreach($get_data_search as $value){
				$data_arr[]  = $value->search_text;
			}
			if(in_array($key_word,$data_arr)){
				// @get search count by key word
				$sql_select_key  = $wpdb->get_results(" SELECT * FROM revo_count_search WHERE search_text= '$key_word' ");
				foreach($sql_select_key as $value){
					$total_count = $value->search_count+1;
				}

				// @update search count
				$wpdb->update('revo_count_search', array('search_count'=>$total_count), array('search_text'=>$key_word));
			} else {
				// @insert data
				$wpdb->insert('revo_count_search', array(
				'search_text'  => $key_word,
				'search_count' => 1, 
				));
			}
			echo json_encode(['status'=>'success']);
		}
		else {
			echo json_encode(['status'=>'Fail']);
		}
	}

	function get_all_province(){
		$province = [];
		$args = array(
			'taxonomy'  => 'location',
			'name__like' => $_POST['search_province'],
			'hide_empty' => false, 
			'meta_query' => array(
				array(
					'key'       => 'taxonomy_type',
					'value'     => 'province',
					'compare'   => '='
				)
			),
		);
		$terms_province = get_terms( $args );
		foreach($terms_province as $value){
			$province[] = array(
				'id' => $value->term_id,
				'name' => $value->name
			);
		}

		$data_json = 
			array(
				'result' => array(
					'code' => '200',
					'data' => $province
				)	
			);
		echo json_encode($data_json);
	}


	function get_all_district(){
		$data_district = [];
		$args = array(
			'taxonomy'  => 'location',
			'parent' => $_POST['province_id'],
			'name__like' => $_POST['search_district'],
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'       => 'taxonomy_type', //name feilt
					'value'     => 'district',      //name
					'compare'   => '='
				)
			)
		);
		$terms_district= get_terms( $args );
		foreach($terms_district as $district){
			$data_district[] = array(
				'id' => $district->term_id,
				'name' => $district->name
			);
		}
		$data_json = 
			array(
				'result' => array(
					'code' => '200',
					'data' => $data_district
				)	
			);
		echo json_encode($data_json);
    }

	function get_all_commune(){
		$data_commune = [];
		$args = array(
			'taxonomy'  => 'location',
			'parent' => $_POST['district_id'],
			'name__like' => $_POST['search_commune'],
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'       => 'taxonomy_type',
					'value'     => 'commune',
					'compare'   => '='
				)
			),
    	);
		$terms_commune = get_terms( $args );
		foreach($terms_commune  as $district){
			$code = get_term_meta ($district->term_id, 'code', true );
			$data_commune[] = array(
				'id' => $district->term_id,
				'name' => $district->name,
				'postal_code' => $code
			);
		}
		$data_json = 
		array(
			'result' => array(
				'code' => '200',
				'data' => $data_commune
			)	
		);
		echo json_encode($data_json);
	}

	// @get token from odoo
	function get_token_from_odoo() {

		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => ODOO_URL.'/client/api/oauth2/access_token',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => 'client_id='.ODOO_CLIENT_ID.'&client_secret='.ODOO_CLIENT_SECRET.'&db='.ODOO_DB.'',
		//CURLOPT_POSTFIELDS => 'client_id=DlusVsqydiYa1HkfBZ4MSQMYxPLaEOPHBIVLZybOnizVXLnZ2kzKQxjzSyPK1f6lVea0FniBxCvda9pEGVr3k347lYAjcETGUrPsDAjvOiGa6LiNQT4xB5RLUHpkLIAM&client_secret=bhkLpwM7TQhrkz6LKIuV8DEEIs9d4AI123DxasZqHYe3kSAHjqOG2jQ62GMzKDq1s0wzHCOQ2FFPrBhV1DqFFf0e0CuUScLnxtiaMdoPTiSwMz6ggWSRR3xXor3AVLQH&db=ljOnboA6uvynfLd3cNLt4FRbVV8BMxE052E0Kc4ua0DtkaksdJ4wIAUsQyRSdpO2qfqq36avtcfkpiBswqvPKMN08rr3mXQfda641jBybdPgOS0DiwKX0Ep2tQlBLOQyizIsUGleBE41iH',
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/x-www-form-urlencoded',
			'Cookie: session_id=49c1d3cb3a4847f7c4bc009bd9465ecec6619dbe'
		),
		));
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		$data = json_decode( $response ,true);
		return $data['access_token'];
	}

	// @mobile create shipping address
	function add_shipping_address() {

		$json     = file_get_contents('php://input');
        $params   = json_decode($json);
		$fullname = $params->fullname;
		$phone    = $params->phone;
        $province = $params->province;
        $district = $params->district;
        $commune  = $params->commune;
        $street   = $params->street;
        $address  = $params->address;
        $lat      = $params->lat;
        $lng      = $params->lng;
        $cookie   = $params->cookie;
		$user_id  = wp_validate_auth_cookie($cookie, 'logged_in');
		$address_id = wp_insert_post(array (
			'post_title'   => 'Customer Address',
			'post_type'    => 'user-address',
			'post_status'  => 'publish',
			'post_author'  => $user_id
		)); 
		$odoo_user_id = get_user_meta( $user_id, 'odoo_id', true );
		// @update address to WC
		if(!empty($address_id)){
			update_post_meta($address_id,'full_name', $fullname);
			update_post_meta($address_id,'phone', $phone);
			update_post_meta($address_id,'province_2', $province);
			update_post_meta($address_id,'district_2', $district);
			update_post_meta($address_id,'commune_2', $commune);
			update_post_meta($address_id,'street', $street);
			update_post_meta($address_id,'address', $address);
			update_post_meta($address_id,'street', $street);
			update_post_meta($address_id,'address', $address);
			//update_post_meta($address_id,'odoo_id',$odoo_user_id);

			$value = array("address" => $address, "lat" => $lat, "lng" =>$lng);
			update_post_meta($address_id, 'map', $value);

            $result_json = response_format(200, "Create address successfully");
		}
		else{
            $result_json = response_format(500, "Internal server error");
		}

		// insert address to ODOO
		$odoo_user_id = get_user_meta( $user_id, 'odoo_id', true );
		
		$uri_create  = "/api/customer/".$odoo_user_id."/create/address";

		$province_odoo_id = get_term_meta( $province, 'odoo_id', true );
		$district_odoo_id = get_term_meta( $district, 'odoo_id', true );
		$commune_odoo_id  = get_term_meta( $commune, 'odoo_id', true );

		$postfields = '{
			"params":{
				"street":      "'.$address.' '.$street.'",
				"province_id": "'.$province_odoo_id.'",
				"district_id": "'.$district_odoo_id.'",
				"commune_id":  "'.$commune_odoo_id.'",
				"phone":       "'.$phone.'",
				"post_code":   "12000",
				"db":      	   "'.ODOO_DB.'"
			}
		}';

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => ODOO_URL . $uri_create,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '.get_token_from_odoo().'',
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_SSL_VERIFYHOST => true,
			CURLOPT_SSL_VERIFYPEER => true,
		));

		$response_obj = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response_obj, true);
        biz_write_log($response_obj ,'Mobile Address 20-10-2022');

		// @update field odoo_id in WC
		if ( 
			!empty($response) && 
			isset($response["result"]) && 
			isset($response["result"]["success"]) && 
			$response["result"]["success"] == true
		){
			update_post_meta( $address_id, 'odoo_id', $response["result"]["id"] );
		}

		echo json_encode($result_json);
	}

    // @mobile set default billing address
	function mobile_default_billing_address() {

		$default_address_id = cek_raw('address_id');
		$cookie             = cek_raw('cookie');

		if(!empty($cookie)) {
			$user_id  = wp_validate_auth_cookie($cookie, 'logged_in');

			// Update other default to number 0
			$args = array(
				'post_type'      => 'user-address',
				'posts_per_page' => -1,
				'author'         => $user_id
			);

			$query = new WP_Query( $args );

			if( $query->have_posts() ):
				while( $query->have_posts() ): $query->the_post();

				$post_id = get_the_id();
				$default_address_status = get_field('default_address');

				if( $default_address_status == 1 )
				{
					update_post_meta( $post_id, 'default_address', 0 );
				}

				endwhile;
			endif;

			// Update new default billing address
			update_post_meta( $default_address_id, 'default_address', 1 );

			// User Information
			$user_info = get_userdata( $user_id );
			$firstname = $user_info->first_name;
			$lastname  = $user_info->last_name;
			$email 	   = $user_info->user_email;

			$address = get_field( 'address', $default_address_id );
			$street  = get_field( 'street', $default_address_id );
			$phone   = get_field( 'phone', $default_address_id );
			
			// Province
			$province_id  = get_field( 'province_2', $default_address_id );
			$province_obj = get_term_by( 'id', $province_id, 'location' );
			$province 	  = $province_obj->name;
			
			// Distric
			$district_id  = get_field( 'district_2', $default_address_id );
			$district_obj = get_term_by( 'id', $district_id, 'location' );
			$district 	  = $district_obj->name;
			
			// Commune
			$commune_id  = get_field( 'commune_2', $default_address_id );
			$commune_obj = get_term_by( 'id', $commune_id, 'location' );
			$commune 	 = $commune_obj->name;

			update_user_meta( $user_id, 'shipping_address_1', $street );
			update_user_meta( $user_id, 'shipping_address_2', $commune.','.$district );
			update_user_meta( $user_id, 'shipping_city', $province );
			update_user_meta( $user_id, 'shipping_state', 'Cambodia' );
			update_user_meta( $user_id, 'shipping_email', $email );
			update_user_meta( $user_id, 'shipping_first_name', $firstname );
			update_user_meta( $user_id, 'shipping_last_name', $lastname );
			update_user_meta( $user_id, 'shipping_postcode', '12000' );

			update_user_meta( $user_id, 'billing_first_name', $firstname );
			update_user_meta( $user_id, 'billing_last_name', $lastname );
			update_user_meta( $user_id, 'billing_address_1', $address );
			update_user_meta( $user_id, 'billing_address_2', $commune.','.$district );
			update_user_meta( $user_id, 'billing_email', $email );
			update_user_meta( $user_id, 'billing_postcode', '12000' );
			update_user_meta( $user_id, 'billing_phone', $phone );
			update_user_meta( $user_id, 'billing_state', 'Cambodia' );

			$response = array(
				'code'    => 200,
				'message' => 'Success'
			);

		}
		else {
			$response = array(
				'code'    => '500',
				'message' => 'User not login.'
			);
		}
		return $response;

	}

	function shipping_address() {
		$cookie   = cek_raw('cookie');
		if(!empty($cookie)){
			$user_id  = wp_validate_auth_cookie($cookie, 'logged_in');
				$arg = array(
					'post_type'      => 'user-address',
					'posts_per_page' => -1,
					'orderby'        => 'id',
					'order'          => 'DESC',
					'post_status'    => 'publish',
					'author'    	 => $user_id
				);
			$data = new WP_Query($arg);
			if( $data->have_posts() ) {
				while( $data->have_posts() ) {
					$data->the_post();
					$fullname = get_field('full_name');
					$phone    = get_field('phone');
					// $district = get_field('district_2');
					// $commune  = get_field('commune_2');
					$street   = get_field('street');
					$address  = get_field('address');
					$lat      = get_field('map')['lat'];
					$lng      = get_field('map')['lng'];

					$province = get_term_by('term_id' ,get_field('province_2'),'location');
					$district = get_term_by('term_id' ,get_field('district_2'),'location');
					$commune  = get_term_by('term_id' ,get_field('commune_2'),'location');
					// $cookie   = $params->cookie;
					// $user_id  = wp_validate_auth_cookie($cookie, 'logged_in');

					$address_id = get_the_id();
							// $address_args = array(
							// 	'post_type' => 'user-address',
							// 	'post_parent' => $address_id
							// );
					$json_data[] = array(
						'ID'          => $address_id,
						'fullname'    => $fullname,
						'phone'       => $phone,
						'province'    => $province->name,
						'province_id' => $province->term_id,
						'district'    => $district->name,
						'district_id' => $district->term_id,
						'commune'     => $commune->name,
						'commune_id'  => $commune->term_id,
						'street'      => $street,
						'address'     => $address,
						'lat'  		  => (string)$lat,
						'lng' 	      => (string)$lng
					);
				}
				$result_json = response_format(200, $json_data);
				echo json_encode($result_json);
			}else{
					$result_json['result'] = [
						'code'    => 401,
						'message' => 'Not Address!'
					];
					echo json_encode($result_json);
		   }
		}else{
			$result_json['result']  = [
				'code'    => 401,
				'message' => 'messing Param'
			];
			echo json_encode($result_json);
		}
	}

	function edit_shipping_address(){
		$json        = file_get_contents('php://input');
        $params      = json_decode($json);
		$shipping_id = $params->shipping_id;
		$fullname    = $params->fullname;
		$phone       = $params->phone;
        $province    = $params->province;
        $district    = $params->district;
        $commune     = $params->commune;
        $street      = $params->street;
        $address     = $params->address;
        $lat         = $params->lat;
        $lng         = $params->lng;
        // $cookie   = $params->cookie;
		// $user_id = wp_validate_auth_cookie($cookie, 'logged_in');

		$address_id = wp_update_post(array (
			'post_title'   => 'Customer Address',
			'post_type'    => 'user-address',
			'post_status'  => 'publish',
			'ID'           => $shipping_id
		));
		if(!empty($address_id)){
			update_post_meta($address_id,'full_name', $fullname);
			update_post_meta($address_id,'phone', $phone);
			update_post_meta($address_id,'province_2', $province);
			update_post_meta($address_id,'district_2', $district);
			update_post_meta($address_id,'commune_2', $commune);
			update_post_meta($address_id,'street', $street);
			update_post_meta($address_id,'address', $address);
			update_post_meta($address_id,'street', $street);
			update_post_meta($address_id,'address', $address);

			$value = array("address" => $address, "lat" => $lat, "lng" =>$lng);
			update_post_meta($address_id, 'map', $value);

            $result_json = response_format(200, "Update address successfully");
		}
		else{
            $result_json = response_format(500, "Internal server error");
		}
		echo json_encode($result_json);
	}

	function delete_shipping_address(){
		$json        = file_get_contents('php://input');
        $params      = json_decode($json);
		$shipping_id = $params->shipping_id;

		if($shipping_id){
			wp_delete_post($shipping_id);
            $result_json = response_format(200, "Delete address successfully");
		}
		else{
            $result_json = response_format(500, "Internal server error");
		}
		echo json_encode($result_json);  
	}

	function invite_code() {
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		if(!empty($cookie)){
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			$user_code = get_user_meta($user_id, 'user_code', true);
			$percentage_amount  = get_field('amount_and_percentage','option');

			if($percentage_amount==0){
				$percentage = 'Percentage discount';
			}
			else{
				$percentage = 'Fixed cart discount';
			}
			// get option page percentage
			$for_invitee = get_field('for_invitee','option');

			$data['result'] = array(
				'code' 			=>'200',
				'message'  		=> 'success',
				'invite_code' 	=> $user_code,
				'coupon_amount' => $for_invitee,
				'discount_type' => $percentage
			);
		}else{
			$data['result'] = array(
				'code'    => 401,
				'message' => 'user not login!'
			);
		}
		echo json_encode($data);
	}

	//Get list coupon
	function get_coupon() {
		$json     = file_get_contents('php://input');
        $params   = json_decode($json);

		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');

		if(!empty($cookie)){

			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');

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
					// array(
					// 	'key'     => 'date_expires',
					// 	'value'   => date("Y-m-d"),
					// 	'compare' => '>'
					// )

				)
			);
			$data_coupon = new WP_Query($arg);
			if( $data_coupon ->have_posts() ) {
				$result = [];
				while($data_coupon ->have_posts()) {
					$data_coupon->the_post();

					$date = get_field('date_expires');
					if(!empty($date)) {
						$arrDate = explode("-", $date);
						if(count($arrDate)>1){
							$expire = date("Y-m-d", strtotime($date." +1 day"));
							$expire_compare = date("Ymd", strtotime($expire));
						}else{
							$date_expires = new DateTime('@'.$date);
							$date_expires->modify( "+1 day" );
							$expire = $date_expires->format('Y-m-d');
							$expire_compare = $date_expires->format('Ymd');
						}
					}
					else {
						$date = '';
					}

					if($expire_compare > date('Ymd')) {
						$code        	= get_the_title();  
						$description 	= get_the_excerpt();
						$coupon_id   	= get_the_ID();
						$usage_limit 	= get_field('usage_limit');
						$usage_count 	= get_field('usage_count');
						$discount_type 	= get_field('discount_type');
						$date_created   = get_the_date('Y-m-d');
						$date_modified  = get_the_date('Y-m-d');
						$usage_limit_per_user 	= get_field('usage_limit_per_user');
						$expire_date		 	= $expire;//get_field('date_expires');
						$minimum_amount 	  	= get_field('minimum_amount');
						$maximum_amount  	 	= get_field('maximum_amount');
						$discount_amount 	  	= get_field('coupon_amount'); 
						$user_used_count = $wpdb->get_row(" SELECT COUNT(meta_value) as user_total_used FROM ph0m31e_postmeta WHERE post_id = $coupon_id AND meta_key = '_used_by' AND meta_value = $user_id ");
						$total_usage_by_user = $user_used_count->user_total_used;
						if($usage_limit > $usage_count) {
							if($usage_limit_per_user > $total_usage_by_user) {

								$result[] = array(
									'id' 			=> $coupon_id,
									'code' 			=> $code,
									'amount' 		=> $discount_amount,
									'date_created'  => $date_created,
									'date_modified' =>$date_modified,
									'discount_type' => $discount_type,
									'description'   => $description,
									'date_expire'   => $expire_date,
									'min_amount'    => $minimum_amount,
									'max_amount'    => $maximum_amount
								);
							}
						}						
					}
				}
				return array(
					'code' => 200,
					'status' => 'success',
					'data' => $result
				);
			}
		}else {
			return array(
				'code'    => 500,
				'message' => 'missing param!'
			);
		}
	}

	// Apply coupon
	function apply_coupon() {
		global $wpdb;
		$cookie       = cek_raw('cookie');
		$coupon_code  = cek_raw('code');
		$price        = cek_raw('price');
		$user_id      = wp_validate_auth_cookie($cookie, 'logged_in'); 

		// @ check required coupon code
		if(empty($coupon_code)){
			return array(
				'code'    => 404,
				'message' => 'Coupon code required.'
			);
		}

		// @get coupon info
		$arg = array(
			'post_type'      => 'shop_coupon',
			'title' 		 => $coupon_code,
			'posts_status'   => 'publish',
			'posts_per_page' => 1
		);

		$data_coupon = new WP_Query($arg);
		if( $data_coupon ->have_posts() ) {
			$json_data = [];
			while( $data_coupon ->have_posts() ) {
				$data_coupon->the_post();
				$id = get_the_ID();
				$minimum_amount   		= get_field('minimum_amount');
				$usage_limit 			= get_field('usage_limit');
				$usage_count 			= get_field('usage_count');
				$usage_limit_per_user 	= get_field('usage_limit_per_user');

				// 1. Check Expire Date
				$date = get_field('date_expires');
				if(!empty($date)) {
					$arrDate = explode("-", $date);
					if(count($arrDate)>1){
						$expire = date("Y-m-d", strtotime($date." +1 day"));
						$expire_compare = date("Ymd", strtotime($expire));
					}else{
						$date_expires = new DateTime('@'.$date);
						$date_expires->modify( "+1 day" );
						$expire = $date_expires->format('Y-m-d');
						$expire_compare = $date_expires->format('Ymd');
					}
				}


				if(!empty($expire_compare) && $expire_compare < date('Y-m-d')) {
					return array(
						'code'    => 404,
						'message' => 'Coupon expired.'
					);
				}

				$user_used_count = $wpdb->get_row(" SELECT COUNT(meta_value) as user_total_used FROM ph0m31e_postmeta WHERE post_id = $id AND meta_key = '_used_by' AND meta_value = $user_id ");
				$total_usage_by_user = $user_used_count->user_total_used;
				if($usage_limit > $usage_count) {
					if($usage_limit_per_user == $total_usage_by_user) {
						return array(
							'code'    => 404,
							'message' => 'Coupon Limit Usage Per User.'
						);
					}
				}						

				// 3. Check minimum price
				if($price < $minimum_amount){
					return array(
						'code'    => 404,
						'message' => 'The minimum spen for this coupon is '.$minimum_amount
					);
				}

				$code			  = get_the_title();	
				$discount_amount  = get_field('coupon_amount'); 
				$discount_type	  = get_field('discount_type');
				$description      = get_the_excerpt();
				$date_created     = get_the_date('Y-m-d');
				$date_modified    = get_the_date('Y-m-d');
				$maximum_amount   = get_field('maximum_amount');

				$json_data[] = array(
					'id' 			=> $id,
					'code' 			=> $code,
					'amount' 		=> $discount_amount,
					'date_created'  => $date_created,
					'date_modified' =>$date_modified,
					'discount_type' => $discount_type,
					'description'   => $description,
					'date_expire'   => $date,
					'min_amount'    => $minimum_amount,
					'max_amount'    => $maximum_amount
				);

			}
			return array(
				'code' => 200,
				'data' => $json_data
			);
		}
		else {
			return array(
				'code'    => 500,
				'message' => 'Invalid Coupon'
			);
		}

	}

	function list_general_notification(){ 
		global $wpdb;
		$json     = file_get_contents('php://input');
        $params   = json_decode($json);
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		if(!empty($cookie)){
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			$result_query = $wpdb->get_results( "SELECT * FROM `revo_mobile_variable` WHERE `slug` LIKE 'firebase_notification' ORDER BY `slug` ASC" );
			foreach($result_query as $value){
				$id = $value->id;
				$title = $value->title;
				$image = $value->image;
				$description = $value->description;
				$link_to = $value->link_to;
				$is_read = '0';
				$created_at = $value->created_at;

				// code check read
				$result = $wpdb->get_results ("SELECT * FROM `ph0m31e_notification_read` WHERE user_id = $user_id AND notification_id = $id");

				if(count($result)>0){
					$is_read = '1';
				}
				$data[] = array(
					'id' => $id,
					'title' =>$title,
					'image'  =>(string)$image,
					'description' =>$description,
					'link_to' =>$link_to,
					'is_read' =>$is_read,
					'date'=>$created_at
				);
			}
		}else{
			$data[] = array(
				"code" => 500,
				'message' => 'missing param!'
			);
		}
		echo json_encode($data);
	}

	// @Noti detail
	function detail_notification(){
		global $wpdb;
		$json     = file_get_contents('php://input');
        $params   = json_decode($json);
		$id = $params->id;
		$result_query = $wpdb->get_results( "SELECT * FROM `revo_mobile_variable` WHERE id =$id");
		if(!empty($result_query)){
			foreach($result_query as $value){
				$title = $value->title;
				$image = $value->image;
				$description = $value->description;
				$data[] = array(
					'title' =>$title,
					'image'  =>$image,
					'description' =>$description
				);
			}
		}else{
			$data[] = array(
				"code" => 500,
				'message' => 'not detail notification'
			);
		}
		echo json_encode($data);
	}

    function response_format($code, $data){
        return [
            'result' => [
                'code' => $code,
                'data' => $data
            ]
        ];
    }

	// @tracking order
	function tracking_order(){
		$json     = file_get_contents('php://input');
        $params   = json_decode($json);
		$order_id = $params->order_id;

		$status   = get_field('packing_order',$order_id);
		$status_1 = get_field('delivery_order',$order_id);
		$status_2 = get_field('arrived',$order_id);
		$status_3 = get_field('confirm_received',$order_id);

		$date_packing_order    = get_field('date_packing_order',$order_id);
		$date_delivery_order   = get_field('date_delivery_order',$order_id);
		$date_arrived          = get_field('date_arrived',$order_id);
		$date_confirm_received = get_field('date_confirm_received',$order_id);
		//$d = count($date_packing_order);
		$d = json_encode($date_packing_order);
		

		if(!empty($status)){
			$data_packing_order = true;
		}else{
			$data_packing_order = false;
		}
		if (!empty($status_1)){
			$data_delivery_order = true;
		}else{
			$data_delivery_order = false;
		}
		if(!empty($status_2)){
			$data_arrived = true;
		}else{
			$data_arrived = false;
		}
		if(!empty($status_3)){
			$data_confirm_received = true;
		}else{
			$data_confirm_received = false;
		}
		$data =array(
			array(
				'id'=> 1,
				'title'=>'Packing Order',
				'status'=>$data_packing_order,
				'date'=>(string)$date_packing_order
				),
			array(
				'id'=> 2,
				'title'=>'Delivery Order',
				'status'=>$data_delivery_order,
				'date'=>(string)$date_delivery_order
				),
			array(
				'id'    => 3,
				'title' =>'Arrived',
				'status'=>$data_arrived,
				'date'  =>(string)$date_arrived
				),
			array(
				'id'=> 4,
				'title'=>'Confirm received',
				'status'=>$data_confirm_received,
				'date'=>(string)$date_confirm_received
			),
		);
		echo json_encode($data);
	}

	// @Count Noti
	function count_notification(){
		global $wpdb;
		$json     = file_get_contents('php://input');
        $params   = json_decode($json);
		$post =$params->post;
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		if(!empty($cookie)){
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in'); 
			$result = $wpdb->get_results ("SELECT `notification_id` FROM  ph0m31e_notification_read WHERE `user_id` =  $user_id");

			$id_read = '';
			foreach($result as $value){
				$id_read .= $value->notification_id.',';
			}
			$is_read = substr_replace($id_read,'','-1','1');
			if(count($result) > 0){
				$result_id = $wpdb->get_row("SELECT count(*) AS noti_order FROM revo_mobile_variable WHERE id NOT IN($is_read) AND `slug` LIKE 'firebase_notification'");
			}else{
				$result_id = $wpdb->get_row("SELECT count(*) AS noti_order FROM `revo_mobile_variable` WHERE `slug` LIKE 'firebase_notification'");
			}
			
			$sql_general = $wpdb->get_row("SELECT count(*) AS noti_general FROM revo_notification WHERE user_id = $user_id AND is_read = 0");

			$count_noti = $result_id->noti_order + $sql_general->noti_general;
			$data[] = array(
				"count_notification" =>$count_noti
			);
		}else{
			$data[] = array(
				"count_notification" =>0
			);
		}
		echo json_encode($data);
	}

	// @Read Order Noti
	function read_order_notification(){
		global $wpdb;
		$json     = file_get_contents('php://input');
        $params   = json_decode($json);
		$order_id = $params->order_id;
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		if(!empty($order_id)){
			$select =  $wpdb->update('revo_notification',["is_read" => 1],['target_id' => $order_id]);
			$data[]=array(
				'code'=>200,
				'messege'=>'seccess'
			);
		}else{
			$data[]=array(
				'code'=>500,
				'messege'=>'Notification order read already'
			);
		}
		echo json_encode($data);
	}

	//@Read general Noti
	function read_general_notification(){
		global $wpdb;
		$json     = file_get_contents('php://input');
        $params   = json_decode($json);
		
		$notification_id = $params->notification_id;
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie  = cek_raw('cookie');
		$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
		$result = $wpdb->get_results ("SELECT * FROM ph0m31e_notification_read WHERE user_id = $user_id AND notification_id = $notification_id");
		if(count($result) > 0){
			$data[] = array(
				'code'=>500,
				'messege'=>'Notification General read already'
			);
		}else{
			$wpdb->insert('ph0m31e_notification_read',                  
			[
				'user_id' => $user_id,
				'notification_id' => $notification_id,
			]);
			$data[] = array(
				'code'=>200,
				'messege'=>'seccess'
			);
		}
		echo json_encode($data);
	}

	/*
		WC Cart Version 2
	*/

	//@List Cart
	function rest_cart_by_user_v2($type = 'rest'){ 
		if ( empty( wc()->cart ) ) {
			$data = ['status' => 'fail','message'=>'Missing param wc_load_cart=1'];
			echo json_encode($data);
			exit();
		}
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		$revo_loader = load_Revo_Flutter_Mobile_App_Public();
		$result = [];
		$cart_count = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart ) {
			$cart_item = wc()->cart->get_cart_item($cart_item_key);
			$search = $cart_item['product_id'] ?? get_page_by_path( cek_raw('slug'), OBJECT, 'product' );
			$product = wc_get_product($search); 
			$product_cart = $revo_loader->reformat_product_result($product);
			$product_cart['cart_quantity'] = (int)$cart_item['quantity'];
			$cart_count = $cart_count + (int)$cart_item['quantity'];
			$product_cart['key'] = $cart_item_key;
			$product_cart['variant_id'] = 0;
			$result[] = $product_cart;
		}
		$data = ['status' => 'success','count'=>$cart_count,'products' => $result];
		if($type = 'rest'){
			echo json_encode($data);
			exit();
		}
		$result = ['status' => 'fail','message' => 'Intternal Server Error !'];
		return json_encode($result);
	}

	// @Add Cart
	function add_cart_by_user_v2($type = 'rest'){ 
		if ( empty( wc()->cart ) ) {
			$data = ['status' => 'fail','message'=>'Missing param wc_load_cart=1'];
			echo json_encode($data);
			exit();
		}
	
		$cookie       = cek_raw('cookie'); 
		$product_id   = cek_raw('product_id');
		$qty          = cek_raw('quantity');
		$variation_id = cek_raw('variation_id');
		
		if($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			$post_args = array(
				'post_type' => 'user-address',
				'post_status' => 'publish',
				'author__in'     => array( $user_id ),
			);
			$query = new WP_Query( $post_args );
			$found_post = $query->found_posts; 
	
			if( $found_post > 0 ) {
				$data = WC()->cart->add_to_cart( $product_id, $qty, $variation_id, '', '' );
				$cart_count = 0;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart ) {
					$cart_item = wc()->cart->get_cart_item($cart_item_key);
					$product_cart['cart_quantity'] = (int)$cart_item['quantity'];
					$cart_count = $cart_count + (int)$cart_item['quantity'];
				}
				$result = ['status' => 'success','code' => 200 ,'count' => $cart_count ,'message' => 'add_cart_success'];
			}
			else {
				$result = ['status' => 'missing_params','count' => 0, "code" => 'user_no_address'];
			}
			echo json_encode($result);
		}
	}

	// @Add Cart Multi
	function multi_cart_add_by_user_v2($type = 'rest') {
		if ( empty( wc()->cart ) ) {
			$data = ['status' => 'fail','message'=>'Missing param wc_load_cart=1'];
			echo json_encode($data);
			exit();
		}
		
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie   = cek_raw('cookie'); 
		$products = cek_raw('products');

		if($cookie) {
			$user_id = wp_validate_auth_cookie($cookie, 'logged_in');
			$post_args = array(
				'post_type'   => 'user-address',
				'post_status' => 'publish',
				'author__in'  => array( $user_id ),
			);
			$query = new WP_Query( $post_args );
			$found_post = $query->found_posts; 
	
			if( $found_post > 0 ) {

				for($i = 0; $i < count($products); $i++) {
					WC()->cart->add_to_cart( $products[$i]->product_id, (int)$products[$i]->quantity, $products[$i]->variation_id, '', '' );
				}

				$cart_count = 0;
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart ) {
					$cart_item = wc()->cart->get_cart_item($cart_item_key);
					$product_cart['cart_quantity'] = (int)$cart_item['quantity'];
					$cart_count = $cart_count + (int)$cart_item['quantity'];
				}
				$result = ['status' => 'success','code' => 200 ,'count' => $cart_count ,'message' => 'add_cart_success'];
			}
			else {
				$result = ['status' => 'missing_params','count' => 0, "code" => 'user_no_address'];
			}
			echo json_encode($result);
		}
	}

	//Update Single & Multiple ,Remove all item in Cart
	function update_cart_by_user_v2($type = 'rest'){ 
		if ( empty( wc()->cart ) ) {
			$data = ['status' => 'fail','message'=>'Missing param wc_load_cart=1'];
			echo json_encode($data);
			exit();
		}
		require (plugin_dir_path( __FILE__ ).'../helper.php');
		$cookie = cek_raw('cookie');
		$key = cek_raw('key');
		$qty = cek_raw('qty');
	
		if(is_array($key) == true){
			for($i = 0; $i < count($key); $i++){
				WC()->cart->set_quantity($key[$i], $qty);
			}
		}else{
			WC()->cart->set_quantity($key, $qty);
		}	
	
		$cart_count = 0;
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart ) {
			$cart_item = wc()->cart->get_cart_item($cart_item_key);
			$product_cart['cart_quantity'] = (int)$cart_item['quantity'];
			$cart_count = $cart_count + (int)$cart_item['quantity'];
		}
		$result = ['status' => 'success' ,'count' => $cart_count ];
		echo json_encode($result);
	
	}

	function test2(){
		global $wpdb;
		$time = time();
		$obj_sale_product = array();
		$query_args = array(
		  'posts_per_page'    => -1,
		  'no_found_rows'     => 1,
		  'post_status'       => 'publish',
		  'post_type'         => 'product',
		  'meta_query'        => WC()->query->get_meta_query(),
		  'post__in'          => array_merge( array( 0 ), wc_get_product_ids_on_sale() )
		);
		
		$products = new WP_Query( $query_args );
		if( $products->have_posts() )
		{ 
		  while( $products->have_posts() ) {
		  $products->the_post();
		  $id = get_the_id();
		  $current_date       = date('Y-m-d : H:i:s');
		  // array_push($obj_sale_product ,"".$id."");
			$post_id = $wpdb->get_results("SELECT post_id FROM `ph0m31e_postmeta` WHERE post_id = $id AND meta_key = 'cambo_exspire_date' AND (meta_value > '$current_date' || meta_value = '')");
			foreach($post_id as $value){
			  	$postId = $value->post_id;
				$data_product_id = $wpdb->get_results("SELECT post_parent FROM `ph0m31e_posts` WHERE post_parent = ' $postId' AND post_type = 'product_variation' and ID in(SELECT post_id FROM `ph0m31e_postmeta` WHERE (meta_key = '_sale_price_dates_from' AND meta_value <= '$time') OR (meta_key = 'cambo_exspire_date' AND (meta_value > '$current_date' || meta_value = '')))");
			  	foreach($data_product_id as $value_id){
					$post_ = $value_id->post_parent;
					array_push($obj_sale_product ,"".$post_."");
				}
				// array_push($obj_sale_product ,"".$postId."");
			}
		  }
		}
		echo $result_id = json_encode($obj_sale_product);
		$wpdb->update('revo_extend_products',array(
		  'products' => $result_id,
		  ), array('id'=>1)
		);
	}
?>