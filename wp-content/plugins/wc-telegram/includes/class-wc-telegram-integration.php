<?php
if(!defined('ABSPATH')){
	exit;
}
class WC_Telegram_Integration extends WC_Integration{
	private $token;
	private $server;
	private $default_limit = 20;
	private $default_cols = 2;
	public function __construct(){
		$this->id = 'wc_telegram';
		$this->method_title = 'Sell via Telegram';
		$this->method_description = apply_filters(
			'wc_telegram_integration_method_description',
			'The plugin helps you sell anything via Telegram.' .
			'<br><a href="https://t.me/bot11x11" target="_blank">Get</a> help.' .
			'<!-- wc-telegram-variations -->' .
			'<br><a href="https://t.me/bot11x11" target="_blank">Get</a> <b>wc-telegram-variations</b> to sell variable products.' .
			'<!-- /wc-telegram-variations -->' .
			'<!-- wc-telegram-external -->' .
			'<br><a href="https://t.me/bot11x11" target="_blank">Get</a> <b>wc-telegram-external</b> to sell external products.' .
			'<!-- /wc-telegram-external -->'
		);
		$this->init_form_fields();
		$this->init_settings();
		$this->token = $this->get_option('token');
		$this->server = $this->get_option('server');
		add_action('woocommerce_update_options_integration_wc_telegram', array($this, 'woocommerce_update_options_integration'));
		add_filter('wc_telegram_limit', array($this, 'wc_telegram_limit'));
		add_filter('wc_telegram_cols', array($this, 'wc_telegram_cols'), 10, 3);
	}
	private function get_limit(){
		if(!$limit = $this->get_option('limit')){
			$limit = $this->default_limit;
		}
		return $limit;
	}
	private function get_cols(){
		if(!$cols = $this->get_option('cols')){
			$cols = $this->default_cols;
		}
		return $cols;
	}
	public function wc_telegram_limit($limit){
		$limit = $this->get_limit();
		return $limit;
	}
	public function wc_telegram_cols($cols, $count, $limit){
		$cols = $this->get_cols();
		if($cols == 1){
			return 1;
		}
		if($count > $limit / 2){
			return 2;
		}
		return 1;
	}
	public function init_form_fields(){
		if(parse_url(admin_url(), PHP_URL_SCHEME) != 'https'){
			WC_Admin_Settings::add_error(esc_html('Migrate your site to HTTPS. All queries to the Telegram Bot API must be served over HTTPS.'));
		}
		$form_fields = array(
			'token' => array(
				'title' => 'Telegram Bot Token',
				'type' => 'text',
				'placeholder' => '984630957:AAE6q2xmIgDY46gr7uFuiZbXqAAR8U1RGts',
				'description' => <<<END
<ol>
	<li><em>Search for <strong>@BotFather</strong> in Telegram</em></li>
	<li><em>Create a new bot by sending <strong>/newbot</strong> command</em></li>
	<li><em>Get its token</em></li>
</ol>
END
,
			),
			'server' => array(
				'title' => 'API Server Telegram',
				'type' => 'select',
				'options' => array(
					'api.telegram.org' => 'api.telegram.org',
					'api.bot11x11.ru' => 'api.bot11x11.ru',
				),
				'description' => <<<END
If your site is located in the country where <strong>api.telegram.org</strong> is blocked use alternative server.
END
,
			),
			'limit' => array(
				'title' => 'Products per page',
				'type' => 'number',
				'default' => $this->default_limit,
				'custom_attributes' => array(
					'min' => 1,
					'max' => 40,
				),
			),
			'cols' => array(
				'title' => 'Columns',
				'type' => 'select',
				'options' => array(
					'1' => 'always one',
					'2' => 'one or two',
				),
				'default' => $this->default_cols,
			),
		);
		$this->form_fields = apply_filters('wc_telegram_settings_form_fields', $form_fields);
	}
	public function woocommerce_update_options_integration(){
		parent::process_admin_options();
		$this->init_settings();
		$this->token = $this->get_option('token');
		$this->server = $this->get_option('server');
		$telegram = new WC_Telegram_API(array(
			'server' => $this->server,
			'token' => $this->token,
		));
		$json = $telegram->setWebhook(admin_url("admin-ajax.php?action=wc_telegram"));
		if(!is_array($json)){
			$error = "Unexpected response while setting the webhook: your site might have no access to {$this->server}";
		}elseif(empty($json['ok'])){
			if(empty($json['description'])){
				$error = 'Unexpected response while setting the webhook: a wrong json';
			}else{
				$error = $json['description'];
			}
		}else{
			$error = false;
		}
		if($error){
			WC_Admin_Settings::add_error(esc_html($error));
		}else{
			WC_Admin_Settings::add_message(esc_html($json['description']));
		}
	}
}
