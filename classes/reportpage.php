<?php
class ReportPage extends RunnerPage
{
	
	public $pagestart = 0;
	
	public $arrReport = array();	
	
	/**
	 * Array of groups per page for report with group fields
	 * @var array
	 * @intellisense
	 */	
	public $arrGroupsPerPage = array();
	
	/**
	 * The flag indicating that report is cross-table
	 */
	public $crossTable = false;
	
	/**
	 * Refresh the crosstab report
	 */
	public $crosstableRefresh = false;
	
	/**
	 * @type Boolean
	 */
	protected $noRecordsFound = false;
	
	protected $crossTableObj = null;
	
	public $pdfJson = false;
	
	public $x;
	public $y;
	public $dataField;
	public $operation;
	public $xType;
	public $yType;
	public $selectedAxis;
	
	/**
	 * @constructor
	 * @param &Array params
	 */
	function __construct( &$params ) 
	{
		parent::__construct($params);
		
		
		$this->crossTable = $this->pSet->isCrossTabReport();
		
		$this->jsSettings['tableSettings'][ $this->tName ]['crossTable'] = $this->crossTable;
		$this->jsSettings['tableSettings'][ $this->tName ]['simpleSearchActive'] = $this->searchClauseObj->simpleSearchActive;
		
		if( $this->mode == REPORT_DASHBOARD || $this->mode == REPORT_DETAILS || $this->mode == REPORT_DASHDETAILS )
		{
			if( $this->mode != REPORT_DETAILS )
			{
				$this->formBricks["header"] = array( 
					array( "name" => "details_found", "align" => "right" )
				);
			}
			$this->formBricks["footer"] = array( "pagination_block" );	
		}

		$this->controlsMap["pdfSettings"] = array();
		$this->controlsMap["pdfSettings"]["allPagesMode"] = 0;
	}

	/**
	 * Set the page's session prefix
	 */
	protected function assignSessionPrefix()
	{
		if( $this->mode == REPORT_DASHBOARD )
			$this->sessionPrefix = $this->dashTName."_".$this->tName;
		else
			$this->sessionPrefix = $this->tName;
	}	
	
	/**
	 * Set session variables
	 */
	public function setSessionVariables()
	{
		parent::setSessionVariables();
		
		$_SESSION[$this->sessionPrefix.'_advsearch'] = serialize($this->searchClauseObj);
		
		if( !$_SESSION[$this->sessionPrefix."_pagesize"] )
			$_SESSION[$this->sessionPrefix."_pagesize"] = $this->pSet->getInitialPageSize();
		
		$this->pageSize = $_SESSION[$this->sessionPrefix."_pagesize"];
		
		if( !$_SESSION[ $this->sessionPrefix."_pagenumber" ] )
			$_SESSION[ $this->sessionPrefix."_pagenumber" ] = 1;
		
		if( isset($_REQUEST["goto"]) )
			$_SESSION[ $this->sessionPrefix."_pagenumber" ] = intval($_REQUEST["goto"]);
		
		$this->myPage = $_SESSION[$this->sessionPrefix."_pagenumber"];
		
		$this->pagestart = ($this->myPage - 1) * $this->pageSize;
	}

	/**
	 * Build the activated Search panel
	 */
	public function buildSearchPanel()
	{
		if( $this->mode == REPORT_DASHBOARD )
			return;
		
		parent::buildSearchPanel();
	}
	
	/**
	 * Process the page 
	 */
	public function process()
	{
		if( $this->mode == REPORT_DASHDETAILS 
			|| $this->mode == REPORT_DETAILS && ( $this->masterPageType == PAGE_LIST || $this->masterPageType == PAGE_REPORT ))
			$this->updateDetailsTabTitles();

		if( $this->crossTable && !$this->checkCrossParams() )
		{
			if( $this->mode == REPORT_SIMPLE )
			{
				$this->crossTableBaseRedirect();
				return;
			}
			
			$this->setDefaultParams();
		}
		
		//	Before Process event
		if( $this->eventsObject->exists("BeforeProcessReport") )
			$this->eventsObject->BeforeProcessReport( $this );

		$this->setDetailsBadgeStyles();
		// array with extra report params
		$extraParams = $this->getExtraReportParams();

		$this->setGoogleMapsParams( $extraParams['fieldsArr'] );
		if( $this->googleMapCfg['isUseGoogleMap'] ) 
			$this->initGmaps();

		$this->buildSearchPanel();

		// build tabs and set current
		$this->processGridTabs();	
			
		$this->setReportData( $extraParams );	

		$this->addCommonJs();
		$this->addButtonHandlers();
		$this->commonAssign();
		$this->doCommonAssignments();	
		$this->addCustomCss();
			
		// display the 'Back to Master' link and master table info
		if( $this->mode == REPORT_SIMPLE ) 
			$this->displayMasterTableInfo();	
		
		$this->showPage();	
	}
	
	/**
	 * @return Boolean
	 */
	protected function checkCrossParams()
	{
		if( !$this->crossTable )
			return true;
		
		return strlen( $this->x ) && strlen( $this->y ) && strlen( $this->dataField );
	}
	
	/**
	 *
	 */
	protected function setDefaultParams()
	{
		$prms = $this->getDefaultCrossParams();
		
		$this->x = $prms["x"];
		$this->y = $prms["y"];
		$this->dataField = $prms["data"];
		$this->operation = $prms["op"];
	}
	
	/**
	 * Fix it!
	 * @return Array
	 */
	protected function getDefaultCrossParams()
	{
		if( !$this->crossTable )
			return array();
			
		$xNames = array();
		$yNames = array();
		$allNames = array();
		foreach( $this->pSet->getReportGroupFieldsData() as $ind => $value )
		{
			$axisName = $value["strGroupField"];
		
			if( $value["crossTabAxis"] == 0 )
				$xNames[] = $axisName;
			elseif( $value["crossTabAxis"] == 1 )
				$yNames[] = $axisName;
			else
				$allNames[]= $axisName;	
		}

		if( count( $xNames ) > 0 )
			$x = $xNames[0];
		else
			$x = $allNames[0];
	
		if( count($yNames) > 0 )
			$y = $yNames[0];
		else 
		{
			if( !count( $xNames ) )
				$y = $allNames[1];
			else
				$y = $allNames[0];
		}
		
		$dataField = "";
		$operation = "";
		$reportFields = $this->pSet->getFieldsList();
		foreach( $reportFields as $field )
		{			
			$operation = "";
			if( $this->pSet->getFieldData($field, 'isTotalMax') )
				$operation = "max";
			else if( $this->pSet->getFieldData($field, 'isTotalMin') )
				$operation = "min";
			else if( $this->pSet->getFieldData($field, 'isTotalAvg') )
				$operation = "avg";
			else if( $this->pSet->getFieldData($field, 'isTotalSum') )
				$operation = "sum";				
					
			if( $operation )
			{			
				$dataField = $field;
				break;
			}
		}	
		
		return array( "x" => $x, "y" => $y, "data" => $dataField, "op" => $operation );
	}
	
