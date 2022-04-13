<?php

class Labels {

	public static function getLanguages()
	{
		$languages = array();
		$languages[] = "Indonesian";
		return $languages;
	}

	private static function findLanguage( $lng ) {
		$languages = Labels::getLanguages();
		if( array_search( $lng, $languages ) !== FALSE )
			return $lng;
		$lng = strtoupper( $lng );
		foreach( $languages as $l )
		{
			if( strtoupper($l) == $lng )
				return $l;
		}
		return mlang_getcurrentlang();
	}

	private static function findTable( $table ) {
		$table = findTable( $table );
		if( $table == "" )
			return "";

		$ps = new ProjectSettings( $table ); // do not remove it - first init tables global settings

		return $table;
	}

	public static function getFieldLabel( $table, $field, $lng = "" ) {
		global $field_labels;
		$table = Labels::findTable( $table );
		if( $table == "" )
			return "";
		$ps = new ProjectSettings( $table );
		$field = $ps->findField( $field );
		if( $field == "" )
			return "";
		$lng = Labels::findLanguage( $lng );
		return $field_labels[ GoodFieldName($table) ][ $lng ][ GoodFieldName($field) ];
	}

	public static function setFieldLabel( $table, $field, $str, $lng = "" ) {
		global $field_labels;
		$table = Labels::findTable( $table );
		if( $table == "" )
			return false;
		$ps = new ProjectSettings( $table );
		$field = $ps->findField( $field );
		if( $field == "" )
			return false;
		$lng = Labels::findLanguage( $lng );
		$field_labels[ GoodFieldName($table) ][ $lng ][ GoodFieldName($field) ] = $str;
		return true;
	}

	public static function getTableCaption( $table, $lng = "" ) {
		global $tableCaptions;
		$table = Labels::findTable( $table );
		if( $table == "" )
			return "";
		$lng = Labels::findLanguage( $lng );
		return $tableCaptions[ $lng ][ GoodFieldName($table) ];
	}

	public static function setTableCaption( $table, $str, $lng = "" ) {
		global $tableCaptions;
		$table = Labels::findTable( $table );
		if( $table == "" )
			return false;
		$lng = Labels::findLanguage( $lng );
		$tableCaptions[ $lng ][ GoodFieldName($table) ] = $str;
		return true;
	}

	public static function getProjectLogo( $lng="")
	{
		global $globalSettings;
		$lng = Labels::findLanguage( $lng );
		return $globalSettings["ProjectLogo"][$lng];
	}

	public static function setProjectLogo( $str, $lng="" )
	{
		global $globalSettings;
		$lng = Labels::findLanguage( $lng );
		$globalSettings["ProjectLogo"][$lng] = $str;
		return true;
	}


	public static function getCookieBanner( $lng="")
	{
		global $globalSettings, $mlang_messages;
		$lng = Labels::findLanguage( $lng );
		$banner = $globalSettings["CookieBanner"][$lng];
		return $banner ? $banner : @$mlang_messages[$lng]["COOKIE_BANNER"];
	}

	public static function setCookieBanner( $str, $lng="" )
	{
		global $globalSettings;
		$lng = Labels::findLanguage( $lng );
		$globalSettings["CookieBanner"][$lng] = $str;
		return true;
	}

	public static function setFieldTooltip($table, $field, $str, $lng = "")
	{
		global $fieldToolTips;

		$table = Labels::findTable( $table );
		if( $table == "" )
			return false;
		$ps = new ProjectSettings( $table );
		$field = $ps->findField( $field );
		if( $field == "" )
			return false;
		$lng = Labels::findLanguage( $lng );
		$fieldToolTips[ GoodFieldName($table) ][ $lng ][ GoodFieldName($field) ] = $str;
		return true;
	}

	public static function getFieldTooltip($table, $field, $lng = "")
	{
		global $fieldToolTips;

		$table = Labels::findTable( $table );
		if( $table == "" )
			return "";
		$ps = new ProjectSettings( $table );
		$field = $ps->findField( $field );
		if( $field == "" )
			return "";
		$lng = Labels::findLanguage( $lng );
		return $fieldToolTips[ GoodFieldName($table) ][ $lng ][ GoodFieldName($field) ];
	}

