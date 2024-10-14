<?Php date_default_timezone_set('Asia/Calcutta');
error_reporting(0);

// $host        = "host=10.236.226.113";
$host        = "host=localhost";
// $host        = "host=10.163.0.195";
$port        = "port=5432";
$dbname      = "dbname=tnrb_visitors";
$credentials = "user=tnrb_visitors password='TNraj$24#Visitor'";

$db = pg_connect("$host $port $dbname $credentials");
if (!$db) {
	die("Connection failed: ");
	exit;
}
session_start();