	/**
	 * @return Array
	 */
	protected function getCurrentCrossParams()
	{
		$prms = array( "x" => $this->x, "y" => $this->y, "data" => $this->dataField, "op" => $this->operation );
		
		if( $this->xType )
			$prms["xtype"] = $this->xType;
			
		if( $this->yType )
			$prms["ytype"] = $this->yType;
			
		return $prms;	
	}
	
	/**
	 * @return String
	 */
	protected function getDefaultCrossParamsString()
	{
		$prms = $this->getDefaultCrossParams();
		return "x=".$prms["x"]."&y=".$prms["y"]."&data=".$prms["data"]."&op=".$prms["op"];
	}
	
	/**
	 *
	 */
	protected function crossTableBaseRedirect() 
	{
		HeaderRedirect( $this->pSet->getShortTableName(), $this->getPageType(), $this->getDefaultCrossParamsString() );
		exit();
	}
	
	/**
	 * Get the report data, assign corresponding xt variables 
	 * @param Array options
	 */
	public function setReportData( $options )
	{
		if( $this->crossTable )
			$this->setCrosstabData( $options );
		else 
			$this->setStandartData( $options );
	}
	
	/**
	 *
	 */
	protected function getCrossGroupFieldsSettings( $repGroupFields )
	{
		$groupFields = array();
		
		$xNames = array();
		$yNames = array();
		
		$xFieldsCount = array();
		$yFieldsCount = array();
		
		foreach( $repGroupFields as $ind => $value )
		{
			if( $value["crossTabAxis"] == 0 || $value["crossTabAxis"] == 2 )
				++$xFieldsCount[ $value["strGroupField"] ];
			if( $value["crossTabAxis"] == 1 || $value["crossTabAxis"] == 2 )
				++$yFieldsCount[ $value["strGroupField"] ];
		}
		
		foreach( $repGroupFields as $ind => $value )
		{
			$groupFields[ $ind ]["name"] = $value["strGroupField"];
			
			$xAxis = ($value["crossTabAxis"] == 0 || $value["crossTabAxis"] == 2);
			$yAxis = ($value["crossTabAxis"] == 1 || $value["crossTabAxis"] == 2);
			
			if( 0 == $value["groupInterval"] ||
				$xAxis && $xFieldsCount[ $value["strGroupField"] ] < 2 ||
				$yAxis && $yFieldsCount[ $value["strGroupField"] ] < 2 )
			{
				$groupFields[ $ind ]["label"] = $this->pSet->label( $value["strGroupField"] );
			}
			else
			{
				$groupFields[ $ind ]["label"] = $this->pSet->label( $value["strGroupField"] ) . " - " . CrossTableReport::getCrossIntervalName( $this->pSet->getFieldType( $value["strGroupField"] ), $value["groupInterval"] );
				
			}
			
			$groupFields[ $ind ]["uniqueName"] = true;
			$groupFields[ $ind ]["int_type"] = $value["groupInterval"];
			
			if( !$xNames[ $value["strGroupField"] ] )
				$xNames[ $value["strGroupField"] ] = array();	
			
			if( !$yNames[ $value["strGroupField"] ] )
				$yNames[ $value["strGroupField"] ] = array();			
			
			if( $value["crossTabAxis"] == 0 )
			{
				$groupFields[ $ind ]["group_type"] = "x";	
				$xNames[ $value["strGroupField"] ][] = $ind;
			}
			elseif( $value["crossTabAxis"] == 1 )
			{
				$groupFields[ $ind ]["group_type"] = "y";			
				$yNames[ $value["strGroupField"] ][] = $ind;
			}
			else
			{
				$groupFields[ $ind ]["group_type"] = "all";			
				$xNames[ $value["strGroupField"] ][] = $ind;			
				$yNames[ $value["strGroupField"] ][] = $ind;
			}
		}
	
	
		foreach( $xNames as $fName => $indices )
		{
			if( count( $indices ) > 1 ) 
			{
				foreach( $indices as $ind )
				{
					$groupFields[ $ind ]["uniqueName"] = false;
				}
			}
		}
	
	
		foreach( $yNames as $fName => $indices )
		{
			if( count( $indices ) > 1 ) 
			{
				foreach( $indices as $ind )
				{
					$groupFields[ $ind ]["uniqueName"] = false;
				}
			}
		}
	
		return $groupFields;
	}
	
	/**
	 * Get data for crosstab and assign with xt
	 * @param &Array _options	 
	 */
	public function setCrosstabData( &$_options )
	{
		if( $this->pSetSearch->noRecordsOnFirstPage() && !$this->isSearchFunctionalityActivated() )
		{
			$this->xt->assign("container_grid", false);
			$this->showNoRecordsMessage();		
		}
		
		include_once( getabspath("classes/crosstable_report.php") );
		
		$params = array();
		
		$params["selectedAxis"] = $this->selectedAxis;
		$params["x"] = $this->x;
		$params["y"] = $this->y;
		$params["data"] = $this->dataField;
		$params["xType"] = $this->xType;
		$params["yType"] = $this->yType;
		$params["operation"] = $this->operation;

		$params["headerClass"] = $this->getFieldClass( $this->x );
		$params["dataClass"] = $this->getFieldClass( $this->dataField );
		
		$params["tableName"] = $this->tName;
		$params["pageType"] = $this->pageType;

		$params["groupFields"] = $this->getCrossGroupFieldsSettings( $_options["repGroupFields"] );
		
		$params["xSummary"] = $this->pSet->reportHasHorizontalSummary();
		$params["ySummary"] = $this->pSet->reportHasVerticalSummary();
		$params["totalSummary"] = $params["xSummary"] || $params["ySummary"];
		
		
		foreach( $_options["fieldsArr"] as $ind => $value )
		{
			$params["totals"][ $value["name"] ]["name"] = $value["name"];
			$params["totals"][ $value["name"] ]["label"] = $value["label"];
			
			$params["totals"][ $value["name"] ]["max"] = $value["totalMax"];
			$params["totals"][ $value["name"] ]["min"] = $value["totalMin"];
			$params["totals"][ $value["name"] ]["sum"] = $value["totalSum"];
			$params["totals"][ $value["name"] ]["avg"] = $value["totalAvg"];
		}
		
		if( $this->pdfJsonMode() )
			$params["pdfJSON"] = true;		
		
		$this->crossTableObj = new CrossTableReport( $params, $this->getBasicCrossTableSQL(), $this );
		
		if( $this->crosstableRefresh )
		{
			$this->refreshCrossTable();
			return;
		}
		
		if( $this->crossTableObj->isEmpty() ) 
		{
			$this->noRecordsFound = true;
			$this->jsSettings["tableSettings"][ $this->tName ]["crossParams"] = $this->getCurrentCrossParams();
		}
		
		$this->crossTableCommonAssign( $params["totalSummary"] );
	}
	
