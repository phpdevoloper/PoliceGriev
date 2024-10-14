<?php
// Starting the session so we could access the session variables
session_start();

// Including our mp3 stitching class
include_once('stitch_mp3.php');

// The captcha word we are making the audio for
$captcha = $_SESSION['key3'];

// Getting the length of the word
$length = strlen($captcha);

// Initiating the MP3 file generation
if($length > 0)
{
    $mp3 = new stitch_mp3('sounds/' . substr(strtoupper($captcha), 0, 1) . '.mp3');
}

// Making sure we got this going to the browser (and not as a download)
$mp3->inline = 1;

// Appending the other files to the end of the previous file
for($c = 1; $c < $length; $c++)
{
    $mp3->append_mp3('sounds/' . substr(strtoupper($captcha), $c, 1) . '.mp3');
}

// Output
$mp3->output(md5($captcha . time()) . '.mp3');
?>