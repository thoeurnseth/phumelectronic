<?php
class WC_Telegram_User extends WC_Telegram_API{
	public $chat_id;
	public $message_id;
	public $callback_query_id;
	function __construct($params){
		parent::__construct($params);
		if(isset($params['chat_id'])){
			$this->chat_id = $params['chat_id'];
		}
		if(isset($params['message_id'])){
			$this->message_id = $params['message_id'];
		}
		if(isset($params['callback_query_id'])){
			$this->callback_query_id = $params['callback_query_id'];
		}
	}
	private function remember_message($response, $stored_message = false, $photo = false){
		$message = false;
		if(!empty($response['ok']) && !empty($response['result'])){
			$message = $response['result'];
		}elseif(!empty($response['message'])){
			$message = $response['message'];
		}
		if($message){
			$user_id = get_current_user_id();
			$message = array(
				'chat_id' => $message['chat']['id'],
				'message_id' => $message['message_id'],
				'date' => $message['date'],
				'text' => !empty($message['text'])?$message['text']:(!empty($message['caption'])?$message['caption']:''),
				'photo' => $photo,
				'reply_markup' => isset($message['reply_markup'])?$message['reply_markup']:false,
			);
			if($stored_message){
				return update_user_meta($user_id, 'wc_telegram_message', $message, $stored_message);
			}else{
				return add_user_meta($user_id, 'wc_telegram_message', $message);
			}
		}
	}
	private function forget_message(){
		$user_id = get_current_user_id();
		if($messages = get_user_meta($user_id, 'wc_telegram_message')){
			foreach($messages as $message){
				if($message['chat_id'] == $this->chat_id
				&& $message['message_id'] == $this->message_id){
					delete_user_meta($user_id, 'wc_telegram_message', $message);
				}
			}
		}
	}
	public function add_message($text, $image = false, $reply_markup = false){
		$text = $this->sanitize_text($text);
		$reply_markup = $this->sanitize($reply_markup);
		if($image){
			$response = $this->sendPhoto($this->chat_id, $text, $image, $reply_markup);
		}else{
			$response = $this->sendMessage($this->chat_id, $text, $reply_markup);
		}
		$this->remember_message($response, false, $image);
		return $response;
	}
	public function update_message($text, $image = false, $reply_markup = false){
		$user_id = get_current_user_id();
		$text = $this->sanitize_text($text);
		$reply_markup = $this->sanitize($reply_markup);
		$updated = false;
		if($messages = get_user_meta($user_id, 'wc_telegram_message')){
			foreach($messages as $message){
				if($message['chat_id'] == $this->chat_id
				&& ($message['message_id'] == $this->message_id)){
					if(!empty($message['photo']) && $image){
						if(strip_tags($message['text']) == strip_tags($text)
						&& $message['reply_markup'] == $reply_markup
						&& $message['photo'] == $image){
							$updated = true;
						}else{
							if($message['photo'] != $image){
								$response = $this->editMessageMedia($message['chat_id'], $message['message_id'], $text, $image, $reply_markup);
								if(!empty($response['ok'])){
									$updated = true;
								}
							}
							if(strip_tags($message['text']) != strip_tags($text)
							|| $message['reply_markup'] != $reply_markup){
								$response = $this->editMessageCaption($message['chat_id'], $message['message_id'], $text, $reply_markup);
								if(!empty($response['ok'])){
									delete_user_meta($user_id, 'wc_telegram_message', $message);
									$this->remember_message($response, false, $image);
									$updated = true;
								}
							}
						}
					}elseif(empty($message['photo']) && !$image){
						if(strip_tags($message['text']) == strip_tags($text)
						&& $message['reply_markup'] == $reply_markup){
							$updated = true;
						}else{
							$response = $this->editMessageText($message['chat_id'], $message['message_id'], $text, $reply_markup);
							if(!empty($response['ok'])){
								delete_user_meta($user_id, 'wc_telegram_message', $message);
								$this->remember_message($response, false, $image);
								$updated = true;
							}
						}
					}else{
						$this->delete_message($message);
						$this->add_message($text, $image, $reply_markup);
						$updated = true;
					}
				}
			}
		}
		return $updated;
	}
	public function delete_message($message = false){
		$user_id = get_current_user_id();
		if($message){
			$response = $this->deleteMessage($message['chat_id'], $message['message_id']);
			if(empty($response['ok'])){
				$text = '-';
				if(!empty($message['photo'])){
					$this->editMessageCaption($message['chat_id'], $message['message_id'], $text);
					$image = WC_TELEGRAM_URL . 'img/1x1.gif';
					$this->editMessageMedia($message['chat_id'], $message['message_id'], $text, $image);
				}else{
					$this->editMessageText($message['chat_id'], $message['message_id'], $text);
				}
			}
			delete_user_meta($user_id, 'wc_telegram_message', $message);
			return;
		}
		$this->deleteMessage($this->chat_id, $this->message_id);
		$this->forget_message();
	}
	public function delete_messages($date = false){
		$user_id = get_current_user_id();
		if($messages = get_user_meta($user_id, 'wc_telegram_message')){
			foreach($messages as $message){
				if($message['chat_id'] == $this->chat_id
				&& (!$date || $message['date'] < $date)){
					$this->delete_message($message);
				}
			}
		}
	}
	public function add_messages($messages){
		$date = time() - DAY_IN_SECONDS * .5;
		$user_id = get_current_user_id();
		$stored_messages = get_user_meta($user_id, 'wc_telegram_message');
		foreach($messages as $message){
			$added = false;
			while($stored_message = array_shift($stored_messages)){
				if($stored_message['date'] < $date){
					$this->delete_message($stored_message);
					continue;
				}
				if(empty($stored_message['photo']) && $message['image']
				|| !empty($stored_message['photo']) && !$message['image']){
					$this->delete_message($stored_message);
					continue;
				}
				if($message['image'] != $stored_message['photo']){
					if(isset($message['reply_markup']['keyboard'])
					|| isset($stored_messages['reply_markup']['keyboard'])){
						$this->delete_message($stored_message);
						continue;
					}
					$response = $this->editMessageMedia($stored_message['chat_id'], $stored_message['message_id'], $this->sanitize_text($message['text']), $message['image'], $this->sanitize($message['reply_markup']));
					if(empty($response['ok'])){
						$this->delete_message($stored_message);
						continue;
					}
					$this->remember_message($response, $stored_message, $message['image']);
				}elseif($this->sanitize($message['text']) != $this->sanitize($stored_message['text'])
				|| $this->sanitize($message['reply_markup']) != $this->sanitize($stored_message['reply_markup'])
				&& (!isset($message['reply_markup']['keyboard']) || $stored_message['reply_markup'])){
					if(isset($message['reply_markup']['keyboard'])){
						$this->delete_message($stored_message);
						continue;
					}
					if(empty($message['image'])){
						$response = $this->editMessageText($stored_message['chat_id'], $stored_message['message_id'], $this->sanitize_text($message['text']), $this->sanitize($message['reply_markup']));
						if(empty($response['ok'])){
							$this->delete_message($stored_message);
							continue;
						}
						$this->remember_message($response, $stored_message, $message['image']);
					}else{
						$response = $this->editMessageCaption($stored_message['chat_id'], $stored_message['message_id'], $this->sanitize_text($message['text']), $this->sanitize($message['reply_markup']));
						if(empty($response['ok'])){
							$this->delete_message($stored_message);
							continue;
						}
						$this->remember_message($response, $stored_message, $message['image']);
					}
				}
				$added = true;
				break;
			}
			if(!$added){
				$this->add_message($message['text'], $message['image'], $message['reply_markup']);
			}
		}
		while($stored_message = array_shift($stored_messages)){
			$this->delete_message($stored_message);
		}
	}
}
