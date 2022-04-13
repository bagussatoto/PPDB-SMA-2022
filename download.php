<?php
/**
 * not used anymore
 */
exit();

@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");

$table = postvalue("table");
$strTableName = GetTableByShort($table);

if (!checkTableName($table))
{
	exit(0);
}

require_once("include/".$table."_variables.php");


if(!isLogged() || !CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{ 
	HeaderRedirect("login"); 
	return;
}

$field = postvalue("field");

//	check permissions
if(!$gSettings->checkFieldPermissions($field))
	return;
	
$fieldsArr = $gSettings->getFieldsList();	

foreach ($fieldsArr as $f)
{
	$fViewFormat = $gSettings->getViewFormat($f);
	if ($field == $f && ($fViewFormat != FORMAT_FILE && $fViewFormat != FORMAT_AUDIO && $fViewFormat != FORMAT_VIDEO))
	{
		exit(0);
	}
}

$_connection = $cman->byTable( $strTableName );

//	construct sql
$keysArr = $gSettings->getTableKeys();
$keys = array();
foreach ($keysArr as $ind=>$k)
{	
	$keys[$k]=postvalue("key".($ind+1));
}
$where = KeyWhere($keys, $table);


if ($gSettings->getAdvancedSecurityType() == ADVSECURITY_VIEW_OWN)
{
	$where=whereAdd($where,SecuritySQL("Search", $strTableName));	
}

$sql = $gQuery->gSQLWhere($where);
$qResult = $_connection->query( $sql );
if(!$qResult)
  return;
  
$data = $qResult->fetchAssoc();
if(!$data)
	return;

$filename = $data[$field];
$ext = substr($filename, strlen($filename)-4);
$ctype = getContentTypeByExtension($ext);

if($gSettings->isAbsolute($field))
	$absFileName = $gSettings->getUploadFolder($field).$filename;
else
	$absFileName = getabspath($gSettings->getUploadFolder($field).$filename);
			
// if no file exists return 404 err
if (!file_exists($absFileName))
{
	returnError404();
	exit();
}
// get file size
$strfilesize = filesize($absFileName);
if($strfilesize===FALSE)
{
	returnError404();
	exit();
}

header("Content-Type: ".$ctype);
header("Content-Disposition: attachment;Filename=\"".$filename."\"");
header("Cache-Control: private");
SendContentLength($strfilesize);
printfile($absFileName);
?>
