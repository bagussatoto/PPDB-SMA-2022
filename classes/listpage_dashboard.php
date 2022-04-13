<?php

class ListPage_Dashboard extends ListPage_Embed
{	
	public $showNoData = false;
	
	/**
	 * @constructor
	 * @param array params
	 */
	function __construct(&$params)
	{
		parent::__construct($params);
		$this->showAddInPopup = true;

		$this->formBricks["header"] = array( 
			array( "name" => "details_block", "align" => "right" ),
			array( "name" => "newrecord_controls_block", "align" => "right" ), 
			array( "name" => "record_controls_block", "align" => "right" ),
			array( "name" => "details_found", "align" => "right" )
		);
		
		$this->formBricks["footer"] = array( "pagination_block" );	
		
		if( $this->mapRefresh )
			$this->pageSize = -1;
	}

	/**
	 * Assign seesion prefix
	 */
	protected function assignSessionPrefix() 
	{
		$this->sessionPrefix = $this->dashTName."_".$this->tName;
	}

	/**
	 * Fill table settings
	 * @intellisense
	 */
	protected function fillTableSettings( $table = "", $pSet = null )
	{
		parent::fillTableSettings( $table, $pSet );
		
		if( $this->addAvailable() )
			$this->jsSettings["tableSettings"][ $this->tName ]["showAddInPopup"] = true;

		if( $this->editAvailable() || $this->updateSelectedAvailable() )
			$this->jsSettings["tableSettings"][ $this->tName ]["showEditInPopup"] = true;
			
		if( $this->viewAvailable() )
			$this->jsSettings["tableSettings"][ $this->tName ]["showViewInPopup"] = true;
			
		if( $this->inlineEditAvailable() )
			$this->jsSettings["tableSettings"][ $this->tName ]["inlineEditAvailable"] = true;
	}
	
	/**
	 * Checks if need to display grid
	 */
	function isDispGrid() 
	{
		return $this->permis[ $this->tName ]['search'];
	}
	
	function addCommonJs()
	{
		$this->addJsForGrid();
		$this->addControlsJSAndCSS();
		$this->addButtonHandlers();
	}
	
	function addJsForGrid()
	{ 
		if( $this->isResizeColumns )
			$this->prepareForResizeColumns();
		
		$this->jsSettings['tableSettings'][ $this->tName ]['showRows'] = ($this->numRowsFromSQL ? true : false);
	}
	
	function prepareForResizeColumns()
	{
		parent::prepareForResizeColumns();
		if(  !$this->isBootstrap() )
			return;
		include_once getabspath("classes/paramsLogger.php");	
		$logger = new paramsLogger( $this->dashTName."_".$this->dashElementName, CRESIZE_PARAMS_TYPE );
		$columnsData = $logger->getData();
		if( $columnsData )
			$this->pageData[ "resizableColumnsData" ] = $columnsData;
	}
	
	function commonAssign()
	{
		parent::commonAssign();
		$this->xt->assign("details_block", true);
		$this->xt->assign("withSelected", $this->inlineEditAvailable() || $this->deleteAvailable() );
		
		$this->hideElement("printpanel");
	}

	protected function prepareBreadcrumbs() {
		//	do nothing
	}
	
	protected function getSubsetSQLComponents() {

		$sql = parent::getSubsetSQLComponents();
		
		if( $this->mode == LIST_DASHBOARD && $this->hasMainDashMapElem() )
			$sql["mandatoryWhere"][] = $this->mapRefresh ? $this->getWhereByMap() : "1=0";

		if( $this->showNoData )
			$sql["mandatoryWhere"][] = "1=0";
		
		return $sql;
	}

	
	
