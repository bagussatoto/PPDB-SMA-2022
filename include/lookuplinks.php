<?php

/**
* getLookupMainTableSettings - tests whether the lookup link exists between the tables
*
*  returns array with ProjectSettings class for main table if the link exists in project settings.
*  returns NULL otherwise
*/
function getLookupMainTableSettings($lookupTable, $mainTableShortName, $mainField, $desiredPage = "")
{
	global $lookupTableLinks;
	if(!isset($lookupTableLinks[$lookupTable]))
		return null;
	if(!isset($lookupTableLinks[$lookupTable][$mainTableShortName.".".$mainField]))
		return null;
	$arr = &$lookupTableLinks[$lookupTable][$mainTableShortName.".".$mainField];
	$effectivePage = $desiredPage;
	if(!isset($arr[$effectivePage]))
	{
		$effectivePage = PAGE_EDIT;
		if(!isset($arr[$effectivePage]))
		{
			if($desiredPage == "" && 0 < count($arr))
			{
				$effectivePage = $arr[0];
			}
			else
				return null;
		}
	}
	return new ProjectSettings($arr[$effectivePage]["table"], $effectivePage);
}

/** 
* $lookupTableLinks array stores all lookup links between tables in the project
*/
function InitLookupLinks()
{
	global $lookupTableLinks;

	$lookupTableLinks = array();

		if( !isset( $lookupTableLinks["pendidikan"] ) ) {
			$lookupTableLinks["pendidikan"] = array();
		}
		if( !isset( $lookupTableLinks["pendidikan"]["calonppdb.pendidikan1"] )) {
			$lookupTableLinks["pendidikan"]["calonppdb.pendidikan1"] = array();
		}
		$lookupTableLinks["pendidikan"]["calonppdb.pendidikan1"]["edit"] = array("table" => "calonppdb", "field" => "pendidikan1", "page" => "edit");
		if( !isset( $lookupTableLinks["pekerjaan"] ) ) {
			$lookupTableLinks["pekerjaan"] = array();
		}
		if( !isset( $lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan1"] )) {
			$lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan1"] = array();
		}
		$lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan1"]["edit"] = array("table" => "calonppdb", "field" => "pekerjaan1", "page" => "edit");
		if( !isset( $lookupTableLinks["penghasilan"] ) ) {
			$lookupTableLinks["penghasilan"] = array();
		}
		if( !isset( $lookupTableLinks["penghasilan"]["calonppdb.penghasilan1"] )) {
			$lookupTableLinks["penghasilan"]["calonppdb.penghasilan1"] = array();
		}
		$lookupTableLinks["penghasilan"]["calonppdb.penghasilan1"]["edit"] = array("table" => "calonppdb", "field" => "penghasilan1", "page" => "edit");
		if( !isset( $lookupTableLinks["pendidikan"] ) ) {
			$lookupTableLinks["pendidikan"] = array();
		}
		if( !isset( $lookupTableLinks["pendidikan"]["calonppdb.pendidikan2"] )) {
			$lookupTableLinks["pendidikan"]["calonppdb.pendidikan2"] = array();
		}
		$lookupTableLinks["pendidikan"]["calonppdb.pendidikan2"]["edit"] = array("table" => "calonppdb", "field" => "pendidikan2", "page" => "edit");
		if( !isset( $lookupTableLinks["pekerjaan"] ) ) {
			$lookupTableLinks["pekerjaan"] = array();
		}
		if( !isset( $lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan2"] )) {
			$lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan2"] = array();
		}
		$lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan2"]["edit"] = array("table" => "calonppdb", "field" => "pekerjaan2", "page" => "edit");
		if( !isset( $lookupTableLinks["penghasilan"] ) ) {
			$lookupTableLinks["penghasilan"] = array();
		}
		if( !isset( $lookupTableLinks["penghasilan"]["calonppdb.penghasilan2"] )) {
			$lookupTableLinks["penghasilan"]["calonppdb.penghasilan2"] = array();
		}
		$lookupTableLinks["penghasilan"]["calonppdb.penghasilan2"]["edit"] = array("table" => "calonppdb", "field" => "penghasilan2", "page" => "edit");
		if( !isset( $lookupTableLinks["pendidikan"] ) ) {
			$lookupTableLinks["pendidikan"] = array();
		}
		if( !isset( $lookupTableLinks["pendidikan"]["calonppdb.pendidikan3"] )) {
			$lookupTableLinks["pendidikan"]["calonppdb.pendidikan3"] = array();
		}
		$lookupTableLinks["pendidikan"]["calonppdb.pendidikan3"]["edit"] = array("table" => "calonppdb", "field" => "pendidikan3", "page" => "edit");
		if( !isset( $lookupTableLinks["pekerjaan"] ) ) {
			$lookupTableLinks["pekerjaan"] = array();
		}
		if( !isset( $lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan3"] )) {
			$lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan3"] = array();
		}
		$lookupTableLinks["pekerjaan"]["calonppdb.pekerjaan3"]["edit"] = array("table" => "calonppdb", "field" => "pekerjaan3", "page" => "edit");
		if( !isset( $lookupTableLinks["penghasilan"] ) ) {
			$lookupTableLinks["penghasilan"] = array();
		}
		if( !isset( $lookupTableLinks["penghasilan"]["calonppdb.penghasilan3"] )) {
			$lookupTableLinks["penghasilan"]["calonppdb.penghasilan3"] = array();
		}
		$lookupTableLinks["penghasilan"]["calonppdb.penghasilan3"]["edit"] = array("table" => "calonppdb", "field" => "penghasilan3", "page" => "edit");
}

?>