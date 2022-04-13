<?php
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");
require_once("classes/searchclause.php");
require_once('include/xtempl.php');
require_once('classes/printpage.php');
require_once('classes/printpage_details.php');
require_once('classes/reportpage.php');
require_once('classes/reportprintpage.php');

add_nocache_headers();

require_once("include/admin_rights_variables.php");

if( !Security::processPageSecurity( $strtablename, 'P' ) )
	return;




$layout = new TLayout("print_bootstrap", "OfficeOffice", "MobileOffice");
$layout->version = 3;
	$layout->bootstrapTheme = "cerulean";
		$layout->customCssPageName = "admin_rights_print";
$layout->blocks["top"] = array();
$layout->containers["pdf"] = array();
$layout->container_properties["pdf"] = array(  "print" => "none"  );
$layout->containers["pdf"][] = array("name"=>"printbuttons",
	"block"=>"printbuttons", "substyle"=>1  );

$layout->skins["pdf"] = "";

$layout->blocks["top"][] = "pdf";
$layout->containers["master"] = array();
$layout->container_properties["master"] = array(  );
$layout->containers["master"][] = array("name"=>"masterinfo",
	"block"=>"mastertable_block", "substyle"=>1  );

$layout->skins["master"] = "";

$layout->blocks["top"][] = "master";
$layout->containers["pageheader"] = array();
$layout->container_properties["pageheader"] = array(  "print" => "repeat"  );
$layout->containers["pageheader"][] = array("name"=>"printheader",
	"block"=>"printheader", "substyle"=>1  );

$layout->containers["pageheader"][] = array("name"=>"page_of_print",
	"block"=>"page_number", "substyle"=>1  );

$layout->skins["pageheader"] = "";

$layout->blocks["top"][] = "pageheader";
$layout->containers["grid"] = array();
$layout->container_properties["grid"] = array(  );
$layout->containers["grid"][] = array("name"=>"printgrid",
	"block"=>"grid_block", "substyle"=>1  );

$layout->skins["grid"] = "";

$layout->blocks["top"][] = "grid";
$page_layouts["admin_rights_print"] = $layout;





$xt = new Xtempl();

//array of params for classes
$params = array();
$params["id"] = postvalue_number("id");
$params["xt"] = &$xt;
$params["pageType"] = PAGE_PRINT;
$params["pageName"] = postvalue("page");
$params["tName"] = $strTableName;
$params["selection"] = postvalue("selection"); //PrintPage::readSelectedRecordsFromRequest( "admin_rights" );
$params["allPagesMode"] = postvalue("all");
$params["detailTables"] = postvalue("details");
$params["splitByRecords"] = postvalue("records");
$params["mode"] = postvalue( "pdfjson" ) ? PRINT_PDFJSON : PRINT_SIMPLE;
$params["pdfBackgroundImage"] = postvalue("pdfBackgroundImage");


$pageObject = new PrintPage($params);
$pageObject->init();
$pageObject->process();
?>