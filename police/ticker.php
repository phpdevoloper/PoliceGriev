<?php
$showTicker = true;
$t=time();
if (date("Y-m-d-H-i",$t) > '2020-01-14-18-00'){
	$showTicker = false;
}
?>

<style>
.blink {
  animation: blink-animation 1s steps(5, start) infinite;
  -webkit-animation: blink-animation 1s steps(5, start) infinite;
}
@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
</style>

<?php
if ($showTicker) {
?>

<!--<b>Server will not be available on 09/12/2019 between <font color=#ff3300 class="blink">20:00 hrs and 22:00 hrs</font> due to system maintenance activity. Inconvenience is regretted.</b></marquee>-->

<div  style="font-size: 110%;position: relative;top:0;bottom: 2;left: 0;right: 0; margin: auto; text-align:center; ">
<marquee BGCOLOR=#f4d142 scrollamount=2 behavior="alternate" WIDTH=1500  >


<b>This Portal will not be available between <font color=#ff3300 class="blink"> today, 14-01-2020, 5.30 PM and tomorrow, 15-0102020, evening</font> for technical maintenance. Inconvenience is regretted.</b></marquee>
<!--<b><font color=#ff3300 class="blink">Kindly do not enter any petition or processing data now. The system is currently being tested for maintenance.</font></b></marquee>-->
<!--<b><font color=#ff3300 class="blink">Please save and close the application immediately. The server will be down for some time.</font></b></marquee>-->

</div>

<?php } ?>

 