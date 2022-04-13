<?php

class ListPage_DPInline extends ListPage_Embed
{
	/**
	 * DP params
	 * @var string
	 */
	var $dpParams = "";

	/**
	 * Array of details preview master key
	 * @var integer
	 */
	var $dpMasterKey = array ();

	/**
	 * Short name of master table
	 * @var string
	 */
	var $masterShortTable = "";

	/**
	 * Master's form name
	 * @var string
	 */	
	var $masterFormName = "";

	/**
	 * Master's id use only for dpInline on list page
	 * (don't confuse with dpInline on add edit pages)
	 * @var string
	 */
	var $masterId = "";

	/**
	 * Which type of master page was called detail table
	 *
	 * @var string
	 */
	var $masterPageType = "";

	/**
	 * View PDF on view page or not
	 *
	 * @var integer
	 */
	
	/**
	 * Constructor, set initial params
	 * @param array $params
	 */
	function __construct(&$params)
	{
		// copy properties to object
		//RunnerApply($this, $params);
		// call parent constructor
		parent::__construct($params);

		$this->showAddInPopup = true;
		$this->showEditInPopup = true;
		$this->showViewInPopup = true;
		
		if($this->mobileTemplateMode())
			$this->pageSize = -1;
		
		$this->initDPInlineParams();
		$this->searchClauseObj->clearSearch();
		
		$this->jsSettings['tableSettings'][$this->tName]['masterPageType'] = $this->masterPageType;
		$this->jsSettings['tableSettings'][$this->tName]['masterTable'] = $this->masterTable;
		$this->jsSettings['tableSettings'][$this->tName]['firstTime'] = $this->firstTime;
		$this->jsSettings['tableSettings'][$this->tName]['strKey'] = $this->getStrMasterKey();
		$this->addRawFieldValues = true;
	}
	
	/**
	 * Assigne Import Links or not
	 */
	function importLinksAttrs() 
	{
	}
	
	/**
	 * The stub preventing the detail page from showing a master info block
	 */
	function displayMasterTableInfo() 
	{
	}
	
	/**
	 * Process master key value
	 * Set master key for create DPInline params
	 */
	function processMasterKeyValue() 
	{
		parent::processMasterKeyValue();
		for($i = 1; $i <= count($this->masterKeysReq); $i++)
			$this->dpMasterKey[] = $this->masterKeysReq[$i];
			
		$masterKeys = array();
		for($i = 0; $i < count($this->dpMasterKey); $i++)
		{
			$masterKeys[ "masterkey".($i + 1) ] = $this->dpMasterKey[ $i ];
		}		
		$this->controlsMap["masterKeys"] = $masterKeys;				
	}
	
	/**
	 * Initialization DPInline params 
	 */
	function initDPInlineParams()
	{
		$strkey = "";
		for($i = 0; $i < count($this->dpMasterKey); $i++)
		{
			$strkey.="&masterkey".($i+1)."=".rawurlencode($this->dpMasterKey[$i]);
		}
		
		$this->dpParams = "mode=listdetails&id=".$this->id."&mastertable=".rawurlencode($this->masterTable).$strkey.
							($this->masterId ? "&masterid=".$this->masterId : "").
							(($this->masterPageType==PAGE_EDIT || $this->masterPageType==PAGE_VIEW) ? "&masterpagetype=".$this->masterPageType : "");

	}
	/**
	 * Get string of master keys for dpInline on Edit page
	 */
	function getStrMasterKey()
	{
		$strkey = array();
		for($i = 0; $i < count($this->dpMasterKey); $i++)
		{
			$strkey[$i] = $this->dpMasterKey[$i];
		}
		return $strkey;	
	}

	/**
	 * @return Boolean
	 */
	public function isReoderByHeaderClickingEnabled()
	{
		return $this->masterPageType != PAGE_ADD;
	}

		
	/**
	 * show inline add link
	 * Add inline add attributes
	 */
	function inlineAddLinksAttrs()
	{	
		//inline add link and attr
		if( $this->masterPageType != PAGE_VIEW )
			parent::inlineAddLinksAttrs();
	}
	