	/**
	 *
	 */
	function showPage() 
	{
		$this->BeforeShowList();
		
		$this->prepareGridTabs();
		
		if( $this->mobileTemplateMode() )
			$bricksExcept = array("grid_mobile", "pagination", "details_found");
		else 
			$bricksExcept = array("grid", "pagination", "message", "add", "recordcontrols_new", "recordcontrol", "details_found", "reorder_records");
		
		$this->xt->hideAllBricksExcept( $bricksExcept );
		$this->xt->prepare_template($this->templatefile);
		$this->showPageAjax();
	}

	
	/**
	 * Display blocks after loaded template of page
	 */
	function showPageAjax() 
	{
		$this->addControlsJSAndCSS();
		$this->fillSetCntrlMaps();
		$returnJSON = array();
		global $pagesData;
		$returnJSON["pagesData"] = $pagesData;
		$returnJSON['controlsMap'] = $this->controlsHTMLMap;
		$returnJSON['viewControlsMap'] = $this->viewControlsHTMLMap;
		$returnJSON['settings'] = $this->jsSettings;
		$this->xt->assign("header",false);
		$this->xt->assign("footer",false);

		if( !$this->isPD() ) {
			if( $this->formBricks["header"] )
				$returnJSON['headerCont'] = $this->fetchBlocksList( $this->formBricks["header"], true );					
		
			//	prepend headerCont with the page title
			$returnJSON['headerCont'] = '<span class="rnr-dbebrick">' 
				. $this->getPageTitle( $this->pageType, GoodFieldName($this->tName) ) 
				. "</span>"
				. $returnJSON['headerCont'];

			if( $this->formBricks["footer"] )
				$returnJSON["footerCont"] = $this->fetchBlocksList( $this->formBricks["footer"], true );
				
			$this->assignFormFooterAndHeaderBricks(false);
			$this->xt->prepareContainers();
			if( $this->isBootstrap() )
				$returnJSON["html"] = $this->xt->fetch_loaded("message_block").$this->xt->fetch_loaded("reorder_records").$this->xt->fetch_loaded("grid_block");
			else
				$returnJSON["html"] = $this->xt->fetch_loaded("body");
		} else {
			$this->hideForm("above-grid");
			$returnJSON["html"] = $this->fetchForms( array( "above-grid", "grid" ) );
			$returnJSON['headerCont'] = '<span class="rnr-dbebrick">' 
			. $this->getPageTitle( $this->pageType, GoodFieldName($this->tName) ) 
			. "</span>";
			$returnJSON["footerCont"] = $this->fetchForms( array( "below-grid" ) );
		}
		

		
		$returnJSON['idStartFrom'] = $this->flyId;
		$returnJSON['success'] = true;
		
		$returnJSON["additionalJS"] = $this->grabAllJsFiles();
		$returnJSON["CSSFiles"] = $this->grabAllCSSFiles();

		echo printJSON($returnJSON);
	}
		
	function fillSetCntrlMaps()
	{
		parent::fillSetCntrlMaps();
		$this->controlsHTMLMap[ $this->tName ][ $this->pageType ][ $this->id ]["pageNumber"] = $this->myPage;
		$this->controlsHTMLMap[ $this->tName ][ $this->pageType ][ $this->id ]["numberOfPages"] = $this->maxPages;
	}

	function fillCheckAttr(&$record, $data, $keyblock)
	{
		if( $this->deleteAvailable() || $this->inlineEditAvailable() || $this->updateSelectedAvailable() ) 
			$record["checkbox"] = true;
			
		$record["checkbox_attrs"]= "name=\"selection[]\" value=\"".runner_htmlspecialchars($keyblock)."\" id=\"check".$this->id."_".$this->recId."\"";
	}

	function deleteAvailable() {
		return parent::deleteAvailable() && $this->dashElementData["deleteRecord"];
	}

	function editAvailable() {
		return parent::editAvailable() && $this->dashElementData["popupEdit"];
	}

	function addAvailable() {
		return parent::addAvailable() && $this->dashElementData["popupAdd"];
	}

	function inlineEditAvailable() {
		return parent::inlineEditAvailable() && $this->dashElementData["inlineEdit"]; 
	}
	
	function inlineAddAvailable() {
		return parent::inlineAddAvailable() && $this->dashElementData["inlineAdd"];
	}

	function updateSelectedAvailable() {
		return parent::updateSelectedAvailable() && $this->dashElementData["updateSelected"];
	}
	
	function viewAvailable() {
		return parent::viewAvailable() && $this->dashElementData["popupView"];
	}
	
	function detailsInGridAvailable()
	{
		return false;
	}
	