	/**
	 *
	 */
	protected function refreshCrossTable()
	{
		$reportData = array();
		
		$reportData["rowsInfo"] = $this->crossTableObj->getCrossTableData();
		$reportData["totalsName"] = $this->crossTableObj->getTotalsName();
		$reportData["columnSummary"] = $this->crossTableObj->getCrossTableSummary();
		$reportData["totalSummary"] = $this->crossTableObj->getTotalSummary();
		
		$reportData["groupFuncCtrl"] = $this->getOperationCtrlMarkup();
		$reportData["dataClass"] = $this->getFieldClass( $this->dataField );

		echo printJSON( $reportData );
		exit();			
	}
	
	/**
	 * @param String axis
	 * @return String
	 */
	protected function getCrossFieldCtrlMarkup( $axis )
	{
		$classAttr = "";
		if( $this->isBootstrap() )
			$classAttr = "form-control bs-cross-dd".$axis;
		
		$options = array();
		$lastLabel = "";
		foreach( $this->crossTableObj->getCrossFieldsData( $axis ) as $fData )
		{
			$intervalDataAttr = $fData["intervalType"] ? " data-".$axis."type=\"". $fData["intervalType"] ."\" " : "";	
			$lastLabel = $fData["label"];
			$options[] = "<option ". $intervalDataAttr ." value=\"".$fData["value"]."\" ".$fData["selected"].">".$lastLabel."</option>";			
		}

		$hiddenAttr = "";
		$labelTag = "";
		if ( count($options) < 2 )
		{
			$labelTag = "<span class=\"" . $classAttr . " like-text\">". $lastLabel ."</span>";
			$hiddenAttr = "style=\"display: none;\"";
		}		

		return "<select ".$hiddenAttr." id=\"select_group_" . $axis . $this->id."\" class=\"" . $classAttr . "\">". implode("", $options) ."</select>" . $labelTag ;
	}	
	
	/**
	 * @return String
	 */
	protected function getDataFieldCtrlMarkup()
	{
		$classAttr = "";
		if( $this->isBootstrap() )
			$classAttr = "form-control bs-cross-ddvalue";
		
		$options = array();
		$lastLabel = "";
		foreach( $this->crossTableObj->getDataFieldsList() as $fData )
		{
			$lastLabel = runner_htmlspecialchars( $fData["label"] );
			$options[] = "<option value=\"". $fData["value"] ."\" ". $fData["selected"] .">". $lastLabel ."</option>";			
		}

		$hiddenAttr = "";
		$labelTag = "";
		if ( count($options) < 2 )
		{
			$labelTag = "<span class=\"" . $classAttr . " like-text\">". $lastLabel ."</span>";
			$hiddenAttr = "style=\"display: none;\"";
		}

		return "<select ".$hiddenAttr." id=\"select_data".$this->id."\" class=\"" . $classAttr . "\">". implode("", $options) ."</select>" . $labelTag;
		
		
	}
	
	/**
	 * @return String
	 */	
	protected function getOldOperationCtrlMarkup()
	{
		$inputCtrls = array();
		foreach( $this->crossTableObj->getCurrentOperationList() as $opData ) 
		{
			$cheked = $opData["selected"] == "selected" ? "checked" : "";
			$inputCtrls[] = "<input type=radio value='".$opData["value"]."' name=\"group_func".$this->id."\" ".$cheked."> ".$opData["label"];
		}

		return implode( "&nbsp;&nbsp;", $inputCtrls );
	}
	
	/**
	 * @return String
	 */
	protected function getOperationCtrlMarkup()
	{
		if( !$this->isBootstrap() )
			return $this->getOldOperationCtrlMarkup();

		$options = array();
		$lastLabel = "";
		foreach( $this->crossTableObj->getCurrentOperationList() as $opData )
		{
			$lastLabel = runner_htmlspecialchars( $opData["label"] );
			$options[] = "<option value=\"". $opData["value"] ."\" ". $opData["selected"] .">". $lastLabel ."</option>";			
		}

		if ( count($options) < 2 )
			return  "<span id=\"group_func". $this->id."\" class=\"bs-cross-ddgroup form-control like-text\">". $lastLabel ."</span>";
		
		return "<select id=\"group_func". $this->id ."\" class=\"bs-cross-ddgroup form-control\">". implode("", $options) ."</select>";
	}
	
