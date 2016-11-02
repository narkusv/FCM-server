<?php
include 'simple_html_dom.php';

if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'addNewGMAApp' : RegisterNewApp();break;
      
      
    }
}

function RegisterNewApp(){
	$GMAURL = $_POST['GMAURL'];
	
	
	$doc = file_get_html($GMAURL);
	$appPhotoURL = $doc->find('.cover-image')[0]->src;
	$appName = $doc->find('.id-app-title')[0]->innertext;
	
	require 'DB.php';
	$helper = new DB();
	
	$GetMoreApp = array("URL"=>$GMAURL, "PhotoURL" => $appPhotoURL, "Name" => $appName);
	
	$helper->InsertGetMoreApp($GetMoreApp);
}




?>