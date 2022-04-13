<?php 
// MessageBird Provider
// https://www.messagebird.com
// Change $mbAuth, $mbSender

$mbAuth = "apikey";
$mbSender = "sender"; 

function runner_sms($number, $message, $parameters = array())
{
	global $mbAuth, $mbSender;

	if ( !isset($parameters["recipients"]) )
		$parameters["recipients"] = $number;

	if ( !isset($parameters["body"]) )
		$parameters["body"] = $message;

	$parameters["originator"] = $mbSender;

	$url = "https://rest.messagebird.com/messages";

	$headers = array();
	$headers["Authorization"] = "AccessKey " . $mbAuth;

	$certPath = getabspath('include/cacert.pem');

	$result = array();
    $result["success"] = false;

	$response = runner_post_request($url, $parameters, $headers, $certPath);
	if ( !$response["error"] )
	{
		$result["response"] = my_json_decode($response["content"]);
		if ( !$result["response"]["errors"] )
	    	$result["success"] = true;
	    else
	    	$result["error"] = "MessageBird error: " . $result["response"]["errors"][0]["description"];
	}
	else
	{
		$result["error"] = $response["error"];
	}

	return $result;
}

?>