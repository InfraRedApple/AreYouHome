<?php
	
	namespace App\Models;
	
	use App\Config;
	use PDO;
	
	/**
	 * LastLogin model
	 *
	 * PHP version 7.0
	 */
	class LastLogin extends \Core\Model
	{
		
		/**
		 * Error messages
		 *
		 * @var array
		 */
		public $errors = [];
		
		/**
		 * Class constructor
		 *
		 * @param array $data Initial property values (optional)
		 *
		 * @return void
		 */
		public function __construct($data = [])
		{
			foreach ($data as $key => $value) {
				$this->$key = $value;
			};
		}
		
		
		/**
		 * Find a last login model by user ID
		 *
		 * @param string $id The user ID
		 *
		 * @return mixed User object if found, false otherwise
		 */
		public static function findByID($id)
		{
			$sql = 'SELECT * FROM last_login WHERE user_id = :id ORDER BY id DESC LIMIT 1';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			
			$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
			
			$stmt->execute();
			
			return $stmt->fetch();
		}
		
		
		/**
		 * Gets all the users login activity
		 *
		 * @param string the id of the  login activity
		 *
		 * @return array  if the data found, false otherwise
		 */
		public static function findAllForId($id)
		{
			$sql = 'SELECT * FROM last_login WHERE user_id = :id ORDER BY last_time DESC LIMIT 10';
			/**
			 * @var $db \PDO
			 */
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public static function getLocation($ip)
		{
			// Initialize CURL:
			$ch = curl_init('http://api.ipstack.com/' . $ip . '?access_key=' . Config::IPSTACK_API_KEY . '');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			// Store the data:
			$json = curl_exec($ch);
			curl_close($ch);
			
			// Decode JSON response:
			$api_result = json_decode($json, true);
			
			// Output the "capital" object inside "location"
			return $api_result['city'] . ", " . $api_result['region_code'] . " " . $api_result['country_code'];
			
		}
		
		/**
		 * Save the login activity model with the current property values
		 *
		 * @return boolean  True if the user was saved, false otherwise
		 */
		public function save()
		{
			
			$sql = 'INSERT INTO last_login (user_id, browser,location,ip_address)
                    		VALUES (:user_id, :browser, :location,  :ip_address)';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_STR);
			$stmt->bindValue(':browser', $this->browser, PDO::PARAM_STR);
			$stmt->bindValue(':location', $this->location, PDO::PARAM_STR);
			$stmt->bindValue(':ip_address', $this->ip_address, PDO::PARAM_STR);
			
			return $stmt->execute();
			
		}
		
		
	}
