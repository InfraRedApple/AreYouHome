<?php
	
	namespace App\Models;
	
	use Core\Model;
	use PDO;
	
	/**
	 * Logs model
	 *
	 * PHP version 7.0
	 */
	class Logs extends Model
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
		 * Find a model by ID
		 *
		 * @param string $id The model ID
		 *
		 * @return mixed model object if found, false otherwise
		 */
		public static function findByID($id)
		{
			$sql = 'SELECT * FROM logs WHERE id = :id ORDER BY id DESC LIMIT 1';
			
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
		public static function findAllForClientId($id)
		{
			$sql = 'SELECT * FROM logs WHERE clients_id = :id ORDER BY last_seen DESC';
			/**
			 * @var $db \PDO
			 */
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		public static function mostRecent($ip)
		{
			$sql = 'SELECT DISTINCT clients_id FROM logs ORDER BY last_seen DESC';
			/**
			 * @var $db \PDO
			 */
			$db = static::getDB();
			$stmt = $db->prepare($sql);

			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Save the login activity model with the current property values
		 *
		 * @return boolean  True if the user was saved, false otherwise
		 */
		public function save()
		{
			
			$sql = 'INSERT INTO logs (clients_id)
                    		VALUES (:clients_id)';
			
			/**
			 * @var $db \PDO
			 */
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':clients_id', $this->clients_id, PDO::PARAM_STR);
			
			return $stmt->execute();
			
		}
		
		
	}
