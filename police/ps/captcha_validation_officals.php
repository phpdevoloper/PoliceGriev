<?php 
 session_start();
if($_REQUEST["q"] !='') {

	$_SESSION['security_code_offical']=substr($_SESSION['key'],0,5);
	
	$q = $_REQUEST["q"];
	
	if($_SESSION['security_code_offical'] != $q) {
		$myArr = array("F");
	}
	else {
	$myArr = array("T");
	}
	$myJSON = json_encode($myArr); 
    echo $myJSON;
}
?>