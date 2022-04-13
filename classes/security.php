<?php
class Security
{
	public static function processPageSecurity( $table, $permission, $ajaxMode = false, $message = '' )
	{
		if( Security::checkPagePermissions( $table, $permission ) )
			return true;

		if( $ajaxMode )
		{
			Security::sendPermissionError( $message );
			return false;
		}
		// The user is logged in but lacks necessary permissions
		// redirect to Menu.
		if( isLogged() && !isLoggedAsGuest() )
		{
			HeaderRedirect("menu");
			return false;
		}

		//	Not logged in
		// 	redirect to Login
		//	Current URL is already saved  in session
		redirectToLogin();
		return false;
	}

	public static function processAdminPageSecurity( $ajaxMode = false )
	{
		Security::processLogoutRequest();

		if( !isLogged() || isLoggedAsGuest() )
		{
			Security::tryRelogin();
		}

		if( IsAdmin() )
			return true;

		if( $ajaxMode )
		{
			Security::sendPermissionError();
			return false;
		}

		// The user is logged in but lacks necessary permissions
		// redirect to Menu.
		if( isLogged() && !isLoggedAsGuest() )
		{
			HeaderRedirect("menu");
			return false;
		}

		//	Not logged in
		// 	redirect to Login
		//	Save current URL in session
		Security::saveRedirectURL();
		redirectToLogin();
		return false;
	}

	public static function saveRedirectURL()
	{
		$url = $_SERVER["SCRIPT_NAME"];
		$query = "";
		foreach( $_GET as $key=>$value )
		{
			if( $key == "a" && $value == "logout" )
				continue;
			if( $query != "" )
				$query.="&";

			if( is_array($value) )
			{
				$query .= rawurlencode($key."[]")."=";
				$query .= implode( rawurlencode($key."[]")."=", $value );
			}
			else
			{
				$query .= rawurlencode($key);
				if( strlen($value) )
					$query .= "=" . rawurlencode($value);
			}
		}
		if( $query != "" )
			$url .= "?" . $query;
		$_SESSION["MyURL"] = $url;
	}

	public static function checkPagePermissions( $table, $permission )
	{
		//	log out if received ?a=logout request
		Security::processLogoutRequest();
		// save current URL
		Security::saveRedirectURL();

		$ret = Security::checkUserPermissions( $table, $permission );
		//	remember if current user has permissions on the page saved in $_SESSION[MyURL]
		$_SESSION["MyUrlAccess"] = $ret;
		return $ret;
	}

	protected static function createLoginPageObject()
	{
		include_once(getabspath('classes/loginpage.php'));
		include_once(getabspath('include/xtempl.php'));
		$loginXt = new Xtempl();

		$loginParams = array("pageType" => PAGE_LOGIN);
		$loginParams['id'] = -1;
		$loginParams['xt'] = &$loginXt;
		$loginParams["tName"]= GLOBAL_PAGES;
		$loginParams['needSearchClauseObj'] = false;
		$loginPageObject = new LoginPage($loginParams);
		$loginPageObject->init();
		return $loginPageObject;
	}

	/**
	 * Try to login automatically using saved login data
	 */
	static function tryRelogin()
	{
		//	don't try if we have just logged out
		if( postvalue("a") == "logout" )
			return;

		$loginPageObject = null;

		//	try to relogin with username & password from cookies first
		$loginToken = postvalue("token");
		if( !$loginToken ) {
			$loginToken = $_COOKIE["token"];
		}
		if( $loginToken ) {
			$tokenPayload = jwt_verify_decode( $loginToken );
			if( $tokenPayload ) {
				if( $tokenPayload["username"] ) {
					$loginPageObject = Security::createLoginPageObject();
					if( $loginPageObject->LogIn( $tokenPayload["username"], "", true ) )
					{
						return true;
					}

				}

			}
			//	clear cookie if weren't able to login
			setProjectCookie( "token", "", time() - 1, true );

		}

		//	try security plugins
		$securityPlugins = Security::GetPlugins();
		foreach( $securityPlugins as $sp )
		{
			$token = $sp->savedToken();
			if( $token )  {
				if( !$loginPageObject )
					$loginPageObject = Security::createLoginPageObject();

				if( $loginPageObject->LoginWithSP( $sp, $token, false ) )
					return true;
			}
		}

		return false;
	}

