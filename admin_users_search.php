<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");
add_nocache_headers();

require_once("classes/searchclause.php");
require_once("include/admin_users_variables.php");
require_once("classes/searchcontrol.php");
require_once("classes/advancedsearchcontrol.php");
require_once("classes/panelsearchcontrol.php");


Security::processLogoutRequest();

if( !isLogged() )
{ 
	Security::saveRedirectURL();
	redirectToLogin();
}

$cname = postvalue("cname");
$rname = postvalue("rname");

$accessGranted = CheckTablePermissions($strTableName, "S");
if(!$accessGranted)
{
	HeaderRedirect("menu");
}






require_once('include/xtempl.php');
require_once('classes/searchpage.php');
require_once('classes/searchpage_dash.php');

$xt = new Xtempl();	
$pageMode = SearchPage::readSearchModeFromRequest();

if( $pageMode == SEARCH_LOAD_CONTROL )
	$layoutVersion = postvalue("layoutVersion");


$params = array();
$params['xt'] = &$xt;
$params['id'] = postvalue_number("id");
$params['mode'] = $pageMode;
$params['tName'] = $strTableName;
$params["pageName"] = postvalue("page");
$params['pageType'] = PAGE_SEARCH;
$params['chartName'] = $cname;
$params['reportName'] = $rname;
$params['templatefile'] = $templatefile;
$params['shortTableName'] = 'admin_users';
$params['layoutVersion'] = $layoutVersion;

$params['searchControllerId'] = postvalue('searchControllerId') ? postvalue('searchControllerId') : $id;
$params['ctrlField'] = postvalue('ctrlField');

$params['needSettings'] = postvalue('isNeedSettings');

if( $pageMode == SEARCH_DASHBOARD )
{
	$params["dashTName"] = postvalue("table");
	$params["dashElementName"] = postvalue("dashelement");
}

// e.g. crosstable params
$params["extraPageParams"] = SearchPage::getExtraPageParams();


$pageObject = new SearchPage($params);

if( $pageMode == SEARCH_LOAD_CONTROL )
{
	$pageObject->displaySearchControl();
	return;
}

$pageObject->init();
$pageObject->process();
?>