	/**
	 * Add common assign for current mode
	 */
	function commonAssign()
	{
		parent::commonAssign();
		
		$this->xt->assign("left_block", false);
		
		//select all link and attr	
		if( $this->masterPageType == PAGE_ADD || $this->masterPageType == PAGE_VIEW  
			|| $this->mode == LIST_DASHDETAILS )
		{
			$this->xt->assign("selectall_link",false);
			$this->xt->assign("checkbox_column",false);
			$this->xt->assign("checkbox_header",false);
			$this->xt->assign("editselected_link",false);
			$this->xt->assign("delete_link",false);
			$this->xt->assign("saveall_link",false);
			$this->xt->assign("withSelectedClass", "rnr-hiddenelem");
			if($this->masterPageType == PAGE_VIEW )
				$this->xt->assign("record_controls_block",false);
		}
		else
		{
			//selectall link attrs
			$this->selectAllLinkAttrs();
			
			//checkbox column
			if(!$this->mobileTemplateMode())
				$this->checkboxColumnAttrs();
			
			//edit selected link and attr
			$this->editSelectedLinkAttrs();	
			
			//save all link, attr, span
			$this->saveAllLinkAttrs();
			
			//delete link and attr
			$this->deleteSelectedLink();
			
			if($this->masterPageType != PAGE_EDIT)
			{
				$searchPermis = $this->permis[$this->tName]['search'];
				$this->xt->assign("record_controls_block", $this->permis[$this->tName]['edit'] && $this->pSet->hasInlineEdit() || $this->permis[$this->tName]['delete'] && $this->pSet->hasDelete());
				$this->xt->assign("details_block", $searchPermis && $this->rowsFound );
				$this->xt->assign("details_attrs","id=\"detFound".$this->id."\" name=\"detFound".$this->id."\"");
				$this->xt->assign("pages_block", $searchPermis && $this->rowsFound );
			}
		}


		
		$this->xt->assign("withSelected", $this->permis[$this->tName]['export'] || $this->permis[$this->tName]['edit'] || $this->permis[$this->tName]['delete']);
				
		if( $this->numRowsFromSQL == 0 ) 
			$this->hideElement("recordcontrol");		
		
		if( $this->masterPageType != PAGE_VIEW  )
		{
			//inline edit column
			$this->xt->assign("inlineedit_column", $this->inlineEditAvailable() && $this->permis[ $this->tName ]['edit']);
			
			//for list icons instead of list links
			$this->assignListIconsColumn();
					
			//cancel all link, attr, span
			$this->cancelAllLinkAttrs();
		}
		
		for($i=0;$i<count($this->allDetailsTablesArr);$i++)
		{
			$permis = ($this->isGroupSecurity && ($this->permis[$this->allDetailsTablesArr[$i]['dDataSourceTable']]['add'] || $this->permis[$this->allDetailsTablesArr[$i]['dDataSourceTable']]['search'])) || (!$this->isGroupSecurity);	
			if($permis)
			{
				$this->xt->assign(GoodFieldName($this->tName)."_dtable_column", $permis);
				break;
			}
		}
	}
	
	
	/**
	 * Hide all excessive bricks and elements in the template.
	 * Prepare the page's template
	 */
	protected function prepareTemplate()
	{
		//set bricks, which	must be shown on details preview page
		$bricksExcept = array("grid", "grid_mobile", "pagination", "reorder_records", "bsgrid_tabs");
		if( $this->masterPageType == PAGE_LIST )
		{	
			$bricksExcept[] = "details_found";
			$bricksExcept[] = "page_of";
		}
		
		if( $this->masterPageType == PAGE_EDIT || $this->masterPageType == PAGE_ADD || $this->masterPageType == PAGE_LIST  )
		{
			if( $this->pSet->hasInlineEdit() || $this->pSet->hasDelete() && $this->masterPageType != PAGE_ADD ) 
			{
				if( $this->permis[ $this->tName ]['edit'] || $this->permis[ $this->tName ]['delete'] )
					$bricksExcept[] = "recordcontrol";
			}

			if( $this->pSet->hasInlineAdd() && $this->permis[$this->tName]['add'] )
				$bricksExcept[] = "recordcontrols_new";
		}
		$bricksExcept[] = "message";
		
		// if we use details inline. We don't need show the header/footer.
		$this->xt->assign("header", false);
		$this->xt->assign("footer", false);
		
		$this->xt->hideAllBricksExcept($bricksExcept);
		$this->xt->prepare_template($this->templatefile);	
	}
	
