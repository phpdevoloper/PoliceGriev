<?php	
include("db.php");
session_start();	
?>
<?php 
error_reporting(0);
include("menu_home.php");
?><!--this php  is included  to set the session menu -->


<script language="javascript">
function check()
{
 	 
	document.getElementById("btn2").style.display="none";
}
</script>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<link href="images/style.css" rel="stylesheet" type="text/css" />
<html>
<head><title>Online GDP</title></head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body >
<center>
 <?
//include("top.php"); ?>
<tr height="5"><td>   </td></tr>
 <? 
    $uname     = $_SESSION['username']; 
	
	$username = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $_GET['user']);;
	 
	$source_id = $_POST["source_id"];
	
	$date			= Date("d/m/Y");
	 
		if( strlen($_SESSION['username']) > 0 )
		{
			$usrname  = $uname;
			 
			$urole     = $_SESSION['urole'];
		?>
<?
 
				if(!empty($username))
				{
				$resSql = pg_exec("select t1.ackmnt_no,to_char(t1.ackmnt_date,'dd/mm/yyyy'),t1.pet_ename,t3.griev_min_edesc,t3.griev_min_tdesc,t2.remarks,t2.status,t1.doorno,t1.street,t1.hamlet,t1.dist_code,t1.taluk,t1.village,t1.pincode,t1.grievance,t2.receipt_date,t4.user_edesc,t5.source_tdesc,t2.remarks,t6.distt_ename,t1.dist,t1.initial,t1.father_husband_name from gdp_mast t1, gdp_detail t2, griev_detail t3,user_mast t4,source_mast t5, distt_mast t6 where t1.ackmnt_no = t2.ackmnt_no and t2.ackmnt_no='$username' and (t2.status='A' or t2.status='R') and t1.griev_min_code = t3.griev_min_code and t2.user_name=t4.user_name and t1.source_id=t5.source_id and t1.dist=t6.distt_sname order by t2.status,t1.ackmnt_no    ");
				
		    	 }
				 
				 $SlNo=0;
					while($rowRes=pg_fetch_row($resSql))
					{
						$pet_no=$rowRes[0];
						$pet_name=$rowRes[2];
						$door_no=$rowRes[7];
						$street=$rowRes[8];
						$hamlet=$rowRes[9];
						$dist_code=$rowRes[10];
						$taluk=$rowRes[11];
						$village=$rowRes[12];
						$pincode=$rowRes[13];
						$receipt_date=$rowRes[15];
						$source=$rowRes[17];
						$officer=$rowRes[16];
						$remarks=$rowRes[18];
						$district=ucfirst(strtolower($rowRes[19]));
						$dist=$rowRes[20];
						$initial=$rowRes[21];
						$father_husband_name=$rowRes[22];
					 
						// for get dist name
						if($dist_code!="")
						{
							$query = "SELECT distt_sname,distt_ename,distt_tname FROM distt_mast where dist_code='$dist_code'";
						}	
						else{
							$query = "SELECT distt_sname,distt_ename,distt_tname FROM distt_mast where distt_sname='$dist'";
							}   
						 $res = pg_query($conn,$query);
						  while($Row = pg_fetch_row($res))
						  {
						  if ($_SESSION['Language']=='E')
						  $desc_dist=ucfirst(strtolower($Row[1]));
						  else
						  $desc_dist=$Row[2];
						  } 
						  
						  // for get taluk name
					  $query1 = "SELECT dist_code,taluk_code,taluk_ename,taluk_name FROM taluk where dist_code='$dist_code' and taluk_code='$taluk'";    
							//echo  $query1;  	 
								 
							  $result1 = pg_query($conn,$query1);
							  while($Row = pg_fetch_row($result1))
							  {
							  if ($_SESSION['Language']=='E')
							  $desc_taluk=$Row[2];
							  else
							  $desc_taluk=$Row[3];
							  }
						  
						  // for get village name
						   $query2 = "SELECT dist_code,taluk_code,vill_code,vill_ename,vill_name FROM village where dist_code='$dist_code' and taluk_code='$taluk' and  vill_code='$village'";
						   //echo $query2; 
						    
                          $result2 = pg_query($conn,$query2);
                          while($Row = pg_fetch_row($result2))
                          {
						  if ($_SESSION['Language']=='E')
		         		  $desc_vige=$Row[3];
		         		  else
		                  $desc_vige=$Row[4];
                          } 
					  
			}
	
    ?>
	 <table border=0 align="center" bordercolor=darkblue cellspacing=1 cellpadding=5 width="70%"> 
	 <th colspan=2 height=30><font size=1><b><? echo "பதில் கடிதம்"; ?></b></font></th> 
	 <tr> 
	 
	 <tr> 
	 <td>
	 <table border=0  bordercolor=darkblue cellspacing=0 cellpadding=5 width="100%">
	  
	 <tr>
	 <td width="50%" class="label"><font size=1><b><?php echo "விடுநர்   ";?></b></font></td>
	 <td width="50%" class="label"><font size=1><b><?php echo "பெறுநர்";?></td>
	 </tr>
	
	 <tr> 
	 
	 <td width="80%" class="label"><font size=1><b> சிறப்பு துணை ஆட்சியர்   <br>(சமூக பாதுகாப்புத் திட்டம்),<br>மாவட்ட ஆட்சியர் அலுவலகம், <br> <?php echo $_SESSION['districtname'];?>. </b></font></td> 
	  
	  
	 
	 <td class="label"><font size=2><?php echo $initial; ?>
	 <? if($initial!="") 
	 	{ ?>. <? } ?> <?php echo $pet_name;?>,
	 <? if($father_husband_name!="")
	 	 { ?><br>(த/க பெயர் <?php echo $father_husband_name; ?>), <? } ?>
		   <?php echo $door_no;?>,&nbsp;<?php echo $street;?>,
		   <? if($hamlet!="") { ?><?php echo $hamlet;?>, <? } ?>
	       <? if (($desc_taluk!="") or ($desc_vige)) { ?>
	      <?php echo $desc_taluk;?>,&nbsp;<?php echo $desc_vige;?>, <? } ?>
	
	      <?php echo $desc_dist;?>-<?php echo $pincode;?>. </font></td><td></td></tr> 
	   <tr><td>&nbsp;</td><td></td></tr>
	   <tr><td>&nbsp;</td><td></td></tr>
	 <tr> 
	 <td width=70 class="label"><font size=1><b>அன்புடையீர்,</b></font></td> 
	 <td width=300 class="label"><font size=1><b> </b></font></td> </tr>
	 <tr><td>&nbsp;</td><td></td></tr>
     <tr>  
    <td colspan="4" class="label"><font size=1><b>பொருள்:- &nbsp;&nbsp;&nbsp;&nbsp;</b></font><font size=1><b>தங்களது <? echo $receipt_date; ?>&nbsp;&nbsp;தேதியிட்ட  மனு எண் <? echo $pet_no; ?> தொடர்பாக.
 </b></font></td></tr>
 
 <tr><td>&nbsp;</td><td></td></tr>
  
     <tr> 
	 <td colspan="4" class="label"> <font size=1><b> </b> 
	   <p> &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; தங்களால் மாவட்ட ஆட்சித் தலைவரிடம்  அளிக்கப்பட்ட <? echo $receipt_date; ?>&nbsp;&nbsp; <br> தேதியிட்ட மனு &nbsp;எண் &nbsp;&nbsp
	   <? echo $pet_no; ?>, &nbsp;&nbsp;<font size=1 color="#990000"><? echo $officer; ?> </font> அலுவலரால் பரிசீலிக்கப்பட்டு, &nbsp;அதன் &nbsp;மீது&nbsp;&nbsp; எடுக்கப்பட்ட &nbsp;&nbsp;  நடவடிக்கைக்கான &nbsp; &nbsp;&nbsp; பதில் &nbsp;&nbsp;&nbsp;கீழே &nbsp;&nbsp;கொடுக்கப்பட்டுள்ளது.</p></font>	    </td> 
	  </tr>
	  
	   
	 <tr> 
	 <? 
	  if($_SESSION['Language']=='E')
	  	$grvdesc=$rowRes[9];
	  else
	  $grvdesc=$rowRes[2];
	 ?>
	 <td colspan="4" class="label"> <font size=1 color="#990000"> <p> <? echo $remarks; ?>   </p></font> </td> </tr>
	 <tr><td>&nbsp;</td></tr>
		
	 <tr> 
	 <td colspan="3" class="label"><font size=1><b>நன்றி,</b></font><font size=1><b> </b></font></td>
	 </tr>
	 
	 <tr> 
	 <td></td>
	 <td colspan="3" class="label" align="right"><font size=1><b>தங்கள் உண்மையுள்ள,</b></font><font size=1><b> </b></font></td>
	 </tr>
	 <tr><td>&nbsp;</td></tr> 
	 <tr><td>&nbsp;</td></tr> 
	 
	  <tr> 
	 <td colspan="2" class="label" align="right"> </td>
	 </tr>
	  
	
	 <tr> 
	 <td  class="label" align="left" width="30%"><p><font size=1>இடம்:</font> &nbsp;<font size=1> <? echo $_SESSION['districtname']; ?></font></td>
	 <td  colspan="2" align="right" class="label"><p><font size=1>அலுவலரின் கையொப்பம்</font></p></td>
	 </tr>
	 
	  <tr> 
	 <td  class="label" align="left"><p><font size=1><? echo $_SESSION['date']; ?>:</font>&nbsp;&nbsp; <font size=1><? echo $date; ?></font> </td>
	  <td colspan="2" class="label" align="right"><p><font size=1> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;சிறப்பு துணை ஆட்சியர்&nbsp;&nbsp;<br>(சமூக பாதுகாப்புத் திட்டம்)</font></p></td>
	 </tr>
	  <tr >
			            <td align="center" colspan=7  height="31"><input name="btn2" type="button" class="butn" id="btn2" value="<?php echo $_SESSION['print'];?>"  onclick="check();window.print();"/></td>
					  </tr>
	 </table> 
 
 	 </th> 
	 <tr height=30> 
    <td width=200>&nbsp; </td> 
<? } ?>
	 <? //include("bottom.php");   ?>
	 </table> 
 
 
 
