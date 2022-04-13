<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");


require_once("include/dbcommon.php");
require_once("classes/searchclause.php");
require_once("classes/controls/ViewControl.php");
require_once("classes/controls/ViewControlsContainer.php");

$mode = postvalue("mode");
$shortTable = postvalue("table");
$field = postvalue("field");
$pageType = postvalue('pagetype');
$pageName = postvalue('page');
$mainTable = postvalue("maintable");
$mainField = postvalue("mainfield");

$lookup = false;
if($mainTable && $mainField) {
	$lookup = true;
}
$table = GetTableByShort( $shortTable );
if( !$table )
	exit(0);

/* TODO: add exception for List page fields from Lookup:list page with search on the Register page */
if( !Security::userHasFieldPermissions( $table, $field, $pageType, $pageName, false ) )
	return;

$pSet = new ProjectSettings( $table , $pageType, $pageName );
$cipherer = new RunnerCipherer( $table, $pSet);
$_connection = $cman->byTable( $table );
$gQuery = $pSet->getSQLQuery();


if(!$gQuery->HasGroupBy())
{
	// Do not select any fields except current (full text) field.
	// If query has 'group by' clause then other fields are used in it and we may not simply cut 'em off.
	// Just don't do anything in that case.
	$gQuery->RemoveAllFieldsExcept($pSet->getFieldIndex($field));
}

$keysArr = $pSet->getTableKeys();
$keys = array();
foreach ($keysArr as $ind=>$k)
	$keys[$k] = postvalue("key".($ind+1));

$where = KeyWhere($keys, $table);

if ($pSet->getAdvancedSecurityType() == ADVSECURITY_VIEW_OWN)
	$where = whereAdd($where,SecuritySQL("Search", $table));	

$sql = $gQuery->gSQLWhere($where);

$qResult = $_connection->query( $sql );
if(!$qResult || !($data = $cipherer->DecryptFetchedArray( $qResult->fetchAssoc() )))
{
	$returnJSON = array("success"=>false, "error"=>'Error: Wrong SQL query');
	echo printJSON($returnJSON);
	return;
}
$fieldValue = $data[$field];

$sessionPrefix = $pSet->getOriginalTableName();
if ( $mode == LIST_DASHBOARD )
{
	//set the session prefix for the dashboard list page
	$sessionPrefix = "Dashboard_".$pSet->getOriginalTableName();	
}
if($lookup) 
{
	//set the session prefix for the lookup list page
	$sessionPrefix = $pSet->getOriginalTableName()."_lookup_".$mainTable.'_'.$mainField;	
}

$searchClauseObj = SearchClause::UnserializeObject($_SESSION[$sessionPrefix."_advsearch"]);
$container = new ViewControlsContainer($pSet, PAGE_LIST, null);
$cViewControl = $container->getControl($field);

if($cViewControl->localControlsContainer && !$cViewControl->linkAndDisplaySame)
	$cViewControl->localControlsContainer->fullText = true;
else
	$cViewControl->container->fullText = true;

if($searchClauseObj)
{ 
	if($searchClauseObj->bIsUsedSrch || $useViewControl) 
	{
		$cViewControl->searchClauseObj = $searchClauseObj;
		$cViewControl->searchHighlight = true;
	}
}

$htmlEncodedValue = $cViewControl->showDBValue($data, ""); 

$returnJSON = array("success"=>true, "textCont"=>$htmlEncodedValue);
echo printJSON($returnJSON); 
return;
?>
