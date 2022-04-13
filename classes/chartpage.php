<?php
class ChartPage extends RunnerPage
{
	
	/**
	 * show message block
	 */
	public $show_message_block = false;

	/**
	 * @constructor
	 */
	function __construct(&$params = "")
	{
		parent::__construct($params);
	
		if( $this->getLayoutVersion() === PD_BS_LAYOUT ) 
		{
			$this->bodyForms = array( "grid" );
		} 
	
		$this->jsSettings['tableSettings'][ $this->tName ]['simpleSearchActive'] = $this->searchClauseObj->simpleSearchActive;
		$this->jsSettings['tableSettings'][ $this->tName ]['startMasterKeys'] = $this->getStartMasterKeys();
	}

	/**
	 * Set the page's session prefix
	 */
	protected function assignSessionPrefix()
	{
		if( $this->mode == CHART_DASHBOARD )
			$this->sessionPrefix = $this->dashTName."_".$this->tName;
		else
			$this->sessionPrefix = $this->tName;
	}	

	/**
	 * Build the activated Search panel
	 */
	public function buildSearchPanel()
	{
		if( $this->mode == CHART_DASHBOARD )
			return;
		
		parent::buildSearchPanel();
	}	
	
	/**
	 * Process the page 
	 */
	public function process()
	{
		if( $this->mode == CHART_DASHDETAILS
			|| $this->mode == CHART_DETAILS && ( $this->masterPageType == PAGE_LIST || $this->masterPageType == PAGE_REPORT ))
			$this->updateDetailsTabTitles();


		//	Before Process event
		if( $this->eventsObject->exists("BeforeProcessChart") )
			$this->eventsObject->BeforeProcessChart( $this );
					
		
		// build tabs and set current
		$this->processGridTabs();

		$this->doCommonAssignments();
		$this->addButtonHandlers();
		$this->addCommonJs();
		$this->commonAssign();

		$this->buildSearchPanel();
		
		// to restore correctly within a chart class
		$_SESSION[ $this->sessionPrefix.'_advsearch' ] = serialize( $this->searchClauseObj );
		
		// display the 'Back to Master' link and master table info
		$this->displayMasterTableInfo();

		$this->showPage();		
	}

	
	protected function getRowCountByTab($tab)
	{
		$sql = $this->getTabSQLComponents( $tab );
		
		//	build SQL
		$strSQL = SQLQuery::buildSQL( $sql["sqlParts"], $sql["mandatoryWhere"], $sql["mandatoryHaving"], $sql["optionalWhere"], $sql["optionalHaving"] );
		$countSQL = $strSQL;
				
		
		if( $this->eventsObject->exists("BeforeQueryChart") )
		{
			//	do Before SQL Query event
			$strSQLbak = $strSQL;
			$sqlModifiedInEvent = false;
			$whereModifiedInEvent = false;

			$tstrWhereClause = SQLQuery::combineCases( array( 
					SQLQuery::combineCases( $sql["mandatoryWhere"], "and" ),
					SQLQuery::combineCases( $sql["optionalWhere"], "or" )
				), "and" );
				
			$strWhereBak = $tstrWhereClause;
			$tOrderBy = $this->getOrderByClause();
			$this->eventsObject->BeforeQueryChart( $strSQL, $tstrWhereClause, $tOrderBy );
			$whereModifiedInEvent = ( $tstrWhereClause != $strWhereBak );
			$sqlModifiedInEvent = ( $strSQL != $strSQLbak );
		
			//	Rebuild SQL if needed
			if( $sqlModifiedInEvent ) 
			{
				return $this->limitRowCount( GetRowCount($strSQL, $this->connection) );
			}
			
			if( $whereModifiedInEvent )
			{
				$countSQL = SQLQuery::buildSQL($sql["sqlParts"], array( $tstrWhereClause ), $sql["mandatoryHaving"] );
			}
		}

		//	normal mode row count
		return $this->limitRowCount( $this->connection->getFetchedRowsNumber( $countSQL ) );
	}

	/**
	 * Get where clause for an active master-detail relationship
	 * @return string
	 */
	public function getMasterTableSQLClause( $basedOnProp = false ) 
	{
		if( $this->mode == CHART_DASHBOARD )
			return "";		
		return parent::getMasterTableSQLClause(); 
	}	

