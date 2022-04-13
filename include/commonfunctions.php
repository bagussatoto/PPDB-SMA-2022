<?php

require_once(getabspath("include/sms.php"));

/**
 * That function  copies all elements from associative array to object, as object properties with same names
 * Usefull when you need to copy many properties
 *
 * @param link $obj
 * @param link $argsArr
 * @intellisense
 */
function RunnerApply (&$obj, &$argsArr)
{
	foreach ($argsArr as $key=>$var)
		setObjectProperty($obj,$key,$argsArr[$key]);
}

/**
 * @intellisense
 */
function GetImageFromDB( $gQuery, $params )
{
	global $cman;

	$table = $params["table"];
	$pSet = new ProjectSettings( $table );

	@ini_set("display_errors", "1");
	@ini_set("display_startup_errors", "1");


	$field = $params["field"];

	//	construct sql
	if( !$gQuery->HasGroupBy() )
	{
		// Do not select any fields except current (image) field.
		// If query has 'group by' clause then other fields are used in it and we may not simply cut 'em off.
		// Just don't do anything in that case.
		$gQuery->RemoveAllFieldsExcept( $pSet->getFieldIndex( $field ) );
	}

	$where = KeyWhere( $params["keys"], $table );
	if( Security::loginMethod() == SECURITY_TABLE ) {
		if ( $pSet->getAdvancedSecurityType() == ADVSECURITY_VIEW_OWN )
			$where = whereAdd( $where, SecuritySQL("Search") );
	}

	$connection = $cman->byTable( $table );
	$sql = $gQuery->gSQLWhere( $where );
	$data = $connection->query( $sql )->fetchAssoc();

	if( !$data )
		return DisplayNoImage();

	$value = $connection->stripSlashesBinary( $data[ $field ] );


	// !Security violation. There was no check for the $params["alt"] field
//	if( !$value && $params["alt"] )
//		$value = $connection->stripSlashesBinary( $data[ $params["alt"] ] );

	if( !$value )
		return DisplayNoImage();

	$itype = SupposeImageType( $value );
	if( !$itype )
		return DisplayFile();

	header("Content-Type: ".$itype);
	header("Cache-Control: private");
	SendContentLength( strlen_bin( $value ) );
	echoBinary( $value );

	return '';
}

/**
 *	Redirects to login.
 *  Displays Session expired message if the page was opened from within the project directory.
 *
 */
function redirectToLogin()
{

	$expired = "";
	$url = "http://";
if( $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off")
		$url = "https://";
	$url .= $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];

	global $logoutPerformed;
	if( !$logoutPerformed && isset($_SERVER['HTTP_REFERER']) )
	{
		if( getDirectoryFromURI( $_SERVER['HTTP_REFERER'] ) == getDirectoryFromURI( $url )
			&& getFilenameFromURI( $_SERVER['HTTP_REFERER'] ) != "index.htm"
			&& $_SERVER['HTTP_REFERER'] != getDirectoryFromURI( $url ))
			$expired = "message=expired";
	}
	if( !$logoutPerformed )
		$expired = "return=true&" . $expired;
	HeaderRedirect("login", "", $expired);
	exit();
}

/**
 *	Returns server and directory path from the file-based URI
 *  Examples:
 *  http://server.com:88/Calendar/login.php?a=expired => http://server.com:88/Calendar/
 *
 *  Beware! These URLs are incorrect input:
 *  https://server.com/app1
 *  https://server.com
 *
 */

function getDirectoryFromURI( $uri )
{
	$qMark = strpos( $uri, "?" );
	if( $qMark !== FALSE )
		$uri = substr( $uri, 0, $qMark );
	$lastSlash = strrpos( $uri, "/" );
	if( $lastSlash !== FALSE )
		return substr($uri, 0, $lastSlash )."/";
	return $uri;
}

function getFilenameFromURI( $uri )
{
	$qMark = strpos( $uri, "?" );
	if( $qMark !== FALSE )
		$uri = substr( $uri, $qMark );
	$lastSlash = strrpos( $uri, "/" );
	if( $lastSlash !== FALSE )
		return substr($uri, $lastSlash + 1 );
	return $uri;
}


/**
 * @intellisense
 */
function getLangFileName($langName)
{
	$langArr = array();
	$langArr["Indonesian"] = "Indonesian";
	return $langArr[$langName];
}

/**
 * @intellisense
 */
function GetGlobalData($name, $defValue = false )
{
	global $globalSettings;
	if(!array_key_exists($name, $globalSettings))
		return $defValue;
	return $globalSettings[$name];
}

/**
 * @intellisense
 */
function DisplayMap($params)
{
	global $pageObject;

	$pageObject->googleMapCfg['mapsData'][$params['id']]['addressField'] = $params['addressField'] ? $params['addressField'] : "";
	$pageObject->googleMapCfg['mapsData'][$params['id']]['latField'] = $params['latField'] ? $params['latField'] : '';
	$pageObject->googleMapCfg['mapsData'][$params['id']]['lngField'] = $params['lngField'] ? $params['lngField'] : '';
	$pageObject->googleMapCfg['mapsData'][$params['id']]['weightField'] = $params['weightField'] ? $params['weightField'] : '';
	$pageObject->googleMapCfg['mapsData'][$params['id']]['clustering'] = $params['clustering'];
	$pageObject->googleMapCfg['mapsData'][$params['id']]['heatMap'] = $params['heatMap'];
	$pageObject->googleMapCfg['mapsData'][$params['id']]['showAllMarkers'] = $params['showAllMarkers'] || $params['clustering'] || $params['heatMap'];
	$pageObject->googleMapCfg['mapsData'][$params['id']]['width'] = $params['width'] ? $params['width'] : 0;
	$pageObject->googleMapCfg['mapsData'][$params['id']]['height'] = $params['height'] ? $params['height'] : 0;
	$pageObject->googleMapCfg['mapsData'][$params['id']]['type'] = 'BIG_MAP';
	$pageObject->googleMapCfg['mapsData'][$params['id']]['showCenterLink'] = $params['showCenterLink'] ? $params['showCenterLink'] : 0;
	$pageObject->googleMapCfg['mapsData'][$params['id']]['descField'] = $params['descField'] ? $params['descField'] : $pageObject->googleMapCfg['mapsData'][$params['id']]['addressField'];
	$pageObject->googleMapCfg['mapsData'][$params['id']]['descField'] = $params['description'] ? $params['description'] : $pageObject->googleMapCfg['mapsData'][$params['id']]['addressField'];
	$pageObject->googleMapCfg['mapsData'][$params['id']]['markerAsEditLink'] = $params['markerAsEditLink'];
	$pageObject->googleMapCfg['mapsData'][$params['id']]['markerIcon'] = $params['markerIcon'] ? $params['markerIcon'] : '';
	$pageObject->googleMapCfg['mapsData'][$params['id']]['markerField'] = $params['markerField'] ? $params['markerField'] : '';

	if (isset($params['zoom']))
		$pageObject->googleMapCfg['mapsData'][$params['id']]['zoom'] = $params['zoom'];

	if ($pageObject->googleMapCfg['mapsData'][$params['id']]['showCenterLink'])
		$pageObject->googleMapCfg['mapsData'][$params['id']]['centerLinkText'] = $params['centerLinkText'] ? $params['centerLinkText'] : '';

	$pageObject->googleMapCfg['mainMapIds'][] = $params['id'];

	if (isset($params['APIkey']))
		$pageObject->googleMapCfg['APIcode'] = $params['APIkey'];
}

/**
 * @intellisense
 */
function checkTableName($shortTName, $type=false)
{
	if (!$shortTName)
		return false;

	if ("user" == $shortTName && ($type===false || ($type!==false && $type == 0)))
		return true;
	if ("calonppdb" == $shortTName && ($type===false || ($type!==false && $type == 0)))
		return true;
	if ("pendidikan" == $shortTName && ($type===false || ($type!==false && $type == 0)))
		return true;
	if ("pekerjaan" == $shortTName && ($type===false || ($type!==false && $type == 0)))
		return true;
	if ("penghasilan" == $shortTName && ($type===false || ($type!==false && $type == 0)))
		return true;
	if ("admin_rights" == $shortTName && ($type===false || ($type!==false && $type == 1)))
		return true;
	if ("admin_members" == $shortTName && ($type===false || ($type!==false && $type == 1)))
		return true;
	if ("admin_users" == $shortTName && ($type===false || ($type!==false && $type == 1)))
		return true;
	return false;
}

/**
 * Get password field on register page
 * @intellisense
 */
function GetPasswordField($table = "")
{
	global $cPasswordField;
	return $cPasswordField;
}

/**
 * Get user name field on register page
 * @intellisense
 */
function GetUserNameField($table = "")
{
	global $cUserNameField;
	return $cUserNameField;
}

/**
 * Get user name field on register page
 * @intellisense
 */
function GetDisplayNameField($table = "")
{
	global $cDisplayNameField;
	return $cDisplayNameField;
}

/**
 * Get user name field on register page
 * @intellisense
 */
function GetEmailField($table = "")
{
	global $cEmailField;
	return $cEmailField;
}

/**
 * @intellisense
 */
function GetTablesList($pdfMode = false)
{
	$arr = array();
	$checkPermissions = Security::permissionsAvailable();
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("user");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="user";
	}
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("calonppdb");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="calonppdb";
	}
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("pendidikan");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="pendidikan";
	}
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("pekerjaan");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="pekerjaan";
	}
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("penghasilan");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="penghasilan";
	}
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("admin_rights");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="admin_rights";
	}
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("admin_members");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="admin_members";
	}
	$tableAvailable = true;
	if( $checkPermissions ) {
		$strPerm = GetUserPermissions("admin_users");
		$tableAvailable = ( strpos($strPerm, "P") !== false
			|| $pdfMode && strpos($strPerm, "S") !== false );
	}
	if( $tableAvailable ) {
		$arr[]="admin_users";
	}
	return $arr;
}

/**
 * @intellisense
 */
function GetTablesListWithoutSecurity()
{
	$arr = array();
	$arr[]="user";
	$arr[]="calonppdb";
	$arr[]="pendidikan";
	$arr[]="pekerjaan";
	$arr[]="penghasilan";
	$arr[]="admin_rights";
	$arr[]="admin_members";
	$arr[]="admin_users";
	return $arr;
}

/**
 * DEPRECATED! Use RunnerPage::_getFieldSQLDecrypt instead
 * Return the full database field original name
 *
 * @param string	$field
 * @param string	$table The datasource table name
 * @param boolean	$addAs OPTIONAL
 *
 * @return String
 * @intellisense
 * @deprecated
 */
function GetFullFieldName($field, $table = "", $addAs = true, $connection = null)
{
	global $strTableName, $cman;

	if( $table == "" )
		$table = $strTableName;

	if( !$connection )
		$connection = $cman->byTable( $table );
	$pSet = new ProjectSettings($table);

	$fname = RunnerPage::_getFieldSQL($field, $connection, $pSet);

	if($pSet->hasEncryptedFields() && !$connection->isEncryptionByPHPEnabled())
	{
		$cipherer = new RunnerCipherer($table);
		return $cipherer->GetFieldName($fname, $field)
			.($cipherer->isFieldEncrypted($field) && $addAs ? " as ".$connection->addFieldWrappers($field) : "");
	}
	return $fname;
}

/**
 * returns Chart type
 * @intellisense
 */
function GetChartType($shorttable)
{
	return "";
}

////////////////////////////////////////////////////////////////////////////////
// data output functions
////////////////////////////////////////////////////////////////////////////////

/**
 * @intellisense
 */
function GetShorteningForLargeText($strValue, $cNumberOfChars, $html = true )
{
	$ret = runner_substr($strValue, 0, $cNumberOfChars );
	if( $html )
		return runner_htmlspecialchars($ret);
	return $ret;
}

/**
 * add prefix to the URL
 * @intellisense
 */
function AddLinkPrefix($pSet, $field, $link)
{
	if(strpos($link,"://")===false && substr($link,0,7)!="mailto:")
		return $pSet->getLinkPrefix($field).$link;
	return $link;
}

/**
 * @intellisense
 */
function GetTotalsForTime($value)
{
	$time=parsenumbers($value);
	while(count($time)<3)
		$time[]=0;
	return $time;
}

/**
 * return Totals string
 * @intellisense
 */
function GetTotals( $field, $value, $stype, $iNumberOfRows, $sFormat, $ptype, $pSet, $useRawValue , $pageObject )
{
	$days = 0;
	if($stype == "AVERAGE")
	{
		if($iNumberOfRows)
		{
			if($sFormat == FORMAT_TIME)
			{
				if($value)
				{
					$value = round($value/$iNumberOfRows,0);
					$s = $value % 60;
					$value -= $s;
					$value /= 60;
					$m = $value % 60;
					$value -= $m;
					$value /= 60;
					$h = $value % 24;
					$value -= $h;
					$value /= 24;
					$d = $value;

					$value = ($d!=0 ? $d.'d ' : ''). mysprintf("%02d:%02d:%02d",array($h,$m,$s));
				}
			}
			else $value = round($value/$iNumberOfRows,2);
		}
		else
			return "";
	}
	if($stype == "TOTAL" || $stype == "SUM")
	{
		if($sFormat == FORMAT_TIME)
		{
			if($value)
			{
				$s = $value % 60;
				$value -= $s;
				$value /= 60;
				$m = $value % 60;
				$value -= $m;
				$value /= 60;
				$h = $value % 24;
				$value -= $h;
				$value /= 24;
				$d = $value;
				$value = ($d!=0 ? $d.'d ' : ''). mysprintf("%02d:%02d:%02d",array($h,$m,$s));
			}
		}
	}

	if( $stype == "COUNT" || $useRawValue )
		return $value;

	$sValue = $value;
	if ( $sFormat == FORMAT_CURRENCY || $sFormat == FORMAT_PERCENT || $sFormat == FORMAT_NUMBER || $sFormat == FORMAT_CUSTOM )
	{
		// get formatted value
		$data = array( $field => $value );
		if( !$pageObject ) {
			include_once getabspath('classes/controls/ViewControlsContainer.php');
			$viewControls = new ViewControlsContainer( $pSet, $ptype );
		} else {
			$viewControls = $pageObject;
		}
		$sValue = $viewControls->showDBValue( $field, $data );
	}

	/*$sValue = "";

	if($sFormat == FORMAT_CURRENCY)
	 	$sValue = str_format_currency($value);
	else if($sFormat == FORMAT_PERCENT)
		$sValue = str_format_number($value*100)."%";
	else if($sFormat == FORMAT_NUMBER)
 		$sValue = str_format_number($value, $pSet->isDecimalDigits($field));
	else if( $sFormat == FORMAT_CUSTOM )
	{
		include_once getabspath('classes/controls/ViewControlsContainer.php');
		$data = array($field => $value);
		$viewControls = new ViewControlsContainer($pSet, $ptype);
		$sValue = $viewControls->showDBValue($field, $data);
	}
	else
 		$sValue = $value;
	*/

	if( $stype == "TOTAL" || $stype == "SUM" || $stype == "AVERAGE" )
		return $sValue;

	// wrong stype ?
	return "";
}


/**
 * @intellisense
 */
function DisplayNoImage()
{
	$path = getabspath("images/no_image.gif");
	header("Content-Type: image/gif");
	printfile($path);
}

/**
 * @intellisense
 */
function DisplayFile()
{
	$path = getabspath("images/file.gif");
	header("Content-Type: image/gif");
	printfile($path);
}

////////////////////////////////////////////////////////////////////////////////
// miscellaneous functions
////////////////////////////////////////////////////////////////////////////////

/**
 * analog of strrpos function
 * @intellisense
 */