	/**
	 * @return String
	 */
	protected function getBSButtonsClass()
	{
		return "btn btn-xs btn-info";
	}
	
	/**
	 * @return String
	 */
	protected function getHeaderControlsBlocks()
	{
		$controlsBlocks = "";
		$buttons = "";
		$bs_button_class = $this->getBSButtonsClass();;
		
		if( $this->inlineAddAvailable() && $this->xt->getVar("inlineadd_link") )
		{
			$inlineaddlink_attrs = $this->xt->getVar("inlineaddlink_attrs");
			
			if( $this->addAvailable() )
				$caption = "Tambah baru";
			else	
				$caption = "Add";
				
			if( !$this->isBootstrap() ) 
			{
				$controlsBlocks = '<span class="rnr-dbebrick ">'
					.'<div class="style1 rnr-bl rnr-b-recordcontrols_new">'
						.'<a class="rnr-button" href="#" '.$inlineaddlink_attrs.'>'.$caption.'</a> '
					.'</div>'
				.'</span>';		
			}
			else
			{
				$buttons.= '<a class="'. $bs_button_class.'" href="#" '.$inlineaddlink_attrs.'>'.$caption.'</a> ';		
			}
		}
		
		if( $this->addAvailable() && $this->xt->getVar("add_link") )
		{
			$addlink_attrs = $this->xt->getVar("addlink_attrs");
			
			if( $this->inlineAddAvailable() )
				$caption = "Tambah baru";
			else	
				$caption = "Add";
				
			if( !$this->isBootstrap() ) 
			{
				$controlsBlocks = '<span class="rnr-dbebrick ">'
					.'<div class="style1 rnr-bl rnr-b-recordcontrols_new">'
						.'<a class="rnr-button" href="#" '.$addlink_attrs.'>'.$caption.'</a> '
					.'</div>'
				.'</span>';		
			}
			else
			{
				$buttons.= '<a class="'. $bs_button_class.'" href="#" '.$addlink_attrs.'>'.$caption.'</a> ';		
			}			
		}

		if( $this->inlineEditAvailable() && $this->xt->getVar("editselected_link") )
		{
			$editselectedlink_attrs = $this->xt->getVar("editselectedlink_attrs");
			$editselectedlink_span = $this->xt->getVar("editselectedlink_span");
			
			// "bs-invisible-button" class need for init hidden in bootstrap
			if( !$this->isBootstrap()) 
				$buttons.= '<a class="rnr-button" href="#" '.$editselectedlink_attrs.' '.$editselectedlink_span.'>'."Edit".'</a> ';
			else
				$buttons.= '<a class="' . $bs_button_class . ' " disabled href="#" '.$editselectedlink_attrs.' '.$editselectedlink_span.'>'."Edit".'</a> ';
		};	

		if( $this->updateSelectedAvailable() && $this->xt->getVar("updateselected_link") && $this->isBootstrap() )
		{
			$updateselectedlink_attrs = $this->xt->getVar("updateselectedlink_attrs");         
			$buttons.= '<a class="' . $bs_button_class . '" disabled href="#" '.$updateselectedlink_attrs.'>'."Update selected".'</a> ';		  		
		}
		
		if( $this->xt->getVar("saveall_link") )
		{
			$savealllink_attrs = $this->xt->getVar("savealllink_attrs");	
			$savealllink_span = $this->xt->getVar("savealllink_span");	
	
			if( !$this->isBootstrap()) 
				$buttons.= '<a class="rnr-button" href="#" '.$savealllink_attrs.' '.$savealllink_span.'>'."Simpan Semua".'</a> ';					  
			else
				$buttons.= '<a class="' . $bs_button_class . '" href="#" '.$savealllink_attrs.' '.$savealllink_span.'>'."Simpan Semua".'</a> ';					  
		}
		
		if( $this->xt->getVar("cancelall_link") )
		{
			$cancelalllink_attrs = $this->xt->getVar("cancelalllink_attrs");	
			$cancelalllink_span = $this->xt->getVar("cancelalllink_span");

			if( !$this->isBootstrap()) 
				$buttons.= '<a class="rnr-button" href="#" '.$cancelalllink_attrs.' '.$cancelalllink_span.'>'."Batalkan Edit".'</a> ';					  
			else
				$buttons.= '<a class="' . $bs_button_class . '" href="#" '.$cancelalllink_attrs.' '.$cancelalllink_span.'>'."Batalkan Edit".'</a> ';					  
		}
		
		if( $this->deleteAvailable() && $this->xt->getVar("deleteselected_link") )
		{
			$deleteselectedlink_attrs = $this->xt->getVar("deleteselectedlink_attrs");	
			$deleteselectedlink_span = $this->xt->getVar("deleteselectedlink_span");            

			// "bs-invisible-button" class need for init hidden in bootstrap
			if( !$this->isBootstrap()) 
				$buttons.= '<a class="rnr-button " href="#" '.$deleteselectedlink_attrs.' '.$deleteselectedlink_span.'>'."Delete".'</a> ';		  
			else
				$buttons.= '<a class="' . $bs_button_class . '" disabled href="#" '.$deleteselectedlink_attrs.' '.$deleteselectedlink_span.'>'."Delete".'</a> ';		  
		}		
		if( $buttons ) 
		{
			if( !$this->isBootstrap()) 
			{
				$controlsBlocks.= '<span class="rnr-dbebrick ">'
					.'<div class="style1 rnr-bl rnr-b-recordcontrol ">'
					.$buttons
					.'</div>'
				.'</span>';				
			} else {
				$controlsBlocks.= '<span class="rnr-dbebrick ">'
					.$buttons
				.'</span>';				
			}
		}
		
		return $controlsBlocks.'<div class="rnr-dbefiller"></div>';
	}	
	
	
	/**
	 * Show the page.
	 * It's supposed to be displayed in resonce on an ajax-like request
	 */
	public function showPage()
	{				
		$this->BeforeShowList();
		
		$response = array();
		
		if( !$this->isDispGrid() && 0 == $this->getGridTabsCount() )
		{
			$response["noData"] = true;
			echo printJSON( $response );
			return; 
		}
		
		$this->prepareTemplate();
		
		if( $this->isPD() ) 
		{
			$contents = $this->fetchForms( array( "grid", "below-grid" ) );	
		} 
		else if( $this->isBootstrap() )
		{
			$contents = $this->xt->fetch_loaded("grid_tabs").$this->xt->fetch_loaded("message_block").$this->xt->fetch_loaded("grid_block").$this->xt->fetch_loaded("pagination_block");	
		}
		else
		{
			$contents = $this->xt->fetch_loaded("body");
		}

		$this->addControlsJSAndCSS();
		$this->fillSetCntrlMaps();
		
		global $pagesData;
		$response["pagesData"] = $pagesData;
		$response['settings'] = $this->jsSettings;
		$response['controlsMap'] = $this->controlsHTMLMap;
		$response['viewControlsMap'] = $this->viewControlsHTMLMap;
	
		if( $this->masterPageType == PAGE_EDIT && $this->dashTName && $this->dashElementName )
			$response['headerButtonsBlock'] = $this->getHeaderControlsBlocks();
	
		$response['html'] = $contents;
		$response['success'] = true;
		$response['id'] = $this->id;
		$response['idStartFrom'] = $this->flyId;
		
		$response['delRecs'] = $this->recordsDeleted;
		if( $this->deleteMessage != '' )
			$response['delMess'] = true;
		
		$response["additionalJS"] = $this->grabAllJsFiles();
		$response["additionalCSS"] = $this->grabAllCSSFiles();
		
		echo printJSON($response);
	}

	
	
