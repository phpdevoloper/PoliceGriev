<?php
// Code 1 with http call and query string
// Error: cURL Error #:couldn't connect to host
//https://www.gyrocode.com/articles/php-urlencode-vs-rawurlencode/

header('Content-Type: text/html; charset=UTF-8');
//header('Content-Type: text/html; charset=ISO-8859-1');
//header('Content-type: application/xml; charset=UTF-8');

function SMS($strMobileNo, $strContent,$unicode,$ct_id) {

        $strContent=rawurlencode($strContent);
        //$strContent=urlencode($strContent);
        //$unicode="2"; 2 for Tamil and 0 for English

        $user_name="tnega_htu";
        $pass_word="tnega@123";
        
		//Commented as new syntax to be from 29/01/2021
		//$sender_id="TNGOVT";
		//$route_id="DLT_SERVICE_IMPLICT";
		
		//From 29/01/2021
        $sender_id="DGMATE";    
        $route_id="DLT_GOVT";    
		
        $camp_name="tnega_u";
        $dlt_tm_id="1001096933494158";  //1001096933494158
        //$dlt_ct_id='"1007665446161588842", "1007768742067827833"';
        $dlt_ct_id=$ct_id;
        $dlt_pe_id="1301157259712022912"; //1301157259712022912

        $airtel_url="http://digimate.airtel.in:15181/BULK_API/SendMessage";


        /* new syntax 22/09/20  http://digimate.airtel.in:15181/BULK_API/SendMessage?loginID=tnega_htu&password=tnega@123&mobile=7012478554&text=This is a testing message to test single short SMS on AIRTEL DLT&senderid=DGMATE&DLT_TM_ID=1001096933494158&DLT_CT_ID=&DLT_PE_ID=&route_id=DLT_SERVICE_IMPLICT&Unicode=0&camp_name=tnega_u

        // old syntax 22/09/20  $url=$airtel_url."?loginID=".$user_name."&password=".$pass_word."&mobile=".$strMobileNo."&text=".$strContent."&senderid=".$sender_id."&route_id=".$route_id."&Unicode=".$unicode."&camp_name=".$camp_name;

        */

        // new syntax 22/09/20
        $url=$airtel_url."?loginID=".$user_name."&password=".$pass_word."&mobile=".$strMobileNo."&text=".$strContent."&senderid=".$sender_id."&DLT_TM_ID=".$dlt_tm_id."&DLT_CT_ID=&DLT_PE_ID=".$dlt_pe_id."&route_id=".$route_id."&Unicode=".$unicode."&camp_name=".$camp_name;
        
		// new syntax 07/01/2021
		$url=$airtel_url."?loginID=".$user_name."&password=".$pass_word."&mobile=".$strMobileNo."&text=".$strContent."&senderid=".$sender_id."&DLT_TM_ID=".$dlt_tm_id."&DLT_CT_ID=".$dlt_ct_id."&DLT_PE_ID=".$dlt_pe_id."&route_id=".$route_id."&Unicode=".$unicode."&camp_name=".$camp_name;
		
		// new syntax 29/01/2021
		//$airtel_url="http://digimate.airtel.in:15181/BULK_API/SendMessage";
		//$url=$airtel_url."loginID=".$user_name."&password=".$pass_word."&mobile=".$strMobileNo."&text=".$strContent"&senderid=".$sender_id."&DLT_TM_ID=".$dlt_tm_id."&DLT_CT_ID=&DLT_PE_ID=".$dlt_pe_id."&route_id=".$route_id."&Unicode=".$unicode."&camp_name=".$camp_name; 
		
//http://digimate.airtel.in:15181/BULK_API/SendMessage?loginID=tnega_htu&password=tnega@123&mobile=98402977896&text=Test+message&senderid=TNDEGA&DLT_TM_ID=1001096933494158&DLT_CT_ID=&DLT_PE_ID=1301157259712022912&route_id=DLT_GOVT&Unicode=2&camp_name=tnega_u

/*
Current: 
Dear Sir,
            Please pass the CT_ID as empty now and try using the below URL and it will be working fine.
           
curl -X POST 'http://digimate.airtel.in:15181/BULK_API/SendMessage?loginID=tnega_htu&password=tnega@123&mobile=9962744106&text=Test+message&senderid=TNDEGA&DLT_TM_ID=1001096933494158&DLT_CT_ID=&DLT_PE_ID=1301157259712022912&route_id=DLT_GOVT&Unicode=2&camp_name=tnega_u';

http://digimate.airtel.in:15181/BULK_API/SendMessage?loginID=tnega_htu&password=tnega@123&mobile=98402977896&text=Test+message&senderid=TNDEGA&DLT_TM_ID=1001096933494158&DLT_CT_ID=&DLT_PE_ID=1301157259712022912&route_id=DLT_GOVT&Unicode=2&camp_name=tnega_u

http://digimate.airtel.in:15181/BULK_API/SendMessage?loginID=tnega_htu&password=xxxxx&mobile=9188185052&text=Test message &senderid=DGMATE&DLT_TM_ID=1001096933494158&DLT_CT_ID=&DLT_PE_ID=&route_id=DLT_GOVT&Unicode=0&camp_name=tnega_u  
*/
//echo $url;

        define("APIURL", $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, APIURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch); //result from mobile seva server
//echo '<pre>';
//print_r($response);
//echo '</pre>';
        if(curl_error($ch)){
                //echo 'Curl error: ' . curl_error($post);
        }
        curl_close($ch);
}
?>