	protected function getSubsetSQLComponents() {

		$sql = parent::getSubsetSQLComponents();
		
		if( $this->connection->dbType == nDATABASE_DB2 ) 
			$sql["sqlParts"]["head"] .= ", ROW_NUMBER() over () as DB2_ROW_NUMBER ";
		
		//	security
		$sql["mandatoryWhere"][] = $this->SecuritySQL("Search", $this->tName);
		
		return $sql;
	}
	
	
	/**
	 * Get started master keys
	 * @return Array
	 */
	public function getStartMasterKeys()
	{		
		$sql = $this->getSubsetSQLComponents();
		$strSQL = SQLQuery::buildSQL( $sql["sqlParts"], $sql["mandatoryWhere"], $sql["mandatoryHaving"], $sql["optionalWhere"], $sql["optionalHaving"] );
		$strSQL .= $this->getOrderByClause();
		$rs = $this->connection->queryPage( $strSQL, 1, 1, true );

		$fetchedArray = $rs->fetchAssoc();
		$data = $this->cipherer->DecryptFetchedArray( $fetchedArray );

		$detailTablesData = $this->pSet->getDetailTablesArr();
		$masterKeysArr = array();
		foreach ( $detailTablesData as $detailId => $detail )
		{
			foreach( $detail['masterKeys'] as $idx => $mk ) 
			{
				$masterKeysArr[ $detail['dDataSourceTable'] ] = array( 'masterkey'.($idx + 1) => $data[$mk] );
			}
		}

		return $masterKeysArr;
	}
	
	/**
	 *
	 */
	public function doCommonAssignments() // TODO: make it protected
	{
		$this->xt->assign("id", $this->id);		
		
		//set the Search panel
		$this->xt->assign("searchPanel", true);

		if( $this->isShowMenu() )
			$this->xt->assign("menu_block", true);		

		$this->setLangParams();
	
		$this->xt->assign("chart_block", true);
		$this->xt->assign("asearch_link", true);
		$this->xt->assign("exportpdflink_attrs", "onclick='chart.saveAsPDF();'");
		$this->xt->assign("advsearchlink_attrs", "id=\"advButton".$this->id."\"");

		if( !GetChartXML( $this->shortTableName ) )
			$this->xt->assign("chart_block", false);			
		
		$this->xt->assign("message_block", true);
		
		if( ($this->mode == CHART_SIMPLE || $this->mode == CHART_DASHBOARD) && $this->pSet->noRecordsOnFirstPage() && !$this->searchClauseObj->isSearchFunctionalityActivated() )
		{
			$this->show_message_block = true;
			$this->hideElement("chart");
			$this->xt->assign("chart_block", false);

			$this->xt->assign("message", $this->noRecordsMessage());
			$this->xt->assign( "message_class", "alert-warning");
		}		

		if( !$this->show_message_block )
			$this->hideElement("message");

		if( $this->mobileTemplateMode() )
			$this->xt->assign('tableinfomobile_block', true);

		
		$this->assignChartElement();
		
		$this->body['begin'].= GetBaseScriptsForPage( $this->isDisplayLoading );
		if( !$this->isDashboardElement() && !$this->mobileTemplateMode() )
			$this->body['begin'].= "<div id=\"search_suggest\" class=\"search_suggest\"></div>";

		// assign body end
		$this->body['end'] = XTempl::create_method_assignment( "assignBodyEnd", $this);

		$this->xt->assignbyref('body', $this->body);	
	}	
	
	/**
	 * Set the chart xt variable
	 */
	public function assignChartElement()
	{
	}
	
	/**
	 *
	 */
	public function prepareDetailsForEditViewPage()
	{
		$this->addButtonHandlers();
		
		$this->xt->assign("body", $this->body);
		$this->xt->assign("chart_block", true);
		$this->xt->assign("message_block", true);		
	}
	
	protected function getExtraAjaxPageParams()
	{
		$returnJSON = array();
		if( $this->mode == REPORT_DETAILS )
		{		
			$returnJSON['headerCont'] = $this->getProceedLink()	. $returnJSON['headerCont'];
		}		
		
		return $returnJSON;
	}
	
	public function beforeShowChart()
	{
		if( $this->eventsObject->exists("BeforeShowChart") )
			$this->eventsObject->BeforeShowChart($this->xt, $this->templatefile, $this);	
	}
	
