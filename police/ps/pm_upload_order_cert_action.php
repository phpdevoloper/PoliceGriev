<?php
session_start();
header('Content-type: application/xml; charset=UTF-8');
include("db.php");
include("Pagination.php");
include("UserProfile.php");
include("common_date_fun.php");
include("sms_send.php");
$userProfile = unserialize($_SESSION['USER_PROFILE']); 

$xml = new SimpleXMLElement($_POST['xml']);

	$current_date = date('Y-m-d h:i:s'); 		
	$user_id = $xml->user_id;	
	$upaction=$xml->upaction;
	$pet_no1 = $xml->pet_no1;
	$document_name1 = $xml->document_name1;
	$document_tmp_name1 = $xml->document_tmp_name1;
	$document_size1 = $xml->document_size1;
	$document_type1 = $xml->document_type1;
	
	$pet_no2 = $xml->pet_no2;
	$document_name2 = $xml->document_name2;
	$document_tmp_name2 = $xml->document_tmp_name2;
	$document_size2 = $xml->document_size2;
	$document_type2 = $xml->document_type2;
	
	$pet_no3 = $xml->pet_no3;
	$document_name3 = $xml->document_name3;
	$document_tmp_name3 = $xml->document_tmp_name3;
	$document_size3 = $xml->document_size3;
	$document_type3 = $xml->document_type3;
	
	$pet_no = array();
	$doc_name = array();
	$doc_tmp_name = array();
	$doc_size = array();
	$doc_type = array();
	 
	$count=0;	
	if ($pet_no1!="") {
		
		$pet_no[$count] = $pet_no1;
		$doc_name[$count] = $document_name1;
		$doc_tmp_name[$count] = $document_tmp_name1;
		$doc_size[$count] = $document_size1;
		$doc_type[$count] = $document_type1;		
		$count=$count+1;
	}
	
	if ($pet_no2!="") {
		
		$pet_no[$count] = $pet_no2;
		$doc_name[$count] = $document_name2;
		$doc_tmp_name[$count] = $document_tmp_name2;
		$doc_size[$count] = $document_size2;
		$doc_type[$count] = $document_type2;		
		$count=$count+1;
	}
	
	if ($pet_no3!="") {
		
		$pet_no[$count] = $pet_no3;
		$doc_name[$count] = $document_name3;
		$doc_tmp_name[$count] = $document_tmp_name3;
		$doc_size[$count] = $document_size3;
		$doc_type[$count] = $document_type3;		
		$count=$count+1;
	}
	
	$t=0;
	$pets = "";
	$files = "";
	for($t=0;$t<$count;$t++)
	{
		$petition_no = $pet_no[$t];
		$p_sql = "SELECT petition_id FROM pet_master where petition_no='$petition_no'";
		
		$rs=$db->query($p_sql);
		$row = $rs->fetch(PDO::FETCH_BOTH);
		$pet_id=$row[0];
		
		$filenames = $doc_name[$t];
		
		$filetmp_names = $doc_tmp_name[$t];
		
		$filesizes = $doc_size[$t];
		
		$filetypes = $doc_type[$t];
		$allowedFileType = array(
            'jpg',
            'jpeg',
            'pdf','tmp'
        );
        $fileExtension = strtolower(pathinfo($filetmp_names, PATHINFO_EXTENSION));
		//echo $fileExtension.$filetmp_names;exit;
        if (! in_array($fileExtension, $allowedFileType)) {
			?>
		<script type="text/javascript">
			alert('<?php echo "<span>File is not supported. Upload only <b>" . implode(", ", $allowedFileType) . "</b> files.</span>"?>');
			self.close;
        </script>
		<?php
		exit;
        }
		$f = fopen($filetmp_names,'r');
		$data = fread($f, filesize($filetmp_names));
		$content = pg_escape_bytea($data);
		fclose($f);

		if ($upaction == 'save') {
			$sql ="INSERT INTO pet_action_doc (petition_id,action_doc_content,action_doc_name,action_doc_size,action_doc_type,action_doc_entby,ent_ip_address,action_doc_entdt)
			VALUES('".$pet_id."','".$content."','".$filenames."','".$filesizes."','".$filetypes."','".$user_id."','".$_SERVER['REMOTE_ADDR']."','".$current_date."')";
		} else {
	$sql="UPDATE pet_action_doc
   SET action_doc_content='".$content."',action_doc_size='".$filesizes."', action_doc_type='".$filetypes."',action_doc_modby='".$user_id."',
   action_doc_moddt='".$current_date."', mod_ip_address='".$_SERVER['REMOTE_ADDR']."', action_doc_name='".$filenames."'
 WHERE petition_id=".$pet_id."";
		}
		
		$result=$db->query($sql);
	
	$pets.=$petition_no.",";
	$files.=$filenames.",";
	if ($result) {
      $response = "Uploaded Success";
  } else {
      $response = "Uploaded Failure";
  }
	
	}
	 
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ;  />
<form name="upload_res" id="upload_res" action="" method="post>
<div class="contentMainDiv">
	<div class="contentDiv">
	<table class="ack_viewTbl">
			<tbody>
             
             
             <tr>
                <td colspan="2" class="heading" style="background-color:#BC7676">
                <img height="50" width="50" src="images/TamilNadu_Logo.jpg" id="prnt_img" align="left"/> 
                <center><?PHP  echo 'Upload Status'; ?>
				
				</center>   
                </td>									
             </tr>           
			 
           <tr>
			<td><?PHP echo 'Petition No.; '?><?php echo $pets; ?></td>
            <td><?PHP echo "Document Name: "; //Source ?><?php echo  $files;?>  </td> 
				
		   </tr>
            
            <tr>
			<td colspan="2"><?PHP echo "Upload Status: " ?> : <?php echo $response;?></td>
			
			</tr>           

		
			<tr>
            	<td colspan="2" class="btn"> 
          <input type="button" name="" id="dontprint1" value="<?PHP echo 'Back'; //Print ?>" class="button" onClick="return backToUpload()">
            		
                   
            	</td>
			</tr>
			
			</tbody>
			</table>
			 

			<div id="footer" role="contentinfo">
			<?php //include("footer.php"); ?>
			</div>
	 
</div>
</div>
</form>
<script>
function backToUpload() {
	document.upload_res.action = "pm_upload_order_cert.php";
	document.upload_res.submit();
}
</script>