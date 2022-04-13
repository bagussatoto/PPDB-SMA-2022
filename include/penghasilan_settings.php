<?php



$tdatapenghasilan = array();
$tdatapenghasilan[".searchableFields"] = array();
$tdatapenghasilan[".ShortName"] = "penghasilan";
$tdatapenghasilan[".OwnerID"] = "";
$tdatapenghasilan[".OriginalTable"] = "penghasilan";


$defaultPages = my_json_decode( "{\"add\":\"add\",\"edit\":\"edit\",\"export\":\"export\",\"import\":\"import\",\"list\":\"list\",\"print\":\"print\",\"search\":\"search\",\"view\":\"view\"}" );

$tdatapenghasilan[".pagesByType"] = my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" );
$tdatapenghasilan[".pages"] = types2pages( my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" ) );
$tdatapenghasilan[".defaultPages"] = $defaultPages;

//	field labels
$fieldLabelspenghasilan = array();
$fieldToolTipspenghasilan = array();
$pageTitlespenghasilan = array();
$placeHolderspenghasilan = array();

if(mlang_getcurrentlang()=="Indonesian")
{
	$fieldLabelspenghasilan["Indonesian"] = array();
	$fieldToolTipspenghasilan["Indonesian"] = array();
	$placeHolderspenghasilan["Indonesian"] = array();
	$pageTitlespenghasilan["Indonesian"] = array();
	$fieldLabelspenghasilan["Indonesian"]["ID"] = "No";
	$fieldToolTipspenghasilan["Indonesian"]["ID"] = "";
	$placeHolderspenghasilan["Indonesian"]["ID"] = "";
	$fieldLabelspenghasilan["Indonesian"]["namapenghasilan"] = "Skala Penghasilan";
	$fieldToolTipspenghasilan["Indonesian"]["namapenghasilan"] = "";
	$placeHolderspenghasilan["Indonesian"]["namapenghasilan"] = "";
	if (count($fieldToolTipspenghasilan["Indonesian"]))
		$tdatapenghasilan[".isUseToolTips"] = true;
}


	$tdatapenghasilan[".NCSearch"] = true;



$tdatapenghasilan[".shortTableName"] = "penghasilan";
$tdatapenghasilan[".nSecOptions"] = 0;

$tdatapenghasilan[".mainTableOwnerID"] = "";
$tdatapenghasilan[".entityType"] = 0;

$tdatapenghasilan[".strOriginalTableName"] = "penghasilan";

	



$tdatapenghasilan[".showAddInPopup"] = false;

$tdatapenghasilan[".showEditInPopup"] = false;

$tdatapenghasilan[".showViewInPopup"] = false;

//page's base css files names
$popupPagesLayoutNames = array();
$tdatapenghasilan[".popupPagesLayoutNames"] = $popupPagesLayoutNames;


$tdatapenghasilan[".listAjax"] = false;
//	temporary
$tdatapenghasilan[".listAjax"] = false;

	$tdatapenghasilan[".audit"] = false;

	$tdatapenghasilan[".locking"] = false;


$pages = $tdatapenghasilan[".defaultPages"];

if( $pages[PAGE_EDIT] ) {
	$tdatapenghasilan[".edit"] = true;
	$tdatapenghasilan[".afterEditAction"] = 1;
	$tdatapenghasilan[".closePopupAfterEdit"] = 1;
	$tdatapenghasilan[".afterEditActionDetTable"] = "";
}

if( $pages[PAGE_ADD] ) {
$tdatapenghasilan[".add"] = true;
$tdatapenghasilan[".afterAddAction"] = 1;
$tdatapenghasilan[".closePopupAfterAdd"] = 1;
$tdatapenghasilan[".afterAddActionDetTable"] = "";
}

if( $pages[PAGE_LIST] ) {
	$tdatapenghasilan[".list"] = true;
}



$tdatapenghasilan[".strSortControlSettingsJSON"] = "";




if( $pages[PAGE_VIEW] ) {
$tdatapenghasilan[".view"] = true;
}

if( $pages[PAGE_IMPORT] ) {
$tdatapenghasilan[".import"] = true;
}

if( $pages[PAGE_EXPORT] ) {
$tdatapenghasilan[".exportTo"] = true;
}

if( $pages[PAGE_PRINT] ) {
$tdatapenghasilan[".printFriendly"] = true;
}



$tdatapenghasilan[".showSimpleSearchOptions"] = true; // temp fix #13449

// Allow Show/Hide Fields in GRID
$tdatapenghasilan[".allowShowHideFields"] = true; // temp fix #13449
//

// Allow Fields Reordering in GRID
$tdatapenghasilan[".allowFieldsReordering"] = true; // temp fix #13449
//

$tdatapenghasilan[".isUseAjaxSuggest"] = true;

$tdatapenghasilan[".rowHighlite"] = true;



			

$tdatapenghasilan[".ajaxCodeSnippetAdded"] = false;

$tdatapenghasilan[".buttonsAdded"] = false;

$tdatapenghasilan[".addPageEvents"] = false;

// use timepicker for search panel
$tdatapenghasilan[".isUseTimeForSearch"] = false;


$tdatapenghasilan[".badgeColor"] = "9ACD32";


$tdatapenghasilan[".allSearchFields"] = array();
$tdatapenghasilan[".filterFields"] = array();
$tdatapenghasilan[".requiredSearchFields"] = array();

$tdatapenghasilan[".googleLikeFields"] = array();
$tdatapenghasilan[".googleLikeFields"][] = "ID";
$tdatapenghasilan[".googleLikeFields"][] = "namapenghasilan";



$tdatapenghasilan[".tableType"] = "list";

$tdatapenghasilan[".printerPageOrientation"] = 0;
$tdatapenghasilan[".nPrinterPageScale"] = 100;

$tdatapenghasilan[".nPrinterSplitRecords"] = 40;

$tdatapenghasilan[".geocodingEnabled"] = false;










$tdatapenghasilan[".pageSize"] = 20;

$tdatapenghasilan[".warnLeavingPages"] = true;



$tstrOrderBy = "";
if(strlen($tstrOrderBy) && strtolower(substr($tstrOrderBy,0,8))!="order by")
	$tstrOrderBy = "order by ".$tstrOrderBy;
$tdatapenghasilan[".strOrderBy"] = $tstrOrderBy;

$tdatapenghasilan[".orderindexes"] = array();

$tdatapenghasilan[".sqlHead"] = "SELECT ID,  	namapenghasilan";
$tdatapenghasilan[".sqlFrom"] = "FROM penghasilan";
$tdatapenghasilan[".sqlWhereExpr"] = "";
$tdatapenghasilan[".sqlTail"] = "";










//fill array of records per page for list and report without group fields
$arrRPP = array();
$arrRPP[] = 10;
$arrRPP[] = 20;
$arrRPP[] = 30;
$arrRPP[] = 50;
$arrRPP[] = 100;
$arrRPP[] = 500;
$arrRPP[] = -1;
$tdatapenghasilan[".arrRecsPerPage"] = $arrRPP;

//fill array of groups per page for report with group fields
$arrGPP = array();
$arrGPP[] = 1;
$arrGPP[] = 3;
$arrGPP[] = 5;
$arrGPP[] = 10;
$arrGPP[] = 50;
$arrGPP[] = 100;
$arrGPP[] = -1;
$tdatapenghasilan[".arrGroupsPerPage"] = $arrGPP;

$tdatapenghasilan[".highlightSearchResults"] = true;

$tableKeyspenghasilan = array();
$tableKeyspenghasilan[] = "ID";
$tdatapenghasilan[".Keys"] = $tableKeyspenghasilan;


$tdatapenghasilan[".hideMobileList"] = array();




//	ID
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 1;
	$fdata["strName"] = "ID";
	$fdata["GoodName"] = "ID";
	$fdata["ownerTable"] = "penghasilan";
	$fdata["Label"] = GetFieldLabel("penghasilan","ID");
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


	$tdatapenghasilan["ID"] = $fdata;
		$tdatapenghasilan[".searchableFields"][] = "ID";
//	namapenghasilan
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 2;
	$fdata["strName"] = "namapenghasilan";
	$fdata["GoodName"] = "namapenghasilan";
	$fdata["ownerTable"] = "penghasilan";
	$fdata["Label"] = GetFieldLabel("penghasilan","namapenghasilan");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "namapenghasilan";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "namapenghasilan";

	
	
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


	$tdatapenghasilan["namapenghasilan"] = $fdata;
		$tdatapenghasilan[".searchableFields"][] = "namapenghasilan";


$tables_data["penghasilan"]=&$tdatapenghasilan;
$field_labels["penghasilan"] = &$fieldLabelspenghasilan;
$fieldToolTips["penghasilan"] = &$fieldToolTipspenghasilan;
$placeHolders["penghasilan"] = &$placeHolderspenghasilan;
$page_titles["penghasilan"] = &$pageTitlespenghasilan;

// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table (master)
$detailsTablesData["penghasilan"] = array();

// tables which are master tables for current table (detail)
$masterTablesData["penghasilan"] = array();



// -----------------end  prepare master-details data arrays ------------------------------//


require_once(getabspath("classes/sql.php"));










function createSqlQuery_penghasilan()
{
$proto0=array();
$proto0["m_strHead"] = "SELECT";
$proto0["m_strFieldList"] = "ID,  	namapenghasilan";
$proto0["m_strFrom"] = "FROM penghasilan";
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
	"m_strTable" => "penghasilan",
	"m_srcTableName" => "penghasilan"
));

