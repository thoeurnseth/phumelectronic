<?php
class WC_Telegram_Bot extends WC_Telegram_User{
	public $checkout;
	function __construct($params){
		parent::__construct($params);
		$this->set_customer_props();
		$this->checkout = new WC_Telegram_Checkout();
	}
	public function input_checkout_field($checkout_field){
		$checkout_field = explode('-', $checkout_field, 3);
		if(count($checkout_field) < 2){
			return;
		}
		$text = '';
		$type = $checkout_field[0];
		if($type == 'billing'){
			if(wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()){
				$text = esc_html__('Billing &amp; Shipping', 'woocommerce');
			}else{
				$text = esc_html__('Billing details', 'woocommerce');
			}
		}elseif($type == 'shipping'){
			$text = esc_html__('Shipping details', 'woocommerce');
		}elseif($type == 'order'){
			$text = esc_html__('Additional information', 'woocommerce');
		}
		$key = $checkout_field[1];
		if(!$fields = WC()->checkout()->get_checkout_fields($type)){
			return;
		}
		if(!isset($fields[$key])){
			return;
		}
		if(isset($checkout_field[2])){
			$page = $checkout_field[2];
		}else{
			$page = 0;
		}
		$field = $fields[$key];
		if($text){
			$text .= ': ';
		}
		if(!empty($field['label'])){
			$text .= $field['label'];
		}elseif(!empty($field['placeholder'])){
			$text .= $field['placeholder'];
		}
		$reply_markup = false;
		if(isset($field['type'])
		&& in_array($field['type'], array(
			'select',
			'country',
			'state',
		))){
			$options = $this->checkout->get_field_options($key, $field);
			if(is_array($options) && count($options)){
				$inline_keyboard = array();
				$next = false;
				$prev = false;
				$limit = apply_filters('wc_telegram_limit', 10);
				$cols = apply_filters('wc_telegram_cols', 1, count($options), $limit);
				if(count($options) > $limit){
					if($page * $limit > count($options)){
						$page = 0;
					}
					if($page > 0){
						$prev = array(
							'text' => "\xe2\xac\x85",
							'callback_data' => "input=$type-$key-" . ($page - 1),
						);
					}
					if($page * $limit + $limit < count($options)){
						$next = array(
							'text' => "\xe2\x9e\xa1",
							'callback_data' => "input=$type-$key-" . ($page + 1),
						);
					}
					$options = array_slice($options, $page * $limit, $limit, true);
				}
				$c = 0;
				$row = array();
				foreach($options as $k => $v){
					if(empty($k)){
						continue;
					}
					$row[] = array(
						'text' => $v,
						'callback_data' => "cf[$type][$key]=$k",
					);
					$c++;
					if($c == $cols){
						$inline_keyboard[] = $row;
						$c = 0;
						$row = array();
					}
				}
				if($c){
					$inline_keyboard[] = $row;
					$row = array();
				}
				if($prev){
					$row[] = $prev;
				}
				if($next){
					$row[] = $next;
				}
				if($row){
					$inline_keyboard[] = $row;
				}
				$reply_markup = array(
					'inline_keyboard' => $inline_keyboard,
				);
			}
		}
		if(!$reply_markup){
			$reply_markup = array(
				'force_reply' => true,
			);
		}
		$messages = array(
			array(
				'text' => $this->get_checkout_title(),
				'image' => false,
				'reply_markup' => $this->get_menu(),
			),
			array(
				'text' => $text,
				'image' => false,
				'reply_markup' => $reply_markup,
			),
		);
		$this->add_messages($messages);
	}
	private function set_customer_props(){
		$user_id = get_current_user_id();
		$props = array();
		$shipping_props = array();
		if($fields = WC()->checkout()->get_checkout_fields('billing')){
			foreach($fields as $key => $field){
				$props[$key] = get_user_meta($user_id, "wc_telegram_{$key}", true);
				$shipping_props[preg_replace('#^billing_#', 'shipping_', $key)] = get_user_meta($user_id, "wc_telegram_{$key}", true);
			}
		}
		WC()->customer->set_props($props);
		if(wc_ship_to_billing_address_only()){
			WC()->customer->set_props($shipping_props);
		}else{
			$props = array();
			if($fields = WC()->checkout()->get_checkout_fields('shipping')){
				foreach($fields as $key => $field){
					$props[$key] = get_user_meta($user_id, "wc_telegram_{$key}", true);
				}
			}
			WC()->customer->set_props($props);
		}
// ???
		if(isset($_POST['has_full_address']) && wc_string_to_bool(wc_clean(wp_unslash($_POST['has_full_address'])))){
			WC()->customer->set_calculated_shipping(true);
		} else {
			WC()->customer->set_calculated_shipping(false);
		}
		WC()->customer->save();
		WC()->cart->calculate_shipping();
		WC()->cart->calculate_totals();
		unset(WC()->session->refresh_totals, WC()->session->reload_checkout);
	}
	public function set_checkout_select_field($type, $key, $value){
		$user_id = get_current_user_id();
		if($fields = WC()->checkout()->get_checkout_fields($type)){
			if(isset($fields[$key])
			&& in_array($fields[$key]['type'], array(
				'country',
				'select',
				'state',
			))){
				update_user_meta($user_id, "wc_telegram_{$key}", wc_clean($value));
				if($key == 'billing_country'){
					delete_user_meta($user_id, 'wc_telegram_billing_state');
				}elseif($key == 'shipping_country'){
					delete_user_meta($user_id, 'wc_telegram_shipping_state');
				}
				if($type == 'billing'
				&& !get_user_meta($user_id, 'wc_telegram_ship_to_different_address', true)){
					$key = preg_replace('#^billing_#', 'shipping_', $key);
					update_user_meta($user_id, "wc_telegram_{$key}", wc_clean($value));
					if($key == 'shipping_country'){
						delete_user_meta($user_id, 'wc_telegram_shipping_state');
					}
				}
				$this->set_customer_props();
				$this->show_checkout();
			}
		}
	}
	public function set_checkout_field($checkout_field, $value){
		$user_id = get_current_user_id();
		$checkout_field = explode(':', $checkout_field, 2);
		if(count($checkout_field) != 2){
			return;
		}
		$type = trim($checkout_field[0]);
		$_key = trim($checkout_field[1]);
		if(wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()){
			$label = esc_html__('Billing &amp; Shipping', 'woocommerce');
		}else{
			$label = esc_html__('Billing details', 'woocommerce');
		}
		$label = $this->sanitize($label);
		if($label == $type){
			$type = 'billing';
		}else{
			$label = esc_html__('Shipping details', 'woocommerce');
			$label = $this->sanitize($label);
			if($label == $type){
				$type = 'shipping';
			}else{
				$label = esc_html__('Additional information', 'woocommerce');
				$label = $this->sanitize($label);
				if($label == $type){
					$type = 'order';
				}else{
					return;
				}
			}
		}
		if(!$fields = WC()->checkout()->get_checkout_fields($type)){
			return;
		}
		foreach($fields as $key => $field){
			if(!$label = $field['label']){
				if(!$label = $field['placeholder']){
					continue;
				}
			}
			$label = $this->sanitize($label);
			if($label == $_key){
				if($value == '-'){
					delete_user_meta($user_id, "wc_telegram_{$key}");
				}else{
					update_user_meta($user_id, "wc_telegram_{$key}", wc_clean($value));
				}
				if($type == 'billing'
				&& !get_user_meta($user_id, "wc_telegram_ship_to_different_address", true)){
					$key = preg_replace('#^billing_#', 'shipping_', $key);
					if($value == '-'){
						delete_user_meta($user_id, "wc_telegram_{$key}");
					}else{
						update_user_meta($user_id, "wc_telegram_{$key}", wc_clean($value));
					}
				}
				$this->set_customer_props();
				$this->delete_messages();
				$this->show_checkout();
				return;
			}
		}
	}
	public function update_ship_to_different_address($value){
		$user_id = get_current_user_id();
		$fields = WC()->checkout()->get_checkout_fields('shipping');
		if($value){
			update_user_meta($user_id, 'wc_telegram_ship_to_different_address', $value);
		}else{
			delete_user_meta($user_id, 'wc_telegram_ship_to_different_address');
			if($fields){
				foreach($fields as $key => $field){
					$billing_key = preg_replace('#^shipping_#', 'billing_', $key);
					$value = get_user_meta($user_id, "wc_telegram_{$billing_key}", true);
					update_user_meta($user_id, "wc_telegram_{$key}", $value);
				}
			}
		}
		$this->set_customer_props();
		$this->show_checkout();
	}
	public function update_shipping_method($method){
		$method = explode('-', $method, 2);
		if(count($method) == 2){
			wc_maybe_define_constant('WOOCOMMERCE_CHECKOUT', true);
			$chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
			$chosen_shipping_methods[$method[0]] = $method[1];
			WC()->session->set('chosen_shipping_methods', $chosen_shipping_methods);
			$this->set_customer_props();
			$this->show_checkout();
		}
	}
	public function update_payment_method($method){
		wc_maybe_define_constant('WOOCOMMERCE_CHECKOUT', true);
		WC()->session->set('chosen_payment_method', $method);
		$this->set_customer_props();
		$this->show_checkout();
	}
	public function update_terms($agree){
		wc_maybe_define_constant('WOOCOMMERCE_CHECKOUT', true);
		WC()->session->set('terms', $agree);
		$this->set_customer_props();
		$this->show_checkout();
	}

