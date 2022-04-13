<?php 
function runner_sms($number, $message, $parameters = array())
{
	global $twilioSID, $twilioAuth, $twilioNumber;

	if ( !isset($parameters["To"]) )
		$parameters["To"] = $number;

	if ( !isset($parameters["Body"]) )
		$parameters["Body"] = $message;

	$parameters["From"] = $twilioNumber;

	$url = "https://api.twilio.com/2010-04-01/Accounts/".$twilioSID."/Messages.json";

	$headers = array();
	$headers["User-Agent"] = "twilio-php/5.7.3 (PHP 5.6.12)";
	$headers["Accept-Charset"] = "utf-8";
	$headers["Content-Type"] = "application/x-www-form-urlencoded";
	$headers["Accept"] = "application/json";
	$headers["Authorization"] = "Basic " . base64_encode( $twilioSID . ":" . $twilioAuth);

	$certPath = getabspath('include/cacert.pem');

	$result = array();
    $result["success"] = false;

	$response = runner_post_request($url, $parameters, $headers, $certPath);
	if ( !$response["error"] )
	{
	    $result["response"] = my_json_decode($response["content"]);
	    if ( $result["response"]["status"] == "queued" )
	    	$result["success"] = true;
	    else
	    	$result["error"] = "Twilio error: " . $result["response"]["message"];
	}
	else
	{
		$result["error"] = $response["error"];
	}

	return $result;
}

?>