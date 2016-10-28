<?php

	class DB{
		public static $conn;
		
		public function __construct(){
			date_default_timezone_set("Asia/Calcutta");
			
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		
			
			$this->getConection();
			
			
			
		}
		
		
		public function getConection(){
			if (is_null(DB::$conn)) {
				require 'dbconfig.php';
				DB::$conn = new mysqli($servername, $username, $password, $db);

				// Check connection
				if (DB::$conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
			}
			return DB::$conn;
		}
			
		
		//TODO Write functions to register user in GCM database;
		public function RegisterUserInGCM($data){
			$email = $data['email'];
			$regId = $data['gcm_id'];
			$app_type = $data['app_type'];
			
		
			$isUserRegisteredInSystem = $this->isUserRegisteredInSystem($email); //Also holds ID for user if is found
			$AppIsInSystem = $this->isAppInSystem($app_type); //Also holds appID for user if is found
	
			if($isUserRegisteredInSystem){
				print("User is in our system");
				if($AppIsInSystem){
					print("App found in our system");
					if($this->isUserHasApp($AppIsInSystem, $isUserRegisteredInSystem)){ //AppIsInSystem && isUserRegisteredInSystem also holds id for mentioned table entries
						//Scenario is verified
						print("User already has app, updating its regid");
						$this->UpdateRegID($isUserRegisteredInSystem, $regId);
					}else{
						//Scenario is verified
						print("User doesnt have app, adding to user_apps");
						$this->AddUserToApp($isUserRegisteredInSystem, $regId, $AppIsInSystem);
					}
				}else{
					//Scenario is verified
					print("App not found in our system, creating app and adding user to it");
					$appId = $this->AddAppToSystem($app_type);
					$this->AddUserToApp($isUserRegisteredInSystem, $regID, $appId);
				}
			
			}else{
				print("User is not in our system, creating new user\n");
				$userId = $this->CreateNewUser($email, $regId);
				print("user id is:" . $userId);
				if($AppIsInSystem){ ///To find app id in here;
					//Scenario is verified
					print("New user created, adding him to found app\n");
					$this->AddUserToApp($userId, $regId, $AppIsInSystem);
				}else{
					
					//Scenario is verified
					print("New user created, app not found, creating new app and adding user to app\n");
					$appId = $this->AddAppToSystem($app_type);
					$this->AddUserToApp($userId, $regId, $appId);
				}
			}
			
			
			
			//$this->WipeAllTables();
		}
		
		
		public function isUserRegisteredInSystem($email){
			$query = "SELECT ID FROM users WHERE email LIKE $email LIMIT 1"; //http://stackoverflow.com/questions/1676551/best-way-to-test-if-a-row-exists-in-a-mysql-table this should be fastest way to find a TEXT
			$result = $this->getConection()->query($query);
		
			return ($result->fetch_assoc()["ID"]);
		}
		
	
		public function isAppInSystem($app){
			$query = "SELECT ID FROM apps WHERE appName LIKE $app LIMIT 1"; //http://stackoverflow.com/questions/1676551/best-way-to-test-if-a-row-exists-in-a-mysql-table this should be fastest way to find a TEXT
			$result = $this->getConection()->query($query);
		
			return ($result->fetch_assoc()["ID"]);
		}
		
		public function isUserHasApp($appID, $userID){
			$query = "SELECT * FROM user_apps WHERE appID = $appID AND userID = $userID";
			$result = $this->getConection()->query($query);
			
			return ($result->num_rows) > 0;
		}

		public function UpdateRegID($userId, $regId){
			$query = "UPDATE users SET regId = $regId WHERE ID = $userId";
			return $this->getConection()->query($query);
		}
		
		public function AddUserToApp($userId, $regId, $appId){
			$result = $this->UpdateRegID($userId, $regId);
			$query = "INSERT INTO user_apps (appID, userID) values ($appId, $userId)";
			return $this->getConection()->query($query);
		}

		public function AddAppToSystem($app){
			$query = "INSERT INTO apps (appName) values ($app)";	
			$result = $this->getConection()->query($query);
			return  mysqli_insert_id($this->getConection());
		}

		public function CreateNewUser($email, $regId){
			$query = "INSERT INTO users (email, regId) values ($email, $regId)";
			$result = $this->getConection()->query($query);
			return  mysqli_insert_id($this->getConection());
		}
		
		
		
		
		
		public function WipeAllTables(){
			
			$query = "TRUNCATE TABLE users";
			$this->getConection()->query($query);
			
			$query = "TRUNCATE TABLE apps";
			$this->getConection()->query($query);
			
			$query = "TRUNCATE TABLE user_apps";
			$this->getConection()->query($query);
			
		}
		
		
		
	}






?>