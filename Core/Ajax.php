<?php
	
	
	namespace Core;
	
	
	class Ajax
	{
		protected $params = [];
		protected $url = "";
		protected $ch;
		
		public $payload = [];
		public $sslVerify = false;
		public $timeout = 5;
		
		/**
		 * Class constructor
		 *
		 * @param string $url The URL to call
		 * @param array $params http query parameters for the url
		 *
		 * @return void
		 */
		public function __construct($url, $params = [])
		{
			$this->url = trim($url);
			
			//Add the ? at the end of the url if not present
			if(substr($this->url,-1) != "?"){
				$this->url = $this->url."?";
			}
			
			$this->params = $params;
			
			$this->ch = curl_init($this->url . http_build_query($this->params));
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $this->sslVerify);
			curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $this->sslVerify);
			curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json'
				)
			);
		}
		
		public function post(){
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
			
			if(!empty($this->payload)){
				$this->payload = json_encode($this->payload);
				curl_setopt($this->ch, CURLOPT_POST, true);
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->payload );
			}
			
			return $this->response();
		}
		
		public function get(){
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
			return $this->response();
		}
		
		/**
		 *  Response
		 *  Common response for all methods
		 *  @return object
		 */
		private function response(){
			$response = curl_exec($this->ch);
			curl_close($this->ch);
			
			return json_decode($response);
		}
		
	}