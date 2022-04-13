<?php
class SecurityPlugin
{
	protected $appId = "";
	protected $appSecret = "";

	protected $error = "";
	
	/**
	 * @constructor
	 */
	function __construct() {
	}

	/**
	 * returns saved token fr automatic logon if any or false/null
	*/
	public function savedToken()
	{
		return false;
	}

	public function getJSSettings()
	{
		return array();
	}

	/**
	 * Returns standardized user info
	 * id: provider-specific user id with provider-specific prefix.
	 * 		fb123423523456
	 * 		go2354098cv0s8
	 * name: user's display name
	 * picture: user's photo
	 * email: user's email address
	 * raw: array of original user info returned from the provider
	 * @return Array
	 */
	public function getUserInfo() {
		return array();
	}

	public function onLogout()
	{
	}
	
	public function getError() {
		return $this->error;
	}
}

?>