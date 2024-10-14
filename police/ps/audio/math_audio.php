<?php
// Starting the session so we could access the session variables
session_start();

// Including our mp3 stitching class
include_once('./stitch_mp3.php');

// The captcha word we are making the audio for
$captcha = $_SESSION['captcha_q'];

// Getting the length of the captcha
$length = strlen($captcha);

$prev_char = null;

// Stitching the MP3 files together
for($c = 0; $c < $length; $c++)
{
    // Current character we are working with
    $char = substr($captcha, $c, 1);
	
    // Checking to make sure we don't do anything with the parenthesis
    if(($char != '(') && ($char != ')'))
    {
        // If previous was an open parenthesis, then we are saying negative (instead of minus; as only negative numbers have parenthesis around them)
        if($prev_char == '(')
        {
            $char = '(-';
        }
        
        // Initiating the first character
        if(!isset($mp3))
        {
            $mp3 = new stitch_mp3('../sounds/' . $char . '.mp3');
        }
        else
        {
            $mp3->append_mp3('../sounds/' . $char . '.mp3');
        }
    }
	
	$prev_char = $char;
}

// Making sure we got this going to the browser (and not as a download)
$mp3->inline = 1;

// Output
$mp3->output(md5($captcha . time()) . '.mp3');
?>