	public static function setPageTitleTempl( $table, $page, $str, $lng = "")
	{
		global $page_titles;

		$table = Labels::findTable( $table );

		if( $table == "" )
			return false;

		$lng = Labels::findLanguage( $lng );
		$page_titles[ GoodFieldName($table) ][ $lng ][ $page ] = $str;
		return  true;
	}

	public static function getPageTitleTempl( $table, $page, $lng = "")
	{
		global $page_titles;

		$table = Labels::findTable( $table );

		if( $table == "" )
			return "";

		$lng = Labels::findLanguage( $lng );

		$templ = $page_titles[ GoodFieldName($table) ][ $lng ][ $page ];
		if( strlen($templ) )
			return $templ;
		$ps = new ProjectSettings( $table, '', $page );
		return RunnerPage::getDefaultPageTitle( $ps->getPageType(), GoodFieldName($table), $ps );
	}

	public static function setBreadcrumbsLabelTempl( $table, $str, $master = "", $page = "", $lng = "" )
	{
		global $breadcrumb_labels;
		$table = Labels::findTable( $table );
		if( !$table )
			$table = ".";
		$master = findTable( $master );
		if( !$master )
			$master = ".";
		$lng = Labels::findLanguage( $lng );
		if( $page == "")
			$page = ".";
		if( !isset( $breadcrumb_labels[$lng] ) )
			$breadcrumb_labels[$lng] = array();
		if( !isset( $breadcrumb_labels[$lng][$table] ) )
			$breadcrumb_labels[$lng][$table] = array();
		if( !isset( $breadcrumb_labels[$lng][$table][$master] ) )
			$breadcrumb_labels[$lng][$table][$master] = array();
		$breadcrumb_labels[$lng][$table][$master][ $page ] = $str;
	}

	public static function getBreadcrumbsLabelTempl( $table, $master = "", $page = "", $lng = "" ) {
		global $breadcrumb_labels;
		$table = Labels::findTable( $table );
		if( !$table )
			$table = ".";
		$master = findTable( $master );
		if( !$master )
			$master = ".";
		$lng = Labels::findLanguage( $lng );
		if( $page == "")
			$page = ".";
		if( !isset( $breadcrumb_labels[$lng] ) )
			return "";
		if( !isset( $breadcrumb_labels[$lng][$table] ) )
			return "";
		if( !isset( $breadcrumb_labels[$lng][$table][$master] ) )
			return "";
		return $breadcrumb_labels[$lng][$table][$master][ $page ];
	}

	/**
	 * @param String table
	 * @param String field
	 * @param String lng
	 * @return String
	 */
	static function getPlaceholder( $table, $field, $lng )
	{
		global $placeHolders;

		$table = findTable( $table );
		if( $table == "" )
			return "";

		$ps = new ProjectSettings( $table );
		$field = $ps->findField( $field );
		if( $field == "" )
			return "";

		$lng = Labels::findLanguage( $lng );

		return $placeHolders[ GoodFieldName($table) ][ $lng ][ GoodFieldName($field) ];
	}

	/**
	 * @param String table
	 * @param String field
	 * @param String placeHolder
	 * @param String lng
	 * @return Boolean
	 */
	static function setPlaceholder( $table, $field, $placeHolder, $lng )
	{
		global $placeHolders;

		$table = findTable( $table );
		if( $table == "" )
			return false;

		$ps = new ProjectSettings( $table );
		$field = $ps->findField( $field );
		if( $field == "" )
			return false;

		$lng = Labels::findLanguage( $lng );
		$tName = GoodFieldName( $table );
		$fName = GoodFieldName( $field );

		if( !$placeHolders[ $tName] )
			 $placeHolders[ $tName] = array();

		if( !$placeHolders[ $tName ][ $lng ] )
			 $placeHolders[ $tName ][ $lng ] = array();

		$placeHolders[ $tName ][ $lng ][ $fName ] = $placeHolder;
		return true;
	}
}

?>