	static function checkUserPermissions($table, $permission)
	{
		//	user is logged in
		if( !isLogged() || isLoggedAsGuest() )
		{
			Security::tryRelogin();
		}
		//	admin area security
		if( $table == ADMIN_USERS )
			return IsAdmin();

		return CheckTablePermissions($table, $permission);
	}

	/**
	 * Returns true if logged out
	 * @return Boolean
	 */
	static function processLogoutRequest()
	{
		//	no need to logout
		if( postvalue("a") != "logout" || !isLogged() || isLoggedAsGuest() )
			return false;

		//	logout and redirect (refresh current page)
		$loginPageObject = Security::createLoginPageObject();
		$loginPageObject->Logout();
		//	login as guest
		Security::doGuestLogin();
		global $logoutPerformed;
		$logoutPerformed = true;
		return true;
	}

	/**
	 * @param String message (optional)
	 */
	public static function sendPermissionError( $message = '' )
	{
		echo printJSON(array("success" => false, "message" => "Anda tidak punya ijin untuk mengakses tabel ini".$message));
		exit();
	}

	public static function redirectToList( $table )
	{
		$settings = new ProjectSettings( $table );
		if( $settings->hasListPage() )
		{
			HeaderRedirect($settings->getShortTableName(), "list", "a=return");
			exit();
		}
		//	no List page
		HeaderRedirect("menu");
		exit();
	}

	public static function clearSecuritySession()
	{
		session_unset();
		setcookie("token", "", time() - 1000, "", "", false, false );


		// these lines are important
		// DO NOT REMOVE THEM!
		unset( $_COOKIE["username"] );
		unset( $_COOKIE["password"] );
		unset( $_COOKIE["token"] );


		unset( $_SESSION["UserID"] );
		unset( $_SESSION["UserName"] );
		unset( $_SESSION["AccessLevel"] );
		unset( $_SESSION["pluginLogin"] );
		unset( $_SESSION["UserRights"] );
		unset( $_SESSION["LastReadRights"] );
		unset( $_SESSION['GroupID'] );
		unset( $_SESSION["OwnerID"] );
		unset( $_SESSION["securityOverrides"] );

		$toClear = array();
		foreach( $_SESSION as $k => $v )
		{
			if( substr($k, -8) == "_OwnerID" )
				$toClear[] = $k;
		}
		foreach( $toClear as $k => $v )
		{
			unset( $_SESSION[ $k ] );
		}
	}

	public static function doGuestLogin()
	{
			$allowGuest = guestHasPermissions();
	if( !$allowGuest )
		return;

	DoLogin(true);
	}

	/**
	 * Security API calls
	 */

	/**
	 *	Return current user's group when Static Permissions are used.
	 *	When Dynamic permissions are used, returns any group name the user belongs to
	 *	@return String
	 */
	public static function getUserGroup()
	{
		$userGroups = Security::getUserGroups();
		foreach( $userGroups as $g => $v )
		{
			return $g;
		}
		return "";
	}

	/**
	 *	Return array of the group IDs the user belongs to. Group Ids are the keys of the array:
	 *	$groups[ <group1> ] = true;
	 *	$groups[ <group2> ] = true;
	 *	Admin group ID is -1
	 *	When Static permissions are used, the array has only one element.
	 *	Returns empty array when the user is Guest or not logged in.
	 *	@return Array
	 */
	public static function getUserGroupIds()
	{
		global $globalSettings;
		if( $globalSettings["nLoginMethod"] == SECURITY_NONE || $globalSettings["nLoginMethod"] == SECURITY_HARDCODED )
			return array();

		if( !$globalSettings["isDynamicPerm"] )
		{
			//	static permissions
			if( $_SESSION["GroupID"] )
				return array( $_SESSION["GroupID"] => true );
			return array();
		}

		//	dynamic permissions
				ReadUserPermissions();
		$groups = array();
		foreach( $_SESSION["UserRights"][ $_SESSION["UserID"] ][ ".Groups" ] as $g )
			$groups[$g] = true;
		return $groups;
	}

