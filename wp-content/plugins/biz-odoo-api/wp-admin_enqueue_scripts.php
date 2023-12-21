<?php

	// WooCommerce Rest API Client
	require_once ('vendor/autoload.php');

	use BizSolution\OdooAPI\OdooWoocommerce;
    use Automattic\WooCommerce\Client;
    use Automattic\WooCommerce\HttpClient\HttpClientException;

	if ( ! function_exists( 'biz_odoo_api_admin_enqueue_scripts' ) ):
		/**
		 * Adds the version of a package to the $jetpack_packages global array so that
		 * the autoloader is able to find it.
		 */
		function biz_odoo_api_admin_enqueue_scripts($hook)
		{
			if( in_array($hook, ["biz-solution_page_biz-odoo-api-page"] ) ):
				wp_register_style( 'biz-odoo-api-loading-bar-css', plugins_url('biz-odoo-api/assets/css/loading-bar.min.css'), false, '1.0.1' );
				wp_enqueue_style( 'biz-odoo-api-loading-bar-css' );

				wp_register_script( 'biz-odoo-api-loading-bar-script', plugins_url('biz-odoo-api/assets/js/loading-bar.min.js'), array('jquery'), '1.0.1' );
				wp_enqueue_script( 'biz-odoo-api-loading-bar-script' );

				wp_register_script( "biz-odoo-api-main-script", plugins_url('biz-odoo-api/assets/js/main.js?v=' . uniqid()), array('jquery') );
				wp_enqueue_script( 'biz-odoo-api-main-script' );
				wp_localize_script( 'biz-odoo-api-main-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
			endif;
		}
		add_action( 'admin_enqueue_scripts', 'biz_odoo_api_admin_enqueue_scripts' );
	endif;



	/**
	 * Sync Data From ODOO
	 */
	add_action("wp_ajax_odoo_sync_action", "odoo_sync_action");
	// add_action("wp_ajax_nopriv_auth_odoo_sync", "auth_odoo_sync");

	function odoo_sync_action()
	{
		if( !isset( $_POST['stage'] ) )
		{
			exit("Page not found.");
		}
		if ( !wp_verify_nonce( $_REQUEST['nonce'], "biz_odoo_api_sync_nonce")) {
			exit("You are not authenticated.");
		}

		$stage = $_POST['stage'];
        $result = ["0"];

		switch( $stage )
		{
			case "locations":

				$odoo_woo = new OdooWoocommerce();
				$result = $odoo_woo->sync_provinces_from_doo();
				$result = $odoo_woo->sync_districts_from_doo();
				$result = $odoo_woo->sync_communes_from_doo('communes?take=50&page=1');
				break;

			case "locations_commune":

				$page_number = 1;
				if( isset( $_POST['page_number'] ) )
				{
					$page_number = $_POST['page_number'];
				}
				$odoo_woo = new OdooWoocommerce();
				$result = $odoo_woo->sync_communes_from_doo('communes?take=100&page=' . $page_number);
				break; 

			// case "age_groups":
			// 	$odoo_woo = new OdooWoocommerce();
			// 	$result = $odoo_woo->sync_agegroup_from_doo();
			// 	break;

			case "categories":
				$odoo_woo = new OdooWoocommerce();
				$result = $odoo_woo->sync_categories_from_doo();
				break;

			case "trash_products":
				$odoo_woo = new OdooWoocommerce();
				$result = $odoo_woo->move_products_to_trash();
				break;

			case "brands":

				$odoo_woo = new OdooWoocommerce();
				$result = $odoo_woo->sync_brands_from_doo();
				break;

			case "products":
				
				$page_number = 1;
				if( isset( $_POST['page_number'] ) )
				{
					$page_number = $_POST['page_number'];
				}

				$categories = [];
				$brands = [];

				$params 	= isset($_POST['params']) ? $_POST['params'] : [];
				$params 	= json_decode(stripcslashes($params), true);

				$result 	= biz_odoo_api_products_sync( 5, $page_number, $params['categories'], $params['brands'] );
				
			break;
		}


		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$result = json_encode($result);
			echo $result;
		}
		else {
			header("Location: ".$_SERVER["HTTP_REFERER"]);
		}

		die();

	}

	function auth_odoo_sync() {
		echo "You must log in to vote";
		die();
	}