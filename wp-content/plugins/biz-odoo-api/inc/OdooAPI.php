<?php

namespace BizSolution\OdooAPI;

class OdooAPI
{
    private $url = ODOO_URL;
    private $client_id = ODOO_CLIENT_ID;
    private $client_secret = ODOO_CLIENT_SECRET;
    private $db = ODOO_DB;
    private $token;

	public function __construct()
	{
		$uri  = '/client/api/oauth2/access_token';
		$postfields = 'client_id=' . $this->client_id . '&client_secret=' . $this->client_secret . '&db=' . $this->db;
		$response = $this->post( $uri, $postfields );
		
		$this->token = json_decode($response, true);
	}

	public function get_odoo_token() {
		return $this->token["access_token"];
	}

	public function get_locations($param)
	{
		$uri = "/api/get/".$param;
		$postfields = 'db='. $this->db;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . $uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $this->token["access_token"],
				'Content-Type: application/x-www-form-urlencoded'
			),
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}
	
	public function create_customer( $nickname, $phone, $email, $customer_odoo_id )
	{
		$uri_create = "/api/customer/create";
		$uri_update = "/api/customer/".$customer_odoo_id."/update";
		$uri = $customer_odoo_id == null ? $uri_create : $uri_update;
		
		$postfields = '{
			"params": {
				"name": 	"'. $nickname .'",
				"phone": 	"'. $phone .'",
				"email": 	"'. $email .'",
				"db": 		"'. $this->db .'"
			}
		}';

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . $uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $this->token["access_token"],
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
		));

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}
	
	public function check_stock( $products )
	{
		// Check default billing location
		$author_ID = get_current_user_id();
		$args = array(
			'post_type'     => 'user-address',
			'post_status'   => 'publish',
			'author'        => $author_ID,
			'meta_query' => array(
				array(
					'key' => 'default_address',
					'value' => 1,
					'compare' => '='
				)
			)
		);

		$query = get_posts( $args );

		$address_id = $query[0]->ID;
		$province_id = get_post_meta($address_id, 'province_2', true);
		$district_id = get_post_meta($address_id, 'district_2', true);

		$odoo_province_id = get_term_meta( $province_id, 'odoo_id', true );
		$odoo_district_id = get_term_meta( $district_id, 'odoo_id', true );


		$product_json = json_encode( $products );
		$uri  = "/api/check/product";
		$postfields = '{
			"params":{
				"db": "'. $this->db .'",
				"province_id": '.$odoo_province_id.',
				"district_id": '.$odoo_district_id.',
				"products": '.$product_json.'
			}
		}';

		biz_write_log(json_encode($query) ,'all address');

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . $uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $this->token["access_token"],
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
		));

		$response = curl_exec($curl);
		curl_close($curl);
		
		return $response;
	}
	
	public function process_checkout( $partner_id, $address_id, $products )
	{
		$product_json = json_encode($products);
		$uri  = "/api/create/sale_order";
		$postfields = ["params"=>[
			"partner_id" => $partner_id,
			"address_id" => $address_id,
			"detail" => $products,
			"db" => $this->db,
		]];
		
		$postfields = json_encode($postfields);
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . $uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $this->token["access_token"],
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
	
	public function confirm_sale_order( $order_odoo_id, $aba_transaction_id )
	{
		$uri  = "/api/confirm/sale_order/" . $order_odoo_id;
		$postfields = ["params"=>[
			"payment_id" => get_field('payment_number','option'),
			"aba_transaction_id" => $aba_transaction_id,
			"db" => $this->db,
		]];
		$postfields = json_encode($postfields);

		biz_write_log($postfields ,'26-11-2021 postfield');
		biz_write_log($order_odoo_id ,'26-11-2021 order_odoo_id');

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . $uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $this->token["access_token"],
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
		));

		$response = curl_exec($curl);
		curl_close($curl);
		biz_write_log($response,'26-11-2021 response');

		return $response;
	}
	
	public function get_age_groups()
	{
		$uri  = "/api/get/age-group";
		$postfields = 'db=' . $this->db;
		$response = $this->get( $uri, $postfields, $this->token["access_token"] );
		return $response;
	}
	
	public function get_categories()
	{
		$uri  = "/api/get/product_category";
		$postfields = 'db=' . $this->db;
		$response = $this->get( $uri, $postfields, $this->token["access_token"] );
		return $response;
	}
	
	public function get_brands()
	{
		$uri  = "/api/get/brand";
		$postfields = 'db=' . $this->db;
		$response = $this->get( $uri, $postfields, $this->token["access_token"] );
		return $response;
	}
	
	public function get_products( $take = 10, $page = 1)
	{
		$uri  = "/api/get/product?page=$page&take=$take";
		biz_write_log( $this->url.$uri, 'url' );
		$postfields = 'db=' . $this->db;
		$response = $this->get( $uri, $postfields, $this->token["access_token"] );

		return $response;
	}

	/**
	 * Get All Odoo Prodcuts ID
	 */
	public function get_odoo_product_ids()
	{
		$uri  = "/api/get/product_id";
		$postfields = 'db=' . $this->db;
		$response = $this->get( $uri, $postfields, $this->token["access_token"] );
		return $response;
	}

	protected function get( $uri, $postfields, $bearer_token = '')
	{
		return $this->curl( 'GET', $uri, $postfields, $bearer_token );
	}

	protected function post($uri, $postfields, $bearer_token = '')
	{
		return $this->curl( 'POST', $uri, $postfields, $bearer_token );
	}

	private function curl( $method, $uri, $postfields, $bearer_token = '', $content_type = "Content-Type: application/x-www-form-urlencoded" )
	{
		$curl = curl_init();

		$http_header = array($content_type);

		if( !empty($bearer_token) )
		{
			$http_header = array(
				"Authorization: Bearer " . $bearer_token,
				$content_type
			);
		}

		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . $uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_HTTPHEADER => $http_header,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSL_VERIFYPEER => false,
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}

	
	/**
	 * Create user to Odoo
	 */
	public function create_customer_address( $address, $street, $province_id, $district_id, $commune_id, $post_code, $user_odoo_id, $odoo_address_id ,$phone_id )
	{
		$uri_create  = "/api/customer/".$user_odoo_id."/create/address";
		$uri_update  = "/api/customer/address/".$odoo_address_id."/update";
		$uri = $odoo_address_id == null ? $uri_create : $uri_update;

		$postfields = '{
			"params":{
				"street": 		"'.$address.' '.$street.'",
				"province_id": 	"'.$province_id.'",
				"district_id": 	"'.$district_id.'",
				"commune_id": 	"'.$commune_id.'",
				"phone": 	    "'.$phone_id.'",
				"post_code": 	"'.$post_code.'",
				"db":			"'.$this->db.'"
			}
		}';

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->url . $uri,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $this->token["access_token"],
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => $postfields,
			CURLOPT_SSL_VERIFYHOST => true,
			CURLOPT_SSL_VERIFYPEER => true,
		));

		$response = curl_exec($curl);
		curl_close($curl);
		biz_write_log($response ,'Create Address to odoo 23-11-2022');
		return $response;
	}
}