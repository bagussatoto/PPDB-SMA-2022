<?php 
//* Easy Send SMS Provider
//* https://www.easysendsms.com
//* Change $ESSusername, $ESSpassword, $ESSsender
//* need to add to function runner_post_request following code:
//* $options = array(
//* ...
//* CURLOPT_SSL_VERIFYHOST => false,
//* CURLOPT_SSL_VERIFYPEER => false);

$ESSusername = "username";
$ESSpassword = "password";
$ESSsender = "sender";

function runner_sms($number, $message, $parameters = array())
{
	global $ESSusername, $ESSpassword, $ESSsender;

	if ( !isset($parameters["to"]) )
		$parameters["to"] = $number;

	if ( !isset($parameters["text"]) )
		$parameters["text"] = $message;

	$parameters["from"] = $ESSsender;

	$url = "https://www.easysendsms.com/sms/bulksms-api/bulksms-api";

	$headers = array();
	$parameters["user"] = $ESSusername;
	$parameters["password"] = $ESSpassword;
	$parameters["type"] = 0;

	
	$certPath = getabspath('include/cacert.pem');	

	$result = array();
    $result["success"] = false;

	$response = runner_post_request($url, $parameters, $headers, $certPath);
	if ( !$response["error"] )
	{
	    $result["response"] = $response["content"];
	    if ( substr($result["response"],0,2) == "OK" )
	    	$result["success"] = true;
	    else
	    	$result["error"] = "EasySendSMS error: " . $result["response"];
	}
	else
	{
		$result["error"] = $response["error"];
	}

	return $result;
}

?>