	/**
	 *	Return array of the group names the user belongs to. Group names are the keys of the array:
	 *	$groups[ <group1> ] = true;
	 *	$groups[ <group2> ] = true;
	 *	When Static permissions option is used, the array has only one element.
	 *	$groups[ <groupId> ] = true;
	 *	Returns empty array when the user is Guest or not logged in or doesn't belong to any group.
	 *	@return Array
	 */
	public static function getUserGroups()
	{
		global $globalSettings;
		if( $globalSettings["nLoginMethod"] == SECURITY_NONE || $globalSettings["nLoginMethod"] == SECURITY_HARDCODED )
			return array();
		if( !$globalSettings["isDynamicPerm"] || $globalSettings["nLoginMethod"] == SECURITY_AD )
			return Security::getUserGroupIds();

		// database-based dynamic permissions
		$groupIds = Security::getUserGroupIds();

		$groupNames = array();

		global $cman;
		$grConnection = $cman->getForUserGroups();

		$sql = "select ". $grConnection->addFieldWrappers( "Label" )
			." from ". $grConnection->addTableWrappers( "ppdb2022_uggroups" ) . " WHERE " . $grConnection->addFieldWrappers( "GroupID" )
			." in ( " . implode( ",", array_keys( $groupIds ) ) . ")";

		$qResult = $grConnection->query( $sql );
		while( $data = $qResult->fetchNumeric() )
		{
			$groupNames[ $data[0] ] = true;
		}

		if( $groupIds[ -1 ] )
			$groupNames["<Admin>"] = true;

		return $groupNames;
	}

	/**
	 *	Return current user's name, the same he entered when logging in.
	 *	@return String
	 */
	public static function getUserName()
	{
		return $_SESSION["UserID"];
	}

	/**
	 *	Return current user's display name, the one to be displayed on the pages.
	 *	@return String
	 */
	public static function getDisplayName()
	{
		return $_SESSION["UserName"];
	}
	/**
	 *	Change the current user's display name, the one to be displayed on the pages.
	 *	@param String $str - new name, HTML formatting is allowed
	 */
	public static function setDisplayName( $str )
	{
		$_SESSION["UserName"] = $str;
	}

	/**
	 *	Checks if the current user is Guest or not.
	 *	@return Boolean
	 */
	public static function isGuest()
	{
		if($_SESSION["UserID"] == "Guest" && $_SESSION["AccessLevel"] == ACCESS_LEVEL_GUEST)
			return true;
		return false;
	}

	/**
	 *	Checks if the current user is Admin or not.
	 *	@return Boolean
	 */
	public static function isAdmin()
	{
		global $globalSettings;
		if( $globalSettings["nLoginMethod"] == SECURITY_NONE || $globalSettings["nLoginMethod"] == SECURITY_HARDCODED )
			return false;

		//	dynamic, DB or AD-based
		if( $globalSettings["isDynamicPerm"] )
			return $_SESSION["UserRights"][ $_SESSION["UserID"] ][ ".IsAdmin" ];

		//	static
		if( $globalSettings["nLoginMethod"] == SECURITY_TABLE )
		{
			return ( ACCESS_LEVEL_ADMIN == $_SESSION["AccessLevel"] );
		}

		//	no admins otherwise
		return false;
	}

	/**
	 *	Checks if the current user is logged in.
	 *	@return Boolean
	 */
	public static function isLoggedIn()
	{
		return ( $_SESSION["UserID"] != "" && !Security::isGuest() );
	}

	/**
	 *	Logs in under specified username
	 *	@param String $username
	 *	@param Boolean $fireEvents - call After Successful Login event or not
	 *	@returns Boolean - true if login was successful
	 */
	public static function loginAs( $username, $fireEvents = true )
	{
		$loginPageObject = Security::createLoginPageObject();
		return $loginPageObject->LogIn($username, "", true, $fireEvents );
	}

	/**
	 * @param String username
	 * @param String password
	 * @param Boolean fireEvents (optional)  Run after unsuccessful event if login/password are incorrect.
	 * @return Boolean
	 */
	public static function checkUsernamePassword( $username, $password, $fireEvents = false )
	{
		$loginPageObject = Security::createLoginPageObject();

		if( $loginPageObject->checkUsernamePassword( $username, $password ) )
			return true;

		if( $fireEvents )
		{
			$loginPageObject->doAfterUnsuccessfulLog( $username );
			$loginPageObject->callAfterUnsuccessfulLoginEvent();
		}
		return false;
	}

	/**
	 * @param String username
	 * @param String password (optional)
	 * @return Array
	 */
	public static function getUserData( $username, $password = "" )
	{
		$loginPageObject = Security::createLoginPageObject();
		return $loginPageObject->getUserData( $username, $password, "" == $password );
	}