	/**
	 * Process an order that doesn't require payment.
	 *
	 * @since 3.0.0
	 * @param int $order_id Order ID.
	 */
	public function process_order_without_payment($order_id){
		$order = wc_get_order( $order_id );
		$order->payment_complete();
		wc_empty_cart();
	}

	/**
	 * Process an order that does require payment.
	 *
	 * @since 3.0.0
	 * @param int    $order_id       Order ID.
	 * @param string $payment_method Payment method.
	 */
	public function process_order_payment($order_id, $payment_method){
		$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

		if ( ! isset( $available_gateways[ $payment_method ] ) ) {
			return;
		}

		// Store Order ID in session so it can be re-used after payment failure.
		WC()->session->set( 'order_awaiting_payment', $order_id );

		// Process Payment.
		$result = $available_gateways[ $payment_method ]->process_payment( $order_id );

		// Redirect to success/confirmation/payment page.
		if ( isset( $result['result'] ) && 'success' === $result['result'] ) {
			$result = apply_filters( 'woocommerce_payment_successful_result', $result, $order_id );
			if ( isset( $result['result'] ) && 'success' === $result['result'] ) {
				$result = apply_filters( 'woocommerce_payment_successful_result', $result, $order_id );
				$order = wc_get_order($order_id);
				if($order->is_paid()){
					$this->show_thankyou($order);
				}else{
					$this->show_pay_button($result['redirect']);
				}
				wp_die();
			}
		}
	}

