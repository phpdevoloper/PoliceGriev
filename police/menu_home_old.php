<?php
session_start(); 

if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
	echo "<script> alert('Timed out. Please login again');</script>";	
	echo '<script type="text/javascript">window.location="logout.php"</script>';
	exit;
}

include("db.php");
if ($showTicker == "1") {
	$top = 111;
} else {
	$top = 92;
}
?>
<?php 
$designId = $userProfile->getDept_desig_id();
$roleId = $userProfile->getDesig_roleid();

if($_SESSION['lang']=='E'){
?>
<style>
#usr_detail{
	height:24px !important;
}
</style> 
<?php
}
?>

<?PHP
 
$menuscript="";
 $menuscript .="fixMozillaZIndex=true; //Fixes Z-Index problem  with Mozilla browsers but causes odd scrolling problem, toggle to see if it helps
									_menuCloseDelay=500;
									_menuOpenDelay=150;
									_subOffsetTop=2;
									_subOffsetLeft=-2;
							
									with(submenuStyle=new mm_style()){
									align=\"center\";
									fontfamily=\"inherit\";
									fontsize=\"75%\";
									fontstyle=\"normal\";
									fontweight=\"bold\";
									keepalive=\"true\";
									itemheight=18;
									itemwidth=165;
									offbgcolor=\"#FD0303\";
									offcolor=\"#ffffff\";
									onbgcolor=\"#FD0303\";
									oncolor=\"#ffffff\";
									ondecoration=\"underline\";
									outfilter=\"fade(duration=0.5)\";
						  overfilter=\"Fade(duration=0.2);Alpha(opacity=90);Shadow(color=#777777', Direction=135, Strength=5)\";
									padding=4;
									rawcss=\"padding-left:10px;padding-right:10px;\";
									separatorsize=5;
									}
									
									with(menuStyle=new mm_style()){
									styleid=1;
									bordercolor=\"#ffffff\";
									borderstyle=\"solid\";	
									borderwidth=0;
									fontfamily=\"inherit\";
									fontsize=\"90%\";
									fontstyle=\"normal\";
									fontweight=\"bold\";
									headerbgcolor=\"#ffffff\";
									headercolor=\"#ffffff\";
																		 
									imagepadding=10;
									offbgcolor=\"#8D4747\";
									itemheight=18;
									itemwidth=160;
									offcolor=\"#ffffff\";
									onbgcolor=\"#C58B8B\";
									oncolor=\"#ffffff\";
									outfilter=\"fade(duration=0.5)\";
							overfilter=\"Fade(duration=0.2);Alpha(opacity=90);Shadow(color=#777777', Direction=135, Strength=5)\";
							overimage=\"images/orangedots.gif\";
							        padding=3;
									separatorsize=3;
									subimage=\"images/arrow.gif\";
									subimagepadding=2;
									}
									
									with(milonic=new menuname(\"Main Menu\")){
									alwaysvisible=1;
									left=0;
								     top=".$top.";
									  // top=92;
									orientation=\"horizontal\";
									style=menuStyle;";

	$menu_lvl_1_sql= "SELECT distinct a.dept_desig_role_id, '' menu_item_link, 
	a.ml1_id, b.ml1_name, b.ml1_tname, NULL mlp_id, b.major_usr_type_id, b.ordering
	FROM menu_role_desig_role_vw a
	INNER JOIN menu_level_1 b ON b.ml1_id = a.ml1_id
	WHERE b.enabling AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_2 c where c.ml1_id = a.ml1_id))
	AND a.menu_item_link is not null AND a.dept_desig_role_id=".$roleId." 
	ORDER BY b.ordering
	";

$menu_role_1_rs=$db->query($menu_lvl_1_sql);
if(!$menu_role_1_rs)
{
	print_r($db->errorInfo());
	exit;
}
while($menu_row=$menu_role_1_rs->fetch(PDO::FETCH_BOTH))
{ 
	if($_SESSION['lang']=='E'){
	$desc=$menu_row['ml1_name'];
	}else{
	$desc=$menu_row['ml1_tname'];
	}
	$url=$menu_row['menu_item_link'];
	$m_id=$menu_row['ml1_id'];
	$menuscript.="aI(\"text=$desc;showmenu=$m_id;url=$url;\");";
}
 if($_SESSION['lang']=='E'){
$desc='Change Password';
}else{
$desc = 'கடவுச்சொல்லை மாற்றுதல்';
}
$url='change_password.php';
$m_id='';
$menuscript.="aI(\"text=$desc;showmenu=$m_id;url=$url;\");"; 

 if($_SESSION['lang']=='E'){
$desc='Help Centre';
}else{
$desc = 'உதவி மையம்';
}
$url='downloads.php';
$m_id='';
$menuscript.="aI(\"text=$desc;showmenu=$m_id;url=$url;\");"; 

