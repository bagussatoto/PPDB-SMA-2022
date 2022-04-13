<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");
add_nocache_headers();

require_once("classes/searchclause.php");
require_once("include/admin_rights_variables.php");
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





$layout = new TLayout("search_bootstrap", "OfficeOffice", "MobileOffice");
$layout->version = 3;
	$layout->bootstrapTheme = "cerulean";
		$layout->customCssPageName = "admin_rights_search";
$layout->blocks["top"] = array();
$layout->containers["searchpage"] = array();
$layout->container_properties["searchpage"] = array(  );
$layout->containers["searchpage"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"header" );
$layout->containers["header"] = array();
$layout->container_properties["header"] = array(  );
$layout->containers["header"][] = array("name"=>"bssearchheader",
	"block"=>"searchheader", "substyle"=>1  );

$layout->skins["header"] = "";


$layout->skins["searchpage"] = "";

$layout->blocks["top"][] = "searchpage";
$layout->containers["fields"] = array();
$layout->container_properties["fields"] = array(  );
$layout->containers["fields"][] = array("name"=>"bssearchfields",
	"block"=>"", "substyle"=>1  );

$layout->skins["fields"] = "";

$layout->blocks["top"][] = "fields";
$layout->containers["bottombuttons"] = array();
$layout->container_properties["bottombuttons"] = array(  );
$layout->containers["bottombuttons"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"bbuttons" );
$layout->containers["bbuttons"] = array();
$layout->container_properties["bbuttons"] = array(  );
$layout->containers["bbuttons"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"leftbuttons" );
$layout->containers["leftbuttons"] = array();
$layout->container_properties["leftbuttons"] = array(  );
$layout->containers["leftbuttons"][] = array("name"=>"srchbuttons",
	"block"=>"searchbuttons", "substyle"=>1  );

$layout->skins["leftbuttons"] = "";


$layout->skins["bbuttons"] = "";


$layout->skins["bottombuttons"] = "";

$layout->blocks["top"][] = "bottombuttons";
$page_layouts["admin_rights_search"] = $layout;




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
$params['shortTableName'] = 'admin_rights';
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