	/**
	 * Assign values obtained from crossTable object to
	 * the basic cross table xt variables
	 * @param Boolean showSummary
	 */
	protected function crossTableCommonAssign( $showSummary )
	{
		if( !$this->mobileTemplateMode() ) 
		{
			$this->xt->assign( "cross_controls", true );	
			
			$this->xt->assign( "select_group_x", $this->getCrossFieldCtrlMarkup("x") );
			$this->xt->assign( "select_group_y", $this->getCrossFieldCtrlMarkup("y") );
			$this->xt->assign("select_data", $this->getDataFieldCtrlMarkup() );
			$this->xt->assign( $this->isBootstrap() ? "select_group" : "group_func", $this->getOperationCtrlMarkup() );
		}
		
		$this->xt->assign( "totals", $this->crossTableObj->getTotalsName() );		
		
		$grid_row["data"] = $this->crossTableObj->getCrossTableData();		
	
		$allow_export = $this->permis[ $this->tName ]["export"]; 
		$this->xt->assign( "export_link", $allow_export && !$this->noRecordsFound );
		
		$this->xt->assign( "prints_block", $this->printAvailable() && !$this->noRecordsFound );
		
		if ( !$this->isDashboardElement() )			
			$this->xt->assign("print_friendly", $this->printAvailable() && !$this->noRecordsFound );
		

		if( !$this->noRecordsFound )
		{
			$this->xt->assign("grid_row", $grid_row);
			$headers = $this->crossTableObj->getCrossTableHeader();
			$this->xt->assignbyref("group_header", $headers );
			$this->xt->assignbyref("col_summary", $this->crossTableObj->getCrossTableSummary());	
			$this->xt->assignbyref("total_summary", $this->crossTableObj->getTotalSummary());
			$this->xt->assignbyref("summary_class", $this->getFieldClass( $this->dataField ));
			$this->xt->assign("cross_totals", $showSummary);
			
			$headerColspan = count( $grid_row["data"][0]["row_record"]["data"] );
			
			if( $grid_row["data"][0]["row_record"]["cross_totals"] )
				++$headerColspan;
				
			if( $headerColspan > 1 )
				$this->xt->assign( "xselcell_attrs", "colspan=".$headerColspan );
		}
		
		$this->xt->assign("crosstable_attrs", "&x=".$this->x."&y=".$this->y."&data=".$this->dataField."&op=".$this->operation);	//?
		
		$this->xt->assign( "grid_header", !$this->noRecordsFound );
	}	
	
	
	/**
	 * Get data for standart report and assign with xt
	 * @param &Array _options	 
	 */
	public function setStandartData(&$_options)
	{		
		include_once(getabspath('classes/reportlib.php'));
		
		$whereComponents = $this->getWhereComponents();
		$sqlArray = $this->getReportSQLData();
		$pageSize = $this->pageSize;
		if ( $this->pageSize == -1 ) {
			$pageSize = 0;
		}
		
		$rb = new Report($sqlArray, $this->pSet->getOrderIndexes(), $this->connection, $pageSize, 0, $_options
			, $whereComponents["searchWhere"], $whereComponents["searchHaving"], $this); 
			
		$this->arrReport = $rb->getReport( $this->pagestart );

		$this->setRecordsId();
		$this->setDetailLinks();
		
		$this->buildPagination();
		
		$this->standardReportCommonAssign();
		$this->assignColumnHeaderClasses();
	}

	/**
	 * Get where clause for an active master-detail relationship
	 * @return string
	 */
	public function getMasterTableSQLClause( $basedOnProp = false ) 
	{
		if( $this->mode == REPORT_DASHBOARD && !isset($this->dashElementData["masterTable"]) )
			return "";		
		return parent::getMasterTableSQLClause(); 
	}
	
	/**
	 * Assign the basic cross table xt variables
	 */
	protected function standardReportCommonAssign()
	{
		$this->xt->assign(GoodFieldName($this->tName)."_dtable_column", true); //fix it!
		$this->xt->assign("dtables_link", true);
			
		foreach($this->arrReport['page'] as $key => $value)
		{
			$this->xt->assign($key, $value);
		}
		
		foreach($this->arrReport['global'] as $key => $value)
		{
			$this->xt->assign($key, $value);
		}			
		
		if( count($this->arrReport['list']) > 0 )
			$this->xt->assign('grid_row', array('data' => $this->arrReport['list']));
		else
			$this->noRecordsFound = true;
		
		$allow_export = $this->permis[ $this->tName ]["export"];
		$allow_search = $this->permis[ $this->tName ]["search"];		
		$this->xt->assign("export_link", $allow_export && $this->arrReport['countRows'] > 0);
		$this->xt->assign("prints_block", $allow_export && $this->arrReport['countRows'] > 0);		
		$this->xt->assign("printall_link", $allow_export && $this->arrReport['countRows'] > $this->pageSize && $this->pageSize > 0);
		$this->xt->assign("print_recspp", $this->pSet->getReportPrintGroupsPerPage() );

		if ( !$this->isDashboardElement() )
		{			
			$this->xt->assign("print_friendly", $this->printAvailable() && $this->arrReport['countRows'] > 0 );
			$this->xt->assign("print_friendly_all", $this->printAvailable() && $this->arrReport['countRows'] > 0);
		}

		if( $this->mode == REPORT_SIMPLE && $allow_search && count($this->arrGroupsPerPage) )
		{
			$this->xt->assign("recordspp_block", true);
			$this->createPerPage();
		}

		if ( $this->isBootstrap() )
		{
			$this->xt->assign("details_found", $this->arrReport['countRows'] != 0);
			
			if( $this->isPD() && $this->noRecordsFound )
			{
				$this->hideItem("details_found");
				$this->hideItem("page_size");
			}			
		}
		
		$this->xt->assign("details_block", $this->arrReport['countRows'] != 0);
		$this->xt->assign("records_found", $this->arrReport['countRows']);
		$this->xt->assign("pages_block", $this->arrReport['countRows'] != 0);
		$this->xt->assign("page", $this->myPage);
		$this->xt->assign("maxpages", $this->maxPages);

		
		$this->xt->assign( "global_summary", !$this->noRecordsFound );
		$this->xt->assign( "page_summary", !$this->noRecordsFound );
		$this->xt->assign( "summary_header", !$this->noRecordsFound );

		// tabular report
		$this->xt->assign( "grid_header", !$this->noRecordsFound );
	}
	
