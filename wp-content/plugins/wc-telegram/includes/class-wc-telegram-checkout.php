<?php
class WC_Telegram_Checkout{
	public function get_value($key, $field = false){
		$user_id = get_current_user_id();
		$value = get_user_meta($user_id, "wc_telegram_{$key}", true);
		if($field){
			if($value){
				if(isset($field['type'])
				&& in_array($field['type'], array(
					'select',
					'country',
					'state',
				))){
					$options = $this->get_field_options($key, $field);
					if(isset($options[$value])){
						$value = $options[$value];
					}
				}
			}else{
				if(!$value = $field['label']){
					$value = $field['placeholder'];
				}
			}
		}
		return $value;
	}
	public function get_field_options($key, $field){
		if($field['type'] == 'country'){
			if(preg_match('#^shipping_#', $key)){
				$options = WC()->countries->get_shipping_countries();
			}elseif(preg_match('#^billing_#', $key)){
				$options = WC()->countries->get_allowed_countries();
			}else{
				$options = WC()->countries->get_countries();
			}
			asort($options);
		}elseif($field['type'] == 'state'){
			if(isset($field['country_field'])){
				if($country = $this->get_value($field['country_field'])){
					if($options = WC()->countries->get_states($country)){
						asort($options);
					}
				}
			}
		}else{
			$options = $field['options'];
		}
		return $options;
	}
	/**
	 * See if a fieldset should be skipped.
	 *
	 * @since 3.0.0
	 * @param string $fieldset_key Fieldset key.
	 * @param array  $data         Posted data.
	 * @return bool
	 */
	protected function maybe_skip_fieldset( $fieldset_key, $data ) {
		if ( 'shipping' === $fieldset_key && ( ! $data['ship_to_different_address'] || ! WC()->cart->needs_shipping_address() ) ) {
			return true;
		}

		if ( 'account' === $fieldset_key && ( is_user_logged_in() || ( ! $this->is_registration_required() && empty( $data['createaccount'] ) ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Get posted data from the checkout form.
	 *
	 * @since  3.1.0
	 * @return array of data.
	 */
	public function get_posted_data(){
		$user_id = get_current_user_id();
		$posted_data = array(
			'payment_method' => WC()->session->get('chosen_payment_method'),
			'ship_to_different_address' => get_user_meta($user_id, 'wc_telegram_ship_to_different_address', true),
		);
		foreach ( WC()->checkout()->get_checkout_fields() as $fieldset_key => $fieldset ) {
			foreach ( $fieldset as $key => $field ) {
				$value = $this->get_value($key);
				$posted_data[$key] = $value;
			}
		}
		return $posted_data;
	}

	/**
	 * Validates the posted checkout data based on field properties.
	 *
	 * @since  3.0.0
	 * @param  array    $data   An array of posted data.
	 * @param  WP_Error $errors Validation error.
	 */
	public function validate_posted_data( &$data, &$errors ) {
		foreach ( WC()->checkout()->get_checkout_fields() as $fieldset_key => $fieldset ) {
			$validate_fieldset = true;
			if ( $this->maybe_skip_fieldset( $fieldset_key, $data ) ) {
				$validate_fieldset = false;
			}

			foreach ( $fieldset as $key => $field ) {
				if ( ! isset( $data[ $key ] ) ) {
					continue;
				}
				$required    = ! empty( $field['required'] );
				$format      = array_filter( isset( $field['validate'] ) ? (array) $field['validate'] : array() );
				$field_label = isset( $field['label'] ) ? $field['label'] : '';

				switch ( $fieldset_key ) {
					case 'shipping':
						/* translators: %s: field name */
						$field_label = sprintf( _x( 'Shipping %s', 'checkout-validation', 'woocommerce' ), $field_label );
						break;
					case 'billing':
						/* translators: %s: field name */
						$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label );
						break;
				}

				if ( in_array( 'postcode', $format, true ) ) {
					$country      = isset( $data[ $fieldset_key . '_country' ] ) ? $data[ $fieldset_key . '_country' ] : WC()->customer->{"get_{$fieldset_key}_country"}();
					$data[ $key ] = wc_format_postcode( $data[ $key ], $country );

					if ( $validate_fieldset && '' !== $data[ $key ] && ! WC_Validation::is_postcode( $data[ $key ], $country ) ) {
						switch ( $country ) {
							case 'IE':
								/* translators: %1$s: field name, %2$s finder.eircode.ie URL */
								$postcode_validation_notice = sprintf( __( '%1$s is not valid. You can look up the correct Eircode <a target="_blank" href="%2$s">here</a>.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>', 'https://finder.eircode.ie' );
								break;
							default:
								/* translators: %s: field name */
								$postcode_validation_notice = sprintf( __( '%s is not a valid postcode / ZIP.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' );
						}
						$errors->add( $key . '_validation', apply_filters( 'woocommerce_checkout_postcode_validation_notice', $postcode_validation_notice, $country, $data[ $key ] ), array( 'id' => $key ) );
					}
				}

				if ( in_array( 'phone', $format, true ) ) {
					if ( $validate_fieldset && '' !== $data[ $key ] && ! WC_Validation::is_phone( $data[ $key ] ) ) {
						/* translators: %s: phone number */
						$errors->add( $key . '_validation', sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), array( 'id' => $key ) );
					}
				}

				if ( in_array( 'email', $format, true ) && '' !== $data[ $key ] ) {
					$email_is_valid = is_email( $data[ $key ] );
					$data[ $key ]   = sanitize_email( $data[ $key ] );

					if ( $validate_fieldset && ! $email_is_valid ) {
						/* translators: %s: email address */
						$errors->add( $key . '_validation', sprintf( __( '%s is not a valid email address.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), array( 'id' => $key ) );
						continue;
					}
				}

				if ( '' !== $data[ $key ] && in_array( 'state', $format, true ) ) {
					$country      = isset( $data[ $fieldset_key . '_country' ] ) ? $data[ $fieldset_key . '_country' ] : WC()->customer->{"get_{$fieldset_key}_country"}();
					$valid_states = WC()->countries->get_states( $country );

					if ( ! empty( $valid_states ) && is_array( $valid_states ) && count( $valid_states ) > 0 ) {
						$valid_state_values = array_map( 'wc_strtoupper', array_flip( array_map( 'wc_strtoupper', $valid_states ) ) );
						$data[ $key ]       = wc_strtoupper( $data[ $key ] );

						if ( isset( $valid_state_values[ $data[ $key ] ] ) ) {
							// With this part we consider state value to be valid as well, convert it to the state key for the valid_states check below.
							$data[ $key ] = $valid_state_values[ $data[ $key ] ];
						}

						if ( $validate_fieldset && ! in_array( $data[ $key ], $valid_state_values, true ) ) {
							/* translators: 1: state field 2: valid states */
							$errors->add( $key . '_validation', sprintf( __( '%1$s is not valid. Please enter one of the following: %2$s', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>', implode( ', ', $valid_states ) ), array( 'id' => $key ) );
						}
					}
				}

				if ( $validate_fieldset && $required && '' === $data[ $key ] ) {
					/* translators: %s: field name */
					$errors->add( $key . '_required', apply_filters( 'woocommerce_checkout_required_field_notice', sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), $field_label ), array( 'id' => $key ) );
				}
			}
		}
	}

	/**
	 * Validates that the checkout has enough info to proceed.
	 *
	 * @since  3.0.0
	 * @param  array    $data   An array of posted data.
	 * @param  WP_Error $errors Validation errors.
	 */
	public function validate_checkout( &$data, &$errors ) {
		$this->validate_posted_data( $data, $errors );


		if ( empty( $data['woocommerce_checkout_update_totals'] ) && empty( $data['terms'] ) && ! empty( WC()->session->get('terms') ) ) { // WPCS: input var ok, CSRF ok.
			$errors->add( 'terms', __( 'Please read and accept the terms and conditions to proceed with your order.', 'woocommerce' ) );
		}

		if ( WC()->cart->needs_shipping() ) {
			$shipping_country = WC()->customer->get_shipping_country();

			if ( empty( $shipping_country ) ) {
				$errors->add( 'shipping', __( 'Please enter an address to continue.', 'woocommerce' ) );
			} elseif ( ! in_array( WC()->customer->get_shipping_country(), array_keys( WC()->countries->get_shipping_countries() ), true ) ) {
				/* translators: %s: shipping location */
				$errors->add( 'shipping', sprintf( __( 'Unfortunately <strong>we do not ship %s</strong>. Please enter an alternative shipping address.', 'woocommerce' ), WC()->countries->shipping_to_prefix() . ' ' . WC()->customer->get_shipping_country() ) );
			} else {
				$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

				foreach ( WC()->shipping()->get_packages() as $i => $package ) {
					if ( ! isset( $chosen_shipping_methods[ $i ], $package['rates'][ $chosen_shipping_methods[ $i ] ] ) ) {
						$errors->add( 'shipping', __( 'No shipping method has been selected. Please double check your address, or contact us if you need any help.', 'woocommerce' ) );
					}
				}
			}
		}

		if ( WC()->cart->needs_payment() ) {
			$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

			if ( ! isset( $available_gateways[ $data['payment_method'] ] ) ) {
				$errors->add( 'payment', __( 'Invalid payment method.', 'woocommerce' ) );
			} else {
				$available_gateways[ $data['payment_method'] ]->validate_fields();
			}
		}

		do_action( 'woocommerce_after_checkout_validation', $data, $errors );
	}
}
