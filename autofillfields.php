<?php
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 


$shortTableName = postvalue("shortTName");
$table = GetTableByShort( $shortTableName );
if( !$table )
	exit(0);

$mainField = postvalue('mainField');
$linkFieldVal = postvalue('linkFieldVal');
$pageName = postvalue('page');
$pageType = postvalue('pageType');

if( !Security::userHasFieldPermissions( $table, $mainField, $pageType, $pageName, true ) )
	return;

$cipherer = new RunnerCipherer( $table );
$pSet = new ProjectSettings( $table, $pageType, $pageName );

include_once getabspath("classes/controls/EditControlsContainer.php");
$editControls = new EditControlsContainer( null, $pSet, $pageType, $cipherer );
$control = $editControls->getControl( $mainField );


$contextParams = array();
$contextParams["data"] = my_json_decode( postvalue('data') );

$masterTable = postvalue('masterTable');
if ( $masterTable != "" && isset($_SESSION[ $masterTable . "_masterRecordData" ]) )
	$contextParams["masterData"] = $_SESSION[ $masterTable . "_masterRecordData" ];

RunnerContext::push( new RunnerContextItem( CONTEXT_ROW, $contextParams ) );
	
echo printJSON( array( 'success'=> true, 'data' => $control->getAutoFillData( $linkFieldVal ) ) );

RunnerContext::pop();
exit();
?>