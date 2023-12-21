<?php


/**
 * REST API authentication class.
 */
class Biz_authentication {

	/**
	 * Authentication error.
	 *
	 * @var WP_Error
	 */
	protected $error = null;

	/**
	 * Logged in user data.
	 *
	 * @var stdClass
	 */
	protected $user = null;

	/**
	 * Current auth method.
	 *
	 * @var string
	 */
	protected $auth_method = '';

	/**
	 * Initialize authentication actions.
	 */
	public function __construct() {
		add_filter( 'determine_current_user', array( $this, 'authenticate' ), 15 );
		add_filter( 'rest_authentication_errors', array( $this, 'authentication_fallback' ) );
		add_filter( 'rest_authentication_errors', array( $this, 'check_authentication_error' ), 15 );
		add_filter( 'rest_post_dispatch', array( $this, 'send_unauthorized_headers' ), 50 );
		add_filter( 'rest_pre_dispatch', array( $this, 'check_user_permissions' ), 10, 3 );
	}

    	/**
	 * Check if is request to our REST API.
	 *
	 * @return bool
	 */
	protected function is_request_to_rest_api() {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix = trailingslashit( rest_get_url_prefix() );
		$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );

		// Check if the request is to the WC API endpoints.
		$woocommerce = ( false !== strpos( $request_uri, $rest_prefix . 'biz-plasgate/' ) );

		// Allow third party plugins use our authentication methods.
		$third_party = ( false !== strpos( $request_uri, $rest_prefix . 'biz-plasgate-' ) );

