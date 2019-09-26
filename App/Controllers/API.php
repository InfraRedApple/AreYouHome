<?php
	
	use App\Models\Ticket;
	use Core\Controller;
	
	
	/**
	 * Api controller
	 *
	 * PHP version 7.0
	 */
	/**
	 * External API access for uploading reports
	 */
	class Api  extends Controller
	{
		private $user;
		private $postData;
		
		public function ticketAction(){
			$this->requirePost();
//		$this->authenticate();
//		{'email': 'micahcase77@gmail.com', 'order': '9', 'count': '1 '}
			$json = json_decode(json_encode($this->postData), true);
			
			if(!isset($json['count'])){
				echo json_encode(array("Error"=>"No count information."));
				exit;
			}
			
			if(!isset($json['email'])){
				echo json_encode(array("Error"=>"No Email information."));
				exit;
			}
			if(!isset($json['order'])){
				echo json_encode(array("Error"=>"No order information."));
				exit;
			}
			
			$ticket = new Ticket($json);
			$ticket->save();
			
			echo json_encode(array("Success"=>true));
			exit;
			
		}
		
		public function echoAction(){
			$this->requirePost();
			$this->authenticate();
			
			$json = json_decode(json_encode($this->postData), true);
			
			echo json_encode($json);
			
		}
		
		private function requirePost(){
			header("Access-Control-Allow-Methods: POST");
			
			if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
				http_response_code(405);
				echo json_encode(array("Error" => "Incorrect method: " .$_SERVER['REQUEST_METHOD']));
				exit;
			}
			
			// get posted data
			$this->postData = json_decode(file_get_contents("php://input"));
			
			if (is_null($this->postData)){
				http_response_code(400);
				echo json_encode(array("Error" => "No data provided."));
				exit;
			}
			
		}
		
		private function authenticate(){
			
			//Needs to be converted to Digest, if SSL is not used.
			//Until that time, just using this for testing
			$headers = apache_request_headers();
			
			if(!isset($headers['Authorization'])) {
				http_response_code(401);
				echo json_encode(array("Error" => "No Authorization Headers Found"));
				exit;
			}
			
			$email = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
			
			if(!isset($email) || !isset($password)) {
				http_response_code(401);
				echo json_encode(array("Error" => "Email and Password required."));
				exit;
			}
			
			$this->user = User::authenticate(htmlspecialchars($email), htmlspecialchars($password));
			
			if ($this->user) {
				
				if ($this->user->is_active) {
					session_regenerate_id(true);
					
					$_SESSION['user_id'] = $this->user->id;
					
					$last_login = new LastLogin();
					$last_login->user_id = $this->user->id;
					$last_login->browser = $_SERVER['HTTP_USER_AGENT'] ?? "Unknown or Masked";
					$last_login->ip_address = Utilities::get_client_ip();
					$last_login->location = "";
					$last_login->save();
					
					
				} else {
					http_response_code(401);
					echo json_encode(array("Error" => "User requires activation."));
					exit;
					
				}
				
			} else {
				// BAD USERNAME OR PASSWORD
				http_response_code(401);
				echo json_encode(array("Error" => "Invalid Email or Password."));
				exit;
			}
		}
		
		/**
		 * Before filter
		 *
		 * @return void
		 */
		protected function before()
		{
			parent::before();
			//convenience  property for the the class name
			$this->name = substr(strrchr(__CLASS__, "\\"), 1);
			
			// required headers
			header('Access-Control-Allow-Origin: http://'.$_SERVER["HTTP_HOST"].'/api/');
			header("Content-Type: application/json; charset=UTF-8");
			header("Access-Control-Max-Age: 3600");
			header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
			
		}
		
		
	}