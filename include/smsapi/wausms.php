<?php 
//* WAUSMS SMS Provider
//* https://www.wausms.com/
//* Change $WAUusername, $WAUpassword, $WAUsender

$WAUusername = "username";
$WAUpassword = "password";
$WAUsender = "sender";


function runner_sms($number, $message, $parameters = array())
{
	global $WAUusername, $WAUpassword, $WAUsender;
	
	if ( !isset($parameters["to"]) )
		$parameters["to"] = array($number);
	else
		$parameters["to"] = array($parameters["to"]);

	if ( !isset($parameters["text"]) )
		$parameters["text"] = $message;

	if ( !isset($parameters["from"]) )
		$parameters["from"] = $WAUsender;

	$post['to'] = $parameters["to"]; 
	$post['text'] = $parameters["text"]; 
	$post['from'] = $parameters["from"]; 

	$user = $WAUusername; 
	$password = $WAUpassword; 
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://dashboard.wausms.com/Api/rest/message"); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post)); 
	curl_setopt($ch,CURLOPT_CAINFO, getabspath('include/cacert.pem'));
	curl_setopt($ch, CURLOPT_HTTPHEADER, 
	array( 
	"Accept: application/json", 
	"Authorization: Basic ".base64_encode($user.":".$password))); 
	try
	{
	    if (!$result["content"] = curl_exec($ch))
	        throw new Exception(curl_error($ch));

	   	curl_close($ch);
    }
    catch ( Exception $e )
    {
 	   	$result["error"] = "CURL error: " . $e->getMessage();
	}
	$response = my_json_decode($result["content"]);

	if ( !$response["error"] )
	{
	    $result["content"] = $response;
	}
	else
	{
		$result["error"] = $response["error"]["description"];
	}

	return $result;
}

?>