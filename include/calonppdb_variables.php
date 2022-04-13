<?php
$strTableName="calonppdb";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="calonppdb";

$gstrOrderBy="ORDER BY tanggaldaftar DESC";
if(strlen($gstrOrderBy) && strtolower(substr($gstrOrderBy,0,8))!="order by")
	$gstrOrderBy="order by ".$gstrOrderBy;

// alias for 'SQLQuery' object
$gSettings = new ProjectSettings("calonppdb");
$gQuery = $gSettings->getSQLQuery();
$eventObj = &$tableEvents["calonppdb"];

$reportCaseSensitiveGroupFields = false;

$gstrSQL = $gQuery->gSQLWhere("");

?>