	/**
	 * Assign pagination_block, pagination xt variables
	 */
	public function buildPagination()
	{
		//	prepare for create pagination
		$this->maxPages = $this->arrReport['maxpages'];

		$lastrecord = $this->myPage * $this->pageSize;
		if( $this->pageSize < 0 || $lastrecord > $this->arrReport['countRows'] )
			$lastrecord = $this->arrReport['countRows'];
		
		$this->prepareRecordsIndicator( ( $this->myPage - 1 ) * $this->pageSize + 1, $lastrecord, $this->arrReport['countRows'] );

		$advSeparator = "&nbsp;:&nbsp;";
		$separator = "&nbsp;";
		if( $this->isBootstrap() )
		{
			$separator = "";
			$advSeparator = "";
		}
		
		if($this->maxPages > 1)
		{		
			$this->xt->assign("pagination_block", true);
			$pagination = '';
			$limit = 10;
			if( $this->mobileTemplateMode() )	
				$limit = 5;

			$counterstart = $this->myPage - ($limit - 1);
			if($this->myPage % $limit != 0)
				$counterstart = $this->myPage -($this->myPage % $limit) + 1;
			
			$counterend = $counterstart + ($limit - 1);
			if($counterend > $this->maxPages)
				$counterend = $this->maxPages;
			
			if($counterstart != 1) 
			{
				$pagination.= $this->getPaginationLink(1, "Pertama").$advSeparator;
				$pagination.= $this->getPaginationLink($counterstart - 1, "Sebelumnya").$separator;
			}
			
			$pageLinks = "";	
			if(isRTL())
			{
				for($counter = $counterend; $counter >= $counterstart; $counter --) 
				{
					$pageLinks.= $separator . $this->getPaginationLink( $counter, $counter, $counter == $this->myPage );
				}
			}
			else
			{
				for($counter = $counterstart; $counter <= $counterend; $counter ++) 
				{
					$pageLinks .= $separator . $this->getPaginationLink( $counter, $counter, $counter == $this->myPage );
				}
			}

			if( !$this->isBootstrap() )
			{
				$pageLinks = "[" . $pageLinks . $separator . "]";
			}
			
			$pagination.= $pageLinks;

			if($counterend != $this->maxPages) 
			{
				$pagination.= $separator . $this->getPaginationLink($counterend + 1, "Berikutnya") . $advSeparator;
				$pagination.= $separator . $this->getPaginationLink($this->maxPages, "Terakhir");
			}			
			if( $this->isBootstrap() )
				$pagination = '<nav><ul class="pagination" data-function="pagination' . $this->id . '">' . $pagination . '</ul></nav>';
			else
				$pagination = "<div data-function=\"pagination" . $this->id . "\">" . $pagination . "</div>";
			
			$this->xt->assign("pagination", $pagination);
		}
		else if( !$this->myPage )
		{
			$this->myPage = 1; //temporary fix
		}
	}
	
	protected function setRecordsId() {
		$recCount = count( $this->arrReport['list'] );
		for( $i = 0; $i < $recCount; ++$i ) {
			$this->genId();
			$this->arrReport['list'][$i]["recId"] = $this->recId;
		}
	}

	/**
	 * fix it!
	 */
	protected function setDetailLinks()
	{
		if( $this->mode == REPORT_DASHBOARD )
			return;
			
		// set detail links 	
		foreach( $this->allDetailsTablesArr as $detailTableData )
		{
			// get perm for det tables
			$this->permis[ $detailTableData['dDataSourceTable'] ] = $this->getPermissions( $detailTableData['dDataSourceTable'] );
			// field names of detail keys of passed detail table, when current is master
			$this->detailKeysByD[] = $this->pSet->getDetailKeysByDetailTable( $detailTableData['dDataSourceTable'] );			
		}
		
		$this->controlsMap['gridRows'] = array();
		$arrReportList = array();
		foreach ($this->arrReport['list'] as $key => $data) 
		{
			if (!isset($data['row_data']))
				continue;
			$record = array();
			//$this->genId();
			$recId = $data["recId"];
			
			$gridRowInd = count($this->controlsMap['gridRows']);
			$this->controlsMap['gridRows'][$gridRowInd] = array();
			$this->controlsMap['gridRows'][$gridRowInd]['id'] = $recId;
			$this->controlsMap['gridRows'][$gridRowInd]['rowInd'] = $gridRowInd;
			//Add the connection with containing row. It's important for vertical layout's multiple records per row mode
			$this->controlsMap['gridRows'][$gridRowInd]['keyFields'] = array();
			$this->controlsMap['gridRows'][$gridRowInd]['keys'] = array();
			for($i = 0; $i < count($tKeys); $i ++) {
				$this->controlsMap['gridRows'][$gridRowInd]['keyFields'][$i] = $tKeys[$i];
				$this->controlsMap['gridRows'][$gridRowInd]['keys'][$i] = $data[$tKeys[$i].'_value'];
			}
			
			$this->proccessDetailGridInfo($record, $data, $gridRowInd);
			$record["recordattrs"] = "data-record-id=\"".$recId."\"";
			//$record["recId"] = $recId;
			$record["rowattrs"] = " id=\"gridRow".$recId."\"";
			$arrReportList[$key] = array_merge_assoc($data, $record);
			$this->recIds[] = $recId;
		}
		
		foreach($arrReportList as $key => $data)
		{
			$this->arrReport['list'][$key] = $data;
		}		
		// end set detail links		
	}
	


	/**
	 * Get the basic SQL query for a crosstable report
	 * @return String
	 */
	protected function getBasicCrossTableSQL() 
	{
		$sql = $this->getSubsetSQLComponents();
		return SQLQuery::buildSQL( $sql["sqlParts"], $sql["mandatoryWhere"], $sql["mandatoryHaving"], $sql["optionalWhere"], $sql["optionalHaving"] );
	}

	protected function getSubsetSQLComponents()
	{
		$sql = parent::getSubsetSQLComponents();

		$sql["mandatoryWhere"][] = $this->SecuritySQL( "Search" );

		
	// hide data until search
		if( $this->pSet->noRecordsOnFirstPage() && !$this->searchClauseObj->isSearchFunctionalityActivated() )
			$sql["mandatoryWhere"][] = " 1=0 ";
		return $sql;
	}
	
	/**
	 * Get SQL query data for the Report constructor
	 * @return Array
	 */
	public function getReportSQLData()
	{
		$sql = $this->getSubsetSQLComponents();
		//	do DB::PrepareSQL for all SQL parts
		
		$sql["sqlParts"]["head"] = DB::PrepareSQL( $sql["sqlParts"]["head"] );
		$sql["sqlParts"]["from"] = DB::PrepareSQL( $sql["sqlParts"]["from"] );
		$sql["sqlParts"]["where"] = DB::PrepareSQL( $sql["sqlParts"]["where"] );
		$sql["sqlParts"]["groupby"] = DB::PrepareSQL( $sql["sqlParts"]["groupby"] );
		$sql["sqlParts"]["having"] = DB::PrepareSQL( $sql["sqlParts"]["having"] );
		
		// combine all where clauses into a single expression
		
		$optWhere = SQLQuery::combineCases( $sql["optionalWhere"], "or" );
		$optHaving = SQLQuery::combineCases( $sql["optionalHaving"], "or" );
		
		$allWhere = SQLQuery::combineCases( 
				array( SQLQuery::combineCases( $sql["mandatoryWhere"], "and" ), 
				$sql["sqlParts"]["where"], 
				$optWhere ), 
			"and" );

		$allHaving = SQLQuery::combineCases( 
				array( SQLQuery::combineCases( $sql["mandatoryHaving"], "and" ), 
				$sql["sqlParts"]["having"], 
				$optHaving ), 
			"and" );
		
		return array($sql["sqlParts"]["head"], $sql["sqlParts"]["from"], $allWhere, $sql["sqlParts"]["groupby"], $allHaving);
	}
	
	
	/**
	 * Prepare detail for edit and view
	 */
	public function prepareDetailsForEditViewPage() 
	{		
		$this->addButtonHandlers();
		$this->commonAssign();

		if( $this->allDetailsTablesArr )
		{
			$this->AddCSSFile("include/jquery-ui/smoothness/jquery-ui.min.css");
			$this->AddCSSFile("include/jquery-ui/smoothness/jquery-ui.theme.min.css");
		}
		
		
		$this->setReportData( $this->getExtraReportParams() );
		$this->xt->assign("cross_controls", false);

		
		$this->xt->assign("grid_block", true);
		$this->xt->assign("recordspp_block", true);

		$this->doCommonAssignments();	
		
		$this->createPerPage();
		
		$this->xt->assign("id", $this->id);
		
		$this->body["begin"] = '';
		$this->body["end"] = '';
		$this->xt->assign("body", $this->body);	
	}
	