	/**
	 * Show a detail preview page
     * @param Array params - asp compatibility issue
	 */
	public function showPageDp($params = "")
	{	
		if( $this->isBootstrap() )
		{
			return $this->showGridOnly();
		}
		
		global $page_layouts;
		
		$this->BeforeShowList();
		
		$this->prepareTemplate();
		$contents = $this->xt->fetch_loaded("body");	
		
		//add for details preview page skin and style
		$layout =& $page_layouts[$this->shortTableName.'_'.$this->pageType];
		$pageSkinStyle = $layout->style." page-".$layout->name;	
		
		$this->xt->assign("dpShowHide", true);
		$this->xt->assign("dpMinus", true);
		$this->xt->assign("dpShowHide_attrs", 'id="dpShowHide'.$this->id.'"');
		$this->xt->assign("dpMinus_attrs", 'id="dpMinus'.$this->id.'"');
		$this->xt->assign("dt_attrs", 'name="dt'.$this->id.'"');
		
		if(GetGlobalData("printDetailTableName", false))
		{
			$this->xt->assign("dpShowHide", false);
			$this->xt->assign("dpMinus", false);
		}
		
		if(postvalue("pdf") == 1)
			$this->xt->assign("dpMinus", false);

		echo '<div id="detailPreview'.$this->id.'" class="'.$pageSkinStyle.' rnr-pagewrapper dpStyle">'.$contents.'</div>';	
	}	

