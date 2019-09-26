<?php
	
	namespace App\Models;
	
	use Core\Model;
	use PDO;
	
	/**
	 * Clients model
	 *
	 * PHP version 7.0
	 */
	class Clients extends Model
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
		public static function find($id)
		{
			$sql = 'SELECT * FROM clients WHERE id = :id ORDER BY id DESC LIMIT 1';
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $id, PDO::PARAM_INT);
			
			$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
			
			$stmt->execute();
			
			return $stmt->fetch();
		}
		
		/**
		 * Gets all the models
		 *
		 * @return array  if the data found, false otherwise
		 */
		public static function all($includeInactive = true)
		{
			$sql = 'SELECT * FROM clients ORDER BY dateAdded ASC';
			
			if(!$includeInactive) {
				$sql = 'SELECT * FROM clients WHERE isActive = 1 ORDER BY dateAdded ASC';
			}
			
			/**
			 * @var $db \PDO
			 */
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->execute();
			
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		/**
		 * Save the  model with the current property values
		 *
		 * @return boolean  True if the object was saved, false otherwise
		 */
		public function save()
		{
			
			$sql = 'INSERT INTO clients (mac_address, ip_address, client_name, isActive)
                    		VALUES (:mac_address, :ip_address, :client_name,  :isActive)';
			/**
			 * @var $db \PDO
			 */
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			
			$stmt->bindValue(':mac_address', $this->mac_address, PDO::PARAM_STR);
			$stmt->bindValue(':ip_address', $this->ip_address, PDO::PARAM_STR);
			$stmt->bindValue(':client_name', $this->client_name, PDO::PARAM_STR);
			$stmt->bindValue(':isActive', $this->isActive, PDO::PARAM_BOOL);
			
			return $stmt->execute();
			
		}
		
		/**
		 * Delete this model
		 *
		 * @return void
		 */
		public function delete()
		{
			$sql = 'DELETE FROM clients WHERE id = :id LIMIT 1';
			
			/**
			 * @var $db \PDO
			 */
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':id', $this->id, PDO::PARAM_STR);
			
			$stmt->execute();
		}
		
		
	}
