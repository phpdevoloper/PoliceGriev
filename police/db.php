<?php
error_reporting(0);
//define("HOST", "dbsrv"); // The host you want to connect to.
define("HOST", "localhost"); // The host you want to connect to.
//define("HOST", "10.163.30.9"); // The host you want to connect to.
define("USER", "postgres"); // The database username.
define("PASSWORD", "postgres"); // The database password. 
//define("DATABASE", "ed_gdp_appscan"); // The database name.  _app ed_ngdp
//define("DATABASE", "ed_gdp_test222"); // The database name. 
//define("DATABASE", "police_old"); // The database name. 
//define("DATABASE", "test222"); // The database name.
define("DATABASE", "police_db"); // The database name. 
//define("DATABASE", "police_test1"); // The database name. 
//define("PORT", "5432"); // The port no.
define("PORT", "5432"); // The port no.
define("PROJECT_NAME", "police");

try{
	$db = new PDO("pgsql:dbname=".DATABASE.";host=".HOST.";port=".PORT, USER, PASSWORD);
}
catch(PDOException $e ){
	die( $e->getMessage() );
}

//session constants
define("LOGIN_LVL", "LOGIN_LVL");

define("BOTTOM", "BOTTOM");//For petition processing to lower level office. This office have no subordinate offices.
define("NON_BOTTOM", "NON_BOTTOM");//Except BOTTOM level office level 

//Below constants are not used from 26/02/2014

define("LOGIN_DEO", "LOGIN_DEO");
define("LOGIN_RPT", "LOGIN_RPT");
define("ADMIN_ROLE", "ADMIN_ROLE");

//Petition processing user roles constants
define("STATE", "STATE");//Matches coordinate dept and coordinate design. user id based on the STATE LEVEL
define("TOP", "TOP");//Matches coordinate dept and execute design. user id based on the DISTRICT LEVEL
define("MIDDLE", "MIDDLE");//For petition processing office level belongs to TOP office and BOTTOM level office

//District & state level report user role constatns
define("STATE_RPT", "STATE_RPT");//Sys control mataches its co-ordinate dept only - all dept & state level.
define("TOP_RPT", "TOP_RPT");//Sys control mataches its co-ordinate dept only - all dept & district level.

//Dept. admin role constants
define("DEPT_STATE_ADMIN", "DEPT_STATE_ADMIN");//State level users SYS ADMIN - user creation enabled for his dept. only and state level
define("DEPT_DIST_ADMIN", "DEPT_DIST_ADMIN");//District level users SYS ADMIN - user creation at dist level for his dept. 

//Dept. report constants
define("DEPT_STATE_RPT", "DEPT_STATE_RPT");//State level users NOT SYS ADMIN - no user creation only report his dept. only
define("DEPT_DIST_RPT", "DEPT_DIST_RPT");//District level users NOT SYS ADMIN - only report at dist level for his dept. only

//Data entry operator's role constants
define("TOP_DEO", "TOP_DEO");//TOP level DEO
define("MIDDLE_DEO", "MIDDLE_DEO");//MIDDLE level DEO
define("BOTTOM_DEO", "BOTTOM_DEO");//BOTTOM level DEO

?>