	public function prepareDisplayDetails() {
		if( $this->isPD() ) {
			$this->prepareDisplayDetailsPD();
			return;
		}
		$this->prepareTemplate();
		
		$contents = $this->xt->fetch_loaded("grid_block");	
		if( $this->masterPageType != PAGE_ADD ) 
			$contents = $this->xt->fetch_loaded("grid_tabs").$this->xt->fetch_loaded("message").$this->xt->fetch_loaded("reorder_records").$contents;
			
		$contents.= $this->xt->fetch_loaded("pagination_block");
		
		$this->renderedBody = '<div id="detailPreview'.$this->id.'">'.$contents.'</div>';	
	}

	public function prepareDisplayDetailsPD() {
		$this->prepareTemplate();

		if( $this->pdfJsonMode() ) {
			$this->xt->assign("embedded_grid", true );
			$this->xt->load_templateJSON( $this->templatefile);
			$this->renderedBody = $this->xt->fetch_loadedJSON("body");
			return;
		}
		
		$forms = array( "grid" );
		if( $this->masterPageType != PAGE_ADD ) 
		{
			$forms = array( "grid", "below-grid" );	
		}
		
		
		$this->renderedButtons = $this->fetchBlocksList( array("firstAboveGridCell") );		
		$this->renderedButtons = str_replace("btn-primary", "btn-xs btn-info", $this->renderedButtons);
		$this->renderedButtons = str_replace("btn-default", "btn-xs btn-info", $this->renderedButtons);

		$bodyContents = $this->fetchForms($forms);		
		$this->renderedBody = '<div id="detailPreview'.$this->id.'">'.$bodyContents.'</div>';	
	}


	public function showGridOnly() 
	{			
		echo $this->renderedBody;
	}
	
	public function showButtonsDp($params = "") {
		echo $this->renderedButtons;
	}


	/**
	 * A stub preventing the Search Panel from building
	 */
	function buildSearchPanel()
	{
	}

	public function isPageSortable()
	{
		return $this->masterPageType != PAGE_ADD;
	}	
	
	/**
	 * A stub
	 */
	function rulePRG()
	{
	}
	
	function getMasterTableSQLClause( $basedOnProp = false ) 
	{
		if($this->masterPageType==PAGE_ADD)
			return "1=0";
		return parent::getMasterTableSQLClause();
	}
	
