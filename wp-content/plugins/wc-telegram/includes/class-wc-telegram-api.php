<?php
class WC_Telegram_API{
	private $server;
	public $token;
	function __construct($params){
		if(isset($params['server'])){
			$this->server = $params['server'];
		}
		if(isset($params['token'])){
			$this->token = $params['token'];
		}
	}
	public function telegram($method, $data = null){
		do_action('wc_telegram_api_request', $method, $data);
		$url = "https://{$this->server}/bot{$this->token}/$method";
		$response = wp_remote_post($url, array(
			'timeout' => 60,
			'headers' => array(
				'Content-Type' => 'application/json',
			),
			'body' => json_encode($data),
		));
		$response = wp_remote_retrieve_body($response);
		$response = json_decode($response, true);
		do_action('wc_telegram_api_response', $response);
		return $response;
	}
	public function setWebhook($url){
		$response = $this->telegram('setWebhook', array(
			'url' => $url,
		));
		return $response;
	}
	public function answerCallbackQuery($callbackQueryId, $text, $show_alert = false){
		$text = trim(strip_tags($text));
		$text = html_entity_decode($text);
		if(strlen($text) > 350){
			$text = substr($text, 0, 350) . '...';
		}
		$response = $this->telegram('answerCallbackQuery', array(
			'callback_query_id' => $callbackQueryId,
			'text' => $text,
			'show_alert' => $show_alert,
		));
		return $response;
	}
	public function deleteMessage($chat_id, $message_id){
		$response = $this->telegram('deleteMessage', array(
			'chat_id' => $chat_id,
			'message_id' => $message_id,
		));
		return $response;
	}
	public function sendMessage($chat_id, $text, $reply_markup = false){
		$params = array(
			'chat_id' => $chat_id,
			'text' => $text,
			'parse_mode' => 'HTML',
			'disable_web_page_preview' => true,
			'disable_notification' => true,
		);
		if($reply_markup){
			$params['reply_markup'] = $reply_markup;
		}
		$response = $this->telegram('sendMessage', $params);
		return $response;
	}
	public function sendPhoto($chat_id, $caption, $photo, $reply_markup = false){
		$params = array(
			'chat_id' => $chat_id,
			'caption' => $caption,
			'photo' => $photo,
			'parse_mode' => 'HTML',
			'disable_notification' => true,
		);
		if($reply_markup){
			$params['reply_markup'] = $reply_markup;
		}
		$response = $this->telegram('sendPhoto', $params);
		return $response;
	}
	public function editMessageText($chat_id, $message_id, $text, $reply_markup = false){
		$params = array(
			'chat_id' => $chat_id,
			'message_id' => $message_id,
			'text' => $text,
			'parse_mode' => 'HTML',
			'disable_web_page_preview' => true,
		);
		if($reply_markup){
			$params['reply_markup'] = $reply_markup;
		}
		$response = $this->telegram('editMessageText', $params);
		return $response;
	}
	public function editMessageCaption($chat_id, $message_id, $caption, $reply_markup = false){
		$params = array(
			'chat_id' => $chat_id,
			'message_id' => $message_id,
			'caption' => $caption,
			'parse_mode' => 'HTML',
			'disable_web_page_preview' => true,
		);
		if($reply_markup){
			$params['reply_markup'] = $reply_markup;
		}
		$response = $this->telegram('editMessageCaption', $params);
		return $response;
	}
	public function editMessageMedia($chat_id, $message_id, $caption, $photo, $reply_markup = false){
		$params = array(
			'chat_id' => $chat_id,
			'message_id' => $message_id,
			'media' => array(
				'type' => 'photo',
				'media' => $photo,
				'caption' => $caption,
				'parse_mode' => 'HTML',
			),
		);
		if($reply_markup){
			$params['reply_markup'] = $reply_markup;
		}
		$response = $this->telegram('editMessageMedia', $params);
		return $response;
	}
	public function editMessageReplyMarkup($chat_id, $message_id, $reply_markup = false){
		$params = array(
			'chat_id' => $chat_id,
			'message_id' => $message_id,
		);
		if($reply_markup){
			$params['reply_markup'] = $reply_markup;
		}
		$response = $this->telegram('editMessageReplyMarkup', $params);
		return $response;
	}
	public function sanitize_text($text){
		$text = html_entity_decode($text);
		$text = strip_tags($text, '<a><i><em><code><pre><b><strong>');
		$text = trim($text);
		$text = preg_replace('#^(.{1023}).+$#us', '$1…', $text);
		return $text;
	}
	public function sanitize($entry){
		if(is_array($entry)){
			foreach($entry as $key => $value){
				$entry[$key] = $this->sanitize($value);
			}
		}elseif(is_string($entry)){
			$entry = trim(strip_tags(html_entity_decode($entry)));
		}
		return $entry;
	}
}
