<?php
/**
 * Plugin Name: Sell via Telegram for WooCommerce
 * Description: The plugin helps you sell anything via Telegram
 * Author: Victor Polezhaev
 * Author URI: https://t.me/bot11x11
 * Version: 1.9
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
class WC_Telegram{
	private static $instance;
	public static function instance(){
		if(empty(self::$instance)){
			self::$instance = new self;
			self::$instance->init();
		}
		return self::$instance;
	}
	private function init(){
		define('WC_TELEGRAM_DIR', __DIR__);
		define('WC_TELEGRAM_URL', plugin_dir_url(__FILE__));
		add_action('plugins_loaded', array($this, 'plugins_loaded'));
		add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'));
		add_action('wp_ajax_nopriv_wc_telegram', array($this, 'webhook'));
	}
	public function webhook(){
		$integration = WC()->integrations->get_integration('wc_telegram');
		$params = array(
			'server' => $integration->get_option('server'),
			'token' => $integration->get_option('token'),
		);
		$content = file_get_contents("php://input");
		$update = json_decode($content, true);
		do_action('wc_telegram_update', $update, $params);
		$this->set_user_id($update);
		if(isset($update['message']['text'])){
			$chat_id = $params['chat_id'] = $update['message']['chat']['id'];
			$message_id = $params['message_id'] = $update['message']['message_id'];
			$bot = new WC_Telegram_Bot($params);
			$text = $update['message']['text'];
			$bot->deleteMessage($chat_id, $message_id);
			do_action('wc_telegram_message', $text, $bot, $update);
			if(isset($update['message']['reply_to_message']['text'])){
				$checkout_field = $update['message']['reply_to_message']['text'];
				$bot->set_checkout_field($checkout_field, $text);
				return;
			}
			if(preg_match('#' . __('View cart', 'woocommerce') . '#i', $text, $matches)){
				$bot->show_cart();
				return;
			}
			if('/cart' == $text){
				$bot->show_cart();
				return;
			}
			if(preg_match('#' . __('Checkout', 'woocommerce') . '#i', $text, $matches)){
				$bot->show_checkout();
				return;
			}
			if('/checkout' == $text){
				$bot->show_cart();
				return;
			}
			if('/start' == $text){
				$bot->delete_messages();
				$bot->show_categories();
				return;
			}
			if(preg_match('#^/start ([0-9]+)$#', $update['message']['text'], $matches)){
				$product_id = $matches[1];
				$bot->show_product($product_id);
				return;
			}
			if(preg_match('#^/start add-to-cart=(.+)$#', $text, $matches)){
				$bot->add_to_cart($matches[1]);
				return;
			}
			$bot->show_categories();
			return;
		}
		if(isset($update['callback_query']['data'])){
			$callback_query_id = $params['callback_query_id'] = $update['callback_query']['id'];
			$chat_id = $params['chat_id'] = $update['callback_query']['message']['chat']['id'];
			$message_id = $params['message_id'] = $update['callback_query']['message']['message_id'];
			$bot = new WC_Telegram_Bot($params);
			$data = $update['callback_query']['data'];
			do_action('wc_telegram_callback_query', $data, $bot, $update);
			if(preg_match('#^input=(.+)$#', $data, $matches)){
				$value = $matches[1];
				$bot->input_checkout_field($value);
				return;
			}
			if(preg_match('#^ship_to_different_address=([01])$#', $data, $matches)){
				$bot->update_ship_to_different_address($matches[1]);
				return;
			}
			if(preg_match('#^cf\[([^\]]+)\]\[([^\]]+)\]=(.+)$#', $data, $matches)){
				$type = $matches[1];
				$key = $matches[2];
				$value = $matches[3];
				$bot->set_checkout_select_field($type, $key, $value);
				return;
			}
			if(preg_match('#^term_id=([|0-9]+)$#', $data, $matches)){
				$bot->show_categories($matches[1]);
				return;
			}
			if(preg_match('#^product_id=([0-9]+)$#', $data, $matches)){
				$product_id = $matches[1];
				$bot->show_product($product_id);
				return;
			}
			if(preg_match('#^add-to-cart=(.+)$#', $data, $matches)){
				$bot->add_to_cart($matches[1]);
				return;
			}
			if(preg_match('#^shipping-method=(.+)$#', $data, $matches)){
				$bot->update_shipping_method($matches[1]);
				return;
			}
			if(preg_match('#^payment-method=(.+)$#', $data, $matches)){
				$bot->update_payment_method($matches[1]);
				return;
			}
			if(preg_match('#^terms=(.+)$#', $data, $matches)){
				$bot->update_terms($matches[1]);
				return;
			}
			if('process-checkout' == $data){
				$bot->process_checkout();
				return;
			}
			if('checkout' == $data){
				$bot->show_checkout();
				return;
			}
			if('cart' == $data){
				$bot->show_cart();
				return;
			}
			$bot->show_categories();
			return;
		}
	}
	public function plugins_loaded(){
		if(class_exists('WC_Integration')){
			include_once WC_TELEGRAM_DIR . '/includes/class-wc-telegram-api.php';
			include_once WC_TELEGRAM_DIR . '/includes/class-wc-telegram-integration.php';
			include_once WC_TELEGRAM_DIR . '/includes/class-wc-telegram-user.php';
			include_once WC_TELEGRAM_DIR . '/includes/class-wc-telegram-checkout.php';
			include_once WC_TELEGRAM_DIR . '/includes/class-wc-telegram-bot.php';
			add_filter('woocommerce_integrations', array($this, 'woocommerce_integrations'));
		}
	}
	public function woocommerce_integrations($integrations){
		$integrations[] = 'WC_Telegram_Integration';
		return $integrations;
	}
	public function plugin_action_links($links){
		$links = array(
			'<a href="' . admin_url('/admin.php?page=wc-settings&tab=integration&section=wc_telegram') . '">' . __('Settings', 'woocommerce') . '</a>',
		) + $links;
		return $links;
	}
	public function set_user_id($update){
		if(get_current_user_id()){
			return;
		}
		if(isset($update['message'])){
			$message = $update['message'];
		}elseif(isset($update['callback_query'])){
			$message = $update['callback_query'];
		}else{
			return;
		}
		if(!empty($message['from']['is_bot'])){
			return;
		}
		$username = "tg{$message['from']['id']}";
		if(!$user_id = username_exists($username)){
			$password = wp_generate_password();
			$user_id = wp_create_user($username, $password);
			if(is_wp_error($user_id)){
				return 0;
			}
			wp_update_user(array(
				'ID' => $user_id,
				'display_name' => empty($message['from']['username'])?$username:$message['from']['username'],
				'first_name' => empty($message['from']['first_name'])?'':$message['from']['first_name'],
				'last_name' => empty($message['from']['last_name'])?'':$message['from']['last_name'],
				'role' => 'customer',
			));
		}
		wp_set_current_user($user_id);
		WC()->session = null;
		WC()->initialize_session();
		WC()->cart->get_cart_from_session();
		WC()->customer  = new WC_Customer($user_id, true);
	}
}
WC_Telegram::instance();
