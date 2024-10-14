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

$designId = $userProfile->getDept_desig_id();
$roleId = $userProfile->getDesig_roleid();

if($_SESSION['lang']=='T'){
?>
<style>
.dropdown .dropbtn{
	font-size: 11px !important;
}
.topnav a {
	font-size: 11px !important;
}
</style> 
<?php
}
?>
<link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<?php 
	//Petiton Processing
	$sql="SELECT distinct a.dept_desig_role_id, '' menu_item_link, 
	a.ml1_id, b.ml1_name, b.ml1_tname, NULL mlp_id, b.major_usr_type_id, b.ordering
	FROM menu_role_desig_role_vw a
	INNER JOIN menu_level_1 b ON b.ml1_id = a.ml1_id
	WHERE b.enabling AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_2 c where c.ml1_id = a.ml1_id))
	AND a.menu_item_link is not null AND a.dept_desig_role_id=".$roleId." and a.ml1_id=1
	ORDER BY b.ordering";

	$rs=$db->query($sql);
	foreach($rs as $row){
		$menu_name=$row["ml1_name"];
		$menu_tname=$row["ml1_tname"];
		if($_SESSION["lang"]=='E'){
			$menu_name=$menu_name;
		}else{
			$menu_name=$menu_tname;	
		}
	}
	// Reports
	$sql="SELECT distinct a.dept_desig_role_id, '' menu_item_link, 
	a.ml1_id, b.ml1_name, b.ml1_tname, NULL mlp_id, b.major_usr_type_id, b.ordering
	FROM menu_role_desig_role_vw a
	INNER JOIN menu_level_1 b ON b.ml1_id = a.ml1_id
	WHERE b.enabling AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_2 c where c.ml1_id = a.ml1_id))
	AND a.menu_item_link is not null AND a.dept_desig_role_id=".$roleId." and a.ml1_id=2
	ORDER BY b.ordering";
	$rs=$db->query($sql);
	$count_r=$rs->rowCount();
	foreach($rs as $row){
		$menu_name2=$row["ml1_name"];
		$menu_tname2=$row["ml1_tname"];
		if($_SESSION["lang"]=='E'){
			$menu_name2=$menu_name2;
		}else{
			$menu_name2=$menu_tname2;	
		}
	}
	// System Administration
	$sql="SELECT distinct a.dept_desig_role_id, '' menu_item_link, 
	a.ml1_id, b.ml1_name, b.ml1_tname, NULL mlp_id, b.major_usr_type_id, b.ordering
	FROM menu_role_desig_role_vw a
	INNER JOIN menu_level_1 b ON b.ml1_id = a.ml1_id
	WHERE b.enabling AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_2 c where c.ml1_id = a.ml1_id))
	AND a.menu_item_link is not null AND a.dept_desig_role_id=".$roleId." and a.ml1_id=3
	ORDER BY b.ordering";
	$rs=$db->query($sql);
	$count_r=$rs->rowCount();
	foreach($rs as $row){
		$menu_name_a=$row["ml1_name"];
		$menu_tname_a=$row["ml1_tname"];
		if($_SESSION["lang"]=='E'){
			$menu_name_a=$menu_name_a;
		}else{
			$menu_name_a=$menu_tname_a;	
		}
	}			 
				 
?>
<style>
 /* Add a black background color to the top navigation */
.topnav {
  background-color: #8d4747;
  overflow: hidden;
}

/* Style the links inside the navigation bar */
.topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 3px 16px;
  text-decoration: none;
  font-size: 15px;
  font-weight: bold;
}

/* Add an active class to highlight the current page */
.active {
  background-color: #04AA6D;
  color: white;
}

/* Hide the link that should open and close the topnav on small screens */
.topnav .icon {
  display: none;
}

/* Dropdown container - needed to position the dropdown content */
.dropdown {
  float: left;
  overflow: hidden;
}

/* Style the dropdown button to fit inside the topnav */
.dropdown .dropbtn {
	font-size: 15px;
	border: none;
	outline: none;
	color: white;
	padding: 3px 16px;
	background-color: inherit;
	font-family: Times new Roman, verdana, Helvetica, sans-serif;
	margin: 0;
	font-weight: bold;
	
}

/* Style the dropdown content (hidden by default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
  list-style-type: none;
}

/* Style the links inside the dropdown */
.dropdown-content a {
  float: none;
  color: #fff;
  padding: 8px 4px;
  text-decoration: none;
  display: block;
  text-align: left;
  border: 2px solid #fff;
  font-size: 13px;
}

/* Add a dark background on topnav links and the dropdown button on hover */
.topnav a:hover, .dropdown:hover .dropbtn {
  background-color: #bc6f6f;
  color: white;
}

/* Add a grey background to dropdown links on hover */
.dropdown-content a:hover {
  background-color: #bc6f6f;
  color: black;
}

/* Show the dropdown menu when the user moves the mouse over the dropdown button */
.dropdown:hover .dropdown-content {
  display: block;
  background: #8d4747;
}

/* When the screen is less than 600 pixels wide, hide all links, except for the first one ("Home"). Show the link that contains should open and close the topnav (.icon) */
@media screen and (max-width: 600px) {
  .topnav a:not(:first-child), .dropdown .dropbtn {
    display: block;
  }
  .topnav a.icon {
    float: right;
    display: block;
  }
}

