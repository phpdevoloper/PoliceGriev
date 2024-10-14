<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>

</head>
<body>
<div class="unauth_top"></div>
<div class="unauth_mid_1"/>
<div class="unauth_mid_2">
    Access Denied !!!
    <?php
	pg_close($db);
	header('Location: logout.php');
	exit; 
	?>
</div>
<div class="unauth_bottom"/>
</body>
</html>