	/**
	 * @return Array
	 */
	public static function currentUserData( )
	{
		return $_SESSION["UserData"];
	}



	/**
	 *	Logs the current user out
	 */
	public static function logout()
	{
		$loginPageObject = Security::createLoginPageObject();
		$loginPageObject->Logout();
	}

	/**
	 *	Returns table permissions array the current user.
	 *	Returns array where keys are specific permission letters:
	 * 	A - add,
	 *  D - delete,
	 *  E - edit,
	 *  S - search/list,
	 *  P - print/export,
	 *  I - import,
	 *	M - admin permission. When advanced permissions are in effect ( users can see/edit their own records only ), this permissions grants access to all records.
	 *
	 *  Sample:
	 *		$rights = Security::getPermissions( $table );
	 *		if( $rights["A"] )
	 *		echo "add permission available";
	 *
	 *	@param String $table - table name
	 *  @returns Array
	 */
	public static function getPermissions( $table )
	{
		$table = findTable( $table );
		if( $table == "" )
			return array();

		return Security::permMask2Array( GetUserPermissions( $table ) );
	}

	/**
	 *	Set table permissions for the current user.
	 *	Permissions should be passed in the form of array where keys are specific permission letters:
	 * 	A - add,
	 *  D - delete,
	 *  E - edit,
	 *  S - search/list,
	 *  P - print/export,
	 *  I - import,
	 *	M - admin permission. When advanced permissions are in effect ( users can see/edit their own records only ), this permissions grants access to all records.
	 *
	 *  Sample:
	 *		$rights = Security::getPermissions( $table );
	 *		$rights["A"] = true;
	 *		$rights["D"] = false;
	 *		Security::setPermissions( $table, $rights );
	 *
	 *  Permissions need to be set only once per user session, i.e. in the 'After Successful Login' event.
	 *
	 *	@param String $table - table name
	 *	@param Array $rights
	 *  @returns nothing
	 */

	public static function setPermissions( $table, $rights )
	{
		$table = findTable( $table );
		if( $table == "" )
			return;

		$strPerm = Security::permArray2Mask( $rights );

		if( !isset( $_SESSION[ "securityOverrides" ] ) )
			$_SESSION[ "securityOverrides" ] = array();

		$_SESSION[ "securityOverrides" ][ $table ] = $strPerm;
	}

	private static function permMask2Array( $str )
	{
		$ret = array();
		for( $i = 0; $i < strlen($str); ++$i )
		{
			$c = substr( $str, $i, 1 );
			if( $c == "A" || $c == "D" || $c == "E" || $c == "S" || $c == "P" || $c == "I" || $c == "M" )
				$ret[ $c ] = true;
		}
		return $ret;
	}

	private static function permArray2Mask( $rights )
	{
		$str = "";
		if( !is_array( $rights ) )
		{
			if( strlen( $rights ) )
				$rights = Security::permMask2Array( $rights );
			else
				return "";
		}
		foreach( $rights as $c => $v )
			if( $v && ( $c == "A" || $c == "D" || $c == "E" || $c == "S" || $c == "P" || $c == "I" || $c == "M" ) )
				$str .= $c;
		return $str;
	}


	/**
	 *	Returns current user's OwnerID - the value used to identify records ownership in the specific table.
	 *
	 *	@param String $table - table name
	 *  @returns String
	 */
	public static function getOwnerId( $table )
	{
		$table = findTable( $table );
		if( $table == "" )
			return;

		return $_SESSION[ "_" . $table . "_OwnerID" ];
	}

	/**
	 *	Change current user's OwnerID - the value used to identify records ownership in the specific table.
	 *
	 *	@param String $table - table name
	 *  @param String $ownerid
	 */
	public static function setOwnerId( $table, $ownerid )
	{
		$table = findTable( $table );
		if( $table == "" )
			return;

		$_SESSION[ "_" . $table . "_OwnerID" ] = $ownerid;
	}

	public static function hasLogin() {
		return GetGlobalData("createLoginPage");
	}

	public static function loginMethod() {
		return GetGlobalData("nLoginMethod");
	}

	public static function dynamicPermissions() {
		$method = Security::loginMethod();
		if( $method != SECURITY_TABLE && $method != SECURITY_AD)
			return false;
		return GetGlobalData("isDynamicPerm");
	}

