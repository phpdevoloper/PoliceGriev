<?php 
error_reporting(0);
ob_start();
session_start();
include("db.php");
//include("common_date_fun.php"); 

include_once 'common_lang.php';


if(!isset($_SESSION['USER_ID_PK']) || empty($_SESSION['USER_ID_PK'])) {
   ob_start();	
   echo "<script> alert('Timed out. Please login again');</script>";
   echo "<script type='text/javascript'> document.location = 'logout.php'; </script>";
   exit;
} 
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color:  #f2dfad;
  padding: 0px;
  line-height: 0px;
  border-bottom: 1px solid #fff;
}

.topnav a {
  float: left;
  display: block;
  color: red;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  border: 1px solid #C0C0C0;
  font-weight:bolder;
}

.topnav a:hover {
  background-color: #c3b091;
  color: black;
}

.topnav a.active {
  background-color: #4CAF50;
  color: white;
}

.topnav .icon {
  display: none;
}


@media screen and (min-width: 600px) {
#lim {
  border: 1px solid #C0C0C0;
}
}
@media screen and (max-width: 600px) {
  .topnav a:not(:first-child) {display: none;}
   .topnav nav.icon,.topnav a.icon{
    float: right;
    display: block;
	border:0px;
  }
  .topnav nav{
	  border:0px;
  }
}

@media screen and (max-width: 600px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
   // right: 0;
    top: 0;
  }
  .topnav.responsive a,nav {
    float: none;
    display: block;
    //text-align: left;
  }
}
</style>
</head>
<?php 
//include('online_header_submission.php');
//include('online_header_status.php');
?>
<body>

<div class="topnav" id="myTopnav">
<a href="javascript:void(0);" class="icon" onclick="myFunction()" style="float:left;">
    <i class="fa fa-bars" style="height:2px;float:left;"></i>
  
  <!--a href="welcome_online.php"><?php echo $lang['Home_LABEL_menu'] ?></a-->
  <a href="online_petition_detail_entry.php"><?php echo $lang['Petition_Entry_LABEL_menu'] ?></a>
  <a href="online_my_petition_status.php"><?php echo $lang['My_Petition_Status_LABEL_menu'] ?></a>
  <a href="online_acknowledment.php"><?php echo $lang['Acknowledgement_LABEL_menu'] ?></a>
  <a href="online_petition_list.php"><?php echo $lang['My_Petition_List_LABEL_menu'] ?></a>
  <a href="index.php"><?php echo $lang['Logout_LABEL_menu'] ?></a>
    

  <nav id='lim' style="float: right;display: block;color: red;font-weight:bolder;text-align: center;padding: 14px 16px;
font-size: 17px;"><?php echo $lang['Welcome_User_Label']; ?> : <?php echo $_SESSION['USER_ID_PK'];?></nav>
</div>

<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>

</body>
</html>

