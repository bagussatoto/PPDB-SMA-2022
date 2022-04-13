<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");
require_once("include/dbcommon.php");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

require_once("classes/searchclause.php");
require_once("classes/sql.php");

require_once("include/admin_members_variables.php");

if( !Security::processPageSecurity( $strtablename, 'P' ) )
	return;




$layout = new TLayout("export_bootstrap", "OfficeOffice", "MobileOffice");
$layout->version = 3;
	$layout->bootstrapTheme = "cerulean";
		$layout->customCssPageName = "admin_members_export";
$layout->blocks["top"] = array();
$layout->containers["page"] = array();
$layout->container_properties["page"] = array(  );
$layout->containers["page"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"page_1" );
$layout->containers["page_1"] = array();
$layout->container_properties["page_1"] = array(  );
$layout->containers["page_1"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"panel" );
$layout->containers["panel"] = array();
$layout->container_properties["panel"] = array(  );
$layout->containers["panel"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"header" );
$layout->containers["header"] = array();
$layout->container_properties["header"] = array(  );
$layout->containers["header"][] = array("name"=>"exportheader",
	"block"=>"exportheader", "substyle"=>1  );

$layout->skins["header"] = "";


$layout->containers["panel"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"body" );
$layout->containers["body"] = array();
$layout->container_properties["body"] = array(  );
$layout->containers["body"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"range" );
$layout->containers["range"] = array();
$layout->container_properties["range"] = array(  );
$layout->containers["range"][] = array("name"=>"bsexprange",
	"block"=>"range_block", "substyle"=>1  );

$layout->skins["range"] = "";


$layout->containers["body"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"fields" );
$layout->containers["fields"] = array();
$layout->container_properties["fields"] = array(  );
$layout->containers["fields"][] = array("name"=>"bsexportchoosefields",
	"block"=>"choosefields", "substyle"=>1  );

$layout->skins["fields"] = "";


$layout->containers["body"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"fields_1" );
$layout->containers["fields_1"] = array();
$layout->container_properties["fields_1"] = array(  );
$layout->containers["fields_1"][] = array("name"=>"bsexportformat",
	"block"=>"exportformat", "substyle"=>1  );

$layout->skins["fields_1"] = "";


$layout->containers["body"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"fields_2" );
$layout->containers["fields_2"] = array();
$layout->container_properties["fields_2"] = array(  );
$layout->containers["fields_2"][] = array("name"=>"bsexpoutput",
	"block"=>"", "substyle"=>1  );

$layout->skins["fields_2"] = "";


$layout->containers["body"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"buttons" );
$layout->containers["buttons"] = array();
$layout->container_properties["buttons"] = array(  );
$layout->containers["buttons"][] = array("name"=>"bsexpbuttons",
	"block"=>"exportbuttons", "substyle"=>2  );

$layout->skins["buttons"] = "";


$layout->skins["body"] = "";


$layout->skins["panel"] = "";


$layout->skins["page_1"] = "";


$layout->skins["page"] = "";

$layout->blocks["top"][] = "page";
$page_layouts["admin_members_export"] = $layout;




require_once("include/export_functions.php");
require_once("classes/exportpage.php");
require_once("include/xtempl.php");

$xt = new Xtempl();

//array of params for classes
$params = array();
$params["id"] = postvalue_number("id");
$params["xt"] = &$xt;
$params["tName"] = $strTableName;
$params["pageType"] = PAGE_EXPORT;
$params["pageName"] = postvalue("page");

if( !$eventObj->exists("ListGetRowCount") && !$eventObj->exists("ListQuery") )
	$params["needSearchClauseObj"] = false;

$params["selectedFields"] = postvalue("exportFields");
$params["exportType"] = postvalue("type");
$params["action"] = postvalue("a");	
$params["records"] = postvalue("records");	
$params["selection"] = postvalue("selection"); 
$params["csvDelimiter"] = postvalue("delimiter"); 

if( postvalue("txtformatting") == "raw" )
	$params["useRawValues"] = true;

$params["mode"] = ExportPage::readModeFromRequest();
	
$pageObject = new ExportPage( $params );
$pageObject->init();

$pageObject->process();
?>