	/**
	 * Process the checkout after the confirm order button is pressed.
	 *
	 * @throws Exception When validation fails.
	 */
	public function process_checkout(){
		try {







			wc_maybe_define_constant( 'WOOCOMMERCE_CHECKOUT', true );
			wc_set_time_limit( 0 );



			if ( WC()->cart->is_empty() ) {
				/* translators: %s: shop cart url */
				throw new Exception( sprintf( __( 'Sorry, your session has expired. <a href="%s" class="wc-backward">Return to shop</a>', 'woocommerce' ), esc_url( wc_get_page_permalink( 'shop' ) ) ) );
			}



			$errors      = new WP_Error();
			$posted_data = $this->checkout->get_posted_data();




			// Validate posted data and cart items before proceeding.
			$this->checkout->validate_checkout( $posted_data, $errors );
			$break = false;
			foreach ( $errors->errors as $code => $messages ) {
				$data = $errors->get_error_data( $code );
				foreach ( $messages as $message ) {
					wc_add_notice( $message, 'error', $data ); $break = true; break;
				} if($break) break;
			}

			if ( 0 === wc_notice_count( 'error' ) ) {

				$order_id = WC()->checkout()->create_order( $posted_data );
				$order    = wc_get_order( $order_id );

				if ( is_wp_error( $order_id ) ) {
					throw new Exception( $order_id->get_error_message() );
				}

				if ( ! $order ) {
					throw new Exception( __( 'Unable to create order.', 'woocommerce' ) );
				}

				update_post_meta($order_id, 'wc_telegram_chat_id', $this->chat_id);

				if ( WC()->cart->needs_payment() ) {
					$this->process_order_payment( $order_id, $posted_data['payment_method'] );
				} else {
					$this->process_order_without_payment( $order_id );
				}
			}
		} catch ( Exception $e ) {
			wc_add_notice( $e->getMessage(), 'error' );
		}
		$text = wc_print_notices(true);
		if($text = trim(strip_tags($text))){
			$this->answerCallbackQuery($this->callback_query_id, $text, true);
		}
	}
	private function show_thankyou($order = false){
		if ( $order ){
			if ( $order->has_status( 'failed' ) ){
				$text = esc_html__( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' );
			}else{

				$text = apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), $order );
				$text .= "\n" . esc_html__( 'Order number:', 'woocommerce' );
				$text .= " <strong>{$order->get_order_number()}</strong>";
				$text .= "\n" . esc_html__( 'Date:', 'woocommerce' );
				$text .= ' <strong>' . wc_format_datetime( $order->get_date_created() ) . '</strong>';
				$text .= "\n" . esc_html__( 'Email:', 'woocommerce' );
				$text .= " <strong>{$order->get_billing_email()}</strong>";
				$text .= "\n" . esc_html__( 'Total:', 'woocommerce' );
				$text .= " <strong>{$order->get_formatted_order_total()}</strong>";
				if ( $order->get_payment_method_title() ){
					$text .= "\n" . esc_html__( 'Payment method:', 'woocommerce' );
					$text .= ' <strong>' . wp_kses_post( $order->get_payment_method_title() ) . '</strong>';
				}
			}
		}else{
			$text = apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null );
		}
		$messages = array(
			array(
				'text' => $this->get_shop_title(),
				'image' => false,
				'reply_markup' => $this->get_menu(),
			),
			array(
				'text' => $text,
				'image' => false,
				'reply_markup' => false,
			),
		);
		$this->add_messages($messages);
	}
	private function show_pay_button($link){
		$text = __('Payment', 'woocommerce');
		$inline_keyboard = array();
		$inline_keyboard[] = array(
			array(
				'text' => "\xe2\x9c\x85 " . esc_html__('Continue', 'woocommerce'),
				'url' => $link,
			),
			array(
				'text' => "\xe2\x9d\x8e " . esc_html__('Cancel', 'woocommerce'),
				'callback_data' => 'checkout',
			),
		);
		$reply_markup = array(
			'inline_keyboard' => $inline_keyboard,
		);
		$this->add_messages(array(
			array(
				'text' => $this->get_shop_title(),
				'image' => false,
				'reply_markup' => $reply_markup,
			),
		));
	}
	public function add_to_cart($add_to_cart){
		$add_to_cart = explode('|', $add_to_cart, 2);
		$product_id = intval($add_to_cart[0]);
		if(!empty($add_to_cart[1])){
			$quantity = intval($add_to_cart[1]);
		}else{
			$quantity = 1;
		}
		if($quantity > 0){
			WC()->cart->add_to_cart($product_id, $quantity);
		}elseif($quantity < 0){
			$cart_id = WC()->cart->generate_cart_id($product_id);
			if($cart_item_key = WC()->cart->find_product_in_cart($cart_id)){
				if(0 < $quantity = WC()->cart->cart_contents[$cart_item_key]['quantity'] - 1){
					WC()->cart->set_quantity($cart_item_key, $quantity);
				}else{
					WC()->cart->remove_cart_item($cart_item_key);
				}
			}
		}
		$text = wc_print_notices(true);
		if($text = trim(strip_tags($text))){
			$this->answerCallbackQuery($this->callback_query_id, $text);
		}else{
			$this->show_product($product_id);
		}
	}
	private function get_shipping_fields($fields){
		if(!$fields){
			return array();
		}
		$user_id = get_current_user_id();
		$checkout_fields = array();
		$label = esc_html__('Ship to a different address?', 'woocommerce');
		$different = get_user_meta($user_id, 'wc_telegram_ship_to_different_address', true);
		$checkout_fields[] = array(
			array(
				'text' => ($different?"\xe2\x9c\x85":"\xe2\x9d\x8e") . ' ' . $label,
				'callback_data' => 'ship_to_different_address=' . ($different?0:1),
			),
		);
		if($different){
			if(isset($field['shipping_country'])){
				$country = $this->checkout->get_value('shipping_country', $field['shipping_country']);
			}else{
				$country = false;
			}
			foreach($fields as $key => $field){
				if(!$label = $this->checkout->get_value($key, $field)){
					continue;
				}
				$checkout_fields[] = array(
					array(
						'text' => $label,
						'callback_data' => "input=shipping-$key",
					),
				);
			}
		}
		return $checkout_fields;
	}
	public function get_menu(){
		$keyboard = array(
			array(
				array(
					'text' => "\xf0\x9f\x9b\x92" . __('View cart', 'woocommerce'),
				),
				array(
					'text' => "\xf0\x9f\x93\xa6 " . __('Checkout', 'woocommerce'),
				),
			),
			array(
				array(
					'text' => "\xf0\x9f\x9b\x8d " . __('Go to shop', 'woocommerce'),
				),
			),
		);
		$reply_markup = array(
			'keyboard' => apply_filters('wc_telegram_menu_keyboard', $keyboard),
			'resize_keyboard' => true,
		);
		return $reply_markup;
	}
	private function get_checkout_title(){
		return "{$this->get_shop_title()} / \xf0\x9f\x93\xa6 " . __('Checkout', 'woocommerce');
	}
	public function get_shop_title(){
		$text = '';
		if($page_id = wc_get_page_id('shop')){
			$text = get_the_title($page_id);
		}
		if(!$text){
			$text = __('breadcrumb', 'woocommerce');
		}
		$text = "\xf0\x9f\x9b\x8d " . $text;
		return $text;
	}
	private function get_order_button(){
		$user_id = get_current_user_id();
		$buttons = array();
		if(wc_terms_and_conditions_checkbox_enabled()){
			$terms = WC()->session->get('terms');
			$buttons[] = array(
				array(
					'text' => ($terms?("\xe2\x9c\x85 " . __('Yes', 'woocommerce')):("\xe2\x9d\x8e " . __('No', 'woocommerce'))),
					'callback_data' => "terms=" . ($terms?0:1),
				),
			);
		}
		$buttons[] = array(
			array(
				'text' => "\xf0\x9f\x93\xa6 " . esc_html(apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce'))),
				'callback_data' => 'process-checkout',
			),
		);
		return $buttons;
	}
	public function show_checkout(){
		if(WC()->cart->is_empty()){
			$this->show_cart();
			return;
		}
		$messages = array();
		$user_id = get_current_user_id();
		$messages[] = array(
			'text' => $this->get_checkout_title(),
			'image' => false,
			'reply_markup' => $this->get_menu(),
		);
		$cart_items = implode("\n", $this->get_cart_items());
		$messages[] = array(
			'text' => "$cart_items\n---{$this->get_total()}",
			'image' => false,
			'reply_markup' => false,
		);
		$reply_markup = false;
		$checkout = WC()->checkout();
		if($fields = $checkout->get_checkout_fields('billing')){
			if(isset($field['billing_country'])){
				$country = $this->checkout->get_value('billing_country', $field['billing_country']);
			}else{
				$country = false;
			}
			$checkout_fields = array();
			foreach($fields as $key => $field){
				if(!$label = $this->checkout->get_value($key, $field)){
					continue;
				}
				$checkout_fields[] = array(
					array(
						'text' => $label,
						'callback_data' => "input=billing-$key",
					),
				);
			}
			if(count($checkout_fields)){
				if(wc_ship_to_billing_address_only() && WC()->cart->needs_shipping()){
					$text = esc_html__('Billing &amp; Shipping', 'woocommerce');
				}else{
					$text = esc_html__('Billing details', 'woocommerce');
				}
				$reply_markup = array(
					'inline_keyboard' => $checkout_fields,
				);
				$messages[] = array(
					'text' => $text,
					'image' => false,
					'reply_markup' => $reply_markup,
				);
			}
		}
		if(true === WC()->cart->needs_shipping_address()){
			if($fields = $checkout->get_checkout_fields('shipping')){
				$checkout_fields = $this->get_shipping_fields($fields);
				if(count($checkout_fields)){
					$text = esc_html__('Shipping details', 'woocommerce');
					$reply_markup = array(
						'inline_keyboard' => $checkout_fields,
					);
					$messages[] = array(
						'text' => $text,
						'image' => false,
						'reply_markup' => $reply_markup,
					);
				}
			}
		}
		if(apply_filters('woocommerce_enable_order_notes_field', 'yes' === get_option('woocommerce_enable_order_comments', 'yes'))){
			if($fields = $checkout->get_checkout_fields('order')){
				$checkout_fields = array();
				foreach($fields as $key => $field){
					if(!$label = $this->checkout->get_value($key, $field)){
						continue;
					}
					$checkout_fields[] = array(
						array(
							'text' => $label,
							'callback_data' => "input=order-$key",
						),
					);
				}
				if(count($checkout_fields)){
					$text = esc_html__('Additional information', 'woocommerce');
					$reply_markup = array(
						'inline_keyboard' => $checkout_fields,
					);
					$messages[] = array(
						'text' => $text,
						'image' => false,
						'reply_markup' => $reply_markup,
					);
				}
			}
		}
		$packages = WC()->shipping()->get_packages();
		foreach($packages as $i => $package){
			$chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
			$product_names = array();
			if(count( $packages ) > 1){
				foreach ( $package['contents'] as $item_id => $values ) {
					$product_names[ $item_id ] = $values['data']->get_name() . ' &times;' . $values['quantity'];
				}
				$product_names = apply_filters( 'woocommerce_shipping_package_details_array', $product_names, $package );
			}
			if($package_details = implode( ', ', $product_names)){
				$package_details = "\n$package_details";
			}
			$text = apply_filters('woocommerce_no_shipping_available_html', __('There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce'));
			$reply_markup = false;
			if($package['rates']){
				$checkout_fields = array();
				foreach($package['rates'] as $method){
					$label = ($method->id == $chosen_method?"\xe2\x9c\x85":"\xe2\x9d\x8e") . ' ' . wc_cart_totals_shipping_method_label($method);
					$label = $this->sanitize($label);
					$checkout_fields[] = array(
						array(
							'text' => $label,
							'callback_data' => "shipping-method=$i-" . $method->id,
						),
					);
				}
				if(count($checkout_fields)){
					$text = esc_html__('Shipping', 'woocommerce') . $package_details;
					$reply_markup = array(
						'inline_keyboard' => $checkout_fields,
					);
				}
			}
			$messages[] = array(
				'text' => $text,
				'image' => false,
				'reply_markup' => $reply_markup,
			);
		}
		if(WC()->cart->needs_payment()){
			$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
			WC()->payment_gateways()->set_current_gateway($available_gateways);
			$text = apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country()? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce'));
			$reply_markup = false;
			if(!empty($available_gateways)){
				$checkout_fields = array();
				foreach($available_gateways as $gateway){
					$label = ($gateway->chosen?"\xe2\x9c\x85":"\xe2\x9d\x8e") . ' ' . $gateway->get_title();
					$label = $this->sanitize($label);
					$checkout_fields[] = array(
						array(
							'text' => $label,
							'callback_data' => "payment-method=" . $gateway->id,
						),
					);
				}
			}
			if(count($checkout_fields)){
				$text = esc_html__('Payment', 'woocommerce');
				$reply_markup = array(
					'inline_keyboard' => $checkout_fields,
				);
			}
			$messages[] = array(
				'text' => $text,
				'image' => false,
				'reply_markup' => $reply_markup,
			);
		}
		$text = false;
		$checkout_fields = $this->get_order_button();
		if(apply_filters('woocommerce_checkout_show_terms', true) && function_exists('wc_terms_and_conditions_checkbox_enabled')){
			ob_start();
			wc_checkout_privacy_policy_text();
			$text = ob_get_clean();
			if(wc_terms_and_conditions_checkbox_enabled()){
				ob_start();
				wc_terms_and_conditions_checkbox_text();
				if($txt = ob_get_clean()){
					$text .= "\n$txt";
				}
				if(!$text = strip_tags($text)){
					$text = esc_html__('Terms and conditions');
				}
			}
			$text = $this->sanitize_text($text);
		}
		if(!$text){
			$text = esc_html(apply_filters('woocommerce_order_button_text', __('Place order', 'woocommerce')));
		}
		$reply_markup = array(
			'inline_keyboard' => $checkout_fields,
		);
		$messages[] = array(
			'text' => $text,
			'image' => false,
			'reply_markup' => $reply_markup,
		);
		$this->add_messages($messages);
	}
	private function get_categories($parent_id = 0){
		$categories = get_terms('product_cat', array(
			'parent' => $parent_id,
			'orderby'    => 'menu_order',
			'order'      => 'asc',
			'hide_empty' => true,
		));
		return $categories;
	}
	public function get_breadcrumbs($product){
		$text = array($this->get_shop_title());
		if($terms = get_the_terms($product->get_id(), 'product_cat')){
			foreach($terms as $category){
				$text[] = <<<END
<b>{$category->name}</b> ({$category->count})
END;
			}
		}
		$text = implode("\n", $text);
		return $text;
	}
	public function show_product($product){
		if(is_numeric($product)){
			if(!$product = wc_get_product($product)){
				return;
			}
		}
		$quantity = 0;
		$name = $product->get_name();
		$image = false;
		if(!$thumbnail_id = $product->get_image_id()){
			$thumbnail_id = get_option('woocommerce_placeholder_image', 0);
		}
		if($thumbnail_id){
			$image = wp_get_attachment_url($thumbnail_id);
		}
		if($terms = get_the_terms($product->get_id(), 'product_cat')){
			$back = reset($terms)->term_id;
		}else{
			$back = false;
		}
		if($description = $product->get_description()){
			$description = "\n$description";
		}
		if($product->is_type('simple')){
			if($price = $product->get_price_html()){
				$price = "\n$price";
			}
		}else{
			$price = '';
		}
		$in_the_cart = '';
		if($product->is_type('simple')){
			$cart_id = WC()->cart->generate_cart_id($product->get_id(), 0, array(), array());
			if($cart_item_key = WC()->cart->find_product_in_cart($cart_id)){
				if($quantity = WC()->cart->cart_contents[$cart_item_key]['quantity']){
					$in_the_cart = sprintf(_n(
						'%s item',
						'%s items',
						$quantity,
						'woocommerce'
					), $quantity);
					$in_the_cart = __('Cart', 'woocommerce') . ": $in_the_cart";
					$in_the_cart = "\n$in_the_cart";
				}
			}
		}
		$text = <<<END
<b>$name</b>$price$description$in_the_cart
END;
		$reply_markup = false;
		$add_to_cart = array();
		if($back){
			$add_to_cart[] = array(
				'text' => "\xe2\x86\xa9",
				'callback_data' => "term_id=$back",
			);
		}
		if($product->is_type('simple')){
			if($quantity){
				$add_to_cart[] = array(
					'text' => "\xe2\x9e\x96",
					'callback_data' => "add-to-cart={$product->get_id()}|-1",
				);
				$add_to_cart[] = array(
					'text' => "\xe2\x9e\x95",
					'callback_data' => "add-to-cart={$product->get_id()}",
				);
			}else{
				$add_to_cart[] = array(
					'text' => "\xf0\x9f\x9b\x92 " . $product->add_to_cart_text(),
					'callback_data' => "add-to-cart={$product->get_id()}",
				);
			}
		}
		if($add_to_cart){
			$reply_markup = array(
				'inline_keyboard' => array(
					$add_to_cart,
				),
			);
		}
		$messages = array(
			array(
				'text' => $this->get_shop_title(),
				'image' => false,
				'reply_markup' => $this->get_menu(),
			),
			array(
				'text' => $text,
				'image' => $image,
				'reply_markup' => $reply_markup,
			),
		);
		$this->add_messages($messages);
	}
	public function show_categories($parent_id = 0){
		$parent_id = explode('|', $parent_id);
		if(isset($parent_id[1])){
			$page = $parent_id[1];
		}else{
			$page = 0;
		}
		if($parent_id = $parent_id[0]){
			if(!$parent_category = get_term($parent_id, 'product_cat')){
				return;
			}
			$back = $parent_category->parent;
		}else{
			$parent_category = false;
			$back = 0;
		}
		if($categories = $this->get_categories($parent_id)){
			if($parent_category){
				$text = $parent_category->name;
			}else{
				$text = __('Subcategories', 'woocommerce');
			}
			$inline_keyboard = array();
			foreach($categories as $category){
				$inline_keyboard[] = array(
					array(
						'text' => $category->name,
						'callback_data' => "term_id={$category->term_id}",
					),
				);
			}
			if($parent_id){
				$inline_keyboard[] = array(
					array(
						'text' => "\xe2\x86\xa9",
						'callback_data' => "term_id=$back",
					),
				);
			}
			$reply_markup = array(
				'inline_keyboard' => $inline_keyboard,
			);
			$messages = array(
				array(
					'text' => $this->get_shop_title(),
					'image' => false,
					'reply_markup' => $this->get_menu(),
				),
				array(
					'text' => $text,
					'image' => false,
					'reply_markup' => $reply_markup,
				),
			);
			$this->add_messages($messages);
			return;
		}
		$orderby_value = apply_filters('woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order'));
		$orderby_value = is_array( $orderby_value ) ? $orderby_value : explode( '-', $orderby_value );
		$orderby       = esc_attr( $orderby_value[0] );
		$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : 'ASC';
		$args = array(
			'status' => array('publish'),			
			'type' => 'simple',
			'orderby' => $orderby,
			'order' => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
			'limit' => -1,
		);
		if($parent_category){
			$args['category'] = array($parent_category->slug);
			$text = $parent_category->name;
		}else{
			$text = __('Products', 'woocommerce');
		}
		$args = apply_filters('wc_telegram_get_products', $args, $parent_id);
		if($products = wc_get_products($args)){
			$inline_keyboard = array();
			$next = false;
			$prev = false;
			$limit = apply_filters('wc_telegram_limit', 10);
			$cols = apply_filters('wc_telegram_cols', 1, count($products), $limit);
			if(count($products) > $limit){
				if($page * $limit > count($products)){
					$page = 0;
				}
				if($page > 0){
					$prev = array(
						'text' => "\xe2\xac\x85",
						'callback_data' => "term_id=$parent_id|" . ($page - 1),
					);
				}
				if($page * $limit + $limit < count($products)){
					$next = array(
						'text' => "\xe2\x9e\xa1",
						'callback_data' => "term_id=$parent_id|" . ($page + 1),
					);
				}
				$products = array_slice($products, $page * $limit, $limit, true);
			}
			$c = 0;
			$row = array();
			foreach($products as $product){
				$link = array(
					'text' => $product->get_title(),
					'callback_data' => "product_id={$product->get_id()}",
				);
				$row[] = $link;
				$c++;
				if($c == $cols){
					$inline_keyboard[] = $row;
					$c = 0;
					$row = array();
				}
			}
			if($c){
				$inline_keyboard[] = $row;
				$row = array();
			}
			if($parent_id){
				$row[] = array(
					'text' => "\xe2\x86\xa9",
					'callback_data' => "term_id=$back",
				);
			}
			if($prev){
				$row[] = $prev;
			}
			if($next){
				$row[] = $next;
			}
			if($row){
				$inline_keyboard[] = $row;
			}
			$reply_markup = array(
				'inline_keyboard' => $inline_keyboard,
			);
			$messages = array(
				array(
					'text' => $this->get_shop_title(),
					'image' => false,
					'reply_markup' => $this->get_menu(),
				),
				array(
					'text' => $text,
					'image' => false,
					'reply_markup' => $reply_markup,
				),
			);
			$this->add_messages($messages);
			return;
		}
		if($parent_id){
			$reply_markup = array(
				'inline_keyboard' => array(
					array(
						array(
							'text' => "\xe2\x86\xa9",
							'callback_data' => "term_id=$back",
						),
					),
				),
			);
		}else{
			$reply_markup = false;
		}
		$messages = array(
			array(
				'text' => $this->get_shop_title(),
				'image' => false,
				'reply_markup' => $this->get_menu(),
			),
			array(
				'text' => __('No products found.', 'woocommerce'),
				'image' => false,
				'reply_markup' => $reply_markup,
			),
		);
		$this->add_messages($messages);
	}
	public function get_total(){
		$text = '';
		ob_start();
		wc_cart_totals_subtotal_html();
		$subtotal = ob_get_clean();
		ob_start();
		wc_cart_totals_order_total_html();
		$total = ob_get_clean();
		if(preg_replace('#[^0-9\-\.,]#', '', $subtotal) != preg_replace('#[^0-9\-\.,]#', '', $total)){
			$text .= "\n" . esc_html__('Subtotal', 'woocommerce');
			$text .= ' <b>' . $subtotal . '</b>';
		}
		$packages = WC()->shipping()->get_packages();
		foreach($packages as $i => $package){
			$chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
			if($package['rates']){
				foreach($package['rates'] as $method){
					if($method->id == $chosen_method){
						$has_cost  = 0 < $method->cost;
						$hide_cost = !$has_cost && in_array($method->get_method_id(), array('free_shipping', 'local_pickup'), true);
						if($has_cost && !$hide_cost){
							$label = $method->get_label();
							$text .= "\n$label";
							if(WC()->cart->display_prices_including_tax()){
								$cost = wc_price($method->cost + $method->get_shipping_tax());
							}else{
								$cost = wc_price($method->cost);
							}
							$text .= ' <b>' . $cost . '</b>';
						}
					}
				}
			}
		}
		foreach(WC()->cart->get_coupons() as $code => $coupon){
			ob_start();
			wc_cart_totals_coupon_label($coupon);
			$text .= "\n" . ob_get_clean();
			ob_start();
			wc_cart_totals_coupon_html($coupon);
			$text .= ' <b>' . ob_get_clean() . '</b>';
		}
		foreach(WC()->cart->get_fees() as $fee){
			$text .= "\n" . esc_html($fee->name);
			ob_start();
			wc_cart_totals_fee_html($fee);
			$text .= ' <b>' . ob_get_clean() . '</b>';
		}
		if(wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()){
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';
			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				$estimated_text = sprintf(esc_html__('(estimated for %s)', 'woocommerce'), WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
			}
			if('itemized' === get_option('woocommerce_tax_total_display')){
				foreach(WC()->cart->get_tax_totals() as $code => $tax){
					$text .= "\n" . esc_html( $tax->label ) . $estimated_text;
					$text .= ' <b>' . wp_kses_post($tax->formatted_amount) . '</b>';
				}
			}else{
				$text .= "\n" . esc_html(WC()->countries->tax_or_vat()) . $estimated_text;
				ob_start();
				wc_cart_totals_taxes_total_html();
				$text .= ' <b>' . ob_get_clean() . '</b>';
			}
		}
		$text .= "\n" . esc_html__('Total', 'woocommerce');
		$text .= ' <b>' . $total . '</b>';
		return $text;
	}
	public function get_cart_items($active = false){
		$cart_items = array();
		foreach(WC()->cart->get_cart() as $cart_item_key => $cart_item){
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
			$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
			if($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)){
				$name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
				if($meta = wc_get_formatted_cart_item_data($cart_item)){
					$meta = " ($meta)";
				}
				$price = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key);
				$product_quantity = $cart_item['quantity'];
				$product_quantity = apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
				$product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity'] ), $cart_item, $cart_item_key);
			}
			$item = <<<END
$name$meta: $price x $product_quantity = $product_subtotal
END;
			$item = preg_replace('#\s+#s', ' ', $item);
			if($active){
				$cart_items[] = array(
					array(
						'text' => $item,
						'callback_data' => "product_id={$product_id}",
					),
				);
			}else{
				$cart_items[] = $item;
			}
		}
		return $cart_items;
	}
	public function show_cart(){
		$messages = array();
		$cart_items = $this->get_cart_items(true);
		if($cart_items){
			$text = "{$this->get_shop_title()} / \xf0\x9f\x9b\x92 " . __('Cart', 'woocommerce');
			$reply_markup = array(
				'inline_keyboard' => $cart_items,
			);
			$messages[] = array(
				'text' => $text,
				'image' => false,
				'reply_markup' => $reply_markup,
			);
			$text = $this->get_total();
		}else{
			$text = apply_filters('wc_empty_cart_message', __('Your cart is currently empty.', 'woocommerce'));
		}
		$messages[] = array(
			'text' => $text,
			'image' => false,
			'reply_markup' => $this->get_menu(),
		);
		$this->add_messages($messages);
	}
}
