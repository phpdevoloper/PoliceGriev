<?php
error_reporting(0);
$pagetitle="Change Password";
include("header_menu.php");
include("menu_home.php");
?>
<?php
if($_GET!==array()){
	if(!(count($_GET)==1 && ($_GET['lang']=='E' || $_GET['lang']=='T'))){
	echo "<script nonce='1a2b'> alert('Session not valid.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='1a2b'> document.location = 'logout.php'; </script>";
	exit;
	}
}else if($_SERVER["QUERY_STRING"]!=''){
	$eng="lang=E";
	$tam="lang=T";
	if(!($_SERVER["QUERY_STRING"]==$eng || $_SERVER["QUERY_STRING"]==$tam)){
	echo "<script nonce='1a2b'> alert('invalid URL.Page will be Refreshed.');</script>";
	echo "<script type='text/javascript' nonce='1a2b'> document.location = 'logout.php'; </script>";
	exit;
	}
}
$actual_link = basename($_SERVER['REQUEST_URI']);//"$_SERVER[REQUEST_URI]";
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='".$actual_link."') order by ordering";
$result = $db->query($query);
while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
}
?>
<script type="text/javascript" src="js/jquery.md5.min.js"></script>
<script LANGUAGE="Javascript" SRC="md5.js"></script>
<script type="text/javascript"> 

