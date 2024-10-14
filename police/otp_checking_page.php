<?php 
ob_start();
$pagetitle="Checking OTP";
include("db.php");
include("header_menu.php");
include("menu_home.php");
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data
include("common_date_fun.php");
include("pm_common_js_css.php"); 
//include("newSMS.php");
include('sms_airtel_code.php');
 header("Location: pm_upload_order_cert.php");
?>
 
<script type="text/javascript">

function verifyOTP() {
	var otp_val = document.getElementById("otp").value;
	var user_id = document.getElementById("user_id").value;
	var language = document.getElementById("language").value;
	var param = "mode=verify_otp"
		        +"&otp_val="+otp_val
				+"&user_id="+user_id;;
	
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionProcessedByUsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			result = $(xml).find('result').eq(0).text();
			if (result == 0) {
				window.location.href="pm_upload_order_cert.php?user_id="+user_id+"&language="+language;
				return false;
			} else {
				alert("The OTP you have entered is not right!!!");
			}
			
			
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end
}
function resendOTP() {

	var user_id = document.getElementById("user_id").value;

	var param = "mode=resend_otp"
		       	+"&user_id="+user_id;;
	
	$.ajax({
		type: "POST",
		dataType: "xml",
		url: "p_PetitionProcessedByUsAction.php",  
		data: param,  
		
		beforeSend: function(){
			//alert( "AJAX - beforeSend()" );
		},
		complete: function(){
			//alert( "AJAX - complete()" );
		},
		success: function(xml){
			status = $(xml).find('status').eq(0).text();
			if (status == 0) {
				alert("The OTP have been sent to your Mobile successfully!!!");
				return false;
			} 
			
			
		},  
		error: function(e){  
			//alert('Error: ' + e);  
		}
	});//ajax end	
}
</script>
<?php
$user_id = $_SESSION['USER_ID_PK'];
 
//echo "=============".$userProfile->getDept_desig_id();
if ($userProfile->getDept_desig_id() == 56) {
	
header("Location: pm_upload_order_cert.php");
}
 $query = "select mobile from usr_dept_users where dept_user_id =".$user_id."";

$result = $db->query($query);
//$result = $db->query($sql);
	$rowarray = $result->fetchall(PDO::FETCH_ASSOC);
	
	foreach($rowarray as $row)
	{
		$mobile = $row['mobile'];
	}
	//echo "============================".$mobile;
	
?>

<form name="srch" id="srch" method="post">
<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo 'OTP Verification'; ?></div></div></div> 	
	<div class="contentMainDiv">
	 <div class="contentDiv"> 
           
	   <div class="div0" id="main1" role="main">
<?php
	if ($mobile == "") {
?>
<table class="formTbl">
		<tbody>
		<tr>
		
		<td style="text-align:center;">
		<span> <b>Your mobile number  is not registered to receive the OTP to upload orders.<br> To upload the orders first register your mobile number.</b></span>
        </td>
		
		</tr>
		        
		</tbody>
		</table>
		

	<?php } else { 
 
	
	$length = 6; 
	$chars = '1234567890';
    $chars_length = (strlen($chars) - 1);
    $string = $chars[rand(0, $chars_length)];
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        $r = $chars[rand(0, $chars_length)];
 //       if ($r != $string{$i - 1}) $string .=  $r;
        $string .=  $r;
    }
	//1007430885434076659 - Your OTP for this transaction is {#var#}. This is valid for next 10 Minutes Do not share this password with anyone - Tamil Nadu e-Governance Agency.
	$stringmsg = 'Your OTP for this transaction is '.$string.'. This is valid for next 10 Minutes. Do not share this password with anyone - Tamil Nadu e-Governance Agency.';
	$ct_id="1007986408450576562";
	
	if ($mobile != "") {
		//$strStatus = SMS($mobile,$stringmsg,'0');
		$strStatus = SMS($mobile,$stringmsg,'0',$ct_id);
	}
	
	$query = "update usr_dept_users set otp=".$string." where   dept_user_id =".$user_id."";
	
	$result=$db->query($query);
	
?>	

		<table class="formTbl">
		<tbody>
		<tr>
		
		<td style="width: 50%"><b><?PHP echo 'Enter the OTP received in your mobile' ?></b><span class="star">*</span></td>
		<td style="width: 50%"> 
		<input type="password" name="otp" id="otp" value="" maxlength="8" /> 
        </td>
		</tr><tr>
		<td colspan="2" style="text-align:right;"><span><b>An OTP is sent to your Mobile number.<br> If SMS is not received&nbsp;&nbsp;
		<a onclick="resendOTP();">Click here</a>&nbsp;&nbsp;to Resend again</b></span></td>
		</tr>
		<tr>
            <td colspan="2" class="btn" >
            <input type="button" name="srch" id="srch" value="Verify"  onClick="return verifyOTP();"/>
            <input type="hidden" name="ackmnt_hid" id="ackmnt_hid">
			<?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
			<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['USER_ID_PK'];?>">
			<input type="hidden" name="language" id="language" value="<?php echo $_SESSION['lang'];?>">
            </td>
        </tr>
        
		</tbody>
		</table>
		</div>
</form>
<?php
	}
$_SESSION['USER_ID_PK']=$_SESSION['USER_ID_PK'];	
include('footer.php');

?>