	/**
	 * @return Array
	 */
	public function getExtraReportParams()
	{
		$extraParams = array();
		
		if( !$this->crossTable )
		{
			$extraParams['tName'] = $this->tName;
			$extraParams['shortTName'] = $this->shortTableName;
			$extraParams['sessionPrefix'] = $this->sessionPrefix;
			$extraParams['shortTableName'] = $this->shortTableName;			
			
			$extraParams['tKeyFields'] = $this->pSet->getTableKeys();
			$extraParams['repPageSummary'] = $this->pSet->reportHasPageSummary();
			$extraParams['repGlobalSummary'] = $this->pSet->reportHasGlobalSummary();
			$extraParams['repLayout'] = $this->pSet->getReportLayout();
			$extraParams['showGroupSummaryCount'] = $this->pSet->isGroupSummaryCountShown();
			$extraParams['repShowDet'] = $this->pSet->reportDetailsShown();
			// if any field used for totals
			$extraParams['isExistTotalFields'] = $this->pSet->reportTotalFieldsExist();
		}
		
		// report field info
		$extraParams['repGroupFields'] = $this->pSet->getReportGroupFieldsData();
		$extraParams['repGroupFieldsCount'] = count( $extraParams['repGroupFields'] );		
		
		$paramfieldArr = array();
		$reportFields = $this->pSet->getFieldsList();
		foreach( $reportFields as $field )
		{
			$fieldArr = array();
			$fieldArr['name'] = $field;
			//'fName' added for maps
			$fieldArr['fName'] = $field;
			$fieldArr['label'] = $this->pSet->label( $field );
			$fieldArr['goodName'] = GoodFieldName( $field );
			$fieldArr['repPage'] = true; //"@f.bReportPage"; 
			$fieldArr['viewFormat'] = $this->pSet->getViewFormat( $field );
			$fieldArr['editFormat'] = $this->pSet->getEditFormat( $field );
		
			$fieldArr['totalMax'] = $this->pSet->getFieldData($field, 'isTotalMax');
			$fieldArr['totalMin'] = $this->pSet->getFieldData($field, 'isTotalMin');
			$fieldArr['totalAvg'] = $this->pSet->getFieldData($field, 'isTotalAvg');
			$fieldArr['totalSum'] = $this->pSet->getFieldData($field, 'isTotalSum');							
			$paramfieldArr[] = $fieldArr;		
		}
		
		$extraParams['fieldsArr'] = $paramfieldArr;
		
		return $extraParams;
	}
	
	/**
	 * Assign recsPerPage, recsPerPage xt variables
	 */
	public function createPerPage()
	{
		$classString = "";
		$allMessage = "Perlihatkan semua";
		if( $this->isBootstrap() )
		{
			$classString = 'class="form-control"';
			$allMessage = "All";
		}
		
		$rpp = "<select ".$classString." id=\"recordspp".$this->id."\">";
		
		// use group fields on report page or not
		$reportGroupFields = $this->pSet->isReportWithGroups();
		
		if( $reportGroupFields )
		{			
			for($i = 0; $i < count($this->arrGroupsPerPage); $i++)
			{			
				if( $this->arrGroupsPerPage[$i] != -1 )
					$rpp.= "<option value=\"".$this->arrGroupsPerPage[$i]."\" ".($this->pageSize == $this->arrGroupsPerPage[$i] ? "selected" : "").">".$this->arrGroupsPerPage[$i]."</option>";
				else	
					$rpp.= "<option value=\"-1\" ".($this->pageSize == $this->arrGroupsPerPage[$i] ? "selected" : "").">".$allMessage."</option>";
			}	
		}	
		else
		{		
			for($i = 0; $i < count($this->arrRecsPerPage); $i++)
			{
				if( $this->arrRecsPerPage[$i] != -1 )
					$rpp.= "<option value=\"".$this->arrRecsPerPage[$i]."\" ".($this->pageSize == $this->arrRecsPerPage[$i] ? "selected" : "").">".$this->arrRecsPerPage[$i]."</option>";
				else
					$rpp.= "<option value=\"-1\" ".($this->pageSize == $this->arrRecsPerPage[$i] ? "selected" : "").">".$allMessage."</option>";
			}
		}
		
		$rpp.= "</select>";
		
		if( $reportGroupFields || $this->isPD() )
			$this->xt->assign("grpsPerPage", $rpp);
		else
			$this->xt->assign("recsPerPage", $rpp);
	}

	/**
	 *
	 */
	public function doCommonAssignments() //make it protected
	{
		$this->xt->assign("id", $this->id);
		$this->xt->assign("left_block", true);
		
		$this->assignBody();
		
		$this->setLangParams();

		//set the Search panel
		$this->xt->assign("searchPanel", true);
		
		if( $this->isShowMenu() )
			$this->xt->assign("menu_block", true);
	
		if( $this->mobileTemplateMode() )
			$this->xt->assign('tableinfomobile_block', true);


		$allow_search = $this->permis[ $this->tName ]["search"];
		$allow_export = $this->permis[ $this->tName ]["export"]; 

		$this->xt->assign("grid_block", $allow_search);			
		$this->xt->assign("toplinks_block", $allow_search);
		$this->xt->assign("asearch_link", $allow_search);
		
		$this->xt->assign("print_link", $allow_export);
		$this->xt->assign("printlink_attrs", "id=print_".$this->id." href='#'");
		$this->xt->assign("printalllink_attrs", "id=printAll_".$this->id." href='#'");
		
		$this->xt->assign("excellink_attrs", "id=export_to_excel".$this->id." href='#'");
		$this->xt->assign("wordlink_attrs", "id=export_to_word".$this->id." href='#'");
		$this->xt->assign("pdflink_attrs", "id=export_to_pdf".$this->id." href='#'");
		
		$this->xt->assign("advsearchlink_attrs", "id=\"advButton".$this->id."\"");
		
		if( $this->noRecordsFound )
		{
			if( !$this->isPD() ) {
				$this->xt->assign("container_grid", false); //??
				$this->xt->assign("grid_block", false);
			}
			$this->showNoRecordsMessage();
		}
	}
	