	/**
	 * @return Boolean
	 */
	protected function hasDependentDashMapElem() 
	{
		foreach( $this->dashSet->getDashboardElements() as $dElem ) 
		{
			if( $dElem["table"] == $this->tName && $dElem["type"] == DASHBOARD_MAP && !$dElem["updateMoved"] )
				return true;
		}
		
		return false;
	}
	
	/**
	 * @return Boolean
	 */	
	protected function hasMainDashMapElem()
	{
		foreach( $this->dashSet->getDashboardElements() as $dElem ) 
		{
			if( $dElem["table"] == $this->tName && $dElem["type"] == DASHBOARD_MAP && $dElem["updateMoved"] )
				return true;
		}
		
		return false;	
	}
	
	/**
	 * @return Boolean
	 */
	protected function hasBigMap() 
	{
		return parent::hasBigMap() || $this->hasDependentDashMapElem(); 
	}
	
	/**
	 * @param &Array data
	 * @param Array keys
	 * @param String editLink
	 */
	function addBigGoogleMapMarkers(&$data, $keys, $editLink = '')
	{	
		parent::addBigGoogleMapMarkers( $data, $keys, $editLink );
			
		foreach( $this->dashSet->getDashboardElements() as $dElem ) 
		{
			if( $dElem["elementName"] == $this->dashElementName || $dElem["table"] != $this->tName || $dElem["type"] != DASHBOARD_MAP || $dElem["updateMoved"] )
				continue;
				
			$markerData = array();
			
			$markerData["lat"] = str_replace( ",", ".", ($data[ $dElem["latF"] ] ? $data[ $dElem["latF"] ] : "") );
			$markerData["lng"] = str_replace( ",", ".", ($data[ $dElem["lonF"] ] ? $data[ $dElem["lonF"] ] : "") );
			$markerData["address"] = $data[ $dElem["addressF"] ] ? $data[ $dElem["addressF"] ] : "";
			$markerData["desc"] = $data[ $dElem["descF"] ] ? $data[ $dElem["descF"] ] : $markerData["address"];				
			$markerData["mapIcon"] = $this->dashSet->getDashMapIcon( $dElem["elementName"], $data );
			
			$markerData["recId"] = $this->recId;
			$markerData["keys"] = $keys; 
			
			$markerData["masterKeys"] = $this->getMarkerMasterKeys( $data );
			
			$mapId = GoodFieldName( $this->dashTName )."_".$dElem["elementName"]."_dashMap";
			
			if( !isset( $this->googleMapCfg["mapsData"][ $mapId ] ) )
			{
				$this->googleMapCfg["mapsData"][ $mapId ] = array();
				$this->googleMapCfg["mapsData"][ $mapId ]["skipped"] = true;
				$this->googleMapCfg["mapsData"][ $mapId ]["dashMap"] = true;
				$this->googleMapCfg["mapsData"][ $mapId ]["heatMap"] = $dElem["heatMap"];
			}
			
			if( !isset( $this->googleMapCfg["mapsData"][ $mapId ]["markers"] ) )	
				$this->googleMapCfg["mapsData"][ $mapId ]["markers"] = array();				

			if( $markerData["lat"] == "" || $markerData["lng"] == "" )
				continue;
			
			$this->googleMapCfg['mapsData'][ $mapId ]['markers'][] = $markerData;			
		}	
	}
	
	protected function isInlineAreaToSet()
	{
		if( $this->mode == LIST_DASHBOARD )
			return true;
			
		return parent::isInlineAreaToSet();
	}
	
	/**
	 * A stub
	 */
	function rulePRG() {}
	
	/**
	 * A stub
	 */		
	function buildSearchPanel() {}

	/**
	 *
	 */
	function printAvailable() 
	{
		return false;
	}
	
	function getTabSQLComponents( $tab )
	{
		$this->skipMapFilter = true;
		$sql = parent::getTabSQLComponents( $tab );
		$this->skipMapFilter = false;
		return $sql;
	}

	/*
	function prepareGridTabs()
	{
		parent::prepareGridTabs();
		$this->pageData["gridTabs"] = $this->getTabsHtml();
		$this->pageData["tabId"] = $this->getCurrentTabId();
	}
	*/
	
}
?>