/* The "responsive" class is added to the topnav with JavaScript when the user clicks on the icon. This class makes the topnav look good on small screens (display the links vertically instead of horizontally) */
@media screen and (max-width: 600px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive a.icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
  .topnav.responsive .dropdown {float: none;}
  .topnav.responsive .dropdown-content {position: relative;}
  .topnav.responsive .dropdown .dropbtn {
    display: block;
    width: 100%;
    text-align: left;
  }
} 
</style>
<div class="topnav" id="myTopnav">
<div class="dropdown">
<button class="dropbtn"><?php echo $menu_name; //Petiton Processing ?><i class="fa fa-sort-asc" aria-hidden="true"></i>
</button>
<div class="dropdown-content">
<?php 

	// Petition processing sub menu
	$sql = "SELECT  a.menu_item_link, b.ml2_name, b.ml2_tname
	FROM menu_role_desig_role_vw a
	INNER JOIN menu_level_2 b ON b.ml2_id = a.ml2_id
	WHERE b.enabling=TRUE AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_3 c where c.ml2_id = a.ml2_id))
	AND b.ml1_id=1 AND a.dept_desig_role_id=".$roleId." ORDER BY b.ordering";
	$rs=$db->query($sql);
	foreach($rs as $row){
		$menu_name3=$row["ml2_name"];
		$menu_tname3=$row["ml2_tname"];
		$menu_link3=$row["menu_item_link"];
		if($_SESSION["lang"]=='E'){
			$menu_name3=$menu_name3;
		}else{
			$menu_name3=$menu_tname3;	
		}
		print("<li><a href='".$menu_link3."'class='b-newpage' value='".$row["ml2_name"]."' >".$menu_name3."</a></li>");
	}
?>
</div>
</div>
<div class="dropdown"><?php if($count_r!=0){?>
<button class="dropbtn"><?php echo $menu_name2; // Report?>
<i class="fa fa-sort-asc"></i>
</button>
<?php }?>
<div class="dropdown-content">
<?php 

	// Report Sub Menu
	$sql = "SELECT  a.menu_item_link, b.ml2_name, b.ml2_tname
	FROM menu_role_desig_role_vw a
	INNER JOIN menu_level_2 b ON b.ml2_id = a.ml2_id
	WHERE b.enabling=TRUE AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_3 c where c.ml2_id = a.ml2_id))
	AND b.ml1_id=2 AND a.dept_desig_role_id=".$roleId." ORDER BY b.ordering";
	$rs=$db->query($sql);
	foreach($rs as $row){
		$menu_name5=$row["ml2_name"];
		$menu_tname5=$row["ml2_tname"];
		$menu_link5=$row["menu_item_link"];
		if($_SESSION["lang"]=='E'){
			$menu_name5=$menu_name5;
		}else{
			$menu_name5=$menu_tname5;	
		}
		print("<li><a href='".$menu_link5."'class='b-newpage' value='".$row["ml2_name"]."' >".$menu_name5."</a></li>");
	}
?>
</div>
</div>
<div class="dropdown"><?php if($count!=0){?>
<button class="dropbtn"><?php echo $menu_name_a; // System Administration?>
<!--i class="fa fa-caret-down"></i-->
<i class="fa fa-sort-asc"></i>
</button>
<?php }?>
 <div class="dropdown-content">
<?php 
	$sql = "SELECT  a.menu_item_link, b.ml2_name, b.ml2_tname
	FROM menu_role_desig_role_vw a
	INNER JOIN menu_level_2 b ON b.ml2_id = a.ml2_id
	WHERE b.enabling=TRUE AND (a.menu_item_link IS NOT NULL OR EXISTS(SELECT * FROM menu_level_3 c where c.ml2_id = a.ml2_id))
	AND b.ml1_id=3 AND a.dept_desig_role_id=".$roleId." ORDER BY b.ordering";
	$rs=$db->query($sql);$count=$rs->rowCount();
	foreach($rs as $row){
		$menu_name4=$row["ml2_name"];
		$menu_tname4=$row["ml2_tname"];
		$menu_link4=$row["menu_item_link"];
		if($_SESSION["lang"]=='E'){
			$menu_name4=$menu_name4;
		}else{
			$menu_name4=$menu_tname4;	
		}
		if($count!=0){
			print("<li><a href='".$menu_link4."'class='b-newpage' value='".$row["ml2_name"]."' >".$menu_name4."</a></li>");
		}
	}

?>	
</div>
</div>
<?php 
	if($_SESSION['lang']=='E'){
		$desc='Change Password'; 
	}
	else if($_SESSION['lang']=='T') {
		$desc = 'கடவுச்சொல்லை மாற்றுதல்';
	}else{
	$	desc='Change Password';
	}
	
	if($_SESSION['lang']=='E'){
		$desc1='Help Centre';
	}else{
		$desc1 = 'உதவி மையம்';
	}
	
	if($_SESSION['lang']=='E'){
		$desc2='Logout';
	}else{
		$desc2='வெளியேறுதல்';
	}
?>
<a href="change_password.php"><?php echo $desc; ?></a>
<a href="downloads.php"><?php echo $desc1; ?></a>
<a href="logout.php"><?php echo $desc2; ?></a>
<a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="myFunction()">&#9776;</a>
</div> 
<script>
/* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
function myFunction() {
	//alert("gggggggg");
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
} 
</script>