	/**
	 * Add common javascript files and code
	 */
	function addCommonJs() 
	{
		parent::addCommonJs();
		
		if( $this->allDetailsTablesArr )
		{
			$this->AddCSSFile("include/jquery-ui/smoothness/jquery-ui.min.css");
			$this->AddCSSFile("include/jquery-ui/smoothness/jquery-ui.theme.min.css");
		}
	}
	
	/**
	 *
	 */
	protected function assignBody()
	{		
		$this->body["begin"].= GetBaseScriptsForPage( $this->isDisplayLoading );

		if( $this->mode == REPORT_SIMPLE && !$this->mobileTemplateMode() )
			$this->body["begin"].= "<div id=\"search_suggest\" class=\"search_suggest\"></div>";
	
		// assign body end in such way, to prevent collisions with flyId increment 
		$this->body['end'] = XTempl::create_method_assignment( "assignBodyEnd", $this);

		$this->xt->assignbyref("body", $this->body);		
	}
	
	/**
	 *
	 */
	public function beforeShowReport()
	{
		if( $this->eventsObject->exists("BeforeShowReport") )
			$this->eventsObject->BeforeShowReport($this->xt, $this->templatefile, $this);	
	}
	
	/**
	 * Display the report page
	 */
	public function showPage()
	{
		$this->beforeShowReport();

		if( $this->mode == REPORT_SIMPLE ) 
		{
			$this->display( $this->templatefile );
			return;
		}		
	
		$this->body["begin"] = '';
		$this->body["end"] = '';
		$this->xt->assign("body", $this->body);	
		
		$this->xt->assign("header", false);
		$this->xt->assign("footer", false);
	
		if( $this->mode == REPORT_DASHBOARD )
		{	
			$bricksExcept = array("grid", "pagination", "details_found", "message");	
			$this->xt->hideAllBricksExcept($bricksExcept);			
			
			$this->xt->prepare_template( $this->templatefile );
			
			$this->addControlsJSAndCSS();
			$this->fillSetCntrlMaps();
			
			$returnJSON = array();
			global $pagesData;
			$returnJSON["pagesData"] = $pagesData;
			$returnJSON['settings'] = $this->jsSettings;
			$returnJSON['controlsMap'] = $this->controlsHTMLMap;
			$returnJSON['viewControlsMap'] = $this->viewControlsHTMLMap;

			if( $this->formBricks["footer"] )
				$returnJSON["footerCont"] = $this->fetchBlocksList( $this->formBricks["footer"], true );
			if( $this->formBricks["header"] )
				$returnJSON["headerCont"] = $this->fetchBlocksList( $this->formBricks["header"], true );

			//	prepend headerCont with the page title
			$returnJSON['headerCont'] = '<span class="rnr-dbebrick">' 
				. $this->getPageTitle( $this->pageType, GoodFieldName($this->tName) ) 
				. "</span>"
				. $returnJSON['headerCont'];
				
			$this->assignFormFooterAndHeaderBricks(false);
			$this->xt->prepareContainers();
			if( $this->isBootstrap() )
				$returnJSON["html"] = $this->xt->fetch_loaded("message_block").$this->xt->fetch_loaded("grid_block");
			else
				$returnJSON["html"] = $this->xt->fetch_loaded("body");
			
			$returnJSON['idStartFrom'] = $this->flyId;
			$returnJSON['success'] = true;
			
			$returnJSON["additionalJS"] = $this->grabAllJsFiles();
			$returnJSON["CSSFiles"] = $this->grabAllCSSFiles();

			echo printJSON($returnJSON);			
			exit();
			return;		
		}		
		
		if( $this->mode == REPORT_DETAILS || $this->mode == REPORT_DASHDETAILS )
		{
			$this->showDpAjax();
			exit();
			return;
		}
	
		if( $this->mode && $this->mode == "listdetailspopup" ) //a currently unused option
		{
			$bricksExcept = array("grid", "pagination");
			$this->xt->assign("container_master", false);

			$this->xt->assign("cross_controls", false);
			$this->xt->hideAllBricksExcept($bricksExcept);
			$this->xt->prepare_template($this->templatefile);
			$respArr = array();
			$respArr['success'] = true;	
			$respArr['body'] = $this->xt->fetch_loaded("body");
			$respArr['counter'] = postvalue('counter');

			echo printJSON($respArr);
			exit();
			return;
		}
	}
	
