<?php

class ListPage_DPList extends ListPage_DPInline
{	
	/**
	 * Constructor, set initial params
	 * @param array $params
	 */
	function __construct(&$params)
	{
		parent::__construct( $params );
	
		$this->formBricks["footer"] = array( "pagination_block" );	
	}
	
	/**
	 * Show the page.
	 * It's supposed to be displayed in response on an ajax-like request
	 */
	public function showPage()
	{				
		$this->BeforeShowList();
		
		if( $this->getLayoutVersion() === PD_BS_LAYOUT ) {


		} else {
			
			if( $this->mobileTemplateMode() )
				$bricksExcept = array("grid_mobile", "pagination", "details_found");
			else 
				$bricksExcept = array("grid", "pagination", "message", "reorder_records" /*,"recordcontrols_new", "recordcontrol"*/);
		
			$this->xt->hideAllBricksExcept( $bricksExcept );
		}
		
		$this->xt->prepare_template($this->templatefile);
		$this->showPageAjax();
	}

	
	/**
	 *
	 */
	function showPageAjax() 
	{
		$returnJSON = array();
		$proceedLink = $this->getProceedLink();
		
		if( !$this->numRowsFromSQL && 
			!$this->addAvailable() && !$this->inlineAddAvailable() && 
			!$this->recordsDeleted && 
			$proceedLink == '' && 
			$this->getGridTabsCount() == 0 ) 
		{
			$returnJSON['success'] = false;
			echo printJSON($returnJSON);
			return;
		}
		
		$this->addControlsJSAndCSS();
		$this->fillSetCntrlMaps();
		
		global $pagesData;
		$returnJSON["pagesData"] = $pagesData;
		$returnJSON['controlsMap'] = $this->controlsHTMLMap;
		$returnJSON['viewControlsMap'] = $this->viewControlsHTMLMap;
		$returnJSON['settings'] = $this->jsSettings;
		$this->xt->assign("header",false);
		$this->xt->assign("footer",false);				
		
		if( $this->getLayoutVersion() === PD_BS_LAYOUT ) {
			$returnJSON['headerCont'] = $proceedLink . $this->xt->fetch_loaded("above-grid_block");
		}
		else 
		{
			$returnJSON['headerCont'] = $proceedLink . $this->getHeaderControlsBlocks();
		}


		if( $this->formBricks["footer"] ) {
			if( $this->getLayoutVersion() === PD_BS_LAYOUT ) {
				$returnJSON["footerCont"] = $this->xt->fetch_loaded("below-grid_block");
			} else {
				$returnJSON["footerCont"] = $this->fetchBlocksList( $this->formBricks["footer"], true );
			}
			
		}
			
		$this->assignFormFooterAndHeaderBricks(false);
		
		$this->xt->prepareContainers();
		
		if( $this->getLayoutVersion() === PD_BS_LAYOUT ) {
			$returnJSON["html"] = $this->xt->fetch_loaded("grid_block");
		} else {
			$returnJSON["html"] = $this->xt->fetch_loaded("body");
		}
		
		$returnJSON['idStartFrom'] = $this->flyId;
		$returnJSON['success'] = true;
		
		$returnJSON["additionalJS"] = $this->grabAllJsFiles();
		$returnJSON["CSSFiles"] = $this->grabAllCSSFiles();

		echo printJSON($returnJSON);
	}

	/**
	 * @return String
	 */
	protected function getBSButtonsClass()
	{
		return "btn btn-sm";
	}
		
	
	protected function assignSessionPrefix() 
	{
		$this->sessionPrefix = $this->tName."_preview";	
	}
	
	function showNoRecordsMessage()
	{
		//	show nothing
	}
}
?>