	public function showPage()
	{
		$this->beforeShowChart();
	
		if( $this->mode == CHART_DETAILS || $this->mode == CHART_DASHBOARD || $this->mode == CHART_DASHDETAILS )
		{
			$this->addControlsJSAndCSS();
			$this->fillSetCntrlMaps();
			
			$this->xt->assign("header", false);
			$this->xt->assign("footer", false);
			
			$this->body["begin"] = "";
			$this->body["end"] = "";
			$this->xt->assign("body", $this->body);	

			$bricksExcept = array("chart", "message");
			if( $this->displayTabsInPage() )
				$bricksExcept[] = "bsgrid_tabs";
			$this->xt->hideAllBricksExcept($bricksExcept);
			
			$this->displayAJAX($this->templatefile, $this->id + 1);
			exit();
		}

		if( $this->mode == CHART_POPUPDETAILS ) //currently unused
		{
			$bricksExcept = array("grid","pagination");
			$this->xt->assign("header", false);
			$this->xt->assign("footer", false);
			$this->body["begin"] = '';
			$this->body["end"] = '';
			
			$this->xt->hideAllBricksExcept($bricksExcept);
			$this->xt->prepare_template($this->templatefile);
			$respArr = array();
			$respArr['success'] = true;	
			$respArr['body'] = $this->xt->fetch_loaded("body");
			$respArr['counter'] = postvalue('counter');
			$this->xt->assign("container_master", false);
			
			echo printJSON($respArr);
			exit();
		}
			
		$this->display( $this->templatefile );			
	}
	
	/**
	 *
	 */
	function processGridTabs() 
	{	
		$ctChanged = parent::processGridTabs();
		$_SESSION[ $this->sessionPrefix . "_chartTabWhere" ] = $this->getCurrentTabWhere();
		
		return $ctChanged;
	}

	function gridTabsAvailable() {
		return true;
	}
	
	function displayTabsInPage() 
	{
		return $this->simpleMode() || ( $this->mode == CHART_DETAILS && ($this->masterPageType == PAGE_VIEW || $this->masterPageType == PAGE_EDIT));
	}
	
	function element2Item( $name ) {
		if( $name == "message" ) {
			return array( "grid_message" );
		}
		if( $name == "chart" ) {
			return array( "chart" );
		}
		return parent::element2Item( $name );
	}

	public function prepareDisplayDetails() 
	{
		$resizeChart = true;
		if( $this->mode == CHART_SIMPLE || 
			$this->mode == CHART_DASHBOARD || 
			$this->mode == CHART_DETAILS && ( $this->masterPageType == PAGE_VIEW || $this->masterPageType == PAGE_EDIT ) )
			$resizeChart = false;
			
		//set params for the 'xt_showchart' method showing the chart
		$chartXtParams = array(
			"id" => $this->id,
			"table" => $this->tName, 
			"ctype" => $this->pSet->getChartType(), 
			"resize" => $resizeChart,
			"chartName" => $this->shortTableName,
			"chartPreview" => $this->mode !== CHART_SIMPLE && $this->mode != CHART_DASHBOARD
		);

		if( $this->mode == CHART_DASHBOARD || $this->mode == CHART_DASHDETAILS )
		{
				$chartXtParams["refreshTime"] = $this->dashElementData["reload"];
		}
		
		$this->prepareCharts();
		$forms = array( "grid" );
		$bodyContents = $this->fetchForms($forms);		
		$this->renderedBody = '<div id="detailPreview'.$this->id.'">'.$bodyContents.'</div>';	
		return;
	}

	public function showGridOnly() 
	{			
		echo $this->renderedBody;
	}
	
	function prepareCharts() 
	{
		$chartXtParams =  array( 
			"id" => $this->id,
			// it shows if chart show details
			"chartPreview" => $this->mode !== CHART_SIMPLE && $this->mode != CHART_DASHBOARD
		);
		
		if( $this->dashTName && $this->mode == CHART_DASHBOARD )
		{
			$chartXtParams["dash"] = true;
			$chartXtParams["dashTName"] = $this->dashTName;
			$chartXtParams["dashElementName"] = $this->dashElementName;
		}
		
		$this->xt->assign_function("chart", "xt_showpdchart", $chartXtParams);
	}	

}
?>