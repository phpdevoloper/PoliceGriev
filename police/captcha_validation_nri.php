<?php 
 session_start();
if($_REQUEST["q"] !='') {

	$_SESSION['security_code_nri']=substr($_SESSION['key_nri'],0,5);
	
	$q = $_REQUEST["q"];
	
	if($_SESSION['security_code_nri'] != $q) {
		$myArr = array("F");
	}
	else {
	$myArr = array("T");
	}
	$myJSON = json_encode($myArr); 
    echo $myJSON;
}
?>