<?php
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");
require_once("include/dbcommon.php");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

set_time_limit(600);

require_once("include/admin_members_variables.php");
require_once("include/import_functions.php");
require_once('classes/importpage.php');

if( !Security::processPageSecurity( $strtablename, 'I' ) )
	return;




$layout = new TLayout("import_bootstrap", "OfficeOffice", "MobileOffice");
$layout->version = 3;
	$layout->bootstrapTheme = "cerulean";
		$layout->customCssPageName = "admin_members_import";
$layout->blocks["top"] = array();
$layout->containers["page"] = array();
$layout->container_properties["page"] = array(  );
$layout->containers["page"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"panel" );
$layout->containers["panel"] = array();
$layout->container_properties["panel"] = array(  );
$layout->containers["panel"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"header" );
$layout->containers["header"] = array();
$layout->container_properties["header"] = array(  );
$layout->containers["header"][] = array("name"=>"importheader",
	"block"=>"", "substyle"=>1  );

$layout->skins["header"] = "";


$layout->containers["panel"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"body" );
$layout->containers["body"] = array();
$layout->container_properties["body"] = array(  );
$layout->containers["body"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"message" );
$layout->containers["message"] = array();
$layout->container_properties["message"] = array(  );
$layout->containers["message"][] = array("name"=>"errormessage",
	"block"=>"", "substyle"=>1  );

$layout->skins["message"] = "";


$layout->containers["body"][] = array("name"=>"wrapper",
	"block"=>"", "substyle"=>1 , "container"=>"import" );
$layout->containers["import"] = array();
$layout->container_properties["import"] = array(  );
$layout->containers["import"][] = array("name"=>"importheader_text",
	"block"=>"", "substyle"=>1  );

$layout->containers["import"][] = array("name"=>"importfields",
	"block"=>"", "substyle"=>1  );

$layout->containers["import"][] = array("name"=>"import_rawtext_control",
	"block"=>"", "substyle"=>1  );

$layout->containers["import"][] = array("name"=>"import_preview",
	"block"=>"", "substyle"=>1  );

$layout->containers["import"][] = array("name"=>"import_process",
	"block"=>"", "substyle"=>1  );

$layout->containers["import"][] = array("name"=>"import_results",
	"block"=>"", "substyle"=>1  );

$layout->containers["import"][] = array("name"=>"importbuttons",
	"block"=>"", "substyle"=>1  );

$layout->skins["import"] = "";


$layout->skins["body"] = "";


$layout->skins["panel"] = "";


$layout->skins["page"] = "";

$layout->blocks["top"][] = "page";
$page_layouts["admin_members_import"] = $layout;




require_once('include/xtempl.php');
$xt = new Xtempl();

$id = postvalue_number("id");
$id = $id != "" ? $id : 1;

//an array of params for ImportPage constructor
$params = array();
$params["id"] = $id;
$params["xt"] = &$xt;
$params["tName"] = $strTableName;
$params["action"] = postvalue("a");
$params["pageType"] = PAGE_IMPORT;
$params["pageName"] = postvalue("page");
$params["needSearchClauseObj"] = false;
$params["strOriginalTableName"] = $strOriginalTableName;

if( $params["action"] == "importPreview" )
{
	$params["importType"] = postvalue("importType");
	$params["importText"] = postvalue("importText");
	$params["useXHR"] = postvalue("useXHR");
} 
elseif( $params["action"] == "importData" )
{
	$params["importData"] = my_json_decode( postvalue("importData") );
}

$pageObject = new ImportPage($params);
$pageObject->init();

$pageObject->process();	

?>