<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

	//Converts the $passArray to correct Sql insert/update key/values
	// example: FirstName = "Adam"
	//          becomes
	//          "first_name" => "Adam"
	// NOTE: the bindInsertValuesToStatementHandler adds the :  and the first item gets : in the insert statement
	protected static function createInsertArray($passedArray, $included = [], $excluded = [])
	{
		//First add any included items
		// These are items that are also required but not passed by the data node
		if (count($included) > 0) {
			foreach ($included as $key => $value) {
				$passedArray[ $key ] = $value;
			}
		}

		//First remove any excluded items
		// Items that are passed in the Data node but we don't want to update. Like an ID field
		if (count($excluded) > 0) {
			foreach ($excluded as $key) {
				if (array_key_exists($key, $passedArray)) {
					unset($passedArray[ $key ]);
				}
			}
		}

		//now parse the cleaned array
		$studly_array = array();
		foreach ($passedArray as $StudKey => $StudResult) {
			$studly_array[ $StudKey ] = $StudResult;
		}

		return $studly_array;
	}


	//Converts the $passedArray to correct Sql insert/update key/values
	// example: FirstName = "Adam"
	//          becomes
	//          "first_name = :first_name" => "Adam"
	// This is the update pattern that is used by PDO for param binding
	protected static function createUpdateArray($passedArray, $included = [], $excluded = [])
	{
		//First add any included items
		// These are items that are also required but not passed by the data node
		if (count($included) > 0) {
			foreach ($included as $key => $value) {
				$passedArray[ $key ] = $value;
			}
		}

		//First remove any excluded items
		// Items that are passed in the Data node but we don't want to update. Like an ID field
		if (count($excluded) > 0) {
			foreach ($excluded as $key) {
				if (array_key_exists($key, $passedArray)) {
					unset($passedArray[ $key ]);
				}
			}
		}

		//now parse the cleaned array
		$studly_array = array();
		foreach ($passedArray as $StudKey => $StudResult) {
			$key = $StudKey . " = :" . $StudKey;
			$studly_array[ $key ] = $StudResult;
		}

		return $studly_array;
	}

	protected static function bindToDBHandler($handler, $data, $mode = MODEL_MODES::INSERT)  {

		foreach ($data as $param_key => $param_value) {
			$bind = explode(" = ", $param_key);

			if ($param_key == "approved_at") {
				if (\DateTime::createFromFormat( Settings::MYSQL_TIMESTAMP_FORMAT, $param_value ) !== false) {
					$date = date( Settings::MYSQL_TIMESTAMP_FORMAT, strtotime( $param_value ) );
					$handler->bindValue( ':approved_at', $date, PDO::PARAM_STR );
					continue;
				}
			}

			$method = PDO::PARAM_STR;

			if (is_numeric($param_value)) {
				$method = PDO::PARAM_INT;
			}

			// use $bind[1] because it has the :somevalue
			// somevalue = :somevalue
			if($mode == MODEL_MODES::INSERT){
				$handler->bindValue($bind[0], $param_value, $method);
			} else {
				$handler->bindValue($bind[1], $param_value, $method);
			}

		}

		return $handler;
	}

	public static function genericAdd($container, $data){

		//Build SQL statement

		//$includeArray = array("CompaniesId" => $OrgUnit);
		$excludeArray = array("errors");
		$insertArray = static::createInsertArray($data, [], $excludeArray);

		$sql = "INSERT INTO  $container";
		$sql .= " ( " . implode(", ", array_keys($insertArray)) . " )";
		$sql .= " VALUES ( :" . implode(", :", array_keys($insertArray)) . " )";

		/**
		 * @var $db PDO
		 */
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		//bind all parameters
		static::bindToDBHandler($stmt, $insertArray);
		$result = $stmt->execute();

		if ($result === true) {
			return $db->lastInsertId();
		}else {
			return false;
		}

	}

	public static function genericUpdate($container, $data){

		// this is the sql_cased values you want to exclude from the update array.
		// Like conditional where id that wont be updated, but used in the WHERE clause
		$excludeArray = array("id");

		if(array_key_exists('errors', $data)){
			unset($data['errors']);
		}

		$updateArray = static::createUpdateArray($data, [], $excludeArray );
		$sql = " UPDATE $container";
		$sql .= " SET " . implode(", ", array_keys($updateArray));
		$sql .= " WHERE id = :id";


		/**
		 * @var $db PDO
		 */
		$db = static::getDB();

//REMOVE for production
		//TODO:  REMOVE for production
//        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );


		$stmt = $db->prepare( $sql );

		//Bind ID
		$stmt->bindParam(":id" , $data['id']);

		static::bindToDBHandler($stmt, $updateArray,MODEL_MODES::UPDATE);
		//Run SQL
		$stmt->execute();

		return $stmt->rowCount();

	}



}