	/**
	 * Returns true if permissions are defined in the project.
	 * When false, no permissions system is present in the project. Everyone sees everything.
	 * @return Boolean
	 */
	public static function permissionsAvailable() {
		$method = Security::loginMethod();
		if( $method != SECURITY_TABLE && $method != SECURITY_AD )
			return false;
		return GetGlobalData("isDynamicPerm") || GetGlobalData("userGroupCount");
	}

	/**
	 * 	$permission - one of A,D,E,S,P,I literals
	 *  $table that the permissions are requested on
	 *  $ownerId - ownerId of the record the permissions is requested on
	 */
	public static function userCan( $permission, $table, $ownerId = null )
	{
		if( !Security::hasLogin() ) {
			return true;
		}

		if($_SESSION["AccessLevel"] == ACCESS_LEVEL_ADMIN)
			return true;
		$strPerm = GetUserPermissions( $table );

		// no permissions
		if( strpos( $strPerm, $permission ) === false )
			return false;

		//	record ownerId check not requested or user has admin permissions
		if( $ownerId === null || strpos($strPerm, "M") !== false )
			return true;

		$pSet = new ProjectSettings($table);
		$advSecType = $pSet->getAdvancedSecurityType();
		if( $advSecType == ADVSECURITY_ALL || $advSecType == ADVSECURITY_NONE /*????*/  )
			return true;

		if( $advSecType == ADVSECURITY_EDIT_OWN && $permissions != 'D' && $permissions != 'E' ) {
			return true;
		}

		$currentOwnerId = (string)$_SESSION["_".$table."_OwnerID"];
		if( $pSet->isCaseInsensitiveUsername() ) {
			$ownerId = strtoupper( $ownerId );
			$currentOwnerId = strtoupper( $currentOwnerId );
		}

		return $ownerId === $currentOwnerId;
	}

	/**
	 * 	User has permissions on fields specified on the Register page and on the fields from pages he has access to
	 *  pageName can be substituted by another page
	 *
	 *  @param String table
	 *  @param String field
	 *  @param String pageType
	 *  @param String pageName
	 *  @param Boolean edit. Either we are asking to show field
	 */
	public static function userHasFieldPermissions( $table, $field, $pageType, $pageName, $edit ) {
		global $cLoginTable;
		$pageTable = $table;
		if( $table === $cLoginTable && $pageType === "register") {
			$pageTable = GLOBAL_PAGES;
		}
		$pSet = new ProjectSettings( $table, $pageType, $pageName, $pageTable );
		$pageType = $pSet->getPageType();

		$permission = Security::pageType2permission( $pageType );
		if( $pageTable != GLOBAL_PAGES && !Security::userCan( $permission, $table ) ) {
			return false;
		}


		//	search panel fields
		if( $edit && !pageTypeInputsData( $pageType ) ) {
			return $pSet->appearOnSearchPanel( $field );
		}
		if( !$edit && !pageTypeShowsData( $pageType ) )
			return false;
		return $pSet->appearOnPage( $field );
	}

	public static function getRestrictedPages( $table )
	{
		if( Security::dynamicPermissions() ) {
			ReadUserPermissions();
			$pages = @$_SESSION["UserRights"][$_SESSION["UserID"]][$table]["pages"];
			if( !$pages ) {
				$pages = array();
			}
			return $pages;
		} else {
			return Security::_staticRestrictedPages( $table );
		}
		return array();
	}
	public static function pageType2permission( $pageType ) {
		if( $pageType == "add" )
			return "A";
		else if( $pageType == "edit" )
			return "E";
		else if( $pageType == "print" || $pageType == "export" )
			return "P";
		else if( $pageType == "import" )
			return "I";
		return "S";
	}

	public static function _staticRestrictedPages( $table ) {
		$group = Security::getUserGroup();
		//	default permissions
		return array();
	}

	public static function GetPlugins() {
		$plugins = array();
		require_once( getabspath('classes/security/securityplugin.php') );

		if( GetGlobalData("isFB", false) )
		{
			require_once( getabspath( 'classes/security/fb.php' ) );
			$plugins["fb"] = new SecurityPluginFB();
		}


		if( GetGlobalData("isGoogleSignIn", false) )
		{
			require_once( getabspath( 'classes/security/google.php' ) );
			$plugins["go"] = new SecurityPluginGoogle();
		}

		return $plugins;
	}

	function getLoginTable() {
		global $cLoginTable;
		if( Security::loginMethod() === SECURITY_TABLE )
			return $cLoginTable;
		return false;
	}


}
?>