<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 

$shortTableName = postvalue("tableName");
$table = GetTableByShort( $shortTableName );
if( !$table )
	exit(0);

$pageType = postvalue("pageType");
$fieldName = postvalue("fieldName");
$fieldControlType = postvalue("fieldControlType");
$value = postvalue("value");

if( !Security::userHasFieldPermissions( $table, $fieldName, $pageType, $pageName, true ) )
	return;

// set db connection
$_connection = $cman->byTable( $table );

$pSet = new ProjectSettings($table, $pageType);
$denyChecking = $pSet->allowDuplicateValues( $fieldName );
$regEmailMode = false;
$regUsernameMode = false;


if( $denyChecking )
{
	$returnJSON = array("success" => false, "error" => "Duplicated values are allowed");
	echo printJSON($returnJSON);
	return;
}

$cipherer = new RunnerCipherer($table, $pSet);

if( $cipherer->isFieldEncrypted($fieldName) )
	$value = $cipherer->MakeDBValue($fieldName, $value, $fieldControlType, true);	
else
	$value = make_db_value($fieldName, $value, $fieldControlType, "", $table);

if( $value == "null" )
{
	$fieldSQL = RunnerPage::_getFieldSQL($fieldName, $_connection, $pSet);
}
else
{
	$fieldSQL = RunnerPage::_getFieldSQLDecrypt($fieldName, $_connection, $pSet, $cipherer);
}
$where = $fieldSQL . ( $value == "null" ? ' is ' : '=' ) . $value; 

/* emails should always be compared case-insensitively */
if( $regEmailMode ) {
	$where = $_connection->comparisonSQL( $fieldSQL, $value, true );
}
/* username on register page */
if( $regUsernameMode ) {
	$where = $_connection->comparisonSQL( $fieldSQL, $value, $pSet->isCaseInsensitiveUsername() );
}
$sql = "SELECT count(*) from ".$_connection->addTableWrappers( $pSet->getOriginalTableName() )." where ".$where;

$qResult = $_connection->query( $sql );
if( !$qResult || !($data = $qResult->fetchNumeric()) )
{
	$returnJSON = array("success" => false, "error" => "Error: Wrong SQL query");
	echo printJSON($returnJSON);
	return;
}

$hasDuplicates = $data[0] ? true : false;
$returnJSON = array("success" => true, "hasDuplicates" => $hasDuplicates, "error"=>"");	
echo printJSON($returnJSON);
return;
?>