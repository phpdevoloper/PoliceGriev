<?php

$fromDate = date("d/m/Y", strtotime("-60 days")); 
$todate = date("d/m/Y", strtotime("-30 days"));


$frdate=$fromDate;
$todate=$todate;
 
 /*if($_POST['from_date']=='')
{
	 //$frdate=$last_mnth_date;
	 $frdate=$fromDate;
}
else
{
	$frdate=$_POST['from_date'];
}

if($_POST['to_date']=='')
{
	$todate= $todate;
}
else
{
	$todate=$_POST['to_date'];
}*/

$date_regex = '/^(19|20)\d\d[\-\/.](0[1-9]|1[012])[\-\/.](0[1-9]|[12][0-9]|3[01])$/'; 
//Here regex didn't work because you had unescaped / delimiter. The regex that would validate date in format YYYY-MM-DD
// https://www.simonholywell.com/post/2014/01/add-a-duration-or-interval-to-a-date/
/*----------------------------------------------------*/

function stripQuotes($strWords) 
{
 
//echo "aaa".$strWords;
  $stripQuotes = str_replace("'", "''",$strWords); 
 //echo "ccc".$stripQuotes;
  return $stripQuotes;
}
//---------------------------------------
function killChars($strWords) 
{
//echo "inside kill function";
  $badChars = array("select", "#", "$", "drop", ";", "--","insert",  "delete", " and ", " or ", "xp_","union","|","&",";","$",
  "%","'",'"',"\'",'\"',"<>","+","SELECT","INSERT","DELETE"," AND ","UNION",">","<","<=",">=","!=","||","=","Select","Insert","Delete","Union","Drop","^"); 
  $newChars = $strWords; //"and","or","()","@",, "-"
   //echo count($badChars);
 for($i=0;$i<count($badChars);$i++)
 {
     //echo $badChars[$i];echo "<br>";
	  $newChars = str_replace($badChars[$i], "",$newChars);
 }
  
  return htmlentities(escapeshellcmd(htmlspecialchars($newChars)));
}
?>
