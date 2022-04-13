<?php 
include_once("include/dbcommon.php");

$shortTable = postvalue("table");
$table = GetTableByShort( $shortTable );
if( !$table )
	exit(0);

$field = postvalue("field");
$pageName = postvalue('page');
if( !Security::userHasFieldPermissions( $table, $field, PAGE_EDIT, $pageName, false ) )
	return;

$pSet = new ProjectSettings( $table , $pageType, $pageName );
$gQuery = $pSet->getSQLQuery();

$params = array();
$params["table"] = $table;
$params["field"] = $field;
$params["src"] = postvalue("src") == 1;
$params["alt"] = postvalue("alt");

$keysArr = $pSet->getTableKeys();
$keys = array();
foreach( $keysArr as $ind => $k )
{
	$keys[ $k ] = postvalue("key".($ind + 1));
}
$params["keys"] = $keys;

GetImageFromDB($gQuery, $params);
?>
