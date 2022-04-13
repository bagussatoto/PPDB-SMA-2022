<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");
require_once("include/admin_rights_variables.php");
require_once('include/xtempl.php');
require_once('classes/viewpage.php');
require_once("classes/searchclause.php");

add_nocache_headers();

if( !ViewPage::processEditPageSecurity( $strTableName ) )
	return;	





$layout = new TLayout("view_bootstrap_2col", "OfficeOffice", "MobileOffice");
$layout->version = 3;
	$layout->bootstrapTheme = "cerulean";
		$layout->customCssPageName = "admin_rights_view";
$layout->blocks["top"] = array();
$layout->containers["viewpage"] = array();
$layout->container_properties["viewpage"] = array(  );
$layout->containers["viewpage"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"header" );
$layout->containers["header"] = array();
$layout->container_properties["header"] = array(  );
$layout->containers["header"][] = array("name"=>"viewheader",
	"block"=>"viewheader", "substyle"=>1  );

$layout->skins["header"] = "";


$layout->skins["viewpage"] = "";

$layout->blocks["top"][] = "viewpage";
$layout->containers["fields"] = array();
$layout->container_properties["fields"] = array(  );
$layout->containers["fields"][] = array("name"=>"viewfields",
	"block"=>"", "substyle"=>1  );

$layout->skins["fields"] = "";

$layout->blocks["top"][] = "fields";
$layout->containers["bottombuttons"] = array();
$layout->container_properties["bottombuttons"] = array(  );
$layout->containers["bottombuttons"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"buttons" );
$layout->containers["buttons"] = array();
$layout->container_properties["buttons"] = array(  );
$layout->containers["buttons"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"leftbuttons" );
$layout->containers["leftbuttons"] = array();
$layout->container_properties["leftbuttons"] = array(  );
$layout->containers["leftbuttons"][] = array("name"=>"viewbuttons",
	"block"=>"viewbuttons", "substyle"=>1  );

$layout->skins["leftbuttons"] = "";


$layout->containers["buttons"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"leftbuttons_1" );
$layout->containers["leftbuttons_1"] = array();
$layout->container_properties["leftbuttons_1"] = array(  );
$layout->containers["leftbuttons_1"][] = array("name"=>"rightviewbuttons",
	"block"=>"rightviewbuttons", "substyle"=>1  );

$layout->skins["leftbuttons_1"] = "";


$layout->skins["buttons"] = "";


$layout->skins["bottombuttons"] = "";

$layout->blocks["top"][] = "bottombuttons";
$page_layouts["admin_rights_view"] = $layout;





$pageMode = ViewPage::readViewModeFromRequest();

$xt = new Xtempl();

// $keys could not be set properly if editid params were no passed
$keys = array();
$keys["TableName"] = postvalue("editid1");
$keys["GroupID"] = postvalue("editid2");

//array of params for classes
$params = array();
$params["id"] = postvalue_number("id");
$params["xt"] = &$xt;
$params["keys"] = $keys;
$params["mode"] = $pageMode;
$params["pageType"] = PAGE_VIEW;
$params["pageName"] = postvalue("page");
$params["tName"] = $strTableName;

$params["pdfMode"] = postvalue("pdf") !== "";

$params["masterTable"] = postvalue("mastertable");

if( $pageMode == VIEW_DASHBOARD ) 
{
	$params["dashElementName"] = postvalue("dashelement");
	$params["dashTName"] = postvalue("table");
	if(	postvalue("mapRefresh") )
	{
		$params["mapRefresh"] = true;
		$params["vpCoordinates"] = my_json_decode( postvalue("vpCoordinates") );
	}		
} 
if( $pageMode == VIEW_POPUP )
{
	$params["dashElementName"] = postvalue("dashelement");
	$params["dashTName"] = postvalue("dashTName");
}

if( $params["masterTable"] )
{
	$params["masterKeysReq"] = ViewPage::processMasterKeys();
}
$params["pdfBackgroundImage"] = postvalue("pdfBackgroundImage");

$pageObject = new ViewPage($params);
$pageObject->init();

$pageObject->process();

?>