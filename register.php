<?php
require 'DB.php';
$helper = new DB();

	if(isset($_REQUEST['email']) && isset($_REQUEST['app_type']) && isset($_REQUEST['gcm_id'])){
		print("Will be registering this user");
		print("<pre>");
			print_r ($_REQUEST);
		print("</pre>");
		
		
		$r = $helper->RegisterUserInGCM($_REQUEST);
		
	}else {
		echo "Missing params";
		
		
	}





?>