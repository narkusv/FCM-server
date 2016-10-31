<?php
require 'DB.php';
$helper = new DB();

	if(isset($_REQUEST['email']) && isset($_REQUEST['app_type']) && isset($_REQUEST['gcm_id'])){
		$r = $helper->RegisterUserInGCM($_REQUEST);	
	}

?>