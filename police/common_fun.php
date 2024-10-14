<?php
 //$last_mnt = mktime(0, 0, 0, date("n") - 1, date("d/m/Y")-1); //for -1 month starting date 01
  //$last_mnth_date=date("d/m/Y", $last_mnt);
 
 
 //echo ">>>>".$date = strtotime('2012-05-01 -4 months');
 $fromDate = date("d/m/Y", strtotime("-1 months"));
 
 if($_POST['from_date']=='') {
	 //$frdate=$last_mnth_date;
	 $frdate=$fromDate;
} else {
	$frdate=$_POST['from_date'];
}

if($_POST['to_date']=='') {
	$todate=date('d/m/Y');
} else {
	$todate=$_POST['to_date'];
}

$date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/'; 
//Here regex didn't work because you had unescaped / delimiter. The regex that would validate date in format YYYY-MM-DD

/*----------------------------------------------------*/

function stripQuotes($strWords) {
 
//echo "aaa".$strWords;
  $stripQuotes = str_replace("'", "''",$strWords); 
 //echo "ccc".$stripQuotes;
  return $stripQuotes;
}
//---------------------------------------
function killChars($strWords) {
//echo "inside kill function";
  $badChars = array("select", "#", "$", "drop", ";", "--","insert",  "delete", " and ", " or ", "xp_","union","|","&",";","$","@","!","%","'",'"',"\'",'/"',"+","SELECT","INSERT","DELETE","AND","UNION",">","<","<=",">=","!=","||","=","*","<",">","[","]","()","Select","Insert","Delete","Union","Drop","^"); 
  $newChars = $strWords; //"and","or","()","@",, "-"
   //echo count($badChars);
 for($i=0;$i<count($badChars);$i++) {
     //echo $badChars[$i];echo "<br>";
	$newChars = str_replace($badChars[$i], "",$newChars);
 }  
  return $newChars;
}
?>
