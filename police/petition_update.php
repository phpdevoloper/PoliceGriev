<?php 
ob_start();
$pagetitle="Petition Update";
include("db.php");
include("header_menu.php");
include("menu_home.php");
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
include("chk_menu_role.php"); //should include after menu_home, becz get userprofile data
include("common_date_fun.php");
include("pm_common_js_css.php"); 
?>
 
<script type="text/javascript">
 
function numbersonly(e,t)
{
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode==13)
	{
		try{t.blur();}catch(e){}
		return true;
	}
	if((unicode >=47 && unicode<57) ||(unicode >65 && unicode<123) || (unicode==8))
		return true;
	else
		return false;	
}

function checkPetNo(e) 
{ 	
		var unicode=e.charCode? e.charCode : e.keyCode;
		if (unicode!=8 && unicode!=9 && unicode!=46)
		{
		if ((unicode >64 && unicode<123)  || (unicode>=47 && unicode<=57)  || (unicode==32) && (unicode!=96 && unicode!=94 && unicode!=93 && unicode!=92 && unicode!=91 ) || 
		(unicode == 12 || (unicode>=33 && unicode <=40) || unicode == 45 )) //&& unicode!=95 (_)
				return true
		else
				alert("Only alphabets, numbers, Special characters / and - are allowed");
				return false
		}
}
	
function search_petition()
{
	pet_no=$('#petition_no').val();
	$('#petition_no').removeClass('error');
	document.getElementById("alrtmsg").innerHTML="";
	if($.trim($('#petition_no').val())=='')
	{
		$("#alrtmsg").html($('#petition_no').attr('data-error'));
		$('#petition_no').addClass('error');
		return false;
	}
	else{
		var param="mode=check_petno"+"&pet_no="+$('#petition_no').val();
		$.ajax({
			type: "POST",
			datatype: "xml",
			url:"petition_update_action.php",
			data:param,
			beforeSend: function(){
				//alert( "AJAX - beforeSend()" );
			},
			complete: function(){
				//alert( "AJAX - complete()" );
			},
			success: function(xml){
				// we have the response 
				//alert(xml);			
				check_petno(xml);  
			},  
			error: function(e){  
				//alert('Error: ' + e);  
			} 
		});//ajax end
	}  
} 
function check_petno(xml){
	// alert();
	var status = $(xml).find('status').eq(0).text();
	//alert(status);  
		if(status=='wrong'){
			alert("Enter Valid Petition No.");
			return false;
		}
		else if(status=='true'){
			//set the xml values in hidden	
			var eo = $(xml).find('eo').eq(0).text();
			document.getElementById('eo_id').value=eo;
			document.srch.method="post";
			document.srch.action = "pm_petition_detail_update.php"
			document.srch.submit();
			return true;
			//alert('ok');
		}
		else {
			alert("A Petition can be edited only with Initiating / Enquiry Filing / Enquiry Officer");
			return false;
		}

}

</script>
<?php
$query = "select label_name,label_tname from apps_labels where menu_item_id=(select menu_item_id from menu_item where menu_item_link='pm_ackmnt.php') order by ordering";

$result = $db->query($query);

while($rowArr = $result->fetch(PDO::FETCH_BOTH)){
	if($_SESSION['lang']=='E'){
		$label_name[] = $rowArr['label_name'];	
	}else{
		$label_name[] = $rowArr['label_tname'];
	}
	
}
?>

<form name="srch" id="srch" method="post">

<div id="dontprint"><div class="form_heading"><div class="heading"><?PHP echo $label_name[25]; ?></div></div></div> 	
	<div class="contentMainDiv">
	 <div class="contentDiv"> 
           <div id="alrtmsg" style="color:#FF0000; font-size:18px" align="center"> 
			<?PHP 
			if($_REQUEST['msg']=='incorrect'){
			echo 'Enter Valid Petition Number !';
			echo "<br>"."<br>";
			}
			?>
			</div>
	   <div class="div0" id="main1" role="main">
		<table class="formTbl">
		<tbody>
		<tr>
		
		<td style="width: 50%"><?PHP echo $label_name[1]; //Petition No. ?><span class="star">*</span></td>
		<td style="width: 50%"> 
		<input type="text" name="petition_no" id="petition_no" value="" maxlength="25" onKeyPress="return checkPetNo(event);" data-error="Please Enter Petition Number !"  /> 
        </td>
		
		</tr>
		<tr>
            <td colspan="2" class="btn" >
            <input type="button" name="srch" id="srch" value="<?PHP echo $label_name[26]; ?>"  onClick="return search_petition();"/>
            <input type="hidden" name="ackmnt_hid" id="ackmnt_hid">
            <input type="hidden" name="eo_id" id="eo_id">
			<?php
            $ptoken = md5(session_id() . $_SESSION['salt']);
            $_SESSION['formptoken']=$ptoken;
            ?>
            <input type="hidden" name="formptoken" id="formptoken" value="<?php echo($ptoken);?>" />
            </td>
        </tr>
        
		</tbody>
		</table>
		</div>
</form>
<?php
include('footer.php');
?>
