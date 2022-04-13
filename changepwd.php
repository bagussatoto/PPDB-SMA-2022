<?php
@ini_set("display_errors", "1");
@ini_set("display_startup_errors", "1");

require_once("include/dbcommon.php");

$token = postvalue("token");

if( !$token && (!isLogged() || @$_SESSION["UserID"] == "<Guest>") )
{ 
	Security::saveRedirectURL();
	HeaderRedirect("login", "", "message=expired"); 
	return;
}

require_once("include/xtempl.php");
require_once("classes/changepwdpage.php");
require_once(getabspath("classes/cipherer.php"));









$xt = new Xtempl();


$params = array();
$params["xt"] = &$xt;
$params["token"] = $token;
$params["id"] = postvalue_number("id");
//$params["tName"] = GLOBAL_PAGES;
$params["tName"] = $cLoginTable;
$params["pageTable"] = GLOBAL_PAGES;
$params["pageType"] = PAGE_CHANGEPASS;
//$params["templatefile"] = "changepwd.htm";
$params["needSearchClauseObj"] = false;
$params["action"] = ChangePasswordPage::readActionFromRequest();

$pageObject = new ChangePasswordPage( $params );
$pageObject->init();

$pageObject->process();
?>