$proto6["m_sql"] = "ID";
$proto6["m_srcTableName"] = "penghasilan";
$proto6["m_expr"]=$obj;
$proto6["m_alias"] = "";
$obj = new SQLFieldListItem($proto6);

$proto0["m_fieldlist"][]=$obj;
						$proto8=array();
			$obj = new SQLField(array(
	"m_strName" => "namapenghasilan",
	"m_strTable" => "penghasilan",
	"m_srcTableName" => "penghasilan"
));

$proto8["m_sql"] = "namapenghasilan";
$proto8["m_srcTableName"] = "penghasilan";
$proto8["m_expr"]=$obj;
$proto8["m_alias"] = "";
$obj = new SQLFieldListItem($proto8);

$proto0["m_fieldlist"][]=$obj;
$proto0["m_fromlist"] = array();
												$proto10=array();
$proto10["m_link"] = "SQLL_MAIN";
			$proto11=array();
$proto11["m_strName"] = "penghasilan";
$proto11["m_srcTableName"] = "penghasilan";
$proto11["m_columns"] = array();
$proto11["m_columns"][] = "ID";
$proto11["m_columns"][] = "namapenghasilan";
$obj = new SQLTable($proto11);

$proto10["m_table"] = $obj;
$proto10["m_sql"] = "penghasilan";
$proto10["m_alias"] = "";
$proto10["m_srcTableName"] = "penghasilan";
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
$proto0["m_srcTableName"]="penghasilan";		
$obj = new SQLQuery($proto0);

	return $obj;
}
$queryData_penghasilan = createSqlQuery_penghasilan();


	
		;

		

$tdatapenghasilan[".sqlquery"] = $queryData_penghasilan;

$tableEvents["penghasilan"] = new eventsBase;
$tdatapenghasilan[".hasEvents"] = false;

?>