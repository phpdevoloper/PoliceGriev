<?php 
$url='http://locahost/police/status/getPetStatus.php?pet_id=12334';
include('qrlib.php'); 
QRcode::png($url); 

?>