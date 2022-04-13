<?php 
//* GateWay SMS Provider
//* https://gatewayapi.com/
//* Change $GWapi_token, $GWsender

$GWapi_token = "api key";
$GWsender = "sender";


function runner_sms($number, $message, $parameters = array())
{
	global $GWapi_token, $GWsender;
    if ( isset($parameters["To"]) )
		$number = $parameters["To"];
    if ( isset($parameters["Body"]) )
    	$message = $parameters["Body"];
    if ( isset($parameters["From"]) ){
   		 $sender = $parameters["From"];
    if(is_numeric($sender)){
        if(length($sender)>15)
            $sender = substr($sender,0,15);
    }
    else
        $sender = substr($sender,0,11);
    }
    else
        $sender = $GWsender;

    $url = "https://gatewayapi.com/rest/mtsms";

	$parameters = array('token' => $GWapi_token,
		'sender' => $GWsender,
		'message' => $message,
		'recipients.0.msisdn' => $number);
	
	$certPath = getabspath('include/cacert.pem');	

	$result = array();
    $result["success"] = false;

	$response = runner_post_request($url, $parameters, $headers, $certPath);


    if ( !$response["error"] )
    {
        $result["response"] = $response["content"];
        if(json_decode($result)->ids[0])
            $result["success"] = true;
        else{
			$v = json_decode($response["content"])->variables[0];
			$result["error"] = "GateWay error: ".str_replace("%1",$v,json_decode($response["content"])->message);
		}
    }
    else
    {
        $result["error"] = $response["error"];
    }
}
?>