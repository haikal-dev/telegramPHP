<?php

/**
 * Core Telegram Push/Receive
 * ----------------------------
 * Author: Haikal Azizan
 * 
 * Telegram Bot API Core Docs: https://core.telegram.org/
 * 
 */

class TelegramModel
{
	/**
	 * $url
	 * 
	 * Telegram Bot API must have URL and its token
	 * to access (push/receive)
	 * 
	 */
	public $url = 'https://api.telegram.org/botXXXXXXXXXXXXX';
	
	/**
	 * $users
	 * 
	 * who are users that need bot to send with.
	 * 
	 */
	private $users = [];
	
	public function __construct(){
		
	}
	
	/**
	 * $setChatId
	 * 
	 * by using this method will push array of '$users'
	 * property.
	 * this allow our bot to send multi-user simultaneously.
	 * 
	 */
	public function setChatId($chatId){
		array_push($this->users, $chatId);
	}
	
	/**
	 * $getData
	 * 
	 * this method allow php to received data
	 * sent by telegram user in json format.
	 * 
	 * with this method, php can response back
	 * based on data received to telegram user
	 * through our bot.
	 * 
	 */
	public function getData(){
		$request = file_get_contents('php://input');
		$request = json_decode($request, TRUE);
		
		$data = [
			"message" => $request['message']['text'],
			"userid" => $request['message']['chat']['id'],
			"username" => $request['message']['chat']['username'],
			"fullname" => $request['message']['chat']['first_name'] . " " . $request['message']['chat']['last_name']
		];
		
		return $data;
	}
	
	/**
	 * $setRobot
	 * 
	 * this method allow us to update our full telegram
	 * api url with our bot token.
	 * 
	 */
	public function setRobot($robot_id){
		$this->url = "https://api.telegram.org/bot" . $robot_id;
	}
	
	/**
	 * $showChat
	 * 
	 * For debugging purpose. Better use $getData instead of
	 * $showChat
	 * 
	 */
	public function showChat(){
		$data = $this->url . '/getUpdates';
		$json = json_decode(file_get_contents($data), true);
		$json = $json;
		echo '<pre>';
		print_r($json);
		echo '</pre>';
	}
	
	/**
	 * $sendPhoto
	 * 
	 * Let php to send photo to user
	 * through our bot.
	 * 
	 */
	public function sendPhoto($file_id){
		foreach($this->users as $chatter){
			$data = $this->url . '/sendPhoto?chat_id=' . $chatter . '&photo=' . $file_id;
			$json = json_decode(file_get_contents($data), true);
			return $json;
		}
	}
	
	/**
	 * $send
	 * 
	 * let php to send message to multi/user through
	 * telegram bot
	 * 
	 * Available $method = 'sendMessage'
	 * 
	 */
	public function send($method, $message, $inline = [])
	{
		$encoded = json_encode($inline);
		
		foreach($this->users as $chatter){
			
			$data = [
				"chat_id" => $chatter,
				"text" => $message,
			];
			
			if(!empty($inline) && count($inline) > 0){
				$data['reply_markup'] = $encoded;
			}
			
			$url = $this->url . '/' . $method;

			if (!$curld = curl_init()) {
				exit;
			}
			curl_setopt($curld, CURLOPT_POST, true);
			curl_setopt($curld, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curld, CURLOPT_URL, $url);
			curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
			$output = curl_exec($curld);
			curl_close($curld);
			return $output;
		}
	}
}