if($_SESSION['lang']=='E'){
$desc='Logout';
}else{
$desc='வெளியேறுதல்';
}
$url='logout.php';
$m_id='';
$menuscript.="aI(\"text=$desc;showmenu=$m_id;url=$url;\");";
$menuscript.="  }";

//*** II menu
	$menu_lvl_1_sql= "SELECT a.dept_desig_role_id, a.menu_item_id, a.menu_item_link, a.ml1_id, b.ml1_name, 
		b.ml1_tname, NULL mlp_id, b.major_usr_type_id
		FROM menu_role_desig_role_vw a
		INNER JOIN menu_level_1 b ON b.ml1_id = a.ml1_id
		WHERE b.enabling AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_2 c where c.ml1_id = a.ml1_id))
			AND a.menu_item_link is not null AND a.dept_desig_role_id=".$roleId;
			
			//echo  '<br><br><br><br>'.$menu_lvl_1_sql;
$menu_rs1=$db->query($menu_lvl_1_sql);
if(!$menu_rs1)
{
	print_r($db->errorInfo());
	exit;
}
while($menu_row1=$menu_rs1->fetch(PDO::FETCH_BOTH))
{
	if($_SESSION['lang']=='E'){
	$desc=$menu_row1['ml1_name'];
	}else{
	$desc=$menu_row1['ml1_tname'];
	}
	$url=$menu_row1['menu_item_link'];
	$m_id=$menu_row1['ml1_id'];
	$major_id=$menu_row1['major_usr_type_id'];
	
	
	// ******************************** SUBMENU *************************************** //
	$sub="".$desc."";
	$sub_item1="";
	
	$menuscript.="with(milonic=new menuname('$m_id')){
	style=menuStyle; ";

	//$menu_sql2= "select ml2_id,ml2_name,ml2_tname,menu_item_link from vw_menu_item where ml1_id='$m_id' order by ml2_id";
	$menu_sql2= "SELECT a.dept_desig_role_id, a.menu_item_id, a.menu_item_link, a.ml2_id, b.ml2_name, b.ml2_tname, b.ml1_id mlp_id, b.ordering
		FROM menu_role_desig_role_vw a
		INNER JOIN menu_level_2 b ON b.ml2_id = a.ml2_id
		WHERE b.enabling=TRUE AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_3 c where c.ml2_id = a.ml2_id))
		AND b.ml1_id=". $m_id ." AND a.dept_desig_role_id=".$roleId.
		" ORDER BY b.ordering";
//echo  '<br><br><br><br>'.$menu_sql2;
	$menu_rs2=$db->query($menu_sql2);
	if(!$menu_rs2)
	{
		print_r($db->errorInfo());
		exit;
	}
	
	while($menu_row2=$menu_rs2->fetch(PDO::FETCH_BOTH))
	{
		if($_SESSION['lang']=='E'){
		$sdesc=$menu_row2['ml2_name'];
		}else{
		$sdesc=$menu_row2['ml2_tname'];
		}
		//$sdesc=$menu_row2['ml2_name'];
		$surl=$menu_row2['menu_item_link'];
		$sm_id=$menu_row2['ml2_id'];
		if($surl!=""){
			$menuscript.="aI(\"text=$sdesc;url=$surl;\"); ";
		}
		else{
			$menuscript.="aI(\"text=$sdesc;showmenu=$scode;\");";  
		}
	} 
	$menuscript.=" } "; 
}//end of while	

$pathToCodeFiles="js/";
$file_milonicsrc="menu_milonic_src.js";
$file_mmenudom="menu_menudom.js";

echo "
<script src=\"$pathToCodeFiles$file_milonicsrc\" type=\"text/javascript\"></script>	
<script src=\"$pathToCodeFiles$file_mmenudom\" type=\"text/javascript\"></script> ";

?>
 
<div id="usr_detail" style="width:100%;">
<div id="menu" style="width:60%;float:left;" >
<?PHP
 
 echo "<script type=\"text/javascript\">";
 echo $menuscript.";drawMenus();</script>";
 
?>
</div>
<!--div id="usr_detail"  style="text-align: right;float:right;  padding-top: 1px; padding-right: 10px; border-style: hidden; font-weight: bold;width:40%;"> 
     
    <?php
if($_SESSION['lang']=='E')
	{ echo 'User';}else{ echo 'பயனர்'; }?> : 
	 <?php if ($userProfile->getOff_desig_emp_name() != '')
	 	  echo $userProfile->getOff_desig_emp_name() .';'; 
		  echo $userProfile->getDept_desig_name().', '.
		  (
		  ($_SESSION['lang']=='E')?
		  $userProfile->getOff_level_name().($userProfile->getOff_loc_name()==''?'':', '.$userProfile->getOff_loc_name())
		  :
		  nl2br ("\n".$userProfile->getOff_level_name().($userProfile->getOff_loc_name()==''?'':', '.$userProfile->getOff_loc_name()))
		  );?>
    
</div-->
</div>