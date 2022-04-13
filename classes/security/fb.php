<?php

class SecurityPluginFB extends SecurityPlugin {

	/**
	 * Facebook plugin
	 */
	public $fbObj;

	/**
	 * @constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->appId = GetGlobalData("FBappId", "");
		$this->appSecret = GetGlobalData("FBappSecret", "");

		$this->fbObj = fbCreateObject( $this->appId, $this->appSecret );
	}

	public function getUserInfo( $token )
	{
		global $cCharset;

		//	facebook API ignores $token and uses $_REQUEST["signed_request"] instead
		$infoData = fbGetUserInfo( $this->fbObj );
		$fbme = $infoData["info"];
		
		if( !$fbme )
		{
			$this->error = $infoData["error"];
			return array();
		}
		
		$ret = array(
				"id" => "fb".(string)$fbme["id"],
				"name" => runner_convert_encoding( (string)$fbme["name"], $cCharset, 'UTF-8' ),
				"email" => (string)$fbme["email"],
				"raw" => $fbme
			);

		if( $fbme["picture"] && is_array( $fbme["picture"] )) {
			$picResult = runner_http_request( @$fbme["picture"]["data"]["url"] );
			if( $picResult["content"] )
				$ret["picture"] = $picResult["content"];
		}
		
		return $ret;
	}

	public function getJSSettings()
	{
		return array(
			"isFB" => true,
			"FBappId" => $this->appId
		);
	}

	public function onLogout()
	{
		fbDestroySession( $this->fbObj );
	}

	public function savedToken()
	{
		return fbGetSignedRequest( $this->fbObj );
	}
}

?>