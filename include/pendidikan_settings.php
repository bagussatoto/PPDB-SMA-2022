<?php



$tdatapendidikan = array();
$tdatapendidikan[".searchableFields"] = array();
$tdatapendidikan[".ShortName"] = "pendidikan";
$tdatapendidikan[".OwnerID"] = "";
$tdatapendidikan[".OriginalTable"] = "pendidikan";


$defaultPages = my_json_decode( "{\"add\":\"add\",\"edit\":\"edit\",\"export\":\"export\",\"import\":\"import\",\"list\":\"list\",\"print\":\"print\",\"search\":\"search\",\"view\":\"view\"}" );

$tdatapendidikan[".pagesByType"] = my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" );
$tdatapendidikan[".pages"] = types2pages( my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" ) );
$tdatapendidikan[".defaultPages"] = $defaultPages;

//	field labels
$fieldLabelspendidikan = array();
$fieldToolTipspendidikan = array();
$pageTitlespendidikan = array();
$placeHolderspendidikan = array();

if(mlang_getcurrentlang()=="Indonesian")
{
	$fieldLabelspendidikan["Indonesian"] = array();
	$fieldToolTipspendidikan["Indonesian"] = array();
	$placeHolderspendidikan["Indonesian"] = array();
	$pageTitlespendidikan["Indonesian"] = array();
	$fieldLabelspendidikan["Indonesian"]["ID"] = "No";
	$fieldToolTipspendidikan["Indonesian"]["ID"] = "";
	$placeHolderspendidikan["Indonesian"]["ID"] = "";
	$fieldLabelspendidikan["Indonesian"]["namatingkat"] = "Tingkat Pendidikan";
	$fieldToolTipspendidikan["Indonesian"]["namatingkat"] = "";
	$placeHolderspendidikan["Indonesian"]["namatingkat"] = "";
	if (count($fieldToolTipspendidikan["Indonesian"]))
		$tdatapendidikan[".isUseToolTips"] = true;
}


	$tdatapendidikan[".NCSearch"] = true;



$tdatapendidikan[".shortTableName"] = "pendidikan";
$tdatapendidikan[".nSecOptions"] = 0;

$tdatapendidikan[".mainTableOwnerID"] = "";
$tdatapendidikan[".entityType"] = 0;

$tdatapendidikan[".strOriginalTableName"] = "pendidikan";

	



$tdatapendidikan[".showAddInPopup"] = false;

$tdatapendidikan[".showEditInPopup"] = false;

$tdatapendidikan[".showViewInPopup"] = false;

//page's base css files names
$popupPagesLayoutNames = array();
$tdatapendidikan[".popupPagesLayoutNames"] = $popupPagesLayoutNames;


$tdatapendidikan[".listAjax"] = false;
//	temporary
$tdatapendidikan[".listAjax"] = false;

	$tdatapendidikan[".audit"] = false;

	$tdatapendidikan[".locking"] = false;


$pages = $tdatapendidikan[".defaultPages"];

if( $pages[PAGE_EDIT] ) {
	$tdatapendidikan[".edit"] = true;
	$tdatapendidikan[".afterEditAction"] = 1;
	$tdatapendidikan[".closePopupAfterEdit"] = 1;
	$tdatapendidikan[".afterEditActionDetTable"] = "";
}

if( $pages[PAGE_ADD] ) {
$tdatapendidikan[".add"] = true;
$tdatapendidikan[".afterAddAction"] = 1;
$tdatapendidikan[".closePopupAfterAdd"] = 1;
$tdatapendidikan[".afterAddActionDetTable"] = "";
}

if( $pages[PAGE_LIST] ) {
	$tdatapendidikan[".list"] = true;
}



$tdatapendidikan[".strSortControlSettingsJSON"] = "";




if( $pages[PAGE_VIEW] ) {
$tdatapendidikan[".view"] = true;
}

if( $pages[PAGE_IMPORT] ) {
$tdatapendidikan[".import"] = true;
}

if( $pages[PAGE_EXPORT] ) {
$tdatapendidikan[".exportTo"] = true;
}

if( $pages[PAGE_PRINT] ) {
$tdatapendidikan[".printFriendly"] = true;
}



$tdatapendidikan[".showSimpleSearchOptions"] = true; // temp fix #13449

// Allow Show/Hide Fields in GRID
$tdatapendidikan[".allowShowHideFields"] = true; // temp fix #13449
//

// Allow Fields Reordering in GRID
$tdatapendidikan[".allowFieldsReordering"] = true; // temp fix #13449
//

$tdatapendidikan[".isUseAjaxSuggest"] = true;

$tdatapendidikan[".rowHighlite"] = true;



			

$tdatapendidikan[".ajaxCodeSnippetAdded"] = false;

$tdatapendidikan[".buttonsAdded"] = false;

$tdatapendidikan[".addPageEvents"] = false;

// use timepicker for search panel
$tdatapendidikan[".isUseTimeForSearch"] = false;


$tdatapendidikan[".badgeColor"] = "5F9EA0";


$tdatapendidikan[".allSearchFields"] = array();
$tdatapendidikan[".filterFields"] = array();
$tdatapendidikan[".requiredSearchFields"] = array();

$tdatapendidikan[".googleLikeFields"] = array();
$tdatapendidikan[".googleLikeFields"][] = "ID";
$tdatapendidikan[".googleLikeFields"][] = "namatingkat";



$tdatapendidikan[".tableType"] = "list";

$tdatapendidikan[".printerPageOrientation"] = 0;
$tdatapendidikan[".nPrinterPageScale"] = 100;

$tdatapendidikan[".nPrinterSplitRecords"] = 40;

$tdatapendidikan[".geocodingEnabled"] = false;










$tdatapendidikan[".pageSize"] = 20;

$tdatapendidikan[".warnLeavingPages"] = true;



$tstrOrderBy = "";
if(strlen($tstrOrderBy) && strtolower(substr($tstrOrderBy,0,8))!="order by")
	$tstrOrderBy = "order by ".$tstrOrderBy;
$tdatapendidikan[".strOrderBy"] = $tstrOrderBy;

$tdatapendidikan[".orderindexes"] = array();

$tdatapendidikan[".sqlHead"] = "SELECT ID,  	namatingkat";
$tdatapendidikan[".sqlFrom"] = "FROM pendidikan";
$tdatapendidikan[".sqlWhereExpr"] = "";
$tdatapendidikan[".sqlTail"] = "";










//fill array of records per page for list and report without group fields
$arrRPP = array();
$arrRPP[] = 10;
$arrRPP[] = 20;
$arrRPP[] = 30;
$arrRPP[] = 50;
$arrRPP[] = 100;
$arrRPP[] = 500;
$arrRPP[] = -1;
$tdatapendidikan[".arrRecsPerPage"] = $arrRPP;

//fill array of groups per page for report with group fields
$arrGPP = array();
$arrGPP[] = 1;
$arrGPP[] = 3;
$arrGPP[] = 5;
$arrGPP[] = 10;
$arrGPP[] = 50;
$arrGPP[] = 100;
$arrGPP[] = -1;
$tdatapendidikan[".arrGroupsPerPage"] = $arrGPP;

$tdatapendidikan[".highlightSearchResults"] = true;

$tableKeyspendidikan = array();
$tableKeyspendidikan[] = "ID";
$tdatapendidikan[".Keys"] = $tableKeyspendidikan;


$tdatapendidikan[".hideMobileList"] = array();




//	ID
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 1;
	$fdata["strName"] = "ID";
	$fdata["GoodName"] = "ID";
	$fdata["ownerTable"] = "pendidikan";
	$fdata["Label"] = GetFieldLabel("pendidikan","ID");
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


	$tdatapendidikan["ID"] = $fdata;
		$tdatapendidikan[".searchableFields"][] = "ID";
//	namatingkat
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 2;
	$fdata["strName"] = "namatingkat";
	$fdata["GoodName"] = "namatingkat";
	$fdata["ownerTable"] = "pendidikan";
	$fdata["Label"] = GetFieldLabel("pendidikan","namatingkat");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "namatingkat";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "namatingkat";

	
	
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


	$tdatapendidikan["namatingkat"] = $fdata;
		$tdatapendidikan[".searchableFields"][] = "namatingkat";


$tables_data["pendidikan"]=&$tdatapendidikan;
$field_labels["pendidikan"] = &$fieldLabelspendidikan;
$fieldToolTips["pendidikan"] = &$fieldToolTipspendidikan;
$placeHolders["pendidikan"] = &$placeHolderspendidikan;
$page_titles["pendidikan"] = &$pageTitlespendidikan;

// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table (master)
$detailsTablesData["pendidikan"] = array();

// tables which are master tables for current table (detail)
$masterTablesData["pendidikan"] = array();



// -----------------end  prepare master-details data arrays ------------------------------//


require_once(getabspath("classes/sql.php"));










function createSqlQuery_pendidikan()
{
$proto0=array();
$proto0["m_strHead"] = "SELECT";
$proto0["m_strFieldList"] = "ID,  	namatingkat";
$proto0["m_strFrom"] = "FROM pendidikan";
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
	"m_strTable" => "pendidikan",
	"m_srcTableName" => "pendidikan"
));

