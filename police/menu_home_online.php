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
//$designId = $userProfile->getDept_desig_id();

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
									
									
if($_SESSION['lang']=='E'){
$desc='Petition Entry';
}else{
$desc = 'மனுப் பதிவு';
}
$url='petition_detail_entry.php';
$m_id='';
$menuscript.="aI(\"text=$desc;showmenu=$m_id;url=$url;\");"; 

 if($_SESSION['lang']=='E'){
$desc='My Petition Status';
}else{
$desc = 'எனது மனுவின் நிலவரம்';
}
$url='online_petition_status.php';
$m_id='';
$menuscript.="aI(\"text=$desc;showmenu=$m_id;url=$url;\");"; 

if($_SESSION['lang']=='E'){
$desc='Acknowledment';
}else{
$desc = 'ஒப்புகைச் சீட்டு';
}
$url='online_acknowledment.php';
$m_id='';
$menuscript.="aI(\"text=$desc;showmenu=$m_id;url=$url;\");"; 

 if($_SESSION['lang']=='E'){
$desc='My Petition List';
}else{
$desc = 'எனது மனுக்களின் பட்டியல்';
}
$url='online_petition_list.php';
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
</div>