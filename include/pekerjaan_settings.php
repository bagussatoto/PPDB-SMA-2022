<?php



$tdatapekerjaan = array();
$tdatapekerjaan[".searchableFields"] = array();
$tdatapekerjaan[".ShortName"] = "pekerjaan";
$tdatapekerjaan[".OwnerID"] = "";
$tdatapekerjaan[".OriginalTable"] = "pekerjaan";


$defaultPages = my_json_decode( "{\"add\":\"add\",\"edit\":\"edit\",\"export\":\"export\",\"import\":\"import\",\"list\":\"list\",\"print\":\"print\",\"search\":\"search\",\"view\":\"view\"}" );

$tdatapekerjaan[".pagesByType"] = my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" );
$tdatapekerjaan[".pages"] = types2pages( my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" ) );
$tdatapekerjaan[".defaultPages"] = $defaultPages;

//	field labels
$fieldLabelspekerjaan = array();
$fieldToolTipspekerjaan = array();
$pageTitlespekerjaan = array();
$placeHolderspekerjaan = array();

if(mlang_getcurrentlang()=="Indonesian")
{
	$fieldLabelspekerjaan["Indonesian"] = array();
	$fieldToolTipspekerjaan["Indonesian"] = array();
	$placeHolderspekerjaan["Indonesian"] = array();
	$pageTitlespekerjaan["Indonesian"] = array();
	$fieldLabelspekerjaan["Indonesian"]["ID"] = "No";
	$fieldToolTipspekerjaan["Indonesian"]["ID"] = "";
	$placeHolderspekerjaan["Indonesian"]["ID"] = "";
	$fieldLabelspekerjaan["Indonesian"]["namapekerjaan"] = "Jenis Pekerjaan";
	$fieldToolTipspekerjaan["Indonesian"]["namapekerjaan"] = "";
	$placeHolderspekerjaan["Indonesian"]["namapekerjaan"] = "";
	if (count($fieldToolTipspekerjaan["Indonesian"]))
		$tdatapekerjaan[".isUseToolTips"] = true;
}


	$tdatapekerjaan[".NCSearch"] = true;



$tdatapekerjaan[".shortTableName"] = "pekerjaan";
$tdatapekerjaan[".nSecOptions"] = 0;

$tdatapekerjaan[".mainTableOwnerID"] = "";
$tdatapekerjaan[".entityType"] = 0;

$tdatapekerjaan[".strOriginalTableName"] = "pekerjaan";

	



$tdatapekerjaan[".showAddInPopup"] = false;

$tdatapekerjaan[".showEditInPopup"] = false;

$tdatapekerjaan[".showViewInPopup"] = false;

//page's base css files names
$popupPagesLayoutNames = array();
$tdatapekerjaan[".popupPagesLayoutNames"] = $popupPagesLayoutNames;


$tdatapekerjaan[".listAjax"] = false;
//	temporary
$tdatapekerjaan[".listAjax"] = false;

	$tdatapekerjaan[".audit"] = false;

	$tdatapekerjaan[".locking"] = false;


$pages = $tdatapekerjaan[".defaultPages"];

if( $pages[PAGE_EDIT] ) {
	$tdatapekerjaan[".edit"] = true;
	$tdatapekerjaan[".afterEditAction"] = 1;
	$tdatapekerjaan[".closePopupAfterEdit"] = 1;
	$tdatapekerjaan[".afterEditActionDetTable"] = "";
}

if( $pages[PAGE_ADD] ) {
$tdatapekerjaan[".add"] = true;
$tdatapekerjaan[".afterAddAction"] = 1;
$tdatapekerjaan[".closePopupAfterAdd"] = 1;
$tdatapekerjaan[".afterAddActionDetTable"] = "";
}

if( $pages[PAGE_LIST] ) {
	$tdatapekerjaan[".list"] = true;
}



$tdatapekerjaan[".strSortControlSettingsJSON"] = "";




if( $pages[PAGE_VIEW] ) {
$tdatapekerjaan[".view"] = true;
}

if( $pages[PAGE_IMPORT] ) {
$tdatapekerjaan[".import"] = true;
}

if( $pages[PAGE_EXPORT] ) {
$tdatapekerjaan[".exportTo"] = true;
}

if( $pages[PAGE_PRINT] ) {
$tdatapekerjaan[".printFriendly"] = true;
}



$tdatapekerjaan[".showSimpleSearchOptions"] = true; // temp fix #13449

// Allow Show/Hide Fields in GRID
$tdatapekerjaan[".allowShowHideFields"] = true; // temp fix #13449
//

// Allow Fields Reordering in GRID
$tdatapekerjaan[".allowFieldsReordering"] = true; // temp fix #13449
//

$tdatapekerjaan[".isUseAjaxSuggest"] = true;

$tdatapekerjaan[".rowHighlite"] = true;



			

$tdatapekerjaan[".ajaxCodeSnippetAdded"] = false;

$tdatapekerjaan[".buttonsAdded"] = false;

$tdatapekerjaan[".addPageEvents"] = false;

// use timepicker for search panel
$tdatapekerjaan[".isUseTimeForSearch"] = false;


$tdatapekerjaan[".badgeColor"] = "EDCA00";


$tdatapekerjaan[".allSearchFields"] = array();
$tdatapekerjaan[".filterFields"] = array();
$tdatapekerjaan[".requiredSearchFields"] = array();

$tdatapekerjaan[".googleLikeFields"] = array();
$tdatapekerjaan[".googleLikeFields"][] = "ID";
$tdatapekerjaan[".googleLikeFields"][] = "namapekerjaan";



$tdatapekerjaan[".tableType"] = "list";

$tdatapekerjaan[".printerPageOrientation"] = 0;
$tdatapekerjaan[".nPrinterPageScale"] = 100;

$tdatapekerjaan[".nPrinterSplitRecords"] = 40;

$tdatapekerjaan[".geocodingEnabled"] = false;










$tdatapekerjaan[".pageSize"] = 20;

$tdatapekerjaan[".warnLeavingPages"] = true;



$tstrOrderBy = "";
if(strlen($tstrOrderBy) && strtolower(substr($tstrOrderBy,0,8))!="order by")
	$tstrOrderBy = "order by ".$tstrOrderBy;
$tdatapekerjaan[".strOrderBy"] = $tstrOrderBy;

$tdatapekerjaan[".orderindexes"] = array();

$tdatapekerjaan[".sqlHead"] = "SELECT ID,  	namapekerjaan";
$tdatapekerjaan[".sqlFrom"] = "FROM pekerjaan";
$tdatapekerjaan[".sqlWhereExpr"] = "";
$tdatapekerjaan[".sqlTail"] = "";










//fill array of records per page for list and report without group fields
$arrRPP = array();
$arrRPP[] = 10;
$arrRPP[] = 20;
$arrRPP[] = 30;
$arrRPP[] = 50;
$arrRPP[] = 100;
$arrRPP[] = 500;
$arrRPP[] = -1;
$tdatapekerjaan[".arrRecsPerPage"] = $arrRPP;

//fill array of groups per page for report with group fields
$arrGPP = array();
$arrGPP[] = 1;
$arrGPP[] = 3;
$arrGPP[] = 5;
$arrGPP[] = 10;
$arrGPP[] = 50;
$arrGPP[] = 100;
$arrGPP[] = -1;
$tdatapekerjaan[".arrGroupsPerPage"] = $arrGPP;

$tdatapekerjaan[".highlightSearchResults"] = true;

$tableKeyspekerjaan = array();
$tableKeyspekerjaan[] = "ID";
$tdatapekerjaan[".Keys"] = $tableKeyspekerjaan;


$tdatapekerjaan[".hideMobileList"] = array();




//	ID
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 1;
	$fdata["strName"] = "ID";
	$fdata["GoodName"] = "ID";
	$fdata["ownerTable"] = "pekerjaan";
	$fdata["Label"] = GetFieldLabel("pekerjaan","ID");
	$fdata["FieldType"] = 3;

	
		$fdata["AutoInc"] = true;

	
			

		$fdata["strField"] = "ID";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "ID";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
		
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatapekerjaan["ID"] = $fdata;
		$tdatapekerjaan[".searchableFields"][] = "ID";
//	namapekerjaan
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 2;
	$fdata["strName"] = "namapekerjaan";
	$fdata["GoodName"] = "namapekerjaan";
	$fdata["ownerTable"] = "pekerjaan";
	$fdata["Label"] = GetFieldLabel("pekerjaan","namapekerjaan");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "namapekerjaan";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "namapekerjaan";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatapekerjaan["namapekerjaan"] = $fdata;
		$tdatapekerjaan[".searchableFields"][] = "namapekerjaan";


$tables_data["pekerjaan"]=&$tdatapekerjaan;
$field_labels["pekerjaan"] = &$fieldLabelspekerjaan;
$fieldToolTips["pekerjaan"] = &$fieldToolTipspekerjaan;
$placeHolders["pekerjaan"] = &$placeHolderspekerjaan;
$page_titles["pekerjaan"] = &$pageTitlespekerjaan;

// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table (master)
$detailsTablesData["pekerjaan"] = array();

// tables which are master tables for current table (detail)
$masterTablesData["pekerjaan"] = array();



// -----------------end  prepare master-details data arrays ------------------------------//


require_once(getabspath("classes/sql.php"));










function createSqlQuery_pekerjaan()
{
$proto0=array();
$proto0["m_strHead"] = "SELECT";
$proto0["m_strFieldList"] = "ID,  	namapekerjaan";
$proto0["m_strFrom"] = "FROM pekerjaan";
$proto0["m_strWhere"] = "";
$proto0["m_strOrderBy"] = "";
	
		;
			$proto0["cipherer"] = null;
$proto2=array();
$proto2["m_sql"] = "";
$proto2["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto2["m_column"]=$obj;
$proto2["m_contained"] = array();
$proto2["m_strCase"] = "";
$proto2["m_havingmode"] = false;
$proto2["m_inBrackets"] = false;
$proto2["m_useAlias"] = false;
$obj = new SQLLogicalExpr($proto2);

$proto0["m_where"] = $obj;
$proto4=array();
$proto4["m_sql"] = "";
$proto4["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto4["m_column"]=$obj;
$proto4["m_contained"] = array();
$proto4["m_strCase"] = "";
$proto4["m_havingmode"] = false;
$proto4["m_inBrackets"] = false;
$proto4["m_useAlias"] = false;
$obj = new SQLLogicalExpr($proto4);

$proto0["m_having"] = $obj;
$proto0["m_fieldlist"] = array();
						$proto6=array();
			$obj = new SQLField(array(
	"m_strName" => "ID",
	"m_strTable" => "pekerjaan",
	"m_srcTableName" => "pekerjaan"
));

$proto6["m_sql"] = "ID";
$proto6["m_srcTableName"] = "pekerjaan";
$proto6["m_expr"]=$obj;
$proto6["m_alias"] = "";
$obj = new SQLFieldListItem($proto6);

$proto0["m_fieldlist"][]=$obj;
						$proto8=array();
			$obj = new SQLField(array(
	"m_strName" => "namapekerjaan",
	"m_strTable" => "pekerjaan",
	"m_srcTableName" => "pekerjaan"
));

$proto8["m_sql"] = "namapekerjaan";
$proto8["m_srcTableName"] = "pekerjaan";
$proto8["m_expr"]=$obj;
$proto8["m_alias"] = "";
$obj = new SQLFieldListItem($proto8);

$proto0["m_fieldlist"][]=$obj;
$proto0["m_fromlist"] = array();
												$proto10=array();
$proto10["m_link"] = "SQLL_MAIN";
			$proto11=array();
$proto11["m_strName"] = "pekerjaan";
$proto11["m_srcTableName"] = "pekerjaan";
$proto11["m_columns"] = array();
$proto11["m_columns"][] = "ID";
$proto11["m_columns"][] = "namapekerjaan";
$obj = new SQLTable($proto11);

$proto10["m_table"] = $obj;
$proto10["m_sql"] = "pekerjaan";
$proto10["m_alias"] = "";
$proto10["m_srcTableName"] = "pekerjaan";
$proto12=array();
$proto12["m_sql"] = "";
$proto12["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto12["m_column"]=$obj;
$proto12["m_contained"] = array();
$proto12["m_strCase"] = "";
$proto12["m_havingmode"] = false;
$proto12["m_inBrackets"] = false;
$proto12["m_useAlias"] = false;
$obj = new SQLLogicalExpr($proto12);

$proto10["m_joinon"] = $obj;
$obj = new SQLFromListItem($proto10);

$proto0["m_fromlist"][]=$obj;
$proto0["m_groupby"] = array();
$proto0["m_orderby"] = array();
$proto0["m_srcTableName"]="pekerjaan";		
$obj = new SQLQuery($proto0);

	return $obj;
}
$queryData_pekerjaan = createSqlQuery_pekerjaan();


	
		;

		

$tdatapekerjaan[".sqlquery"] = $queryData_pekerjaan;

$tableEvents["pekerjaan"] = new eventsBase;
$tdatapekerjaan[".hasEvents"] = false;

?>