$proto6["m_sql"] = "ID";
$proto6["m_srcTableName"] = "pendidikan";
$proto6["m_expr"]=$obj;
$proto6["m_alias"] = "";
$obj = new SQLFieldListItem($proto6);

$proto0["m_fieldlist"][]=$obj;
						$proto8=array();
			$obj = new SQLField(array(
	"m_strName" => "namatingkat",
	"m_strTable" => "pendidikan",
	"m_srcTableName" => "pendidikan"
));

$proto8["m_sql"] = "namatingkat";
$proto8["m_srcTableName"] = "pendidikan";
$proto8["m_expr"]=$obj;
$proto8["m_alias"] = "";
$obj = new SQLFieldListItem($proto8);

$proto0["m_fieldlist"][]=$obj;
$proto0["m_fromlist"] = array();
												$proto10=array();
$proto10["m_link"] = "SQLL_MAIN";
			$proto11=array();
$proto11["m_strName"] = "pendidikan";
$proto11["m_srcTableName"] = "pendidikan";
$proto11["m_columns"] = array();
$proto11["m_columns"][] = "ID";
$proto11["m_columns"][] = "namatingkat";
$obj = new SQLTable($proto11);

$proto10["m_table"] = $obj;
$proto10["m_sql"] = "pendidikan";
$proto10["m_alias"] = "";
$proto10["m_srcTableName"] = "pendidikan";
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
$proto0["m_srcTableName"]="pendidikan";		
$obj = new SQLQuery($proto0);

	return $obj;
}
$queryData_pendidikan = createSqlQuery_pendidikan();


	
		;

		

$tdatapendidikan[".sqlquery"] = $queryData_pendidikan;

$tableEvents["pendidikan"] = new eventsBase;
$tdatapendidikan[".hasEvents"] = false;

?>