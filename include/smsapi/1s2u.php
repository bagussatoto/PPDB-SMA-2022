<?php 
//* International Bulk SMS Messaging Provider
//* https://1s2u.com/
//* Change $IBusername, $IBpassword, $IBipcl, $IBsender
//* need to add to function runner_post_request following code:
//* $options = array(
//* ...
//* CURLOPT_SSL_VERIFYHOST => false,
//* CURLOPT_SSL_VERIFYPEER => false);
$IBusername = "username";
$IBpassword = "password";
$IBsender = "sender";


function runner_sms($number, $message, $parameters = array())
{
	global $IBusername, $IBpassword, $IBipcl, $IBsender;

	if ( !isset($parameters["mno"]) )
		$parameters["mno"] = $number;
	$parameters["mno"] = str_replace("+","",$parameters["mno"]);

	if ( !isset($parameters["msg"]) )
		$parameters["msg"] = $message;

	$parameters["sid"] = $IBsender;

	$url = 'https://api.1s2u.io/bulksms';

	$headers = array();
	$parameters["username"] = $IBusername;
	$parameters["password"] = $IBpassword;
	$parameters["fl"] = 0;
	$parameters["mt"] = 0;

	
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
	    	$result["error"] = "1s2s error: " . $result["response"];
	}
	else
	{
		$result["error"] = $response["error"];
	}

	return $result;
}

?>