function my_strrpos($haystack, $needle)
{
	$index = strpos(strrev($haystack), strrev($needle));
	if($index === false)
		return false;
	$index = strlen($haystack) - strlen($needle) - $index;
	return $index;
}

/**
 * prepare string for JavaScript. Replace ' with \' and linebreaks with \r\n
 * @intellisense
 */
function jsreplace($str)
{
	$ret= str_replace(array("\\","'",'"',"\r","\n"),array("\\\\","\\'",'\\"',"\\r","\\n"),$str);
	return my_str_ireplace("</script>","</scr'+'ipt>",$ret);
}

/**
 * @intellisense
 */
function LogInfo($SQL)
{/*
	global $dSQL,$dDebug;
	$dSQL=$SQL;
	if($dDebug)
	{
		echo $dSQL;
		echo "<br>";
	}*/
}

/**
 * check if file extension is image extension
 * @intellisense
 */
function CheckImageExtension($filename)
{
	if(strlen($filename)<4)
		return false;
	$ext=strtoupper(substr($filename,strlen($filename)-4));
	if($ext==".GIF" || $ext==".JPG" || $ext=="JPEG" || $ext==".PNG" || $ext==".BMP")
		return $ext;
	return false;
}

/**
 * @intellisense
 */
function html_special_decode($str)
{
	$ret=$str;
	$ret=str_replace("&gt;",">",$ret);
	$ret=str_replace("&lt;","<",$ret);
	$ret=str_replace("&quot;","\"",$ret);
	$ret=str_replace("&#039;","'",$ret);
	$ret=str_replace("&#39;","'",$ret);
	$ret=str_replace("&amp;","&",$ret);
	return $ret;
}

////////////////////////////////////////////////////////////////////////////////
// database and SQL related functions
////////////////////////////////////////////////////////////////////////////////

/**
 * add clause to WHERE or HAVING expression
 * @intellisense
 */
function whereAdd($where,$clause)
{
	if(!strlen($clause))
		return $where;
	if(!strlen($where))
		return $clause;
	return "(".$where.") and (".$clause.")";
}

/**
 * combine SQL logincal clauses from array using AND (default) or OR
 * @intellisense
 */
function combineSQLCriteria($arrElements, $and = true)
{
	$filteredCriteria = array();
	$union = $and ? " AND " : " OR ";

	foreach($arrElements as $e)
	{
		if(strlen($e))
			$filteredCriteria[] = "( " . $e . " )";
	}
	return implode( $union, $filteredCriteria );
}


function sqlMoreThan( $field, $value )
{
	if( $value == 'null' )
		return $field . " is not null";
	return $field . " > " . $value;
}
function sqlLessThan( $field, $value )
{
	if( $value == 'null' )
		return "";
	return $field . " < " . $value . ' or ' . $field . ' is null';

}

function sqlEqual( $field, $value )
{
	if( $value == 'null' )
		return $value . ' is null';
	return $field . " = " . $value;
}


/**
 * add WHERE clause to SQL string
 * @intellisense
 */
function AddWhere($sql,$where)
{
	if(!strlen($where))
		return $sql;
	$sql=str_replace(array("\r\n","\n","\t")," ",$sql);
	$tsql = strtolower($sql);
	$n = my_strrpos($tsql," where ");
	$n1 = my_strrpos($tsql," group by ");
	$n2 = my_strrpos($tsql," order by ");
	if($n1===false)
		$n1=strlen($tsql);
	if($n2===false)
		$n2=strlen($tsql);
	if ($n1>$n2)
		$n1=$n2;
	if($n===false)
		return substr($sql,0,$n1)." where ".$where.substr($sql,$n1);
	else
		return substr($sql,0,$n+strlen(" where "))."(".substr($sql,$n+strlen(" where "),$n1-$n-strlen(" where ")).") and (".$where.")".substr($sql,$n1);
}

/**
 *  DEPRECATED. Use RunnerPage::keysSQLExpression instead
 *	Construct WHERE clause with key values
 *
 * @param &Array	$keys
 * @param String	$table Teh data source table name OPTIONAL
 *
 * @return String
 * @intellisense
 * @deprecated
 */
function KeyWhere(&$keys, $table )
{
	global $cman;

	$strWhere="";

	$pSet = new ProjectSettings($table);
	$cipherer = new RunnerCipherer($table);
	$connection = $cman->byTable( $table );

	$keyFields = $pSet->getTableKeys();
	foreach($keyFields as $kf)
	{
		if( strlen($strWhere) )
			$strWhere.= " and ";

		$value = $cipherer->MakeDBValue($kf, $keys[ $kf ], "", true);

		if( $connection->dbType == nDATABASE_Oracle )
			$valueisnull = $value === "null" || $value == "''";
		else
			$valueisnull = $value === "null";

		if( $valueisnull )
			$strWhere.= RunnerPage::_getFieldSQL($kf, $connection, $pSet)." is null";
		else
			$strWhere.= RunnerPage::_getFieldSQLDecrypt($kf, $connection, $pSet, $cipherer)."=".$cipherer->MakeDBValue($kf, $keys[ $kf ], "", true);
	}
	return $strWhere;
}

/**
 * Get count of rows from the query
 * @param String strSQL
 * @param Connection connection
 * @return Number
 * @intellisense
 */
function GetRowCount($strSQL, $connection)
{
	$strSQL = str_replace(array("\r\n","\n","\t")," ",$strSQL);
	$tstr = strtoupper($strSQL);
	$ind1 = strpos($tstr, "SELECT ");
	$ind2 = my_strrpos($tstr," FROM ");
	$ind3 = my_strrpos($tstr," GROUP BY ");
	if($ind3 === false)
	{
		$ind3 = strpos($tstr," ORDER BY ");
		if($ind3 === false)
			$ind3 = strlen($strSQL);
	}

	$countstr = substr($strSQL, 0, $ind1 + 6)." count(*) ".substr($strSQL, $ind2 + 1, $ind3 -$ind2);
	$countdata = $connection->query( $countstr )->fetchNumeric();
	return $countdata[0];
}

/**
 * 	add MSSQL Server TOP clause
 * @intellisense
 */
function AddTop( $strSQL, $n )
{
	$pattern = "/(^\\s*select\\s+distinct\\s+)|(^\\s*select\\s+)/i";
	$matches = array();
	preg_match_all( $pattern, $strSQL, $matches );

	if( $matches[0] ) {
		return $matches[0][0] . "top " . $n . " " . substr( $strSQL, strlen( $matches[0][0] ) );
	}
	return $strSQL;
}

/**
 * 	add DB2 Server TOP clause
 * @intellisense
 */
function AddTopDB2($strSQL, $n)
{
	return $strSQL." fetch first ".$n." rows only";
}
/**
 * @intellisense
 */
function AddTopIfx($strSQL,$n)
{
	return substr($strSQL,0,7)."limit ".$n." ".substr($strSQL,7);
}
/**
 * 	add Oracle ROWNUMBER checking
 * @intellisense
 */
function AddRowNumber($strSQL, $n)
{
	return "select * from (".$strSQL.") where rownum<".($n+1);
}

/**
 * Apply a limit to an SQL-query
 * @param String sql
 * @param Number N
 * @param Number dbType
 * @return String
 */
function applyDBrecordLimit($sql, $N, $dbType)
{
	if( !strlen($dbType) )
		return $sql;

	if( $dbType == nDATABASE_MySQL || $dbType == nDATABASE_PostgreSQL || $dbType == nDATABASE_SQLite3 )
		return $sql." LIMIT ".$N;

	if( $dbType == nDATABASE_Oracle )
		return AddRowNumber($sql, $N);

	if( $dbType == nDATABASE_MSSQLServer || $dbType == nDATABASE_Access )
		return AddTop($sql, $N);

	if( $dbType == nDATABASE_Informix )
		return AddTopIfx($sql, $N);

	if( $dbType == nDATABASE_DB2 )
		return AddTopDB2($sql, $N);

	return $sql;
}

/**
 *  test database type if values need to be quoted
 * @intellisense
 */
function NeedQuotesNumeric($type)
{
    if($type == 203 || $type == 8 || $type == 129 || $type == 130 ||
		$type == 7 || $type == 133 || $type == 134 || $type == 135 ||
		$type == 201 || $type == 205 || $type == 200 || $type == 202 || $type==72 || $type==13)
		return true;
	else
		return false;
}

//	using ADO DataTypeEnum constants
//	the full list available at:
//	https://docs.microsoft.com/en-us/sql/ado/reference/ado-api/datatypeenum?view=sql-server-2017
//	or
//	https://www.google.com/search?q=ADO+DataTypeEnum

/**
 * @intellisense
 */
function IsNumberType($type)
{
	if($type==20 || $type==14 || $type==5 || $type==10 || $type==6
	|| $type==3 || $type==131 || $type==4 || $type==2 || $type==16
	|| $type==21 || $type==19 || $type==18 || $type==17 || $type==139
	|| $type==11)
		return true;
	return false;
}

/**
 * @intellisense
 */
function IsFloatType($type)
{
	if($type==14 || $type==5 || $type==131 || $type==4 || $type==6)
		return true;
	return false;
}


/**
 * @intellisense
 */
function NeedQuotes($type)
{
	return !IsNumberType($type);
}

/**
 * @intellisense
 */
function IsBinaryType($type)
{
	if($type==128 || $type==205 || $type==204)
		return true;
	return false;
}

/**
 * date and datetime
 * @intellisense
 */
function IsDateFieldType($type)
{
	if($type==7 || $type==133 || $type==135)
		return true;
	return false;
}

/**
 * only datetime
 * @intellisense
 */
function IsDateTimeFieldType($type)
{
	if($type==135)
		return true;
	return false;
}

/**
 * @intellisense
 */
function IsTimeType($type)
{
	if($type==134)
		return true;
	return false;
}

/**
 * @intellisense
 */
function IsCharType($type)
{
	if(IsTextType($type) || $type==8 || $type==129 || $type==200 || $type==202 ||  $type==130)
		return true;
	return false;
}

/**
 * @intellisense
 */
function IsTextType($type)
{
	if($type==201 || $type==203)
		return true;
	return false;
}

/**
 * @intellisense
 */
function IsGuid($type)
{
	if($type==72)
		return true;
	return false;
}

function IsBigInt($type)
{
	if($type==20)
		return true;
	return false;
}

function ReadUserPermissions($userID = "")
{
	global $gPermissionsRead, $gPermissionsRefreshTime, $caseInsensitiveUsername, $cman;

	if (!strlen($userID))
		$userID = $_SESSION["UserID"];

	$needreload = false;
	if( !isset( $_SESSION["UserRights"] ) )
		$needreload = true;
	elseif( !isset( $_SESSION["UserRights"][ $userID ] ) )
		$needreload = true;

	if(!$needreload && ($gPermissionsRead || time()-@$_SESSION["LastReadRights"]<=$gPermissionsRefreshTime))
		return;

	$groups = array();
	$bIsAdmin = false;
	$gConn = $cman->getForUserGroups();
	$userGroups = array();

	if($userID != "Guest")
	{

		$usernameClause = $gConn->comparisonSQL( $gConn->addFieldWrappers( "UserName" ), $gConn->prepareString($userID), $caseInsensitiveUsername );
		$sql = "select ".$gConn->addFieldWrappers( "GroupID" )
			.", ".$gConn->addFieldWrappers( "UserName" )
			." from ". $gConn->addTableWrappers( "ppdb2022_ugmembers" )
			." where " . $usernameClause;

		$qResult = $gConn->query( $sql );
		while( $data = $qResult->fetchNumeric() )
		{
			if ( $caseInsensitiveUsername || strcmp($data[1],$userID) == 0 )
				$groups[] = $data[0];
		}

		if( !count($groups) )
			$groups[] = -2;
	}
	else
		$groups[] = -3;


	$groupstr = "";
	foreach($groups as $g)
	{
		if($groupstr != "")
			$groupstr.= ",";
		$groupstr.= $g;
		if($g == -1)
			$bIsAdmin = true;
	}
	$rights = array();

	$sql = "select ". $gConn->addFieldWrappers( "TableName" )
		.", ". $gConn->addFieldWrappers( "AccessMask" )
		.", ". $gConn->addFieldWrappers( "Page" )
		." from ". $gConn->addTableWrappers( "ppdb2022_ugrights" )
		." where ". $gConn->addFieldWrappers( "GroupID" ) ." in (".$groupstr.")";

	$qResult = $gConn->query( $sql );
	while( $data = $qResult->fetchNumeric() )
	{
		$table = $data[0];
		$mask = $data[1];
		$restrictedPages = my_json_decode( $data[2] );
		if( !is_array( $restrictedPages )) {
			$restrictedPages = array();
		}
		if(!array_key_exists( $table, $rights))
		{
			$rights[ $table ] = array( "mask" => $mask, "pages" => $restrictedPages );
			continue;
		}
		$currentMask = $rights[ $table ]["mask"];
		$currentPages = &$rights[ $table ]["pages"];
		for($i = 0; $i < strlen($mask); $i++)
		{
			$perm = substr($mask, $i, 1);
			if( strpos($currentMask, $perm ) === false )
				$rights[ $table ]["mask"] .= $perm;
		}
		foreach( $restrictedPages as $page => $dummy ) {
			$currentPages[$page] = true;
		}
	}

	if(!array_key_exists("UserRights", $_SESSION))
		$_SESSION["UserRights"] = array();

	if($bIsAdmin)
		$rights[".IsAdmin"] = true;
	$rights[".Groups"] = $groups;
	$_SESSION["UserRights"][ $userID ] = &$rights;
	$_SESSION["LastReadRights"] = time();

	$gPermissionsRead = true;
}


/**
 * @intellisense
 */
function GetUserPermissionsDynamic($table="")
{
	if( !isLogged() )
		return "";
	global $strTableName,$gPermissionsRefreshTime,$gPermissionsRead;
	if(!$table)
		$table=$strTableName;

	ReadUserPermissions();
	if(IsAdmin())
	{
		if($table=="admin_rights")
			return "ADESPIM";
		if($table=="admin_members")
			return "ADESPIM";
		if($table=="admin_users")
			return "ADESPIM";
	}

	return @$_SESSION["UserRights"][$_SESSION["UserID"]][$table]["mask"];
}


/**
 * @intellisense
 */