		return apply_filters( 'woocommerce_rest_is_request_to_rest_api', $woocommerce || $third_party );
	}

	/**
	 * Authenticate user.
	 *
	 * @param int|false $user_id User ID if one has been determined, false otherwise.
	 * @return int|false
	 */
	public function authenticate( $user_id ) {
        		// Do not authenticate twice and check if is a request to our endpoint in the WP REST API.

        if ( ! empty( $user_id ) || ! $this->is_request_to_rest_api() ) {
			return $user_id;
		}

		if ( is_ssl() ) {
			$user_id = $this->perform_basic_authentication();
		}

		if ( $user_id ) {
			return $user_id;
		}

		return $this->perform_basic_authentication();
	}

	/**
	 * Authenticate the user if authentication wasn't performed during the
	 * determine_current_user action.
	 *
	 * Necessary in cases where wp_get_current_user() is called before WooCommerce is loaded.
	 *
	 * @see https://github.com/woocommerce/woocommerce/issues/26847
	 *
	 * @param WP_Error|null|bool $error Error data.
	 * @return WP_Error|null|bool
	 */
	public function authentication_fallback( $error ) {
		if ( ! empty( $error ) ) {
			// Another plugin has already declared a failure.
			return $error;
		}
		if ( empty( $this->error ) && empty( $this->auth_method ) && empty( $this->user ) && 0 === get_current_user_id() ) {
			// Authentication hasn't occurred during `determine_current_user`, so check auth.
			$user_id = $this->authenticate( false );
			if ( $user_id ) {
				wp_set_current_user( $user_id );
				return true;
			}
		}
		return $error;
	}

	/**
	 * Check for authentication error.
	 *
	 * @param WP_Error|null|bool $error Error data.
	 * @return WP_Error|null|bool
	 */
	public function check_authentication_error( $error ) {
		// Pass through other errors.
		if ( ! empty( $error ) ) {
			return $error;
		}

		return $this->get_error();
	}

	/**
	 * Set authentication error.
	 *
	 * @param WP_Error $error Authentication error data.
	 */
	protected function set_error( $error ) {
		// Reset user.
		$this->user = null;

		$this->error = $error;
	}

	/**
	 * Get authentication error.
	 *
	 * @return WP_Error|null.
	 */
	protected function get_error() {
		return $this->error;
	}

	/**
	 * Basic Authentication.
	 *
	 * SSL-encrypted requests are not subject to sniffing or man-in-the-middle
	 * attacks, so the request can be authenticated by simply looking up the user
	 * associated with the given consumer key and confirming the consumer secret
	 * provided is valid.
	 *
	 * @return int|bool
	 */
	private function perform_basic_authentication() {
		$this->auth_method = 'basic_auth';
		$consumer_key      = '';
		$consumer_secret   = '';

		// If the $_GET parameters are present, use those first.
		if ( ! empty( $_GET['consumer_key'] ) && ! empty( $_GET['consumer_secret'] ) ) { // WPCS: CSRF ok.
			$consumer_key    = $_GET['consumer_key']; // WPCS: CSRF ok, sanitization ok.
			$consumer_secret = $_GET['consumer_secret']; // WPCS: CSRF ok, sanitization ok.
		}

		// If the above is not present, we will do full basic auth.
		if ( ! $consumer_key && ! empty( $_SERVER['PHP_AUTH_USER'] ) && ! empty( $_SERVER['PHP_AUTH_PW'] ) ) {
			$consumer_key    = $_SERVER['PHP_AUTH_USER']; // WPCS: CSRF ok, sanitization ok.
			$consumer_secret = $_SERVER['PHP_AUTH_PW']; // WPCS: CSRF ok, sanitization ok.
		}

		// Stop if don't have any key.
		if ( ! $consumer_key || ! $consumer_secret ) {
			return false;
		}

		// Get user data.
		$this->user = $this->get_user_data_by_consumer_key( $consumer_key );
		if ( empty( $this->user ) ) {
			return false;
		}

		// Validate user secret.
		if ( ! hash_equals( $this->user->consumer_secret, $consumer_secret ) ) { // @codingStandardsIgnoreLine
			$this->set_error( new WP_Error( 'revo_rest_authentication_error', __( 'Consumer secret is invalid.', 'woocommerce' ), array( 'status' => 401 ) ) );

			return false;
		}

		return $this->user->user_id;
	}

	/**
	 * Parse the Authorization header into parameters.
	 *
	 * @since 3.0.0
	 *
	 * @param string $header Authorization header value (not including "Authorization: " prefix).
	 *
	 * @return array Map of parameter values.
	 */
	public function parse_header( $header ) {
		if ( 'OAuth ' !== substr( $header, 0, 6 ) ) {
			return array();
		}

		// From OAuth PHP library, used under MIT license.
		$params = array();
		if ( preg_match_all( '/(oauth_[a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $header, $matches ) ) {
			foreach ( $matches[1] as $i => $h ) {
				$params[ $h ] = urldecode( empty( $matches[3][ $i ] ) ? $matches[4][ $i ] : $matches[3][ $i ] );
			}
			if ( isset( $params['realm'] ) ) {
				unset( $params['realm'] );
			}
		}

		return $params;
	}

	/**
	 * Get the authorization header.
	 *
	 * On certain systems and configurations, the Authorization header will be
	 * stripped out by the server or PHP. Typically this is then used to
	 * generate `PHP_AUTH_USER`/`PHP_AUTH_PASS` but not passed on. We use
	 * `getallheaders` here to try and grab it out instead.
	 *
	 * @since 3.0.0
	 *
	 * @return string Authorization header if set.
	 */
	public function get_authorization_header() {
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



	/**
	 * Creates an array of urlencoded strings out of each array key/value pairs.
	 *
	 * @param  array  $params       Array of parameters to convert.
	 * @param  array  $query_params Array to extend.
	 * @param  string $key          Optional Array key to append.
	 * @return string               Array of urlencoded strings.
	 */
	private function join_with_equals_sign( $params, $query_params = array(), $key = '' ) {
		foreach ( $params as $param_key => $param_value ) {
			if ( $key ) {
				$param_key = $key . '%5B' . $param_key . '%5D'; // Handle multi-dimensional array.
			}

			if ( is_array( $param_value ) ) {
				$query_params = $this->join_with_equals_sign( $param_value, $query_params, $param_key );
			} else {
				$string         = $param_key . '=' . $param_value; // Join with equals sign.
				$query_params[] = $this->wc_rest_urlencode_rfc3986( $string );
			}
		}

		return $query_params;
	}

	/**
	 * Normalize each parameter by assuming each parameter may have already been
	 * encoded, so attempt to decode, and then re-encode according to RFC 3986.
	 *
	 * Note both the key and value is normalized so a filter param like:
	 *
	 * 'filter[period]' => 'week'
	 *
	 * is encoded to:
	 *
	 * 'filter%255Bperiod%255D' => 'week'
	 *
	 * This conforms to the OAuth 1.0a spec which indicates the entire query string
	 * should be URL encoded.
	 *
	 * @see rawurlencode()
	 * @param array $parameters Un-normalized parameters.
	 * @return array Normalized parameters.
	 */
	private function normalize_parameters( $parameters ) {
		$keys       = $this->wc_rest_urlencode_rfc3986( array_keys( $parameters ) );
		$values     = $this->wc_rest_urlencode_rfc3986( array_values( $parameters ) );
		$parameters = array_combine( $keys, $values );

		return $parameters;
	}

	private function wc_rest_urlencode_rfc3986( $value ) {
		if ( is_array( $value ) ) {
			return array_map( 'wc_rest_urlencode_rfc3986', $value );
		}

		return str_replace( array( '+', '%7E' ), array( ' ', '~' ), rawurlencode( $value ) );
	}

	/**
	 * Verify that the timestamp and nonce provided with the request are valid. This prevents replay attacks where
	 * an attacker could attempt to re-send an intercepted request at a later time.
	 *
	 * - A timestamp is valid if it is within 15 minutes of now.
	 * - A nonce is valid if it has not been used within the last 15 minutes.
	 *
	 * @param stdClass $user      User data.
	 * @param int      $timestamp The unix timestamp for when the request was made.
	 * @param string   $nonce     A unique (for the given user) 32 alphanumeric string, consumer-generated.
	 * @return bool|WP_Error
	 */
	private function check_oauth_timestamp_and_nonce( $user, $timestamp, $nonce ) {
		global $wpdb;

		$valid_window = 15 * 60; // 15 minute window.

		if ( ( $timestamp < time() - $valid_window ) || ( $timestamp > time() + $valid_window ) ) {
			return new WP_Error( 'revo_rest_authentication_error', __( 'Invalid timestamp.', 'woocommerce' ), array( 'status' => 401 ) );
		}

		$used_nonces = maybe_unserialize( $user->nonces );

		if ( empty( $used_nonces ) ) {
			$used_nonces = array();
		}

		if ( in_array( $nonce, $used_nonces, true ) ) {
			return new WP_Error( 'revo_rest_authentication_error', __( 'Invalid nonce - nonce has already been used.', 'woocommerce' ), array( 'status' => 401 ) );
		}

		$used_nonces[ $timestamp ] = $nonce;

		// Remove expired nonces.
		foreach ( $used_nonces as $nonce_timestamp => $nonce ) {
			if ( $nonce_timestamp < ( time() - $valid_window ) ) {
				unset( $used_nonces[ $nonce_timestamp ] );
			}
		}

		$used_nonces = maybe_serialize( $used_nonces );

		$wpdb->update(
			$wpdb->prefix . 'woocommerce_api_keys',
			array( 'nonces' => $used_nonces ),
			array( 'key_id' => $user->key_id ),
			array( '%s' ),
			array( '%d' )
		);

		return true;
	}

	/**
	 * Return the user data for the given consumer_key.
	 *
	 * @param string $consumer_key Consumer key.
	 * @return array
	 */
	private function get_user_data_by_consumer_key( $consumer_key ) {
		global $wpdb;

		$consumer_key = wc_api_hash( sanitize_text_field( $consumer_key ) );
		$user         = $wpdb->get_row(
			$wpdb->prepare(
				"
			SELECT key_id, user_id, permissions, consumer_key, consumer_secret, nonces
			FROM {$wpdb->prefix}woocommerce_api_keys
			WHERE consumer_key = %s
		",
				$consumer_key
			)
		);

		return $user;
	}

	/**
	 * Check that the API keys provided have the proper key-specific permissions to either read or write API resources.
	 *
	 * @param string $method Request method.
	 * @return bool|WP_Error
	 */
	private function check_permissions( $method ) {
		$permissions = $this->user->permissions;

		switch ( $method ) {
			case 'HEAD':
			case 'GET':
				if ( 'read' !== $permissions && 'read_write' !== $permissions ) {
					return new WP_Error( 'revo_rest_authentication_error', __( 'The API key provided does not have read permissions.', 'woocommerce' ), array( 'status' => 401 ) );
				}
				break;
			case 'POST':
			case 'PUT':
			case 'PATCH':
			case 'DELETE':
				if ( 'write' !== $permissions && 'read_write' !== $permissions ) {
					return new WP_Error( 'revo_rest_authentication_error', __( 'The API key provided does not have write permissions.', 'woocommerce' ), array( 'status' => 401 ) );
				}
				break;
			case 'OPTIONS':
				return true;

			default:
				return new WP_Error( 'revo_rest_authentication_error', __( 'Unknown request method.', 'woocommerce' ), array( 'status' => 401 ) );
		}

		return true;
	}

	/**
	 * Updated API Key last access datetime.
	 */
	private function update_last_access() {
		global $wpdb;

		$wpdb->update(
			$wpdb->prefix . 'woocommerce_api_keys',
			array( 'last_access' => current_time( 'mysql' ) ),
			array( 'key_id' => $this->user->key_id ),
			array( '%s' ),
			array( '%d' )
		);
	}

	/**
	 * If the consumer_key and consumer_secret $_GET parameters are NOT provided
	 * and the Basic auth headers are either not present or the consumer secret does not match the consumer
	 * key provided, then return the correct Basic headers and an error message.
	 *
	 * @param WP_REST_Response $response Current response being served.
	 * @return WP_REST_Response
	 */
	public function send_unauthorized_headers( $response ) {
		if ( is_wp_error( $this->get_error() ) && 'basic_auth' === $this->auth_method ) {
			$auth_message = __( 'WooCommerce API. Use a consumer key in the username field and a consumer secret in the password field.', 'woocommerce' );
			$response->header( 'WWW-Authenticate', 'Basic realm="' . $auth_message . '"', true );
		}

		return $response;
	}

	/**
	 * Check for user permissions and register last access.
	 *
	 * @param mixed           $result  Response to replace the requested version with.
	 * @param WP_REST_Server  $server  Server instance.
	 * @param WP_REST_Request $request Request used to generate the response.
	 * @return mixed
	 */
	public function check_user_permissions( $result, $server, $request ) {
		if ( $this->user ) {
			// Check API Key permissions.
			$allowed = $this->check_permissions( $request->get_method() );
			if ( is_wp_error( $allowed ) ) {
				return $allowed;
			}

			// Register last access.
			$this->update_last_access();
		}

		return $result;
	}
}

new Biz_authentication();