	/**
	 * only for bootstrap layers in add/edit page
	 */ 
	function assignButtonsOnMasterEdit( $masterXt )
	{
		if( $this->inlineAddAvailable() )
		{
			$masterXt->assign( "details_inlineadd_" . $this->shortTableName . "_link", true );
			$masterXt->assign( "details_inlineadd_" . $this->shortTableName . "_attrs", $this->getInlineAddLinksAttrs() );			
		}

		if( $this->addAvailable() )
		{
			$masterXt->assign( "details_add_" . $this->shortTableName . "_link", true );
			$masterXt->assign( "details_add_" . $this->shortTableName . "_attrs", $this->getAddLinksAttrs() );		
		}
		
		if( $this->deleteAvailable() )
		{
			$masterXt->assign( "details_delete_" . $this->shortTableName . "_link", true );
			$masterXt->assign( "details_delete_" . $this->shortTableName . "_attrs", $this->getDeleteLinksAttrs() );
		}
		
		if( $this->inlineEditAvailable() )
		{
			$masterXt->assign( "details_edit_" . $this->shortTableName . "_link", true );
			$masterXt->assign( "details_edit_" . $this->shortTableName . "_attrs", $this->getEditLinksAttrs() );
		}

		if( $this->updateSelectedAvailable() )
		{
			$masterXt->assign( "details_updateselected_" . $this->shortTableName . "_link", true );
			$masterXt->assign( "details_updateselected_" . $this->shortTableName . "_attrs", $this->getUpdateSelectedAttrs() );		
		}
		
		if ( $this->inlineAddAvailable() || $this->inlineEditAvailable() )
		{
			$masterXt->assign("cancelall_" . $this->shortTableName . "_link", true);
			$masterXt->assign("cancelalllink_" . $this->shortTableName . "_span",$this->buttonShowHideStyle('cancelall'));
			$masterXt->assign("cancelalllink_" . $this->shortTableName . "_attrs","name=\"revertall_edited".$this->id."\" id=\"revertall_edited".$this->id."\"");
		}

		// Do not show save all button on add, view, dashdetail list pages 
		if ( $this->masterPageType == PAGE_EDIT && ( $this->inlineAddAvailable() || $this->inlineEditAvailable() ) )
		{
			$masterXt->assign("saveall_" . $this->shortTableName . "_link", true);
			$masterXt->assign("savealllink_" . $this->shortTableName . "_span",$this->buttonShowHideStyle('saveall'));	
			$masterXt->assign("savealllink_" . $this->shortTableName . "_attrs","name=\"saveall_edited".$this->id."\" id=\"saveall_edited".$this->id."\"");
		}
		
	}
	
	protected function fillTableSettings( $table = "", $pSet = null )
	{
		parent::fillTableSettings( $table, $pSet );
		
		if( $this->addAvailable() )
			$this->jsSettings["tableSettings"][ $this->tName ]["showAddInPopup"] = true;

		if( $this->editAvailable() || $this->updateSelectedAvailable() )
			$this->jsSettings["tableSettings"][ $this->tName ]["showEditInPopup"] = true;
			
		if( $this->viewAvailable() )
			$this->jsSettings["tableSettings"][ $this->tName ]["showViewInPopup"] = true;			
	}		
	
	function deleteAvailable() {
		return $this->masterPageType != PAGE_VIEW && $this->masterPageType != PAGE_ADD && parent::deleteAvailable();
	}
	
	function importAvailable() {
		return false;
	}
	
	function editAvailable() {
		return $this->masterPageType != PAGE_VIEW && $this->masterPageType != PAGE_ADD && parent::editAvailable();
	}
	
	function addAvailable() {
		
		//	add in popup is not possible on master Add page. Only Inline Add is available there
		return $this->masterPageType != PAGE_VIEW  && $this->masterPageType != PAGE_ADD && parent::addAvailable();
	} 
	
	function copyAvailable() {
		return $this->masterPageType != PAGE_VIEW && $this->masterPageType != PAGE_ADD && parent::copyAvailable();
	}
	
	function inlineEditAvailable() {
		return $this->masterPageType != PAGE_VIEW && $this->masterPageType != PAGE_ADD && parent::inlineEditAvailable();
	}

	
	function inlineAddAvailable() {
		
		//	don't show inlineAdd on View page
		//	Always change Add to Inline Add on master Add page
		return $this->masterPageType != PAGE_VIEW && parent::inlineAddAvailable() 
			|| $this->masterPageType == PAGE_ADD && parent::addAvailable();
	}
	
	protected function displayViewLink() {
		return $this->masterPageType != PAGE_VIEW && $this->masterPageType != PAGE_ADD && $this->viewAvailable();
	}
	
	
	function updateSelectedAvailable()
	{
		return $this->masterPageType != PAGE_VIEW && $this->masterPageType != PAGE_ADD && parent::updateSelectedAvailable();
	}

	/**
	 * Checks if need to display grid
	 */
	function isDispGrid() 
	{
		//	can add data to grid
		if ( $this->inlineAddAvailable() || $this->addAvailable() )
			return true;
		
		return parent::isDispGrid();
	}

	function shouldDisplayDetailsPage()
	{
		//	always display the page if there are visible grid tabs
		return $this->isDispGrid() || 0 != $this->getGridTabsCount();
	}

	function pdfJsonMode() 
	{
		return $this->mode == LIST_PDFJSON;
	}
}
?>