	/**
	 * Display the inline report preview in respone on an ajax-like request
	 */
	protected function showDpAjax()
	{		
		$returnJSON = array();
		
		if( $this->mode == REPORT_DETAILS 
			&& $this->dashTName 
			&& $this->dashElementName 
			&& !$this->shouldDisplayDetailsPage() 
			&& ($this->masterPageType == PAGE_EDIT ||  $this->masterPageType == PAGE_VIEW) 
			)
		{
			$returnJSON['noData'] = true;
			echo printJSON( $returnJSON );			
			return;
		}
		
		if( $this->mode == REPORT_DETAILS 
			&& $this->masterPageType == PAGE_LIST 
			&& !$this->shouldDisplayDetailsPage()
			) 
		{
			$returnJSON['success'] = false;
			echo printJSON( $returnJSON );			
			return;
		}
		
		$this->xt->unassign("cross_controls");
		
		$this->hideElement("printpanel");
		
		$bricksExcept = array("grid", "pagination", "message" );	
		if( $this->mode != REPORT_DETAILS )
		{
			$bricksExcept[] = "details_found";
		}

		$bricksExcept[] = "bsgrid_tabs";
		
		$this->xt->hideAllBricksExcept($bricksExcept);
		
		$this->xt->prepare_template( $this->templatefile );

		$this->addControlsJSAndCSS();
		$this->fillSetCntrlMaps();
		
		global $pagesData;
		$returnJSON["pagesData"] = $pagesData;
		$returnJSON['settings'] = $this->jsSettings;
		$returnJSON['controlsMap'] = $this->controlsHTMLMap;
		$returnJSON['viewControlsMap'] = $this->viewControlsHTMLMap;
					
		if( $this->formBricks["footer"] )
			$returnJSON["footerCont"] = $this->fetchBlocksList( $this->formBricks["footer"], true );
		if( $this->formBricks["header"] )
			$returnJSON["headerCont"] = $this->fetchBlocksList( $this->formBricks["header"], true );

		if( $this->mode == REPORT_DETAILS && ( $this->masterPageType == PAGE_LIST || $this->masterPageType == PAGE_REPORT || $this->masterPageType == PAGE_CHART ) )
		{		
			$returnJSON['headerCont'] = $this->getProceedLink()	. $returnJSON['headerCont'];
		}
		else if( $this->mode == REPORT_DASHDETAILS )
		{
			//	prepend headerCont with the page title
			$returnJSON['headerCont'] = '<span class="rnr-dbebrick">' 
				. $this->getPageTitle( $this->pageType, GoodFieldName($this->tName) ) 
				. "</span>"
				. $returnJSON['headerCont'];
		}
		
		$this->assignFormFooterAndHeaderBricks(false);
		$this->xt->prepareContainers();
		if( $this->isBootstrap() )
		{
			$returnJSON["html"] = $this->xt->fetch_loaded("grid_tabs"). 
				$this->xt->fetch_loaded("message_block"). 
				$this->xt->fetch_loaded("grid_block"). 
				$this->xt->fetch_loaded("pagination_block");	
		}
		else
			$returnJSON["html"] = $this->xt->fetch_loaded("body");
		$returnJSON['success'] = true;
		$returnJSON['id'] = $this->id;
		$returnJSON['idStartFrom'] = $this->flyId;	
		
		$returnJSON["additionalJS"] = $this->grabAllJsFiles();
		$returnJSON["additionalCSS"] = $this->grabAllCSSFiles();

		echo printJSON( $returnJSON );			
	}
	
	/**
	 *
	 */
	function printAvailable() 
	{
		if( $this->mode == REPORT_DASHBOARD || $this->mode == REPORT_DETAILS || $this->mode == REPORT_DASHDETAILS )
			return false;
		return RunnerPage::printAvailable();
	}
	
	/**
	 * Show a detail preview page
	 * @param Array params - asp compatibility issue
	 */
	function showPageDp($params = "")
	{
		if( $this->crossTable )
			$this->xt->unassign("cross_controls");
		
		parent::showPageDp( $params );
	}

	public function prepareDisplayDetails() {
		if ( $this->isPD() )
		{
			$this->prepareDisplayDetailsPD();
			return;
		}		
		
		$contents = $this->xt->fetch_loaded("grid_block");
		$contents = $this->xt->fetch_loaded("grid_tabs").$contents;
		$contents.= $this->xt->fetch_loaded("pagination_block");
		
		$this->renderedBody = '<div id="detailPreview'.$this->id.'">'.$contents.'</div>';
	}

	public function prepareDisplayDetailsPD()
	{		
		if( $this->pdfJsonMode() ) 
		{
			$this->xt->assign("embedded_grid", true );
			$this->xt->load_templateJSON( $this->templatefile);
			$this->renderedBody = $this->xt->fetch_loadedJSON("body");
			return;
		}
				
		$forms = array( "grid" );
		$bodyContents = $this->fetchForms($forms);		
		$this->renderedBody = '<div id="detailPreview'.$this->id.'">'.$bodyContents.'</div>';	
	}

	public function showGridOnly() 
	{			
		echo $this->renderedBody;
	}

	/**
	*
	*/
	function shouldDisplayDetailsPage()
	{
		if( !$this->permis[$this->tName]['search'] )
			return false;
		
		if( $this->noRecordsFound && 0 == $this->getGridTabsCount() )
			return false;
		
		return true;
	}
	
	/**
	 * @param String field
	 * @return String
	 */
	protected function getFieldClass( $field )
	{
		$class = parent::fieldClass( $field );

		if( $this->crossTable ) {
			if( in_array( $this->pSet->getViewFormat( $field ), array( FORMAT_DATE_SHORT, FORMAT_DATE_LONG, FORMAT_DATE_TIME ) ) )
				return $class." rnr-field-crossdate";
		}
			
		return $class;
	}
	
	/**
	 * @return Array
	 */
	public static function getMasterKeysFromRequest()
	{
		if( isset( $_REQUEST["masterKeys"] ) ) 
			$masterKeys = my_json_decode( $_REQUEST["masterKeys"] );		
		
		$i = 0;
		$masterKeysReq = array();
		while( true )
		{
			$i++;
			if( isset( $_REQUEST["masterkey".$i] ) )
				$_masterKey = $_REQUEST["masterkey".$i];
			elseif( isset( $masterKeys["masterkey".$i] ) )
				$_masterKey = $masterKeys["masterkey".$i];
			else
				break;
				
			$masterKeysReq[ $i ] = $_masterKey;
		}
		return $masterKeysReq ;
	}
	
	/**
	 * @return String
	 */
	public static function readReportModeFromRequest()
	{
		$mode = postvalue("mode");
		if( $mode == "listdetails" )
			$pageMode = REPORT_DETAILS; 
		else if( $mode == "listdetailspopup" )
			$pageMode = REPORT_POPUPDETAILS;
		else if( $mode == "dashreport" )
			$pageMode = REPORT_DASHBOARD;
		else if ( $mode == "dashdetails" )
			$pageMode = REPORT_DASHDETAILS;	
		else 
			$pageMode = REPORT_SIMPLE;

		return $pageMode;
	}

	function gridTabsAvailable() {
		return true;
	}

	function displayTabsInPage() 
	{
		return $this->simpleMode() || ( $this->mode == REPORT_DETAILS && ($this->masterPageType == PAGE_VIEW || $this->masterPageType == PAGE_EDIT));
	}
	
	function renderPageBody() 
	{
		if( !$this->isBootstrap() )
			return parent::renderPageBody();
		
		$blocks = array("grid_tabs", "message", "grid_block", "pagination_block");
		return $this->fetchBlocksList($blocks, false);

	}

	protected function assignColumnHeaderClasses()
	{
		$reportFields = $this->pSet->getFieldsList();
		foreach( $reportFields as $field )
		{
			$goodName = GoodFieldname($field);
			$this->xt->assign("fieldclass_".$goodName, $this->getFieldClass($field) ); 
		}
	}
	
	function pdfJsonMode() 
	{
		return $this->pdfJson;
	}	
}
?>