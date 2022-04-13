<?php

class SecurityPluginGoogle extends SecurityPlugin {
	
	/**
	 * @constructor
	 */
	function __construct()
	{
		parent::__construct();
		// client id
		$this->appId = GetGlobalData("GoogleClientId", "");
	}

	public function getUserInfo( $id_token )
	{
		global $cCharset;
		
//		require_once getabspath('plugins/google-api-php-client/vendor/autoload.php');
//		$client = new Google_Client( array( 'client_id' => $this->appId ) );		
//		$payload = $client->verifyIdToken($id_token);
		
		$payload = $this->verifyIdToken( $id_token );
		
		if( $payload["error"] )
			$this->error = "Google security plugin: "
				.$payload["error"]." ".$payload["error_description"];
		
		if( !$payload || $payload["error"] )
			return array();

		//	save token in cookies
		setProjectCookie( 'google_token', $id_token, time() + 30 * 1440 * 60, true );

		$ret = array(
				"id" => "go".$payload["sub"],
				"name" => runner_convert_encoding( $payload["name"], $cCharset, 'UTF-8' ),
				"email" => $payload["email"],
				"raw" => $payload
			);
			
		if( $payload["picture"] ) {
			$picResult = runner_http_request( $payload["picture"] );
			if( $picResult["content"] )
				$ret["picture"] = $picResult["content"];
		}
		
		return $ret;
	}

	public function verifyIdToken( $id_token ) {
		$certPath = getabspath('include/cacert.pem');

		$headers = array();
		$headers["User-Agent"] = "PHPRunner app";
		$headers["Accept-Charset"] = "utf-8";

		$params = array( "id_token" => $id_token );
	

		$url = "https://oauth2.googleapis.com/tokeninfo";
	
		$response = runner_http_request($url, 
			$params, 
			"GET",
			$headers, 
			$certPath);
			
		if( $response["error"] ) {
			$this->error = $response["error"];
			return false;
		}
		
		$payload = my_json_decode( $response["content"] );
		if( !$payload ) {
			// payload is not valid JSON
			$this->error = $response["content"];
		}
		
		return $payload;
	}
	
	public function getJSSettings()
	{
		return array(
			"isGoogleSignIn" => true,
			"GoogleClientId" => $this->appId
		);
	}

	public function onLogout()
	{
		setProjectCookie( 'google_token', "", time() - 1, true );
	}	

	public function savedToken() 
	{
		return $_COOKIE[ 'google_token' ];
	}
}
?>