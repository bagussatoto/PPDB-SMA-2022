<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");


$shortTableName = postvalue("table");
$table = GetTableByShort( $shortTableName );
if( !$table )
	exit(0);

$pageName = postvalue("pagename");

$strFilename = postvalue("filename");
$ext = substr( $strFilename, strlen($strFilename) - 4 );
$ctype = getContentTypeByExtension($ext);
$field = postvalue("field");

if( !Security::userHasFieldPermissions( $table, $field, PAGE_LIST, $pageName, false ) )
	return;

$pSet = new ProjectSettings( $table, PAGE_LIST, $pageName );
$gQuery = $pSet->getSQLQuery();

if( !$gQuery->HasGroupBy() )
{
	// Do not select any fields except current (file) field.
	// If query has 'group by' clause then other fields are used in it and we may not simply cut 'em off.
	// Just don't do anything in that case.
	$gQuery->RemoveAllFieldsExcept( $pSet->getFieldIndex($field) );
}

$_connection = $cman->byTable( $table );

//	construct sql
$keysArr = $pSet->getTableKeys();
$keys = array();
foreach( $keysArr as $ind=>$k )
{	
	$keys[$k] = postvalue("key".($ind + 1));
}
$where = KeyWhere($keys, $table);

if( $pSet->getAdvancedSecurityType() == ADVSECURITY_VIEW_OWN )
{
	$where = whereAdd( $where, SecuritySQL("Search") );	
}


$sql = $gQuery->gSQLWhere( $where );
$qResult = $_connection->query( $sql );
if( !$qResult || !($data = $qResult->fetchAssoc()) )
	return;

$value = $_connection->stripSlashesBinary( $data[$field ] );

header("Content-Type: ".$ctype);
header("Content-Disposition: attachment;Filename=\"".$strFilename."\"");
header("Cache-Control: private");
SendContentLength( strlen_bin($value) );
echoBinary( $value );
return;

?>
