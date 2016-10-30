<?php
	
	
	class DB{
		public static $conn;
		
		public function __construct(){
			

			date_default_timezone_set("Asia/Calcutta");
			
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
		
			
	
		
		}
		
		
		public function getConection(){
			if (is_null(DB::$conn)) {
				file_put_contents("log.txt", "GetConnectionCalled" );
				require 'dbconfig.php';
				DB::$conn = new mysqli($servername, $username, $password, $db);

				// Check connection
				if (DB::$conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
			}
			return DB::$conn;
		}
			
			/* --Registration methods-- */
		//TODO Write functions to register user in GCM database;
		public function RegisterUserInGCM($data){
			$email = $data['email'];
			$regId = $data['gcm_id'];
			$app_type = $data['app_type'];
			
		
			$isUserRegisteredInSystem = $this->isUserRegisteredInSystem($email); //Also holds ID for user if is found
			$AppIsInSystem = $this->isAppInSystem($app_type); //Also holds appID for user if is found
			file_put_contents("log.txt", "RegisterUserInGCM" . $_REQUEST['email']);
			if($isUserRegisteredInSystem){
				file_put_contents("log.txt", "User is in our system" . $_REQUEST['email']);
		
				if($AppIsInSystem){
					file_put_contents("log.txt", "App found in our system\n" . $_REQUEST['email']);
				
					if($this->isUserHasApp($AppIsInSystem, $isUserRegisteredInSystem)){ //AppIsInSystem && isUserRegisteredInSystem also holds id for mentioned table entries
						//Scenario is verified
						file_put_contents("log.txt", "User already has app, updating its regid\n" . $_REQUEST['email']);
					
						$this->UpdateRegID($isUserRegisteredInSystem, $regId);
						
					}else{
						//Scenario is verified
						file_put_contents("log.txt", "User doesnt have app, adding to user_apps\n" . $_REQUEST['email']);
					
						$this->AddUserToApp($isUserRegisteredInSystem, $regId, $AppIsInSystem);
					}
				}else{
					//Scenario is verified
					file_put_contents("log.txt", "App not found in our system, creating app and adding user to it\n" . $_REQUEST['email']);
					
					$appId = $this->AddAppToSystem($app_type);
					$this->AddUserToApp($isUserRegisteredInSystem, $regID, $appId);
				}
			
			}else{
				file_put_contents("log.txt", "User is not in our system, creating new user\n" . $_REQUEST['email']);
				print("User is not in our system, creating new user\n");
				$userId = $this->CreateNewUser($email, $regId);
				if($AppIsInSystem){ ///To find app id in here;
					//Scenario is verified
					file_put_contents("log.txt", "New user created, adding him to found app\n" . $_REQUEST['email']);
				
					$this->AddUserToApp($userId, $regId, $AppIsInSystem);
				}else{
					
					//Scenario is verified
					file_put_contents("log.txt", "New user created, app not found, creating new app and adding user to app\n" . $_REQUEST['email']);
				
					$appId = $this->AddAppToSystem($app_type);
					$this->AddUserToApp($userId, $regId, $appId);
				}
			}
			
			
			
			//$this->WipeAllTables();
		}
		
		
		private function isUserRegisteredInSystem($email){
			$query = "SELECT ID FROM users WHERE email LIKE '$email' LIMIT 1"; //http://stackoverflow.com/questions/1676551/best-way-to-test-if-a-row-exists-in-a-mysql-table this should be fastest way to find a TEXT
			$result = $this->getConection()->query($query);
		
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
		
			return ($result->fetch_assoc()["ID"]);
		}
		
	
		private function isAppInSystem($app){
			$query = "SELECT ID FROM apps WHERE appName LIKE '$app' LIMIT 1"; //http://stackoverflow.com/questions/1676551/best-way-to-test-if-a-row-exists-in-a-mysql-table this should be fastest way to find a TEXT
			$result = $this->getConection()->query($query);
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
		
			return ($result->fetch_assoc()["ID"]);
		}
		
		private function isUserHasApp($appID, $userID){
			$query = "SELECT * FROM user_apps WHERE appID = $appID AND userID = $userID";
			$result = $this->getConection()->query($query);
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
			return ($result->num_rows) > 0;
		}

		private function UpdateRegID($userId, $regId){
			$query = "UPDATE users SET regId = '$regId' WHERE ID = $userId";
			return $this->getConection()->query($query);
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
		}
		
		private function AddUserToApp($userId, $regId, $appId){
			$result = $this->UpdateRegID($userId, $regId);
			$query = "INSERT INTO user_apps (appID, userID) values ($appId, $userId)";
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
			
			
			
			$result = $this->getConection()->query($query);
			
			//Closing connection in this method, as it is called in 5/6 cases when registering users.
	
			return $result;
		}

		private function AddAppToSystem($app){
			$query = "INSERT INTO apps (appName) values ('$app')";	
			$result = $this->getConection()->query($query);
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
			return  mysqli_insert_id($this->getConection());
		}

		private function CreateNewUser($email, $regId){
			$query = "INSERT INTO users (email, regId) values ('$email', '$regId')";
			$result = $this->getConection()->query($query);
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
		
			return  mysqli_insert_id($this->getConection());
			
		}
		
		/* --Registration Methods are over-- */
		
		/* UI methods*/
		
		public function getAllApps(){
			$query = "SELECT * FROM apps";
			$result = $this->getConection()->query($query);
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
			while($row = $result->fetch_array()){
				$rows[] = $row;
			}
			$result->close();  
			return $rows;
		}
		
		public function getAllIcons(){
			$query = "SELECT * FROM notif_icons";
			$result = $this->getConection()->query($query);
			if (!$result) {
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
			while($row = $result->fetch_array()){
				$rows[] = $row;
			}
			 $result->close();
			return $rows;
		}
		
		
		/* --UI methods are over-- */
		
		/* --GCM SENDING METHODS-- */
		
		public function GetGCMUsers($appTypes){
			//Get Selected Apps
			//Get all users regIds who has selected apps
			
			//Send to all apps is selected (value 0)
			if(in_array(0, $appTypes)){
				$query="SELECT regId from users WHERE isactive = 1";
			}else{			
				$query="SELECT users.regId from users LEFT JOIN user_apps ON users.ID = user_apps.userID WHERE user_apps.appID IN (".implode(',',$appTypes).") AND users.isactive = 1";
			}
		
			$result = $this->getConection()->query($query);
			
			
			
			
			if($result){
				    $gcmRegIds = array();
					$i = 0;
				while($row = $result->fetch_assoc()){
					$i++;
					$gcmRegIds[floor($i/1000)][] = $row['regId'];
				}
				return $gcmRegIds;
			}else{
				file_put_contents("log.txt", "Query error " . $this->getConection()->error);
			}
		}
		
		
		
		
		
		/** --GCM SENDING METHODS are OVER-- */
		
		
		
		
		
		private function WipeAllTables(){
			
			$query = "TRUNCATE TABLE users";
			$this->getConection()->query($query);
			
			$query = "TRUNCATE TABLE apps";
			$this->getConection()->query($query);
			
			$query = "TRUNCATE TABLE user_apps";
			$this->getConection()->query($query);
			
		}
		
		
		
	}






?>