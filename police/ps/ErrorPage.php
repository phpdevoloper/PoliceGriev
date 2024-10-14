<?php 
//error_reporting(0);
ob_start();
include("header.php");
$msg= str_replace("_","/",$_GET['errormsg']); 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Error Page</title>
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  /> 
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<style>
#span_dwnd
{
	cursor:pointer;
	font-weight:bold;
}
</style>
<script>
function closeWindow() {
	//alert("1111");
	self.close();
}
</script>
<body>
<div class="contentMainDiv" style="width:100%;background-color:#bc7676;margin-right:auto;margin-left:auto;" align="center">
<div class="contentDiv" >
<table class="viewTbl">
<tbody>
	<tr>
    	<td colspan="2" class="heading" style="background-color: #BC7676;">		
        	<?PHP echo "Error Page"; ?>
        </td>
    </tr>
	<tr><td  class="heading"><?echo $msg; ?></td></tr>
	
	<tr id='btn_row'>
	<td class="btn">
		<input type="button" name="back" id="back" value="Close" onClick="closeWindow();">
	</td>
	</tr>
		
</tbody>
</table>
</div>
</div>
</body>
</html>
 <?php include("footer.php"); ?>