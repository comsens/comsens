<?php

phpinfo();
exit;

include_once('./_common.php');

define ( 'API_KEY', "ABQIAAAA8D5V9s7ay_iqldwtIMO8MBR9aA0Kgb-pNVF80hdyC9JTJXYg9g" );

define ( 'PROTOCOL_VER', '3.0' );

define ( 'CLIENT', 'checkURLapp' );

define ( 'APP_VER', '1.0' );

 
?>
 
 
 
<!--제목/버튼부 시작--->
	<TABLE border=0 cellSpacing=0 cellPadding=0 width=100% align=center>
	<TBODY>
	<TR>
		<TD height=34 background="./img/center_title_bg.gif" >
			<TABLE border=0 cellSpacing=0 cellPadding=0 width=300>
		  <TBODY>
			<TR>	
			<TD height=34 width=13>&nbsp;</TD>
			<TD height=34><SPAN class=ooi>Safe Browsing 탐지 시스템</SPAN></TD></TR></TBODY>
			</TABLE>
		</TD>
		<TD vAlign=bottom background="./img/center_title_bg.gif" width="33%" align=right>&nbsp;</TD>
	</TR>
	</TBODY>
	</TABLE><!--제목/버튼부 끝--->
<TABLE border=0 cellSpacing=0 cellPadding=0 width=100%>
<TR>

<TD class=tds8 vAlign=top >
<DIV style="padding-left:5px; width:100%; OVERFLOW-Y: hidden; OVERFLOW: auto" 
class=boxBig2>
<textarea cols="80" rows="5" name="test31" id="test31" title="higheditor_simple" style="width:90%;height:500px;">

<?php


$sql = " select *  from g5_write_openurl   ";
$result = sql_query($sql);

$k = 0;

while ($row = sql_fetch_array($result))
{
  
	
 
echo "".$row[wr_link2]."----";
 

$checkMalware = send_response ( $row[wr_link2] );
$checkMalware = json_decode($checkMalware, true);
//print_R($checkMalware);

$malwareStatus = $checkMalware['status'];
echo $malwareStatus;


echo "-----";
if($malwareStatus!=204){
	
	$webhookurl="https://nexus-mink.ncsoft.com/webhook/f5fe18b4-4317-459f-9db9-2aac42b017f1";
	$data = $row[wr_link2]." 세이프 브라우징 탐지 ".$checkMalware['data']." ".$checkMalware['message'];
	$webhook = $webhookurl . "?text=".urlencode($data);
	//echo $webhook;
	getFromUrl($webhook);


}else{
}
 
			
echo $checkMalware['data'];

echo "----";

			
echo $checkMalware['message'];

 
echo "\n";


 
			


 
 

}//contents

		 
 
 ?>
</textarea>

<div>
 </TD></TR>









<?php




/**

 * Defining constants for the CGI parameters of the HTTP GET Request

 * */


/**

 * Function for sending a HTTP GET Request

 * to the Google Safe Browsing Lookup API

 */

function get_data($url) {

        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_URL, $url );

        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );

        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );

        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );

        

        $data = curl_exec ( $ch );

        $httpStatus = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );

        curl_close ( $ch );

        

        return array (

                        'status' => $httpStatus,

                        'data' => $data 

        );

}



/**

 * Function for analyzing and paring the

 * data received from the Google Safe Browsing Lookup API 

 */

function send_response($input) {

        if (! empty ( $input )) {

                $urlToCheck = urlencode ( $input );

                $url = 'https://sb-ssl.google.com/safebrowsing/api/lookup?client=' . CLIENT . '&apikey=' . API_KEY . '&appver=' . APP_VER . '&pver=' . PROTOCOL_VER . '&url=' . $urlToCheck;
				//echo $url ;
                

                $response = get_data ( $url );
				//print_r(  $response);

                

                if ($response ['status'] == 204) {

                        return json_encode ( array (

                                        'status' => 204,
											'data' => "clean",

                                        'checkedUrl' => $urlToCheck,

                                        'message' => 'The website is not blacklisted and looks safe to use.' 

                        ) );

                } elseif ($response ['status'] == 200) {

                        return json_encode ( array (

                                        'status' => 200,
										'data' => $response ['data'],

                                        'checkedUrl' => $urlToCheck,

                                        'message' => 'The website is blacklisted as ' . $response ['data'] . '.' 

                        ) );

                } else {

                        return json_encode ( array (

                                        'status' => 501,
										'data' => $response ['data'],

                                        'checkedUrl' => $urlToCheck,

                                        'message' => 'Something went wrong on the server. Please try again.' 

                        ) );

                }

        } else {

                return json_encode ( array (

                                'status' => 401,

                                'checkedUrl' => '',

                                'message' => 'Please enter URL.' 

                ) );

        }

        ;

}


?>