function GetUserPermissionsStatic( $table )
{
	if( !isLogged() )
		return "";

	$extraPerm = $_SESSION["AccessLevel"] == ACCESS_LEVEL_ADMINGROUP ? 'M' : '';
	$sUserGroup = @$_SESSION["GroupID"];
	if( $table=="user" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	if( $table=="calonppdb" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	if( $table=="pendidikan" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	if( $table=="pekerjaan" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	if( $table=="penghasilan" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	if( $table=="admin_rights" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	if( $table=="admin_members" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	if( $table=="admin_users" )
	{
//	default permissions
		// grant all by default
		return "ADESPI".$extraPerm;
	}
	// grant nothing by default
	return "";
}

/**
 * Check if the current user is admin user
 * @return Boolean
 * @intellisense
 */
function IsAdmin()
{
	if( !Security::dynamicPermissions() ) {
		return false;
	}
	global $gPermissionsRefreshTime, $gPermissionsRead, $caseInsensitiveUsername;
	ReadUserPermissions();
	return array_key_exists(".IsAdmin", @$_SESSION["UserRights"][ $_SESSION["UserID"] ]);
}

/**
 * Returns user permissions for a particular table
 *	A - Add
 *	D - Delete
 *	E - Edit
 *	S - List/View/Search
 *	P - Print/Expor
 * @param String table
 * @return String
 * @intellisense
 */
function GetUserPermissions($table="")
{
	global $strTableName, $globalEvents;
	if(!$table)
		$table = $strTableName;
	$permissions = "";
	if( Security::hasLogin() ) {
		if( !IsLogged() )
			return "";
	}

	if( is_array( $_SESSION["securityOverrides"] ) )
	{
		if( isset( $_SESSION["securityOverrides"][ $table ] ) )
			return $_SESSION["securityOverrides"][ $table ];
	}

	if( Security::permissionsAvailable() ) {
		if( Security::dynamicPermissions() ) {
			$permissions =  GetUserPermissionsDynamic($table);
		} else {
			$permissions =  GetUserPermissionsStatic($table);
		}
	} else {
		$permissions =  "ADESPIM";
	}

	if($globalEvents->exists("GetTablePermissions", $table))
	{
		$permissions = $globalEvents->GetTablePermissions($permissions, $table);
	}
	return $permissions;
}

/**
 * @intellisense
 */
function isLogged()
{
	if( !Security::hasLogin() ) {
		return true;
	}

	if( @$_SESSION["UserID"] )
		return true;

	return false;
}


/**
 * @intellisense
 */
function guestHasPermissions()
{
	$tables = GetTablesListWithoutSecurity();
	if( Security::dynamicPermissions() ) {
		ReadUserPermissions("Guest");
		if(!count($_SESSION["UserRights"]["Guest"]))
			return false;
		foreach($tables as $t) {
			if(array_key_exists( $t ,$_SESSION["UserRights"]["Guest"]))
				return true;
		}
		return false;
	} else {
		return false;
	}
}

/**
 * SetAuthSessionData
 * Add to session auth data and permissions
 * @intellisense
 */
function SetAuthSessionData($pUsername, &$data, $password, &$pageObject = null, $fireEvents = true )
{
	global $globalEvents, $cUserGroupField;
	if( Security::permissionsAvailable() ) {
		$_SESSION["GroupID"] = $data[ $cUserGroupField ];
	} else {
		$_SESSION["GroupID"] = "";
	}



	$_SESSION["UserData"] = $data;

	if( $fireEvents && $globalEvents->exists("AfterSuccessfulLogin") )
	{
		$globalEvents->AfterSuccessfulLogin($pUsername != "Guest" ? $pUsername : "", $password, $data, $pageObject);
	}
}

/**
 * @intellisense
 */
function DoLogin($callAfterLoginEvent = false, $userID = "Guest", $userName = "", $groupID = "<Guest>", $accessLevel = ACCESS_LEVEL_GUEST, $password = "", &$pageObject = null)
{
	global $globalEvents;

	if($userID == "Guest" && $userName == "")
		$userName = "Guest";

	if( !GetGlobalData("bTwoFactorAuth", false) || $userID == "Guest" )
	{
		$_SESSION["UserID"] = $userID;
		$_SESSION["UserName"] = runner_htmlspecialchars( $userName );
		$_SESSION["GroupID"] = $groupID;
		$_SESSION["AccessLevel"] = $accessLevel;
	}

	$auditObj = GetAuditObject();
	if($auditObj)
	{
		$auditObj->LogLogin($userID);
		if( $userID != "Guest" )
			$auditObj->LoginSuccessful();
	}
	if($callAfterLoginEvent && $globalEvents->exists("AfterSuccessfulLogin"))
	{
		$dummy = array();
		$globalEvents->AfterSuccessfulLogin($userID != "Guest" ? $userID : "", $password, $dummy, $pageObject);
	}
}

/**
 * @intellisense
 */
function CheckSecurity($strValue, $strAction, $table = "")
{
	if( !Security::hasLogin() ) {
		return true;
	}

	global $cAdvSecurityMethod, $strTableName;
	if( $table == "" )
		$table = $strTableName;
	$pSet = new ProjectSettings($table);

	if($_SESSION["AccessLevel"]==ACCESS_LEVEL_ADMIN)
		return true;

	$strPerm = GetUserPermissions();
	if( strpos($strPerm, "M") === false )
	{
	}
	if( Security::permissionsAvailable() )
	{
//	 check user group permissions
		$localAction = strtolower($strAction);
		return ( $localAction == "add" && strpos($strPerm, "A") !== false ||
			$localAction == "edit" && strpos($strPerm, "E") !== false ||
			$localAction == "delete" && strpos($strPerm, "D") !== false ||
			$localAction == "search" && strpos($strPerm, "S") !== false ||
			$localAction == "import" && strpos($strPerm, "I") !== false ||
			$localAction == "export" && strpos($strPerm, "P") !== false );
	}
	return true;
}

/**
 * @intellisense
 */
function CheckTablePermissions($strTableName, $permission)
{
	if( strpos(GetUserPermissions($strTableName), $permission) === false )
		return false;

	return true;
}

function pagetypeToPermissions($pageType)
{
	global $_pagetypeToPermissions_dict;

	if(!$_pagetypeToPermissions_dict)
	{
		$_pagetypeToPermissions_dict = array();
		$_pagetypeToPermissions_dict["list"] = "S";
		$_pagetypeToPermissions_dict["search"] = "S";
		$_pagetypeToPermissions_dict["view"] = "S";
		$_pagetypeToPermissions_dict["add"] = "A";
		$_pagetypeToPermissions_dict["edit"] = "E";
		$_pagetypeToPermissions_dict["print"] = "P";
		$_pagetypeToPermissions_dict["export"] = "P";
		$_pagetypeToPermissions_dict["import"] = "I";
	}

	return $_pagetypeToPermissions_dict[$pageType];
}

/**
 * Add security WHERE clause to SELECT SQL command
 * @intellisense
 */
function SecuritySQL($strAction, $table="", $strPerm="")
{
	global $cAdvSecurityMethod,$strTableName;

	if (!strlen($table))
		$table = $strTableName;

	$pSet = new ProjectSettings($table);

   	$ownerid = @$_SESSION["_".$table."_OwnerID"];
	$ret="";
	if(@$_SESSION["AccessLevel"]==ACCESS_LEVEL_ADMIN)
		return "";

	$ret="";
	if(!strlen($strPerm))
		$strPerm = GetUserPermissions($table);

	if(strpos($strPerm,"M")===false)
	{
	}

	if($strAction=="Edit" && !(strpos($strPerm, "E")===false) ||
	   $strAction=="Delete" && !(strpos($strPerm, "D")===false) ||
	   $strAction=="Search" && !(strpos($strPerm, "S")===false) ||
	   $strAction=="Export" && !(strpos($strPerm, "P")===false) )
		return $ret;
	else
		return "1=0";
	return "";
}

////////////////////////////////////////////////////////////////////////////////
// editing functions
////////////////////////////////////////////////////////////////////////////////
/**
 * @intellisense
 */
function make_db_value($field,$value,$controltype="",$postfilename="",$table="")
{
	$ret = prepare_for_db($field, $value, $controltype, $postfilename, $table);

	if($ret === false)
		return $ret;

	return add_db_quotes($field, $ret, $table);
}

/**
 * @param String field
 * @param Mixed value
 * @param String table		The datasource table name
 * @param String type
 * @intellisense
 */
function add_db_quotes($field, $value, $table = "", $type = null)
{
	global $strTableName, $locale_info, $cman;

	if( $table == "" )
		$table=$strTableName;

	$pSet = new ProjectSettings($table);
	$connection = $cman->byTable( $table );

	if( $type == null )
		$type = $pSet->getFieldType($field);

	if( IsBinaryType($type) )
		return $connection->addSlashesBinary( $value );

	if( ($value === "" || $value === FALSE || is_null($value)) && !IsCharType($type) )
		return "null";

	if(NeedQuotes($type))
	{
		if( !IsDateFieldType($type) )
			$value = $connection->prepareString($value);
		else
		{
			$y = "(\d\d\d\d)";
			$m = "(0?[1-9]|1[0-2])";
			$d = "(0?[1-9]|[1-2][0-9]|3[0-1])";
			$delim = "(-|".preg_quote($locale_info["LOCALE_SDATE"], "/").")";
			$reg = "/".$d.$delim.$m.$delim.$y."|".$m.$delim.$d.$delim.$y."|".$y.$delim.$m.$delim.$d."/";
			if( !preg_match($reg, $value, $matches) )
				return "null";

			$value = $connection->addDateQuotes( $value );
		}
	}
	else
	{
		// if boolean type field, add quotes
		if( $connection->dbType == nDATABASE_PostgreSQL && $type == 11 )
		{
			$value = strtolower($value);
			if (!strlen($value) || $value == 0 || $value == "0" || $value == "false" || $value == "f" || $value == "n" || $value == "no" || $value == "off")
				$value = "f";
			else
				$value = "t";
			$value = $connection->prepareString($value);
		}
		else
			$value = DB::prepareNumberValue( $value );
	}
	return $value;
}


/**
 * @param String field
 * @param Mixed value
 * @param String controltype
 * @param String postfilename
 * @param String table			The datasource table name
 * @intellisense
 */
function prepare_for_db($field, $value, $controltype = "", $postfilename = "", $table = "")
{
	global $strTableName, $cman;

	if( $controltype == "display" )
		return $value;

	if($table == "")
		$table = $strTableName;

	$pSet = new ProjectSettings($table);
	$connection = $cman->byTable( $table );

	$filename = "";
	$type = $pSet->getFieldType($field);
	if((!$controltype || $controltype == "multiselect") && !IsTimeType($type))
	{
		if(is_array($value))
			$value = combinevalues($value);
		if(($value === "" || $value === FALSE) && !IsCharType($type))
			return "";
		if(IsGuid($type))
		{
			if(!IsGuidString($value))
				return "";
		}

		if( IsFloatType($type) )
			return makeFloat($value);

		if( IsNumberType($type) && !is_int($value) )
		{
			$value = trim($value);
			if( !is_numeric( str_replace(",", ".", $value) ) )
				$value = "";
		}
		return $value;
	}
	else if($controltype == "time" || IsTimeType($type))
	{
		if(!strlen($value))
			return "";

		$time = localtime2db($value);
		if( $connection->dbType == nDATABASE_PostgreSQL )
		{
			$timeArr = explode(":",$time);
			if($timeArr[0]>24 || $timeArr[1]>59 || $timeArr[2]>59)
				return "";
		}

		if( IsDateFieldType($type) )
			$time = "2000-01-01 ".$time;

		return $time;
	}
	else if(substr($controltype, 0, 4) == "date")
	{
		$dformat = substr($controltype, 4);
		if($dformat == EDIT_DATE_SIMPLE || $dformat == EDIT_DATE_SIMPLE_INLINE || $dformat == EDIT_DATE_SIMPLE_DP)
		{
			$time = localdatetime2db($value);
			if($time == "null")
				return "";
			return $time;
		}
		else if($dformat == EDIT_DATE_DD || $dformat == EDIT_DATE_DD_INLINE || $dformat == EDIT_DATE_DD_DP)
		{
			$a = explode("-",$value);
			if(count($a) < 3)
				return "";
			else
			{
				$y = $a[0];
				$m = $a[1];
				$d = $a[2];
			}
			if($y < 100)
			{
				if($y < 70)
					$y += 2000;
				else
					$y += 1900;
			}
			return mysprintf("%04d-%02d-%02d",array($y,$m,$d));
		}
		else
			return "";
	}
	else if(substr($controltype, 0, 8) == "checkbox")
	{
		if($value == "on")
			$ret = 1;
		else if($value == "none")
			return "";
		else
			$ret = 0;
		return $ret;
	}
	else
		return false;
}

/**
 * 	delete uploaded files when deleting the record
 * @intellisense
 */
function DeleteUploadedFiles($pSet, $deleted_values)
{
	foreach($deleted_values as $field => $value)
	{
		if(($pSet->getEditFormat($field) == EDIT_FORMAT_FILE || $pSet->getPageTypeByFieldEditFormat($field, EDIT_FORMAT_FILE) != "")
			&& $pSet->isDeleteAssociatedFile($field))
		{
			if(!strlen($value))
				return;

			$filesArray = my_json_decode($value);
			if(!is_array($filesArray) || count($filesArray) == 0)
			{
				$filesArray = array(array("name" => $pSet->getUploadFolder($field).$value));
				if($pSet->getCreateThumbnail($field))
					$filesArray[0]["thumbnail"] = $pSet->getUploadFolder($field).$pSet->getStrThumbnail($field).$value;
			}

			foreach($filesArray as $delFile)
			{
				$filename = $delFile["name"];
				$isAbs = $pSet->isAbsolute($field) || isAbsolutePath($filename);
				if(!$isAbs)
					$filename = getabspath($filename);
				runner_delete_file($filename);
				if($delFile["thumbnail"] != "")
				{
					$filename = $delFile["thumbnail"];
					if(!$isAbs)
						$filename = getabspath($filename);
					runner_delete_file($filename);
				}
			}
		}
	}
}

/**
 * 	combine checked values from multi-select list box
 * @intellisense
 */
function combinevalues($arr)
{
	$ret="";
	foreach($arr as $item)
	{
		$val = $item;
		if(strlen($ret))
			$ret.=",";
		if(strpos($val,",")===false && strpos($val,'"')===false)
			$ret.=$val;
		else
		{
			$val=str_replace('"','""',$val);
			$ret.='"'.$val.'"';
		}
	}
	return $ret;
}

/**
 * 	split values for multi-select list box
 * @intellisense
 */
function splitvalues($str)
{
	$arr=array();
	if($str=="")
	{
		$arr[] = "";
		return $arr;
	}
	$start=0;
	$i=0;
	$inquot=false;
	while($i<=strlen($str))
	{
		if($i<strlen($str) && substr($str,$i,1)=='"')
			$inquot=!$inquot;
		else if($i==strlen($str) || !$inquot && substr($str,$i,1)==',')
		{
			$val=substr($str,$start,$i-$start);
			$start=$i+1;
			if(strlen($val) && substr($val,0,1)=='"')
			{
				$val=substr($val,1,strlen($val)-2);
				$val=str_replace('""','"',$val);
			}

			if( $val !== FALSE )
			$arr[]=$val;
		}
		$i++;
	}
	return $arr;
}

/**
 *
 */
function GetLookupFieldsIndexes($pSet, $field)
{
	$lookupTable = $pSet->getLookupTable($field);
	$lookupType = $pSet->getLookupType($field);
	$displayFieldName = $pSet->getDisplayField($field);
	$linkFieldName = $pSet->getLinkField($field);
	$linkAndDisplaySame = $linkFieldName == $displayFieldName;
	if($lookupType == LT_QUERY)
	{
		$lookupPSet = new ProjectSettings($lookupTable);
		$linkFieldIndex = $lookupPSet->getFieldIndex($linkFieldName) - 1;
		if($linkAndDisplaySame)
			$displayFieldIndex = $linkFieldIndex;
		else
		{
			if($pSet->getCustomDisplay($field))
				$displayFieldIndex = $lookupPSet->getCustomExpressionIndex($pSet->_table, $field);
			else
				$displayFieldIndex = $lookupPSet->getFieldIndex($displayFieldName) - 1;
		}
	}
	else
	{
		$linkFieldIndex = 0;
		$displayFieldIndex = $linkAndDisplaySame ? 0 : 1;
	}
	return array("linkFieldIndex" => $linkFieldIndex, "displayFieldIndex" => $displayFieldIndex);
}

//////////////////////////////////////////////////////////////////////////////
/**
 * Get locale, am, pm for field edit as time
 * @param integer $convention - 24 or 12 hours format for timePicker
 * @param boolean $useTimePicker -  use timePicker or not
 * @return array
 * @intellisense
 */
function getLacaleAmPmForTimePicker($convention, $useTimePicker = false)
{
	$am = '';
	$pm = '';
	global $locale_info;
	if($useTimePicker)
	{
		$locale_convention = $locale_info["LOCALE_ITIME"] ? 24 : 12;
		if($convention == $locale_convention)
		{
			$am = $locale_info["LOCALE_S1159"];
			$pm = $locale_info["LOCALE_S2359"];
			$locale = $locale_info["LOCALE_STIMEFORMAT"];
		}
		else
		{
			if($convention == 24)
			{
				$am = '';
				$pm = '';
				$locale = "H:mm:ss";
			}
			else
			{
				$am = 'am';
				$pm = 'pm';
				$locale = "h:mm:ss tt";
			}
		}
	}
	else
		$locale = $locale_info["LOCALE_STIMEFORMAT"];

	return array('am'=>$am,'pm'=>$pm,'locale'=>$locale);
}

/**
 * Get value for field edit as time and get dpTime settings
 * @param integer $convention - 24 or 12 hours format for timePicker
 * @param string $type - type of field
 * @param string $value - value of field
 * @param boolean $useTimePicker -  use timePicker or not
 * @return array
 * @intellisense
 */
function getValForTimePicker($type,$value,$locale)
{
	$val = "";
	$dbtime = array();
	if(IsDateFieldType($type))
	{
		$dbtime = db2time($value);
		if(count($dbtime))
			$val = format_datetime_custom($dbtime, $locale);
	}
	else
	{
		$arr = parsenumbers($value);
		if(count($arr))
		{
			while(count($arr)<3)
				$arr[] = 0;
			$dbtime = array(0, 0, 0, $arr[0], $arr[1], $arr[2]);
			$val = format_datetime_custom($dbtime, $locale);
		}
	}

	return array('val'=>$val,'dbTime'=>$dbtime);
}


/**
 * @intellisense
 */
function my_stripos($str,$needle, $offest)
{
	if (strlen($needle)==0 || strlen($str)==0)
		return false;
	return strpos(strtolower($str),strtolower($needle), $offest);
}

/**
 * @intellisense
 */
function my_str_ireplace($search, $replace,$str)
{
	$pos=my_stripos($str,$search,0);
	if($pos===false)
		return $str;
	return substr($str,0,$pos).$replace.substr($str,$pos+strlen($search));
}


/**
 * @intellisense
 */
function in_assoc_array($name, $arr)
{
	foreach ($arr as $key => $value)
	{
		if ($key==$name)
			return true;
	}
	return false;
}

/**
 * @intellisense
 */
function xmlencode($str)
{
	$str = str_replace("&","&amp;",$str);
	$str = str_replace("<","&lt;",$str);
	$str = str_replace(">","&gt;",$str);
	$str = str_replace("'","&apos;",$str);
	return escapeEntities($str);
}

/**
 * @intellisense
 */
function print_inline_array(&$arr,$printkey=false)
{
	if(!$printkey)
	{
		foreach ( $arr as $key=>$val )
			echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$val))."\\n";
	}
	else
	{
		foreach( $arr as $key=>$val )
			echo str_replace(array("&","<","\\","\r","\n"),array("&amp;","&lt;","\\\\","\\r","\\n"),str_replace(array("\\","\r","\n"),array("\\\\","\\r","\\n"),$key))."\\n";
	}

}

/**
 * @intellisense
 */
function checkpassword($pwd)
{
	$len = strlen($pwd);
	if($len < 8)
		return false;
	$cUnique = array();
	$cLower = $cUpper = $cDigit = 0;
	for($i=0; $i<$len; $i++)
	{
		$c = substr($pwd, $i, 1);
		if($c >= 'a' && $c <= 'z')
			$cLower++;
		else if($c >= 'A' && $c<= 'Z')
			$cUpper++;
		else
			$cDigit++;

		$cUnique[$c] = 1;
	}
	if(count($cUnique)<4)
		return false;
	if($cDigit<2)
		return false;
	return true;
}

/**
 * @intellisense
 */
function GetChartXML($chartname)
{
	$strTableName = GetTableByShort($chartname);
	$settings = new ProjectSettings($strTableName);
	return $settings->getChartXml();
}

function isSecureProtocol() {
  return
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || $_SERVER['SERVER_PORT'] == 443;
}

/**
 * Returns site address without path
 * Examples:
 * http://server.com:88
 * https://server.com
 *
 * @intellisense
 */
function GetSiteUrl()
{
	$proto = "http://";
	if( $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off")
		$proto = "https://";
	return $proto . $_SERVER["HTTP_HOST"];
}

/**
 * Returns site address with path
 * Examples:
 * http://server.com:88/Calendar
 * https://server.com
 *
 * @intellisense
 */
function GetFullSiteUrl()
{
	return getDirectoryFromURI( GetSiteUrl() . $_SERVER['REQUEST_URI'] );
}


/**
 * @intellisense
 */
function GetAuditObject($table="")
{
	return NULL;

	$linkAudit = false;
	if(!$table)
	{
		$linkAudit = true;
	}
	else
	{
		$settings = new ProjectSettings($table);
		$linkAudit = $settings->auditEnabled();
	}
	if ($linkAudit)
	{
		require_once(getabspath("include/audit.php"));
	}
	else
	{
		return NULL;
	}
}

/**
 * @intellisense
 */
function GetLockingObject($table="")
{
	return NULL;

	if(!$table)
	{
		global $strTableName;
		$table = $strTableName;
	}
	$settings = new ProjectSettings($table);
	if ($settings->lockingEnabled())
	{
		require_once(getabspath("include/locking.php"));
		return new oLocking();
	}
	else
	{
		return NULL;
	}
}

/**
 * @intellisense
 */
function isEnableSection508()
{
	return GetGlobalData("isSection508",false);
}

/**
 * Returns validation type which defined in js validation object.
 * Use this function, because runner constants has another names of validation functions
 *
 * @param string $name
 * @return string
  * @intellisense
 */
function getJsValidatorName($name)
{
	switch ($name)
	{
		case "Number":
			return "IsNumeric";
			break;
		case "Password":
			return "IsPassword";
			break;
		case "Email":
			return "IsEmail";
			break;
		case "Currency":
			return "IsMoney";
			break;
		case "US ZIP Code":
			return "IsZipCode";
			break;
		case "US Phone Number":
			return "IsPhoneNumber";
			break;
		case "US State":
			return "IsState";
			break;
		case "US SSN":
			return "IsSSN";
			break;
		case "Credit Card":
			return "IsCC";
			break;
		case "Time":
			return "IsTime";
			break;
		case "Regular expression":
			return "RegExp";
			break;
		default:
			return $name;
			break;
	}
}

/**
 * @intellisense
 */
function SetLangVars($xt, $prefix, $pageName = "", $extraparams = "")
{
	$xt->assign("lang_label", true);

	if( @$_REQUEST["language"] )
		$_SESSION["language"] = @$_REQUEST["language"];

	$currentLang = mlang_getcurrentlang();

	$var = GoodFieldName($currentLang)."_langattrs";
	$xt->assign($var, "selected");

	$xt->assign($currentLang . "LANGLINK_ACTIVE", true);

	$xt->assign("IndonesianLANGLINK", "Indonesian" != $currentLang);

	if( isEnableSection508() )
		$xt->assign_section("lang_label", "<label for=\"languageSelector\">","</label>");

	if( $extraparams )
		$extraparams = $extraparams."&";

	$dataAttr = 'data-params="'.$extraparams.'" data-prefix="'.$prefix.'"';
	$xt->assign("langselector_attrs", "id=\"languageSelector\" ".$dataAttr);

	$xt->assign("languages_block", true);
}

/**
 * @intellisense
 */
function GetTableCaption($table)
{
	global $tableCaptions;
	return @$tableCaptions[mlang_getcurrentlang()][$table];
}

/**
 * @intellisense
 */
function GetFieldByLabel($table, $label)
{
	global $field_labels, $strTableName;
	if (!$table)
	{
		$table = $strTableName;
	}

	if(!array_key_exists($table,$field_labels))
		return "";
	$currLang = mlang_getcurrentlang();
	if(!array_key_exists($currLang,$field_labels[$table]))
		return "";
	$lables = $field_labels[$table][mlang_getcurrentlang()];
	foreach ($lables as $key=>$val)
	{
		if ($val == $label)
		{
			return $key;
		}
	}
	return '';
}

/**
 * @intellisense
 */
function GetFieldLabel($table,$field)
{
	global $field_labels;
	if( !array_key_exists( $table, $field_labels ) )
		return "";

	return @$field_labels[ $table ][ mlang_getcurrentlang() ][ $field ];
}


/**
 * @intellisense
 */
function GetFieldToolTip($table, $field)
{
	global $fieldToolTips;
	if( !array_key_exists( $table, $fieldToolTips ) )
		return "";

	return @$fieldToolTips[ $table ][ mlang_getcurrentlang() ][ $field ];
}

/**
 * @param String table
 * @param String field
 * @return String
 */
function GetFieldPlaceHolder( $table, $field )
{
	global $placeHolders;

	if( !array_key_exists( $table, $placeHolders ) )
		return "";

	return @$placeHolders[ $table ][ mlang_getcurrentlang() ][ $field ];
}


function GetMLString($mLString)
{
	switch($mLString["type"])
	{
		case ML_TEXT:
			return $mLString["text"];

		case ML_CUSTOM_LABEL:
			return GetCustomLabel($mLString["label"]);

		case ML_MESSAGE:
			return mlang_message($mLString["tag"]);
	}

	return "";
}




/**
 * @intellisense
 */
function GetCustomLabel($custom)
{
	global $custom_labels;
	return @$custom_labels[mlang_getcurrentlang()][$custom];
}

/**
 * @intellisense
 */
function mlang_getcurrentlang()
{
	global $_currentLanguage, $mlang_messages,$mlang_defaultlang;
	if(@$_REQUEST["language"])
		$_SESSION["language"]=@$_REQUEST["language"];
	if(@$_SESSION["language"])
		return $_SESSION["language"];
	return $mlang_defaultlang;
}

function isRTL()
{
	global $mlang_charsets;
	$cp = strtolower($mlang_charsets[mlang_getcurrentlang()]);
	return ($cp == 'windows-1256' || $cp == 'windows-1255');
}


/**
 * @intellisense
 */
function mlang_getlanglist()
{
	global $mlang_messages,$mlang_defaultlang;
	return array_keys($mlang_messages);
}

/**
 * @intellisense
 */
function getMountNames()
{
	$mounts = array();
		$mounts[1] = "Januari";
	$mounts[2] = "Februari";
	$mounts[3] = "Maret";
	$mounts[4] = "April";
	$mounts[5] = "Mei";
	$mounts[6] = "Juni";
	$mounts[7] = "Juli";
	$mounts[8] = "Agustus";
	$mounts[9] = "September";
	$mounts[10] = "Oktober";
	$mounts[11] = "Nopember";
	$mounts[12] = "Desember";

	return $mounts;
}


/**
 * @intellisense
 */
function showDetailTable($params)
{
	global $strTableName;
	$oldTableName = $strTableName;
	$strTableName = $params["table"];
	// show page
	if($params["dpObject"]->isDispGrid())
		$params["dpObject"]->showPage();
	$strTableName = $oldTableName;
}


/**
 * update record on Edit page
 * @intellisense
 */
function DoUpdateRecordSQL( $pageObject )
{
	$table = $pageObject->pSet->getOriginalTableName();
	$strWhereClause = $pageObject->getKeysWhereClause( true );
	$evalues = $pageObject->getNewRecordData();
	$blobfields = $pageObject->getBlobFields();

	if(!count($evalues))
		return true;

	$strSQL = "update ".$pageObject->connection->addTableWrappers($table)." set ";
	$blobs = PrepareBlobs($evalues, $blobfields, $pageObject);
	//	construct SQL string
	foreach($evalues as $ekey=>$value)
	{
		if ( $pageObject->pSet->insertNull($ekey) && trim($value) === "" )
		{
			$strValue = "NULL";
		}
		else if(in_array($ekey,$blobfields))
			$strValue = $value;
		else
		{
			if( is_null( $pageObject->cipherer ) )
				$strValue = add_db_quotes( $ekey, $value );
			else
				$strValue = $pageObject->cipherer->AddDBQuotes( $ekey, $value );
		}
		$strSQL .= $pageObject->getTableField($ekey)."=".$strValue.", ";
	}
	$strSQL = substr( $strSQL, 0, strlen($strSQL) - 2 );
	if($strWhereClause === "")
	{
		$strWhereClause = " (1=1) ";
	}
	$strSQL.=" where ".$strWhereClause;

	if(SecuritySQL("Edit", $pageObject->tName))
		$strSQL .= " and (".SecuritySQL("Edit", $pageObject->tName).")";

	if( !ExecuteUpdate($pageObject, $strSQL, $blobs) )
		return false;

	return true;
}

/**
 * insert record on Add & Register pages
 * @intellisense
 */
function DoInsertRecordSQL($table, &$avalues, &$blobfields, &$pageObject)
{
	//	make SQL string
	$strSQL = "insert into ".$pageObject->connection->addTableWrappers($table)." ";

	$strFields = "(";
	$strValues = "(";
	$blobs = PrepareBlobs($avalues, $blobfields, $pageObject);

	foreach($avalues as $akey => $value)
	{
		$strFields.= $pageObject->getTableField($akey).", ";

		if( in_array($akey, $blobfields) )
			$strValues.= $value.", ";
		else
		{
			if( is_null($pageObject->cipherer) )
				$strValues.= add_db_quotes($akey, $value).", ";
			else
				$strValues.= $pageObject->cipherer->AddDBQuotes($akey, $value).", ";
		}
	}

	if( substr($strFields, -2) == ", " )
		$strFields = substr($strFields, 0, strlen($strFields) - 2);

	if( substr($strValues, -2) == ", " )
		$strValues = substr($strValues, 0, strlen($strValues) - 2);

	$strSQL.= $strFields.") values ".$strValues.")";

	if( !ExecuteUpdate($pageObject, $strSQL, $blobs) )
		return false;

	$pageObject->ProcessFiles();

	return true;
}

/**
 * insert record on Add page
 * @param RunnerPage &pageObject
 * @intellisense
 */
function DoInsertRecordSQLOnAdd( &$pageObject )
{
	$table = $pageObject->pSet->getOriginalTableName();
	$avalues = $pageObject->getNewRecordData();
	$blobfields = $pageObject->getBlobFields();

	//	make SQL string
	$strSQL = "insert into ".$pageObject->connection->addTableWrappers($table)." ";

	$strFields="(";
	$strValues="(";
	$blobs = PrepareBlobs( $avalues, $blobfields, $pageObject );

	foreach($avalues as $akey => $value)
	{
		$strFields.= $pageObject->getTableField($akey).", ";

		if ( $pageObject->pSet->insertNull($akey) && trim($value) === "" )
		{
			$strValues .= "NULL, ";
		}
		else if( in_array($akey, $blobfields) )
			$strValues.= $value.", ";
		else
		{
			if( is_null( $pageObject->cipherer ) )
				$strValues.= add_db_quotes($akey, $value).", ";
			else
				$strValues.= $pageObject->cipherer->AddDBQuotes($akey, $value).", ";
		}
	}

	if( substr($strFields, -2) == ", ")
		$strFields = substr($strFields, 0, strlen($strFields) - 2);

	if( substr($strValues, -2) == ", ")
		$strValues = substr($strValues, 0, strlen($strValues) - 2);

	$strSQL.= $strFields.") values ".$strValues.")";

	if( !ExecuteUpdate($pageObject, $strSQL, $blobs) )
		return false;

	return true;
}


function &getEventObject($table)
{
	global $tableEvents;
	$ret = null;
	if(!array_key_exists($table,$tableEvents))
		return $ret;
	return $tableEvents[$table];
}

/**
 * @intellisense
 */
function tableEventExists($event,$table)
{
	global $tableEvents;
	if(!array_key_exists($table,$tableEvents))
		return false;
	return $tableEvents[$table]->exists($event);
}

/**
 * @intellisense
 */
function add_nocache_headers()
{
	header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");
}

/**
 * @intellisense
 */
function IsGuidString(&$str)
{
//	{3F2504E0-4F89-11D3-9A0C-0305E82C3301}
	if(strlen($str)==36 && substr($str,0,1)!="{" && substr($str,-1)!="}")
		$str="{".$str."}";
	elseif(strlen($str)==37 && substr($str,0,1)=="{" && substr($str,-1)!="}")
		$str=$str."}";
	elseif(strlen($str)==37 && substr($str,0,1)!="{" && substr($str,-1)=="}")
		$str="{".$str;

	if(strlen($str)!=38)
		return false;
	for($i=0;$i<38;$i++)
	{
		$c = substr($str,$i,1);
		if($i==0)
		{
			if($c!='{')
				return false;
		}
		elseif($i==37 )
		{
			if($c!='}')
				return false;
		}
		elseif($i==9 || $i==14 || $i==19 || $i==24)
		{
			if($c!='-')
				return false;
		}
		else
		{
			if(($c<'0' || $c>'9') && ($c<'a' || $c>'f') && ($c<'A' || $c>'F'))
				return false;
		}
	}
	return true;
}

/**
 * @intellisense
 */
function IsStoredProcedure($strSQL)
{
	if(strlen($strSQL)>6)
	{
		$c=strtolower(substr($strSQL,6,1));
		if(strtolower(substr($strSQL,0,6))=="select" && ($c<'0' || $c>'9') && ($c<'a' || $c>'z') && $c!='_')
			return false;
		else
			return true;
	}
	else
		return true;
}

/**
 * Checks whether an browser for mobile devices
 * Returns false - Browser stationary or it was not possible to determine
 * 1-4 - the browser is running on your mobile device
 * @return Mixed
 * @intellisense
 */
function MobileDetected()
{
	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$accept = strtolower($_SERVER['HTTP_ACCEPT']);

	if( strpos($accept,'text/vnd.wap.wml')!==false || strpos($accept,'application/vnd.wap.xhtml+xml')!==false )
		return 1; // Mobile browser detected by HTTP-headers

	if( isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) )
		return 2; // Mobile browser detected by server settings


	if(preg_match('/(ipad|android|symbianos|opera mini|ipod|blackberry|'.
		'palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine|iris|3g_t|'.
		'windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile|'.
		'mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|'.
		'wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|'.
		'lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|'.
		'mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|'.
		'm881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|'.
		'r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|'.
		'i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|'.
		'htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|'.
		'sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|'.
		'p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|'.
		'_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|'.
		's800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|'.
		'd736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |'.
		'sonyericsson|samsung|240x|x320vx10|nokia|sony cmd|motorola|'.
		'up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|'.
		'pocket|kindle|silk|hpwos|mobile|psp|treo)/', $user_agent))
	{
		return 3; // Mobile browser detected by User Agent signature
	}

	if(in_array(substr($user_agent,0,4),
		Array("1207", "3gso", "4thp", "501i", "502i", "503i", "504i", "505i", "506i",
			  "6310", "6590", "770s", "802s", "a wa", "abac", "acer", "acoo", "acs-",
			  "aiko", "airn", "alav", "alca", "alco", "amoi", "anex", "anny", "anyw",
			  "aptu", "arch", "argo", "aste", "asus", "attw", "au-m", "audi", "aur ",
			  "aus ", "avan", "beck", "bell", "benq", "bilb", "bird", "blac", "blaz",
			  "brew", "brvw", "bumb", "bw-n", "bw-u", "c55/", "capi", "ccwa", "cdm-",
			  "cell", "chtm", "cldc", "cmd-", "cond", "craw", "dait", "dall", "dang",
			  "dbte", "dc-s", "devi", "dica", "dmob", "doco", "dopo", "ds-d", "ds12",
			  "el49", "elai", "eml2", "emul", "eric", "erk0", "esl8", "ez40", "ez60",
			  "ez70", "ezos", "ezwa", "ezze", "fake", "fetc", "fly-", "fly_", "g-mo",
			  "g1 u", "g560", "gene", "gf-5", "go.w", "good", "grad", "grun", "haie",
			  "hcit", "hd-m", "hd-p", "hd-t", "hei-", "hiba", "hipt", "hita", "hp i",
			  "hpip", "hs-c", "htc ", "htc-", "htc_", "htca", "htcg", "htcp", "htcs",
			  "htct", "http", "huaw", "hutc", "i-20", "i-go", "i-ma", "i230", "iac",
			  "iac-", "iac/", "ibro", "idea", "ig01", "ikom", "im1k", "inno", "ipaq",
			  "iris", "jata", "java", "jbro", "jemu", "jigs", "kddi", "keji", "kgt",
			  "kgt/", "klon", "kpt ", "kwc-", "kyoc", "kyok", "leno", "lexi", "lg g",
			  "lg-a", "lg-b", "lg-c", "lg-d", "lg-f", "lg-g", "lg-k", "lg-l", "lg-m",
			  "lg-o", "lg-p", "lg-s", "lg-t", "lg-u", "lg-w", "lg/k", "lg/l", "lg/u",
			  "lg50", "lg54", "lge-", "lge/", "libw", "lynx", "m-cr", "m1-w", "m3ga",
			  "m50/", "mate", "maui", "maxo", "mc01", "mc21", "mcca", "medi", "merc",
			  "meri", "midp", "mio8", "mioa", "mits", "mmef", "mo01", "mo02", "mobi",
			  "mode", "modo", "mot ", "mot-", "moto", "motv", "mozz", "mt50", "mtp1",
			  "mtv ", "mwbp", "mywa", "n100", "n101", "n102", "n202", "n203", "n300",
			  "n302", "n500", "n502", "n505", "n700", "n701", "n710", "nec-", "nem-",
			  "neon", "netf", "newg", "newt", "nok6", "noki", "nzph", "o2 x", "o2-x",
			  "o2im", "opti", "opwv", "oran", "owg1", "p800", "palm", "pana", "pand",
			  "pant", "pdxg", "pg-1", "pg-2", "pg-3", "pg-6", "pg-8", "pg-c", "pg13",
			  "phil", "pire", "play", "pluc", "pn-2", "pock", "port", "pose", "prox",
			  "psio", "pt-g", "qa-a", "qc-2", "qc-3", "qc-5", "qc-7", "qc07", "qc12",
			  "qc21", "qc32", "qc60", "qci-", "qtek", "qwap", "r380", "r600", "raks",
			  "rim9", "rove", "rozo", "s55/", "sage", "sama", "samm", "sams", "sany",
			  "sava", "sc01", "sch-", "scoo", "scp-", "sdk/", "se47", "sec-", "sec0",
			  "sec1", "semc", "send", "seri", "sgh-", "shar", "sie-", "siem", "sk-0",
			  "sl45", "slid", "smal", "smar", "smb3", "smit", "smt5", "soft", "sony",
			  "sp01", "sph-", "spv ", "spv-", "sy01", "symb", "t-mo", "t218", "t250",
			  "t600", "t610", "t618", "tagt", "talk", "tcl-", "tdg-", "teli", "telm",
			  "tim-", "topl", "tosh", "treo", "ts70", "tsm-", "tsm3", "tsm5", "tx-9",
			  "up.b", "upg1", "upsi", "utst", "v400", "v750", "veri", "virg", "vite",
			  "vk-v", "vk40", "vk50", "vk52", "vk53", "vm40", "voda", "vulc", "vx52",
			  "vx53", "vx60", "vx61", "vx70", "vx80", "vx81", "vx83", "vx85", "vx98",
			  "w3c ", "w3c-", "wap-", "wapa", "wapi", "wapj", "wapm", "wapp", "wapr",
			  "waps", "wapt", "wapu", "wapv", "wapy", "webc", "whit", "wig ", "winc",
			  "winw", "wmlb", "wonu", "x700", "xda-", "xda2", "xdag", "yas-", "your",
			  "zeto", "zte-")))
	{
		return 4; // Mobile browser detected by User Agent signature
	}

	return false; // Mobile browser not found
}

/**
 * Check if user is using IE 8
 * @intellisense
 */
function isIE8()
{
	$matches = "";
	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
	return (count($matches)>1 && $matches[1]<=8);
}

/**
 * Check if the client application's part is run on a mobile device
 * @intellisense
 */
function mobileDeviceDetected()
{
	return false;
}

/**
 * Check if the client application's part is run on a mobile device
 * @return Boolean
 */
function detectMobileDevice()
{
	return false;
}

function IsMobile()
{
	return detectMobileDevice();
}

/**
 * GetPageLayout
 * Return reference to layout object by table name, page type and section (or tab) name
 * @param {string} tableName				the short table name (may be empty)
 * @param {string} pageType					the page's type
 * @param {string} suffixName (optional) 	It could contain a section/tab name or a pages layout's suffix (eg 'success')
 * @return {reference} 						a reference to the layout object
 */
function & GetPageLayout($table, $page, $suffixName = '')
{
	$shortTableName = GetTableURL($table);
	global $page_layouts, $arrCustomPages, $all_page_layouts, $pd_pages;

	// try open old layout first
	$layoutName = ($shortTableName != '' ? $shortTableName.'_' : '').$page.($suffixName != '' ? '_'.$suffixName : '');
	$oldLayoutName = $layoutName;
	if( $shortTableName == ".global" )
		$oldLayoutName = $page;
	if( $arrCustomPages[ $oldLayoutName . ".htm" ] /*||  isAdminPage( $shortTableName ) */) {
		$layout = $page_layouts[ $oldLayoutName ];
		if($layout)
		{
			if(postvalue("pdf"))
			{
				$layout->style = $layout->pdfStyle();
			}
		}
		return $layout;
	}
	//	find and return new layout
	$shortTableName = $shortTableName == "" ? GLOBAL_PAGES_SHORT : $shortTableName;
	$pdLayoutName = $shortTableName . '_' .$page;
	if( isset( $all_page_layouts[$pdLayoutName] ))
		return $all_page_layouts[$pdLayoutName];

	importPageOptions( $table, $page );
	if( !isset( $pd_pages[ $table ] ) || !isset( $pd_pages[ $table ][ $page ] ) ) {
		return null;
	}

	global $bsProjectTheme, $bsProjectSize, $styleOverrides;

	$theme = $bsProjectTheme;
	$size = $bsProjectSize;
	$customSettings = false;
	$override = $styleOverrides[ $table . "_" . $page ];
	if( $override ) {
		$theme = $override["theme"];
		$size = $override["size"];
		$customSettings = true;
	}
	$layout  = new PDLayout(
			$shortTableName,
			$pd_pages[ $table ][ $page ],
			$theme,
			$size,
			$customSettings );
	$all_page_layouts[ $shortTableName."_".$page ] = $layout;
	return $layout;
}

function & getLayoutByFilename( $filename ) {
	global $page_layouts, $arrCustomPages, $all_page_layouts;
	if( isset( $all_page_layouts[$filename] ))
		return $all_page_layouts[$filename];

	$oldFileName = $filename;
	if( substr($filename, 0, 8 ) == ".global_" )
		$oldFileName = substr( $filename, 8 );
	return $page_layouts[$oldFileName];
}

/**
 * Check if the mobile template is set
 * @param String templateFileName
 * @return Boolean
 */
function isPageLayoutMobile( $templateFileName )
{
	return false;
}

/**
 * @intellisense
 */
function extractStyle($str)
{
	$pos = my_stripos($str,'style="',0);
	$quot = '"';
	if($pos === false)
	{
		$pos = my_stripos($str,'style=\'',0);
		$quot = '\'';
	}
	if($pos === false)
		return;
	$pos1 = strpos($str,$quot, $pos+7);
	if($pos1 === false)
		return "";
	return substr($str, $pos+7, $pos1-$pos-7);
}

/**
 * @intellisense
 */
function injectStyle($str, $style)
{
	$pos = my_stripos($str,'style="',0);
	$quot = '"';
	if($pos === false)
	{
		$pos = my_stripos($str,'style=\'',0);
		$quot = '\'';
	}
	if($pos === false)
		return $str.' style="'.$style.'"';
	return substr($str,0, $pos+7).$style.";".substr($str, $pos+7);
}

/**
 * @intellisense
 */
function isSingleSign()
{
	if( GetGlobalData("ADSingleSign",0) && $_SERVER["REMOTE_USER"] )
		return false;

	return true;
}

/**
 * A number string of a particular length
 * @param Number length
 * @return String
 */
function generateUserCode($length)
{
	$code = "";
	for($i = 0; $i < $length; $i++)
	{
		$code.= rand(0, 9);
	}

	return $code;
}

/**
 * @intellisense
 */
function generatePassword($length)
{
	$password="";
	for($i=0;$i<$length;$i++)
	{
		$j = rand(0,35);
		if($j<26)
			$password.= chr(ord('a')+$j);
		else
			$password.= chr(ord('0')-26+$j);
	}
	return $password;
}

/**
 * @intellisense
 */
function securityCheckFileName($fileName)
{
	$maliciousStrings = array("../", "..\\");
	for($i = 0; $i < count($maliciousStrings); $i++)
	{
		while(strpos($fileName, $maliciousStrings[$i]) !== FALSE)
		{
			$fileName = str_replace($maliciousStrings, "", $fileName);
		}
	}
	return $fileName;
}

/**
 * @intellisense
 */
function getOptionsForMultiUpload($pSet, $field)
{
	if($pSet->isAbsolute($field))
		$uploadDir = $pSet->getUploadFolder($field);
	else
		$uploadDir = getabspath($pSet->getUploadFolder($field));
	$options = array(
		"max_file_size" => $pSet->getMaxFileSize($field),
		"max_totalFile_size" => $pSet->getMaxTotalFilesSize($field),
		"max_number_of_files" => $pSet->getMaxNumberOfFiles($field),
		"max_width" => $pSet->getMaxImageWidth($field),
		"max_height" => $pSet->getMaxImageHeight($field));
	if($pSet->getResizeOnUpload($field))
	{
		$options["resizeOnUpload"] = true;
		$options["max_width"] = $pSet->getNewImageSize($field);
		$options["max_height"] = $options["max_width"];
	}
	if($pSet->getCreateThumbnail($field))
	{
		$options['image_versions'] = array(
                'thumbnail' => array(
                    'max_width' => $pSet->getThumbnailSize($field),
                    'max_height' => $pSet->getThumbnailSize($field),
					'thumbnailPrefix' => $pSet->getStrThumbnail($field)
                ));
	}
    return $options;
}

/**
 * @intellisense
 */
function getContentTypeByExtension($ext)
{
	$ext = strtolower( $ext );
	if( substr($ext,0,1) != "." )
		$ext = ".".$ext;

		if($ext==".asf")
		$ctype = "video/x-ms-asf";
	elseif($ext==".avi")
		$ctype = "video/avi";
	elseif($ext==".doc")
		$ctype = "application/msword";
	elseif($ext==".zip")
		$ctype = "application/zip";
	elseif($ext==".xls")
		$ctype = "application/vnd.ms-excel";
	elseif($ext==".png")
		$ctype = "image/png";
	elseif($ext==".gif")
		$ctype = "image/gif";
	elseif($ext==".jpg" || $ext=="jpeg")
		$ctype = "image/jpeg";
	elseif($ext==".wav")
		$ctype = "audio/wav";
	elseif($ext==".mp3")
		$ctype = "audio/mpeg";
	elseif($ext==".mpg" || $ext=="mpeg")
		$ctype = "video/mpeg";
	elseif($ext==".rtf")
		$ctype = "application/rtf";
	elseif($ext==".htm" || $ext=="html")
		$ctype = "text/html";
	elseif($ext==".asp")
		$ctype = "text/asp";
	elseif($ext == ".flv")
		$ctype = "video/flv";
	elseif($ext == ".mp4")
		$ctype = "video/mp4";
	elseif($ext == ".webm")
		$ctype = "video/webm";
	elseif($ext == ".pdf")
		$ctype = "application/pdf";
	else
		$ctype = "application/octet-stream";

	return $ctype;
}

/**
 * @intellisense
 */
function getLatLngByAddr($addr)
{
	global $globalSettings;

	$apiKey = $globalSettings["apiGoogleMapsCode"];

	switch( getMapProvider() ){
		case GOOGLE_MAPS:	$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.rawurlencode($addr).'&sensor=false&key=' . $apiKey;
				$result = my_json_decode(myurl_get_contents($url));
				if($result['status'] == 'OK')
				{
					return $result['results'][0]['geometry']['location'];
				}
				break;
		case OPEN_STREET_MAPS: $url = 'https://nominatim.openstreetmap.org/search/'.rawurlencode($addr).'?format=json&addressdetails=1&limit=1';
				$result = my_json_decode(myurl_get_contents($url));
				if($result)
				{
					$lat = $result[0]['lat'];
					if( !$lat )
						$lat = 0;
					$lng = $result[0]['lon'];
					if( !$lng )
						$lng = 0;
					return array("lat"=>$lat,"lng"=>$lng);
				}
				break;
		case BING_MAPS:
				if( !$apiKey || !$addr )
					return false;

				$url = 'https://dev.virtualearth.net/REST/v1/Locations?query='.rawurlencode( $addr ).'&output=json&key='.$apiKey;
				$result = my_json_decode(myurl_get_contents($url));
				if($result)
				{
					$lat = $result["resourceSets"][0]["resources"][0]["geocodePoints"][0]["coordinates"][0];
					if( !$lat )
						$lat = 0;
					$lng = $result["resourceSets"][0]["resources"][0]["geocodePoints"][0]["coordinates"][1];
					if( !$lng )
						$lng = 0;
					return array("lat"=>$lat,"lng"=>$lng);
				}
				break;
	}
	return false;
}

/**
 * @intellisense
 */
function isLoggedAsGuest()
{
	return Security::isGuest();
}

/**
 * Check if the "Login as Guest" option is turned on
 * @return Boolean
 */
function isGuestLoginAvailable()
{
	if( Security::dynamicPermissions() ) {
		return guestHasPermissions();
	}

	return false;
}

/**
 * @intellisense
 */
function func_Override($page)
{
	global $globalSettings;
	if(!isset($globalSettings["override"][$page]))
		return otNone;
	return $globalSettings["override"][$page];
}


/**
 * printMFHandlerHeaders
 * Print HTTP headers for mfhandler.php
 * @intellisense
 */
function printMFHandlerHeaders()
{
	header('Content-Disposition: inline; filename="files.json"');
	header('X-Content-Type-Options: nosniff');
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: OPTIONS, HEAD, GET, POST');
	header('Access-Control-Allow-Headers: X-File-Name, X-File-Type, X-File-Size');
}

/**
 * @intellisense
 */
function GetFieldType($field,$table="")
{
	global $pageObject;
	if($table != "" || !isset($pageObject))
	{
		if($table == "")
		{
			global $strTableName;
			$table = $strTableName;
		}
		$newSet = new ProjectSettings($table);
		return $newSet->getFieldType($field);
	}
	else
		return $pageObject->pSet->getFieldType($field);
}

/**
 * @intellisense
 */
function Label($field,$table="")
{
	global $pageObject;
	if($table != "" || !isset($pageObject))
	{
		if($table == "")
		{
			global $strTableName;
			$table = $strTableName;
		}
		$newSet = new ProjectSettings($table);
		$result = $newSet->label($field);
	}
	else
		$result = $pageObject->pSet->label($field);
	return $result != "" ? $result : $field;
}

/**
 * @intellisense
 */
function getIconByFileType($fileType, $sourceFileName)
{
	switch($fileType)
	{
		case "text/html":
			$fileName = "html.png";
			break;
		case "text/asp":
			$fileName = "code.png";
			break;
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
			$fileName = "doc.png";
			break;
		case "application/vnd.ms-excel":
		case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
			$fileName = "xls.png";
			break;
		case "application/rtf":
			$fileName = "rtf.png";
			break;
		case "image/png":
		case "image/x-png":
			$fileName = "png.png";
			break;
		case "image/gif":
			$fileName = "gif.png";
			break;
		case "image/jpeg":
		case "image/pjpeg":
			$fileName = "jpg.png";
			break;
		case "audio/wav":
			$fileName = "wma.png";
			break;
		case "audio/mp3":
		case "audio/mpeg3":
		case "audio/mpeg":
			$fileName = "mp2.png";
			break;
		case "video/mpeg":
			$fileName = "mpeg.png";
			break;
		case "video/flv":
			$fileName = "flv.png";
			break;
		case "video/mp4":
			$fileName = "mp4.png";
			break;
		case "video/x-ms-asf":
			$fileName = "asf.png";
			break;
		case "video/webm":
		case "video/x-webm":
		case "video/avi":
			$fileName = "mpg.png";
			break;
		case "application/zip":
		case "application/x-zip-compressed":
			$fileName = "zip.png";
			break;
		default:
			$fileName = "text.png";
			$dotPosition = strrpos($sourceFileName, '.');
			if($dotPosition !== false && $dotPosition < strlen($sourceFileName) - 1)
			{
				$ext = substr($sourceFileName, $dotPosition + 1);
				$icons = array();
	           	$icons['7z'] = '7z';
	        		$icons['asf'] = 'asf';
	        		$icons['asp'] = 'code';
	        		$icons['avi'] = 'mpg';
	        		$icons['chm'] = 'chm';
	        		$icons['doc'] = 'doc';
	        		$icons['docx'] = 'doc';
	        		$icons['flv'] = 'flv';
	        		$icons['gz'] = 'gz';
	        		$icons['html'] = 'html';
	        		$icons['mdb'] = 'mdb';
	        		$icons['mdbx'] = 'mdb';
	        		$icons['mp3'] = 'mp2';
	        		$icons['mp4'] = 'mp4';
	        		$icons['mpeg'] = 'mpeg';
	        		$icons['mpg'] = 'mpg';
	        		$icons['mov'] = 'mov';
	        		$icons['pdf'] = 'pdf';
	        		$icons['php'] = 'code';
	        		$icons['pps'] = 'pps';
	        		$icons['ppt'] = 'powerpoint';
	        		$icons['psd'] = 'psd';
	        		$icons['rar'] = 'rar';
	        		$icons['rtf'] = 'rtf';
	        		$icons['swf'] = 'swf';
	        		$icons['tif'] = 'tif';
	        		$icons['ttf'] = 'ttf';
	        		$icons['txt'] = 'txt';
	        		$icons['wav'] = 'wav';
	        		$icons['webm'] = 'mpg';
	        		$icons['wma'] = 'wma';
	        		$icons['wmv'] = 'emv';
	        		$icons['xls'] = 'xls';
	        		$icons['xlsx'] = 'xls';
	        		$icons['zip'] = 'zip';
				if(isset($icons[$ext]))
					$fileName = $icons[$ext].".png";
			}
	}
	return $fileName;
}

function isImageType($type)
{
	switch($type)
	{
		case "image/png":
		case "image/x-png":
		case "image/gif":
		case "image/jpeg":
		case "image/pjpeg":
			return true;
	}
	return false;
}

function initArray(&$array, $key)
{
	if(!isset($array[$key]))
	{
		$array[$key] = array();
	}
}

/**
 * GetKeysArray
 * Form aray of primary keys and their values for audit
 * @param {array} $arr array of inserting values
 * @param {bool} $searchId - find last inserted id or not
 * @return {array} array of keys and their values
 */
function GetKeysArray($arr, $pageObject, $searchId = false)
{
	$keyfields = $pageObject->pSet->GetTableKeys();
	$aKeys = array();
    if(count($keyfields))
    {
    	foreach ($keyfields as $kfield)
		{
    		if(array_key_exists($kfield, $arr))
    			$aKeys[$kfield] = $arr[$kfield];
	    }
		if(count($aKeys) == 0 && $searchId)
	    {
	    	$lastId = $pageObject->connection->getInsertedId();
	    	if($lastId > 0)
				$aKeys[$keyfields[0]] = $lastId;
	    }
    }
    return $aKeys;
}

function GetBaseScriptsForPage($isDisplayLoading, $additionalScripts = "", $customText = "")
{
	$result = "";
	$result .= "<script type=\"text/javascript\" src=\"".GetRootPathForResources("include/loadfirst.js?33793")."\"></script>";
	$result .= $additionalScripts;
	$result .= "<script type=\"text/javascript\" src=\"".GetRootPathForResources("include/lang/".getLangFileName(mlang_getcurrentlang()).".js?33793")."\"></script>";

	if( getMapProvider() == BING_MAPS )
	{
		$result .= "<script type=\"text/javascript\" src=\"https://www.bing.com/api/maps/mapcontrol?&setMkt=".getBingMapsLang()."\"></script>";
	}

	if($isDisplayLoading)
	{
		$result .= "<script type=\"text/javascript\">Runner.runLoading('".$customText."');</script>";
	}

	return $result;
}

/**
 * @param Mixed data
 * @param Bolean plainJSON (optional)	The flag indicating that the JSON's html special chars mustn't be convarted
 * @return String
 */
function printJSON($data, $returnPlainJSON = false)
{
	$rJSON = my_json_encode( $data );

	return $returnPlainJSON ? $rJSON : runner_htmlspecialchars( $rJSON );
}

/**
 * Get the value of an interval limit's custom PHP expression
 * @param String table
 * @param String field
 * @param Number idx
 * @param Boolean isLowerBound
 * @return Mixed
 */
function getIntervalLimitsExprs($table, $field, $idx, $isLowerBound)
{


}

function import_error_handler($errno, $errstr, $errfile, $errline)
{
	/*global $error_happened;

	$error_happened=1;*/
}
function PrepareForExcel($ret)
{
	//$ret = htmlspecialchars($str); commented for bug #6823
	if (substr($ret,0,1)== "=")
		$ret = "&#61;".substr($ret,1);
	return $ret;

}

function countTotals(&$totals, $totalsFields, $data)
{
	for($i = 0; $i < count($totalsFields); $i ++)
	{
		if($totalsFields[$i]['totalsType'] == 'COUNT')
			$totals[$totalsFields[$i]['fName']]["value"] += ($data[$totalsFields[$i]['fName']]!= "");
		else if($totalsFields[$i]['viewFormat'] == "Time")
		{
			$time = GetTotalsForTime($data[$totalsFields[$i]['fName']]);
			$totals[$totalsFields[$i]['fName']]["value"] += $time[2]+$time[1]*60 + $time[0]*3600;
		}
		else
			$totals[$totalsFields[$i]['fName']]["value"] += ($data[$totalsFields[$i]['fName']]+ 0);

		if($totalsFields[$i]['totalsType'] == 'AVERAGE')
		{
			if(!is_null($data[$totalsFields[$i]['fName']]) && $data[$totalsFields[$i]['fName']]!=="")
				$totals[$totalsFields[$i]['fName']]['numRows']++;
		}
	}
}

function XMLNameEncode($strValue)
{
	$search=array(" ","#","'","/","\\","(",")",",","[");
	$ret=str_replace($search,"",$strValue);
	$search=array("]","+","\"","-","_","|","}","{","=");
	$ret=str_replace($search,"",$ret);
	return $ret;
}

/**
 * Get the file extention string
 * @param String fileName
 * @return String
 */
function getFileExtension($fileName)
{
	$pos = strrpos($fileName, ".");
	if( $pos === FALSE )
		return "";

	return substr($fileName, $pos + 1);
}

/**
 * Get the default db connection object
 * @return Connection
 */
function getDefaultConnection()
{
	global $cman;
	return $cman->getDefault();
}

function isIOS()
{
	return stripos($_SERVER['HTTP_USER_AGENT'],"iPod") !== false
		|| stripos($_SERVER['HTTP_USER_AGENT'],"iPad") !== false
		|| stripos($_SERVER['HTTP_USER_AGENT'],"iPhone") !== false;
}


/* Get map provider google = 0, openStreetMap = 1*/
function getMapProvider(){
	return GetGlobalData("mapProvider", true);
}

function getBingMapsLang()
{
	$arrBimgMapLang = array();
	$arrBimgMapLang["Czech"] = "cs-CZ";
	$arrBimgMapLang["Danish"] = "da-DK";
	$arrBimgMapLang["Dutch"] = "nl-NL";
	$arrBimgMapLang["English"] = "en-US";
	$arrBimgMapLang["French"] = "fr-FR";
	$arrBimgMapLang["German"] = "de-DE";
	$arrBimgMapLang["Italian"] = "it-IT";
	$arrBimgMapLang["Japanese"] = "ja-JP";
	$arrBimgMapLang["Norwegian"] = "nb-NO";
	$arrBimgMapLang["Polish"] = "pl-PL";
	$arrBimgMapLang["Portugal"] = "pt-PT";
	$arrBimgMapLang["Portuguese"] = "pt-BR";
	$arrBimgMapLang["Russian"] = "ru-RU";
	$arrBimgMapLang["Spanish"] = "es-ES";
	$arrBimgMapLang["Swedish"] = "sw-SE";
	$arrBimgMapLang["Chinese"] = "zh-TW";
	$arrBimgMapLang["Hongkong"] = "zh-HK";
	if( array_key_exists( mlang_getcurrentlang(), $arrBimgMapLang ) )
		return $arrBimgMapLang[mlang_getcurrentlang()];

	return	$arrBimgMapLang["English"];
}

function getDefaultLanguage()
{
	if( strlen($_SESSION["language"]) == 0 && $_SERVER['HTTP_ACCEPT_LANGUAGE'] )
	{
		$arrWizardLang = array();
		$arrWizardLang[] = "Indonesian";
		$arrLang = array();
		$arrLang["af"] = "Afrikaans";
		$arrLang["ar"] = "Arabic";
		$arrLang["bs"] = "Bosnian"; //?
		$arrLang["bg"] = "Bulgarian";
		$arrLang["ca"] = "Catalan";
		$arrLang["zh"] = "Chinese";// 1
		$arrLang["hr"] = "Croatian";
		$arrLang["cs"] = "Czech";
		$arrLang["da"] = "Danish";
		$arrLang["nl"] = "Dutch";
		$arrLang["en"] = "English";
		$arrLang["fa"] = "Farsi"; //?
		$arrLang["fr"] = "French";
		$arrLang["ka"] = "Georgian";
		$arrLang["de"] = "German";
		$arrLang["el"] = "Greek";
		$arrLang["he"] = "Hebrew";//?
		$arrLang["hk"] = "Hongkong";// 1
		$arrLang["hu"] = "Hungarian";
		$arrLang["id"] = "Indonesian";//?
		$arrLang["it"] = "Italian";
		$arrLang["ja"] = "Japanese";
		$arrLang["ms"] = "Malay";
		$arrLang["no"] = "Norwegian";
		$arrLang["fl"] = "Phillipines";//?
		$arrLang["pl"] = "Polish";
		$arrLang["pt"] = "Portugal"; // 2
		$arrLang["br"] = "Portuguese"; // 2
		$arrLang["ro"] = "Romanian";
		$arrLang["ru"] = "Russian";
		$arrLang["sk"] = "Slovak";
		$arrLang["es"] = "Spanish";
		$arrLang["sv"] = "Swedish";
		$arrLang["tw"] = "Taiwan";//??
		$arrLang["th"] = "Thai";
		$arrLang["tr"] = "Turkish";
		$arrLang["ur"] = "Urdu";
		$arrLang["cy"] = "Welsh";

		$http_lang = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']); //return string ex.:fr-FR,fr;q=0.8,en-US;q=0.6,en;q=0.4
		$http_lang = str_replace(";",",",$http_lang);
		$http_lang = str_replace("-",",",$http_lang);

		$langcode = array();
		$langcode = explode(",", $http_lang);

		foreach($langcode as $lang)
		{
			if( in_array($arrLang[$lang], $arrWizardLang) )
				return $arrLang[$lang];
		}
	}
	return "Indonesian";
}


function xt_showpdchart($params) {
	$params["chartName"] = $params["custom1"];
	$params["pdMode"] = true;
	$params["table"] = GetTableByShort( $params["chartName"] );
	xt_showchart($params);
}

function xt_showchart($params)
{
	$showDetails = isset( $params["showDetails"] ) ? $params["showDetails"] : true;

	$settings = new ProjectSettings(GetTableByShort($params["chartName"]));
	$refresh = $settings->getChartRefreshTime();

	$chartParams = array();
	$chartParams['showDetails'] = $showDetails;
	$chartParams['chartName'] = $params["chartName"];

	//css id identifiers are not allowed to start with a number or underscore
	$chartParams['containerId'] = "rnr".$params["chartName"].$params["id"];
	$chartParams['chartType'] = $params["ctype"];
	$chartParams['refreshTime'] = $refresh;
	$chartParams['xmlFile'] = GetTableLink("dchartdata") . '?chartname=' . $params["chartName"] . $chartPreview .
		'&ctype=' . $params["ctype"] .
		'&showDetails=' . $showDetails;

	if( isset( $params["dash"] ) && $params["dash"] )
	{
		$chartParams['xmlFile'] .= '&dashChart=' . $params["dash"];
		$chartParams['dashChart'] = !!$params["dash"];
	}

	$chartParams['pageId'] = $params["id"];

	if( isset( $params["dashTName"] ) && $params["dashTName"] )
	{
		$chartParams['dashTName'] = $params["dashTName"];
		$chartParams['dashElementName'] = $params["dashElementName"];
		$chartParams['pageId'] = $params["id"];

		$chartParams['xmlFile'] .= '&dashTName=' . $params["dashTName"];
		$chartParams['xmlFile'] .= '&dashElName=' . $params["dashElementName"];
		$chartParams['xmlFile'] .= '&pageId=' . $params["id"];
		$dashSet = new ProjectSettings( $params["dashTName"] );
		$dashElement = $dashSet->getDashboardElementData( $params["dashElementName"] );
		if( $dashElement )
		{
			if( $dashElement["reload"] )
			{
				$chartParams['refreshTime'] = $dashElement["reload"];
			}
		}
	}

	if ( isset($params["refreshTime"]) )
	{
		$chartParams['refreshTime'] = $params["refreshTime"];
	}

	echo '<div class="bs-chart" id="' . $chartParams['containerId'] . '"></div>';
	if( true || !$params["singlePage"] )
	{
		$chartParams["webRootPath"] = GetWebRootPath();

		echo '<div data-runner-chart-params="' . runner_htmlspecialchars( my_json_encode( $chartParams ) ) . '"></div>';
	}
}

function getFileUrl( $params )
{
	echo GetRootPathForResources($params["custom1"]);
}

function getPdfImageObject( $params )
{
	$imagePath = GetRootPathForResources( $params["custom1"] );
	$imagePath = getabspath( $imagePath );

	$width = $params["custom2"];
	$height = $params["custom3"];

	$content = myfile_get_contents_binary( urldecode( $imagePath ) );
	$imageType = SupposeImageType( $content );

	if( $imageType != "image/jpeg" && $imageType != "image/png" )
	{
		echo '""';
		return;
	}

	echo '{
		image: "'.jsreplace( 'data:'. $imageType. ';base64,' . base64_bin2str( $content ) ) . '",
		width: '. $width .',
		height: '. $height .'
	}';
}


function setHomePage( $url )
{
	global $globalSettings;
	$globalSettings["LandingPageType"] = 2;
	$globalSettings["LandingURL"] = $url;
}

function getHomePage()
{
	global $globalSettings;
	if( $globalSettings["LandingPageType"] == 2 )
	{
		return $globalSettings["LandingURL"];
	}

//	GetLocalLink makes any difference with GetTableLink in ASP.NET only
	if( $globalSettings["LandingPageType"] == 0 )
		return GetLocalLink("menu");

	if( $globalSettings["LandingPage"]=="" || $globalSettings["LandingPage"] == "login" || $globalSettings["LandingPage"] == "register" )
		return GetLocalLink("menu");

	if( strlen($globalSettings["LandingTable"]) )
	{
		if( !strlen( $globalSettings["LandingPageId"] ) )
			return GetLocalLink( GetTableURL($globalSettings["LandingTable"]), $globalSettings["LandingPage"] );

		return GetLocalLink( GetTableURL($globalSettings["LandingTable"]), $globalSettings["LandingPage"], "page=".$globalSettings["LandingPageId"] );
	}

	return GetLocalLink( $globalSettings["LandingPage"] );
}



function printHomeLink( $params )
{
	echo runner_htmlspecialchars( getHomePage() );
}


function setProjectLogo( $html, $lng="" )
{
	global $globalSettings;
	if(strlen($lng) == 0)
		$lng = mlang_getcurrentlang();
	$globalSettings["ProjectLogo"][$lng] = $html;
}

function getProjectLogo($lng="")
{
	global $globalSettings;
	if(strlen($lng) == 0)
		$lng = mlang_getcurrentlang();
	return $globalSettings["ProjectLogo"][$lng];
}

function printProjectLogo( $params )
{
	echo getProjectLogo(mlang_getcurrentlang());
}


function xt_jspagetitlelabel($params)
{
	echo jsreplace( _pagetitlelabel($params) );
}

function xt_pagetitlelabel($params)
{
	echo _pagetitlelabel($params);
}

function _pagetitlelabel($params)
{
	global $pageObject;

	$record = isset($params["record"]) ? $params["record"] : null;
	$settings = isset($params["settings"]) ? $params["settings"] : null;

	if( isset($params["custom2"]) )
		return $pageObject->getPageTitle( $params["custom2"], $params["custom1"] , $record, $settings );
	else
	return $pageObject->getPageTitle( $params["custom1"], "", $record, $settings );
}

function xt_label($params)
{
	echo GetFieldLabel($params["custom1"],$params["custom2"]);
}

function xt_jslabel($params)
{
	echo jsreplace( GetFieldLabel($params["custom1"],$params["custom2"]) );
}


function xt_tooltip($params)
{
	echo GetFieldToolTip( $params["custom1"], $params["custom2"] );
}

function xt_custom($params)
{
	echo GetCustomLabel($params["custom1"]);
}

function xt_htmlcustom($params)
{
	echo runner_htmlspecialchars(GetCustomLabel($params["custom1"]));
}

// custom label comment length
function xt_cl_length( $params )
{
	echo strlen( trim( GetCustomLabel( $params["custom1"] ) ) );
}

function xt_caption($params)
{
	echo GetTableCaption($params["custom1"]);
}

function xt_jscaption($params)
{
	echo jsreplace( GetTableCaption($params["custom1"]) );
}


//	BuildEditControl wrapper
function xt_buildeditcontrol(&$params)
{
	$pageObj = $params["pageObj"];
	$data = $pageObj->getFieldControlsData();

	$field = $params["field"];

	if($params["mode"] == "edit")
		$mode = MODE_EDIT;
	else if($params["mode"] == "add")
		$mode = MODE_ADD;
	else if($params["mode"]=="inline_edit")
		$mode = MODE_INLINE_EDIT;
	else if($params["mode"]=="inline_add")
		$mode = MODE_INLINE_ADD;
	else
		$mode = MODE_SEARCH;

	$fieldNum = 0;
	if(@$params["fieldNum"])
		$fieldNum = $params["fieldNum"];

	$id = "";
	if(@$params["id"] !== "")
		$id = $params["id"];

	$validate = array();
	if(count(@$params["validate"]))
		$validate = @$params["validate"];

	$additionalCtrlParams = array();
	if(count(@$params["additionalCtrlParams"]))
		$additionalCtrlParams = @$params["additionalCtrlParams"];

	$extraParams = array();
	if( count(@$params["extraParams"]) )
		$extraParams = @$params["extraParams"];

	$pageObj->getControl($field, $id, $extraParams)->buildControl(@$params["value"], $mode, $fieldNum, $validate, $additionalCtrlParams, $data);
}

function getArrayElementNC( &$arr, $key )
{
	if( isset( $arr[ $key ] ) )
		return $arr[ $key ];
	$keys = array_keys( $arr );
	$uKey = strtoupper( $key );
	foreach( $keys as $k )
	{
		if( strtoupper($k) == $uKey )
			return $arr[ $k ];
	}
	return null;
}

/**
 * 	This function is needed for conversion to ASP & ASP.NET
 */
function getSessionElementNC( $key )
{
	if( isset( $_SESSION[ $key ] ) )
		return $_SESSION[ $key ];
	$ukey = strtoupper( $key );
	foreach( $_SESSION as $k => $v )
	{
		if( strtoupper($k) == $ukey )
			return $v;
	}
	return null;
}



/**
 *  Returns ready-to-use additional lookup wizard WHERE expression
 *  @param String field - field name
 *  @param Object pSet - ProjectSettings object
 *  @return String
 */
function prepareLookupWhere( $field, $pSet ) {

	$where = $pSet->getLookupWhere( $field );
	if( $pSet->isLookupWhereCode( $field ) )
		return $where;

	return DB::PrepareSQL( $pSet->getLookupWhere( $field ) );
}

function verifyRecaptchaResponse( $response ) {

	$verifyUrl = "https://www.google.com/recaptcha/api/siteverify?";

	$errors = array();
	$errors["missing-input-response"] = "Invalid security code.";
	$errors["invalid-input-response"] = "Invalid security code.";
	$errors["missing-input-secret"] = "The secret parameter is missing";
	$errors["invalid-input-secret"] = "The secret parameter is invalid or malformed";
	$errors["bad-request"] = "The request is invalid or malformed";

	$captchaSettings = GetGlobalData("CaptchaSettings", "");

	$data = array();
	$data['secret'] = $captchaSettings["secretKey"];
	$data['response'] = $response;
	$data['remoteIp'] = $_SERVER["REMOTE_ADDR"];

	$req = array();
	foreach ($data as $key => $value) {
		$req []= $key . '=' . rawurlencode($value);
	}

	$response = myurl_get_contents($verifyUrl . implode('&', $req) );
	$answers = my_json_decode($response);

	$message = '';
	if ( $response == "" )
	{
		$success = false;
		$message = "Unable to contact reCaptcha server";
	}
	else if ( !isset($answers['success']) )
	{
		$success = false;
		$message = "Unable to contact reCaptcha server<br>" . runner_htmlspecialchars( substr( $response, 0, 100 ) );
	}
	else
	{
		$success = $answers['success'];
		//	form message
		for( $i=0; $i < count( $answers['error-codes'] ); ++$i )
		{
			$code = $answers['error-codes'][$i];

			//	beautify and sanitize error messages
			if( isset( $errors[ $code ] ) )
				$answers['error-codes'][$i] = $errors[ $code ];
			else
				$answers['error-codes'][$i] = runner_htmlspecialchars( $code );

			$message = implode( '<br>', $answers['error-codes'] );
		}
	}

	return array(
		'success' => $success,
		'message' => $message
	);
}

function toSet( $arr ) {
	$ret = array();
	foreach( $arr as $v ) {
		$ret[ $v ] = true;
	}
	return $ret;
}

function _loadTablePages() {
	global $all_pages;

	if( !$all_pages ) {
		$all_pages = my_json_decode( myfile_get_contents( getabspath("include/pages/pages.json" ), "r" ) );
	}
}

function pageEnabled( $tablename, $type ) {
	global $all_pages;
	_loadTablePages();

	return isset( $all_pages[ $tablename ][ $type ] );
}

/**
 * returns list of all table pages in format
 * { pageType: pageId }
 */
function tablePages( $tablename ) {
	global $all_pages;
	_loadTablePages();

	return $all_pages[ $tablename ];
}

/**
 * returns list of all pages in format
 * { table: { pageType: pageId } }
 */
function & allTablePages() {
	global $all_pages;
	_loadTablePages();

	return $all_pages;
}



function isAdminPage( $table ) {
	return $table=="admin_rights" || $table=="admin_members" || $table=="admin_admembers";
}

function isOldCustomFile( $filename ) {
	global $arrCustomPages;
	$oldFileName = getOldTemplateFilename( $filename );
	 return !!$arrCustomPages[ $oldFileName . ".htm" ];
}

function getOldTemplateFilename( $filename ) {
	if( substr($filename, 0, 8 ) == ".global_" )
		return  substr( $filename, 8 );
	return $filename;
}

function types2pages( &$pagesByTypes ) {
	$pages = array();
	foreach( $pagesByTypes as $ptype => $pids ) {
		foreach(  $pids as $pid ) {
			$pages[$pid] = $ptype;
		}
	}
	return $pages;
}

function getMediaType() {
	global $mediaType;
	return $mediaType;
}

/**
 * @param String searchField - GoodFieldName ( field )
 */
function getListOfSuggests( $sfields, $table, $whereClauses, $numberOfSuggests, $searchOpt, $searchFor, $searchField = '', $detailKeys = array() )
{
	global $cman;

	if( !count( $whereClauses ) )
		$whereClauses = array();
	$whereClauses[] = SecuritySQL( "Search", $table );

	$conn = $cman->byTable( $table );
	$pSet = new ProjectSettings( $table, PAGE_SEARCH );
	$query = $pSet->getSQLQuery();
	$cipherer = new RunnerCipherer( $table );
	$controls = new EditControlsContainer( null, $pSet, PAGE_LIST, $cipherer );

	$response = array();
	$result = array();

	// traverse all searchable fields
	foreach( $sfields as $f )
	{
		// filter fields by type
		$fType = $pSet->getFieldType( $f );
		if( !IsCharType( $fType ) && !IsNumberType( $fType ) && !IsGuid( $fType )	|| in_array( $f, $detailKeys ) )
			continue;

		if( $conn->dbType == nDATABASE_Oracle && IsTextType( $fType ) )
			continue;

		if( $searchField != '' && $searchField != GoodFieldName( $f ) )
			continue;

		$fieldControl = $controls->getControl( $f );

		$isAggregateField = $pSet->isAggregateField( $f );
		$where = $fieldControl->getSuggestWhere( $searchOpt, $searchFor, $isAggregateField );
		$having = $fieldControl->getSuggestHaving( $searchOpt, $searchFor, $isAggregateField );

		if( !strlen( $where ) && !strlen( $having ) )
			continue;

		$distinct = "DISTINCT";
		if( $conn->dbType == nDATABASE_MSSQLServer || $conn->dbType == nDATABASE_Access )
		{
			if( IsTextType( $fType ) )
				$distinct = "";
		}

		$sql = $query->getSQLComponents();
		$clausesData = $fieldControl->getSelectColumnsAndJoinFromPart( $searchFor, $searchOpt, true );
		if( 0 == strlen( $clausesData["joinFromPart"] ) )
		{
			//	no hassle, just make a subquery

			$subQuery = SQLQuery::buildSQL( $sql, $whereClauses, array(), array( $where ), array( $having ) );
			$strSQL = "SELECT " . $distinct . " st.".$conn->addFieldWrappers($f)." from (" . $subQuery . ") st";
		}
		else
		{
			//	special case, with lookup wizards and joins
			$sql['from'] .= $clausesData["joinFromPart"];
			$sql['head'] = "SELECT " . $distinct . " " . $clausesData["selectColumns"] . " as " . $conn->addFieldWrappers("_srchfld_");
			$subQuery = SQLQuery::buildSQL( $sql, $whereClauses, array(), array( $where ), array( $having ) );
			$strSQL = "SELECT " . $conn->addFieldWrappers("_srchfld_") . " from (" . $subQuery . ") st";
		}

		$qResult = $conn->queryPage( $strSQL, 1,  $numberOfSuggests, true );

		// fill $response array with the field's suggest value
		while( ( $row = $qResult->fetchNumeric() ) && count($response) < $numberOfSuggests )
		{
			$val = $cipherer->DecryptField($f, $row[0]);
			if( IsGuid( $fType ) )
				$val = substr($val, 1, -1);

			// "_" is added to conver number type to string
			$fieldControl->suggestValue("_".$val, $searchFor, $response, $row);
		}
	}

	ksort( $response, SORT_STRING );

	foreach( $response as $value => $realValue )
	{
		if( count( $result ) > $numberOfSuggests )
			break;

		$strValue = $value[0] == '_' ? substr($value, 1) : $value; // .net compatibility issue
		$strRealValue = $realValue[0] == '_' ? substr($realValue, 1) : $realValue;

		$pos = my_stripos($strValue, $searchFor, 0);

		if( $pos === FALSE )
		{
			$result[] = array("value" => runner_htmlspecialchars( $strValue ), "realValue" => $strRealValue);
		}
		else
		{
			$highlightedValue = runner_htmlspecialchars( substr($strValue, 0, $pos) )."<b>".runner_htmlspecialchars( substr($strValue, $pos, strlen($searchFor)) )."</b>"
				.runner_htmlspecialchars( substr($strValue, $pos + strlen($searchFor)) );

			$result[] = array("value" => $highlightedValue, "realValue" => $strRealValue);
		}
	}

	return $result;
}

function IsEmptyRequest() {
	$allowedKeys = array( "menuItemId", "page" );
	$allowedKeysCount = 0;
	foreach( $allowedKeys as $k ) {
		if( isset( $_GET[ $k ] ) )
			++$allowedKeysCount;
	}
	return !count($_POST) && (count($_GET) <= $allowedKeysCount);
}

function xt_process_template(&$xt,$str)
{
//	parse template file tag by tag
	$varparams=array();
	$start=0;
	$literal=false;
	$len = strlen($str);
	while(true)
	{
		$pos = strpos($str,"{",$start);
		if($pos===false)
		{
			echo substr($str,$start,$len-$start);
			break;
		}
		$section=false;
		$var=false;
		$message=false;
		if(substr($str,$pos+1,6)=="BEGIN ")
			$section=true;
		elseif(substr($str,$pos+1,1)=='$')
			$var=true;
		elseif(substr($str,$pos+1,14)=='mlang_message ')
		{
			$message=true;
		}
		else
		{
//	no tag, just '{' char
			echo substr($str,$start,$pos-$start+1);
			$start=$pos+1;
			continue;
		}
		echo substr($str,$start,$pos-$start);
		if($section)
		{
//	section
			$endpos=strpos($str,"}",$pos);
			if($endpos===false)
			{
				$xt->report_error("Page is broken");
				return;
			}
			$section_name=trim(substr($str,$pos+7,$endpos-$pos-7));
			$endtag="{END ".$section_name."}";
			$endpos1=strpos($str,$endtag,$endpos);
			if($endpos1===false)
			{
				echo "End tag not found:".runner_htmlspecialchars($endtag);
				$xt->report_error("Page is broken");
				return;
			}
			$section=substr($str,$endpos+1,$endpos1-$endpos-1);
			$start=$endpos1+strlen($endtag);
			$sectionVar = xt_getvar($xt,$section_name);
			if($sectionVar===false)
			{
				continue;
			}
			$begin="";
			$end="";
			if(is_array($sectionVar))
			{
				$begin=@$sectionVar["begin"];
				$end=@$sectionVar["end"];

				$var=@$sectionVar["data"];
			}
			else
			{
				$var = $sectionVar;
			}
			if(!is_array($var))
			{
//	if section
				echo $begin;
				if(is_array($sectionVar))
					$xt->xt_stack[]=&$sectionVar;
				xt_process_template($xt,$section);
				if(is_array($sectionVar))
					array_pop($xt->xt_stack);
				$xt->processVar($end, $varparams);
			}
			else
			{
//	foreach section
				echo $begin;
				$keys=array_keys($var);
				foreach($keys as $i)
				{
					$xt->xt_stack[]=&$var[$i];
					if(is_array($var[$i]) && isset($var[$i]["begin"]))
						echo $var[$i]["begin"];
					xt_process_template($xt,$section);
					array_pop($xt->xt_stack);
					if(is_array($var[$i]) && isset($var[$i]["end"]))
						echo $var[$i]["end"];
				}
				$xt->processVar($end, $varparams);
			}
		}
		elseif($var)
		{
//	display a variable or call a function
			$endpos=strpos($str,"}",$pos);
			if($endpos===false)
			{
				$xt->report_error("Page is broken");
				return;
			}
			$varparams=array();
			$var_name = trim(substr($str,$pos+2,$endpos-$pos-2));
			if(strpos($var_name," ")!==FALSE)
			{
				$varparams = explode(" ",$var_name);
				$var_name = $varparams[0];
				unset($varparams[0]);
			}
			$start=$endpos+1;
			$var = xt_getvar($xt,$var_name);
			if($var===false)
			{
				continue;
			}
			$xt->processVar($var, $varparams);
		}
		elseif($message)
		{
			$endpos=strpos($str,"}",$pos);
			if($endpos===false)
			{
				$xt->report_error("Page is broken");
				return;
			}
			$tag = trim(substr($str,$pos+15,$endpos-$pos-15));
			$start=$endpos+1;
			echo runner_htmlspecialchars(mlang_message($tag));
		}
	}
}

/**
 * adds $arr elements as keys to $assocArray
 * returns $assocArray
 */
function addToAssocArray( $assocArray, $arr ) {
	foreach( $arr as $e ) {
		$assocArray[ $e ] = true;
	}
	return $assocArray;
}

function postvalue_number( $key ) {
	return 0 + postvalue($key);
}

/**
 * Basic View Format doesn't need other field values. So passing of the record data can be omitted for performance reasons.
 */
function basicViewFormat( $format ) {
	return $format === FORMAT_NONE
		|| $format === FORMAT_DATE_SHORT
		|| $format === FORMAT_DATE_LONG
		|| $format === FORMAT_DATE_TIME
		|| $format === FORMAT_TIME
		|| $format === FORMAT_CURRENCY
		|| $format === FORMAT_PERCENT
		|| $format === FORMAT_LOOKUP_WIZARD
		|| $format === FORMAT_PHONE_NUMBER
		|| $format === FORMAT_NUMBER
		|| $format === FORMAT_HTML
		|| $format === FORMAT_CHECKBOX;
}

/**
 * Currently used for MultilanguageString settings. Returns actual message string
 */
function getCustomMessage( $message ) {
	if( !$message || !is_array( $message ) ) {
		return "";
	}
	if( $message["messageType"] == "Text" )
		return $message["message"];
	else
		return GetCustomLabel( $message["message"] );
}

/**
 * Returns a page the user has access to.
 */
function availablePage( $table, $pageType, $page = "" ) {
	$pSet = new ProjectSettings( $table, $pageType, $page );
	return $pSet->pageName();
}


function formatDateIntervalValue( $value, $intervalType )
{
	global $locale_info;

	$tm = array();
	$tm[0] = 0 + substr( $value, 0, 4 ); // year
	$tm[1] = 0 + substr( $value, 4, 2 ); // month or quarter or week
	$tm[2] = 0 + substr( $value, 6, 2 ); // day
	$tm[3] = 0 + substr( $value, 8, 2 ); //	hour
	$tm[4] = 0 + substr( $value, 10, 2 ); // minute
	$tm[5] = 0;

	if( !$tm[0] )
		return "";

	switch( $intervalType )
	{
		case 1: // DATE_INTERVAL_YEAR
			return substr( $value, 0, 4 );
		case 2: // DATE_INTERVAL_QUARTER
			return $tm[0]."/Q".$tm[1];
		case 3: // DATE_INTERVAL_MONTH
			return @$locale_info[ "LOCALE_SABBREVMONTHNAME".$tm[1] ]." ".$tm[0];
		case 4: // DATE_INTERVAL_WEEK
			$dates = getDatesByWeek( $tm[1] + 1, $tm[0] );
			return format_shortdate( db2time( $dates[0] ) ) . ' - ' . format_shortdate( db2time( $dates[1] ) );
		case 5: // DATE_INTERVAL_DAY
			return format_shortdate( $tm );
		case 6: // DATE_INTERVAL_HOUR
			$tm[4] = 0;
			$tm[5] = 0;
			return str_format_datetime( $tm );
		case 7: // DATE_INTERVAL_MINUTE
			$tm[5] = 0;
			return str_format_datetime( $tm );
		default:
			return str_format_datetime( $tm );
	}
}


function getDatesByWeek( $week, $year )
{
	global $locale_info;
	$startweekday = 0;
	if($locale_info["LOCALE_IFIRSTDAYOFWEEK"]>0)
		$startweekday = 7 - $locale_info["LOCALE_IFIRSTDAYOFWEEK"];

	$L = isleapyear($year) ? 1 : 0;
	$months = array(31, 28 + $L, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$total_days = ($week - 1) * 7;
	$i = 0;
	$sum = 0;
	while($sum <= $total_days)
	{
		$sum += $months[$i++];
	}
	$sum -= $months[$i-1];
	$month = $i;
	$day = $total_days - $sum;
	$day_of_week = getdayofweek(array($year, $month, $day));
	if ($day_of_week == 0)
		$day_of_week = 7;

	$day = $day - ($day_of_week - 1) - $startweekday;
	$dates = array();
	$dates[0] = getYMDdate(mktime(0,0,0, $month, $day, $year));
	$dates[1] = getYMDdate(mktime(1,1,1, $month, $day+6, $year));

	return $dates;
}

/**
 * @return Boolean
 */
function pageTypeShowsData( $pageType ) {
	return $pageType == "list"
	  || $pageType == "report"
	  || $pageType == "chart"
	  || $pageType == "view"
	  || $pageType == "export"
	  || $pageType == "edit"
	  || $pageType == "print"
	  || $pageType == "rprint"
	  || $pageType == "masterlist"
	  || $pageType == "masterprint";
}

/**
 * @return Boolean
 */
function pageTypeInputsData( $pageType ) {
	return $pageType == "add"
	  || $pageType == "edit"
	  || $pageType == "search"
	  || $pageType == "register"
	  || $pageType == "login";
}

function base64_encode_url( $str ) {
	return str_replace( array( '+', '/', '='), array('-', '_', ''), base64_encode( $str ) );
}

function base64_encode_url_binary( $bin ) {
	return str_replace( array( '+', '/', '='), array('-', '_', ''), base64_encode_binary( $bin ) );
}

function base64_decode_url( $str ) {
	//	pad with =
	while( strlen($str) % 4 !== 0 ) {
		$str .= "=";
	}
	$ret = base64_decode( str_replace( array('-', '_'), array( '+', '/' ), $str ) );
	return $ret;
}

function base64_decode_url_binary( $str ) {
	//	pad with =
	while( strlen($str) % 4 !== 0 ) {
		$str .= "=";
	}
	$ret = base64_decode_binary( str_replace( array('-', '_'), array( '+', '/' ), $str ) );
	return $ret;
}

function jwt_encode( $payload, $duration = 10 ) {

	if( !$payload["exp"] ) {
		$payload["exp"] = time() + $duration;
	}
	$header64 = base64_encode_url( my_json_encode( array('typ' => 'JWT', 'alg' => 'HS256') ) );
	$payload64 = base64_encode_url( my_json_encode( $payload) );
	$signature = hash_hmac_sha256($header64 . "." . $payload64, GetGlobalData("jwtSecret"), true);
	$base64UrlSignature = base64_encode_url_binary( $signature );
	return $header64 . "." . $payload64 . "." . $base64UrlSignature;
}

function jwt_verify_decode( $jwt ) {
	$parts = explode('.', $jwt);
	if( count( $parts) != 3 )
		return false;
	$signature = base64_decode_url_binary( $parts[2] );
	if( hash_hmac_sha256( $parts[0] . "." . $parts[1], GetGlobalData("jwtSecret"), true ) !== $signature )
		return false;
	$ret = my_json_decode( base64_decode_url( $parts[1] ) );
	if( !is_array( $ret ) )
		return false;
	if( !$ret[ "exp" ] || $ret[ "exp" ] <= time() )
		return false;
	return $ret;
}

/**
 * returns http or https
 */
function request_protocol() {
	return $_SERVER["HTTPS"] && $_SERVER["HTTPS"] != "off"
		? 'https'
		: 'http';
}

/**
 * Project base URL
 * Ends with /
 * May return empty string if server doesn't report it
 * @return string
 */
function projectURL() {
	return request_protocol() . "://" . $_SERVER['HTTP_HOST'] . projectPath();
}

function setProjectCookie( $name, $value, $expires = 0, $httpOnly = false) {
	setcookie( $name, $value, $expires, projectPath(), "", false, $httpOnly );
}

/**
 * Returns image data usl for PNG and JPG files
 * false otherwise
 */
function imageDataUrl( $image ) {
	$imageType = SupposeImageType( $image );
	if( $imageType == "image/jpeg" || $imageType == "image/png" ) {
		return 'data:'. $imageType. ';base64,' . base64_bin2str( $image );
	}
	return false;

}

function ldap_getUrl( $address ) {
	if( strpos( $address , "://" ) !== false ) {
		return $address;
	}
	return "ldap://" . $address;
}

function ldap_getServer( $address ) {
	$pos = strpos( $address , "://" );
	if( $pos !== false ) {
		return substr( $address, $pos + 3 );
	}
	return $address;
}

/**
 * Convert domain name test.xlinesoft.com
 * return DC=test,DC=xlinesoft,DC=com
 */
function ldap_Domain2DN( $aDomain )
{
	if( strpos( $aDomain, "=" ) !== FALSE ) {
		return $aDomain;
	}
	$arrDomain = explode(".", $aDomain);
	for ($i = 0; $i < sizeof($arrDomain); $i++)
	{
		$arrDomain[$i] = "dc=".$arrDomain[$i];
	}
	return implode(',', $arrDomain);
}

function ldap_DN2Domain($dn)
{
	if( strpos( $dn, "=" ) === FALSE ) {
		return $dn;
	}
	$dom = array();
	foreach( explode(",", $dn) as $d ) {
		if( strtoupper( substr( $d, 0, 3 ) ) == "DC=" ) {
			$dom []= substr( $d, 3 );
		}
	}
	return implode('.', $dom);
}

function ldap_factory() {
	return new RunnerLdap( 
		GetGlobalData( "ADDomain", "" ), 
		GetGlobalData( "ADServer", null ), 
		GetGlobalData( "customLDAP", false ), 
		GetGlobalData( "ADBaseDN", "" ),
		GetGlobalData( "ADFollowRefs",false ) 
	);
}
	
?>