function chk_val(strSalt,strit)
{
var current_psw = document.getElementById('current_psw').value;
var new_psw = document.getElementById('new_psw').value;
var confirm_psw = document.getElementById('confirm_psw').value;

		//var ck_password =/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*-_])$/;
		var ck_password = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[~!#@_])[A-Za-z\d~!#@_]{8,}/;
   		var errors = [];
		

if(current_psw == null || current_psw == '')
{
	alert('Enter your old password.');
	document.getElementById('current_psw').focus();
	return false;
}
else if(new_psw == null || new_psw == '')
{
	alert('Enter your new password.');
	document.getElementById('new_psw').focus();
	return false;
}
else if(new_psw.length < 8)
{
	alert('New Password should not be less than 8 characters');
	document.getElementById('new_psw').focus();
	return false;
}
else if(new_psw.length > 16)
{
	alert('New Password should not be greater than 16 characters');
	document.getElementById('new_psw').focus();
	return false;
}
else if(confirm_psw == null || confirm_psw == '')
{
	alert('Enter your confirm password.');
	document.getElementById('confirm_psw').focus();
	return false;
}
else if(new_psw != confirm_psw)
{
	alert('Password confirmation does not match.');
	document.getElementById('confirm_psw').focus();
	return false;
} 
else if (new_psw!="" && new_psw.length<8)
	{
		alert ("Password should be more than or equal to 8 characters");
		document.getElementById("new_psw").value="";
		document.getElementById("confirm_psw").value="";
		document.getElementById("new_psw").focus();
		return false;
	}
else if (!ck_password.test(new_psw)) 
	{
		errors[errors.length] = "Minimum one lowercase characters (a to z)";
		errors[errors.length] = "Minimum one uppercase characters (A to Z)";
		errors[errors.length] = "Minimum one numeric characters (0 to 9)";
		errors[errors.length] = "Minimum one special Characters(~!#@_)";
		errors[errors.length] = "Minimum 8 in length";
		if (errors.length > 0)
		{
			reportErrors(errors);
			document.getElementById("new_psw").value="";
			document.getElementById("confirm_psw").value="";
			document.getElementById("new_psw").focus();
			return false;
		}
	} 
else {
		var strEncPwd=MD5(document.getElementById("new_psw").value);
		document.getElementById("newpsw").value = document.getElementById("new_psw").value;
		document.getElementById("new_psw").value=strEncPwd;
		//document.getElementById("encry_psw").value=strEncPwd; 

		var strEnccnfPwd=MD5(document.getElementById("confirm_psw").value);
		document.getElementById("confirm_psw").value=strEnccnfPwd;
		 
		var encr_current_psw=new String(encryptPwd1(document.getElementById("current_psw").value, strSalt,strit));
		document.getElementById("current_psw").value=encr_current_psw;
		 
	}

 return true;
}

function encryptPwd1(strPwd, strSalt,strit)
			{
				 
				var strNewSalt=new String(strSalt);
				
				if (strPwd=="" || strSalt=="")
				{
					return null;
				}
				 
				var strEncPwd;
				var strPwdHash = MD5(strPwd);
				var strMerged = strSalt+strPwdHash;
				 
				 
				var strMerged1 = MD5(strMerged);
				 
				return strMerged1;
				 
 			}	
function reportErrors(errors)
			{
				 var msg = "Please Enter Valid Password...\n";
				 for (var i = 0; i<errors.length; i++) 
				 {
					  var numError = i + 1;
					  msg += "\n" + numError + ". " + errors[i];
				 }
				 alert(msg);
			}

function cancel_psw()
{
	window.location.href='menu_home.php';
}
</script>

<div id="div_content" class="divTable" style="background-color:#F4CBCB;">
	<div class="form_heading"><div class="heading"><?php echo $label_name[0]; //Change Password?></div></div>
	<div class="contentMainDiv" style="width:98%;margin:auto;">
	<div class="contentDiv">
	<?php
	if(isset($_SESSION['alert_msg']))
	{
	$alert_msg = $_SESSION['alert_msg'];
	?>
	<script>alert('<?php echo $alert_msg; ?>');</script>
	<?php
	unset($alert_msg);
	unset($_SESSION['alert_msg']);
	}
	?>
	<form method="post" name="change_password_frm" action="" style="background-color:#F4CBCB;">
	<table class="formTbl">
        <tbody>
			<tr id="alrtmsg" style="display:none;" ><td>&nbsp;</td> </tr>
			<tr>
				<td><?php echo $label_name[1]; //Current password?>: </td>
				<td><input type="password" name="current_psw" id="current_psw"></td>
			</tr>
			<tr>
				<td><?php echo $label_name[2]; //New password?>: </td>
				<td><input type="password" name="new_psw" id="new_psw">
                <input type="hidden" name="encry_newpsw" id="encry_newpsw">
				<input type="hidden" name="newpsw" id="newpsw">
				</td>
			</tr>
			<tr>
				<td><?php echo $label_name[3]; //Confirm password?>: </td>
				<td><input type="password" name="confirm_psw" id="confirm_psw">
				<input type="hidden" name="encry_cnfpsw" id="encry_cnfpsw"></td>
			</tr>
			<tr>
				<td></td>
				<td>
				<input type="submit" name="chng_psw" id="chng_psw" value="<?php echo $label_name[4]; //Submit?>" onclick="return chk_val('<?php echo $_SESSION['salt'];?>','<?php echo $_SESSION['itno'];?>');">
				<!--&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="cancel_psw" id="cancel_psw" value="Cancel" onclick="cancel_psw();">-->
				</td>
			</tr>
		</tbody>
	</table>
	</form>
	</div>
	</div>
</div>
<?php
if(isset($_POST['chng_psw']))
{
$newpsw	= $_POST['newpsw'];
$old_psw = htmlspecialchars(strip_tags(trim($_POST['current_psw'])));
$new_psw = htmlspecialchars(strip_tags(trim($_POST['new_psw'])));
$confirm_psw = htmlspecialchars(strip_tags(trim($_POST['confirm_psw'])));
$encrypt_psw= htmlspecialchars(strip_tags(trim($_POST['encry_psw'])));
$userid = $_SESSION['USER_ID_PK'];

$ip=$_SERVER['REMOTE_ADDR'];

if($new_psw == $confirm_psw)
{
	$qry = "SELECT user_pwd,user_pwd_encr from usr_dept_users where dept_user_id='".strip_tags($userid)."'";
	$pgquery = $db->prepare($qry);
	$pgquery->execute();
	$row = $pgquery->fetch(PDO::FETCH_BOTH);

	$exist_psw = $row[0];
	$exist_psw_encr = $row[1];
	
	$encpasswd=$_SESSION['salt'].$exist_psw_encr;
	 
	$epwd=md5($encpasswd);
	  
	if($epwd != $old_psw)
	{
	?>
	<table class="formTbl">
    <tbody>
	<tr>
	<td></td>
	<td>
	<span style="color:#A94442;font-weight:bold;">
	<?php
		echo $errmsg = 'Old password is wrong. Enter Correct password';
	?>
	</span>
  </td>
  </tr>
  </tbody>
  </table>
  <?php
	}
	else if($exist_psw_encr == $new_psw)
	{
	?>
	<table class="formTbl">
    <tbody>
	<tr>
	<td></td>
	<td>
	<span style="color:#A94442;font-weight:bold;">
	<?php
		echo $errmsg = 'New password should not be same as old password.';
	?>
	</span>
  </td>
  </tr>
  </tbody>
  </table>
  <?php
	}else{
		$ip_addr = $_SERVER['REMOTE_ADDR'];
		$datetime = date("Y-m-d H:i:s");

		$sql = "UPDATE usr_dept_users SET user_pwd='".$newpsw."',user_pwd_encr='".strip_tags($new_psw)."',pwd_upd_dt=current_timestamp,pwd_upd_ip_address='".$ip_addr."' where dept_user_id='".strip_tags($userid)."'";
		$pgqry = $db->prepare($sql);
		$pgqry->execute();
		?>
	<table class="formTbl">
    <tbody>
	<tr>
	<td></td>
	<td>
	<span style="color:#38761D;font-weight:bold;">
	<?php
		echo $msg = 'Password Changed Successfully.';
	?>
	</span>
	</td>
	</tr>
	</tbody>
	</table>
  	<?php
	}
}
}
 
?>
<?php
include('footer.php');
?>