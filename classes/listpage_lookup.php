<?php

class ListPage_Lookup extends ListPage_Embed
{
	/**
      * Field of link
      *
      * @var string
      */
	var $linkField = "";

	
	/**
      * Select field of lookup
      *
      * @var string
      */
	var $lookupSelectField = "";
	
	/**
      * Field customed
      *
      * @var string
      */
	var $customField = "";
	
	/**
      * Field displayed
      *
      * @var string
      */
	var $dispField = "";	
	
	var $dispFieldAlias = "";
	
	var $lookupValuesArr = array();

	/**
     * @type Array
     */
	public $parentCtrlsData;	
	
	/**
	 * The name of a table containing a lookup-field
	 * @type String
	 */	
	public $mainTable;
	
	/**
	 * The type of a page containing a lookup-field
	 * @type String	 
	 */		
	public $mainPageType;
	
	/**
	 * The lookup-field's
	 * @type String	 
	 */
	public $mainField = "";
	
	/**
	 * A  settings object for the table 
	 * containing the lookup field
	 * @type ProjectSettings
	 */
	protected $mainPSet;
	
	public $mainRecordData;
	public $mainRecordMasterTable;	
	
	public $mainContext;
	
	
	/**
      * Constructor, set initial params
      *
      * @param array $params
      */
	function __construct(&$params)
	{
		// call parent constructor. always at the first line!!!
		parent::__construct($params);
		// init params
		$this->initLookupParams();	
		
		$this->permis[ $this->tName ]["search"] = 1;		
		$this->jsSettings['tableSettings'][$this->tName]['permissions'] = $this->permis[$this->tName];

		$this->isUseAjaxSuggest = false;	
	}

	/**
	 * Set the correct session prefix
	 */
	protected function assignSessionPrefix() 
	{
		$this->sessionPrefix = $this->tName."_lookup_".$this->mainTable.'_'.$this->mainField;	
	}
	
	/**
	 *
	 */
	function initLookupParams()
	{							
		if( $this->mainPageType != PAGE_ADD && $this->mainPageType != PAGE_EDIT )
			$this->mainPageType = PAGE_SEARCH;
			
		$this->mainPSet = new ProjectSettings($this->mainTable, $this->mainPageType);

		$this->linkField = $this->mainPSet->getLinkField( $this->mainField );
		$this->dispField = $this->mainPSet->getDisplayField( $this->mainField ); 
		
		if( $this->mainPSet->getCustomDisplay( $this->mainField ) )
		{
			$this->dispFieldAlias = GetGlobalData("dispFieldAlias", "rrdf1");
			
			$this->customField = $this->linkField;
		}
		
		$this->outputFieldValue($this->linkField, 2);
		$this->outputFieldValue($this->dispField, 2);
		
		if( $this->dispFieldAlias && $this->pSet->appearOnListPage( $this->dispField ) )
			$this->lookupSelectField = $this->dispField;	
		elseif( $this->pSet->appearOnListPage( $this->dispField ) )
			$this->lookupSelectField = $this->dispField;
		else
			$this->lookupSelectField = $this->listFields[0]['fName'];
			
		$this->mainContext = $this->getMainContext();	
	}

	/**
	 * The stub - ListPage Lookup no supported MasterTable 
	 */
	function displayMasterTableInfo() 
	{
	}

	/**
	 * The stub - ListPage Lookup no supported master-details mode
	 */
	function processMasterKeyValue()
	{
	}
	
	/**
	 * @return Array
	 */
	protected function getMainContext()
	{
		$contextParams = array();
		
		$contextParams["data"] = $this->mainRecordData;

		if ( $this->mainRecordMasterTable && isset($_SESSION[ $this->mainRecordMasterTable . "_masterRecordData" ]) )
			$contextParams["masterData"] = $_SESSION[ $this->mainRecordMasterTable . "_masterRecordData" ];
			
		return $contextParams;
	}
	
	/**
	 * clear lookup session data, while loading at first time
	 */
	function clearLookupSessionData()
	{
		if($this->firstTime)
		{
			$sessLookUpUnset = array();
			foreach($_SESSION as $key=>$value)
				if(strpos($key, "_lookup_")!== false)
					$sessLookUpUnset[] = $key;
					
			foreach($sessLookUpUnset as $key)
				unset($_SESSION[$key]);
		}
	}
	
	
	function addCommonJs()
	{
		$this->controlsMap['dispFieldAlias'] = $this->dispFieldAlias;
		
		$this->addControlsJSAndCSS();
		$this->addButtonHandlers();
	}
	
	/**
	 *
	 */
	function addSpanVal($fName, &$data) 
	{
		if ($this->dispFieldAlias && @$this->arrFieldSpanVal[$fName] == 2)
			return "val=\"".runner_htmlspecialchars($data[$this->dispFieldAlias])."\" ";
		else
			return parent::addSpanVal($fName, $data);
	}
	
	public function getOrderByClause()
	{
		$userStartingSort = false;
		if ( isset($_SESSION[ $this->sessionPrefix . "_orderby" ]) )
		{
			$userStartingSort = true;
		}

		$orderByField = $this->mainPSet->getLookupOrderBy( $this->mainField );		
		if( !$userStartingSort && strlen($orderByField) )
		{
			$strOrder = " ORDER BY ".$this->getFieldSQL( $orderByField );
			if( $this->mainPSet->isLookupDesc( $this->mainField ) )
				$strOrder .= ' DESC';			
		}
		else
			$strOrder = parent::getOrderByClause();
		
		return $strOrder;
	}
	
	/**
	 * Build and return SQL logical clause serving dependent dropdowns
	 * "make='Ford'" or similar
	 * @return String
	 */
	protected function getDependentDropdownFilter() 
	{
		if( !$this->mainPSet->useCategory( $this->mainField ) ) 
			return "";
	
		// add 1=0 if parent control contain empty value and no search used	
		if( $this->mainPageType != PAGE_SEARCH && !count($this->parentCtrlsData) )
		{
			return "1=0";
		}
		
		$parentWhereParts = array();
		foreach( $this->mainPSet->getParentFieldsData( $this->mainField ) as $cData )
		{
			if( !isset( $this->parentCtrlsData[ $cData["main"] ] ) )
				continue;
			
			$parentFieldName = $cData["lookup"];
			$parentFieldValues = splitvalues( $this->parentCtrlsData[ $cData["main"] ] );
			
			$arWhereClause = array();
			foreach($parentFieldValues as $value)
			{
				if( $this->cipherer != null )
					$lookupValue = $this->cipherer->MakeDBValue($parentFieldName, $value);
				else 
					$lookupValue = make_db_value($parentFieldName, $value);
					
				$arWhereClause[] = $this->getFieldSQLDecrypt($parentFieldName) . "=" . $lookupValue;
			}
			
			if( count($arWhereClause) )
				$parentWhereParts[] = "(".implode(" OR ", $arWhereClause).")";	
		}
		return "(".implode(" AND ", $parentWhereParts).")";
	}
	
	protected function getSubsetSQLComponents() 
	{
		$sql = parent::getSubsetSQLComponents();
		
		if ($this->dispFieldAlias)
		{
			$sql["sqlParts"]["head"] .= ", " . $this->dispField." ";
			$sql["sqlParts"]["head"] .= "as " . $this->connection->addFieldWrappers($this->dispFieldAlias);
		}
		
		$sql["mandatoryWhere"][] = $this->getLookupWizardWhere();
		
		//	dependent dropdown filter
		$sql["mandatoryWhere"][] = $this->getDependentDropdownFilter();
		
		return $sql;
	}

	/**
	 * @return String
	 */
	protected function getLookupWizardWhere()
	{
		RunnerContext::push( new RunnerContextItem( CONTEXT_ROW, $this->mainContext ) );
		$where = prepareLookupWhere( $this->mainField, $this->mainPSet );
		RunnerContext::pop();
		
		return $where;
	}
	
	/**
	 * Build a lookup's search panel
	 */
	function buildSearchPanel() 
	{
		if( !$this->permis[ $this->tName ]['search'] )
			return;
		
		$params = array();
		$params['pageObj'] = &$this;
		$params['panelSearchFields'] = $this->panelSearchFields;
		$this->searchPanel = new SearchPanelLookup($params);
		$this->searchPanel->buildSearchPanel();
	}
	
	/**
	 *
	 */
	function addLookupVals()
	{
		$this->controlsMap['lookupVals'] = $this->lookupValuesArr;
	}
	
	function fillGridData()
	{
		parent::fillGridData();

		$this->addLookupVals();
		
	}

	
	function fillCheckAttr(&$record,$data,$keyblock)
	{
		$checkbox_attrs="name=\"selection[]\" value=\"".runner_htmlspecialchars(@$data[$this->linkField])."\" id=\"check".$this->recId."\"";
		$record["checkbox"]=array("begin"=>"<input type='checkbox' ".$checkbox_attrs.">", "data"=>array());
	}
	
	/**
	 * Name of function came from listpage class, but on listpage_lookup it used for collection link and display field data
	 *
	 * @param String type
	 * @param Array &record
	 * @param Array data (optional)
	 */
	function addSpansForGridCells($type, &$record, $data = null) 
	{
		if($type == 'add')
		{
			parent::addSpansForGridCells($type, $record, $data);
			return;
		}
		
		if(!is_null($data))
		{
			if ($this->dispFieldAlias)
				$dispVal = $data[$this->dispFieldAlias];
			else 
				$dispVal = $data[$this->dispField];

			if (  in_array( $this->mainPSet->getViewFormat( $this->mainField ), array(FORMAT_DATE_SHORT, FORMAT_DATE_LONG, FORMAT_DATE_TIME) ) )
			{			
				$viewContainer = new ViewControlsContainer( $this->mainPSet, PAGE_LIST, null );
				$ctrlData = array();
				$ctrlData[ $this->mainField ] = $data[ $this->linkField ];
	
				$dispVal = $viewContainer->getControl( $this->mainField )->getTextValue( $ctrlData );
			}
			
			$this->lookupValuesArr[] = array('linkVal' => $data[$this->linkField], 'dispVal' => $dispVal);
		}
	}
	
	/**
	 *
	 */
	function proccessRecordValue(&$data, &$keylink, $listFieldInfo)
	{
		$value = parent::proccessRecordValue($data, $keylink, $listFieldInfo);
		
		if ($this->lookupSelectField == $listFieldInfo['fName'])
			$value = '<a href="#" data-ind="'.count( $this->lookupValuesArr ).'" type="lookupSelect'.$this->id.'">'.$value."</a>";
		
		return $value;
	}
	
	/**
	 *
	 */	
	function showPage() 
	{
		$this->BeforeShowList();
		
		if( !$this->isPD() ) {

			if ($this->mobileTemplateMode())
			{
				$this->xt->assign("cancelbutton_block",true);
				$this->xt->assign("searchform_block", true);
				$this->xt->assign("searchform_showall", true);
				$bricksExcept = array("grid_mobile", "message", "pagination", "vmsearch2", "cancelbutton_mobile");
			}
			else 
			{
				$bricksExcept = array("grid", "message", "pagination", "vsearch1", "vsearch2", "search", "recordcontrols_new", "bsgrid_tabs");
				if( $this->isBootstrap() )
				{
					$bricksExcept[] = "add";
					$bricksExcept[] = "reorder_records";
				}
			}
		}
		if( $this->isPD() ) {
			$this->hideAllFormItems( 'supertop' );
			$this->showItemType( 'simple_search' );
			$this->showItemType( 'simple_search_field' );
			$this->showItemType( 'simple_search_option' );
			
			$this->hideItemType('columns_control');
		} else {
			$this->xt->hideAllBricksExcept($bricksExcept);
		}
		$this->xt->prepare_template($this->templatefile);
		$this->showPageAjax();
	}

	
	/**
	 * Display blocks after loaded template of page
	 */
	function showPageAjax() 
	{
		$lookupSearchControls = $this->xt->fetch_loaded('searchform_text').$this->xt->fetch_loaded('searchform_search')
			.$this->xt->fetch_loaded('searchform_showall');
		$this->xt->assign("lookupSearchControls", $lookupSearchControls);
		
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
		// popup header shows PD items only
		
		if( $this->isPD() ) {
			$returnJSON["headerCont"] = '<h3 data-itemtype="lookupheader" data-itemid="lookupheader">' . $this->getPageTitle( $this->pageType, GoodFieldName($this->tName) ) . "</h3>";
			$returnJSON["html"] = $this->xt->fetch_loaded("supertop_block")
				. '<div class="r-popup-block">'
					. '<div class="r-popup-data">'
						. $this->xt->fetch_loaded("above-grid_block")
						. $this->xt->fetch_loaded("grid_block")
					. "</div>"
				."</div>";
				
			$returnJSON["footerCont"] = $this->xt->fetch_loaded("below-grid_block");
		} else {
			$returnJSON["headerCont"] = '<h2 data-itemid="lookupheader">' . $this->getPageTitle( $this->pageType, GoodFieldName($this->tName) ) . "</h2>";
			$returnJSON["html"] = $this->xt->fetch_loaded("body");
		}
		
		$returnJSON['idStartFrom'] = $this->flyId;
		$returnJSON['success'] = true;
		
		$returnJSON["additionalJS"] = $this->grabAllJsFiles();
		$returnJSON["CSSFiles"] = $this->grabAllCSSFiles();

		echo printJSON($returnJSON);
	}
	
	/**
	 *
	 */
	function SecuritySQL($strAction, $table="")
	{
		global $strTableName;
		
		if( !strlen($table) )	
			$table = $strTableName;
		
		$strPerm = GetUserPermissions($table);
		if( strpos( $strPerm, "S" ) === false )
			$strPerm .=  "S" ;
		
		return SecuritySQL($strAction, $table, $strPerm);
	}

	function displayTabsInPage() 
	{		
		return true;
	}
	
	/**
	 * A stub
	 */
	function buildTotals(&$totals)
	{
	}
	
	/**
	 * Returns where clause for active master-detail relationship
	 * @return string
	 */
	function getMasterTableSQLClause( $basedOnProp = false )
	{
		return "";
	}	

	function deleteAvailable() {
		return false;
	}
	function importAvailable() {
		return false;
	}
	function editAvailable() {
		return false;
	}
	function addAvailable() {
		return false;
	}
	function copyAvailable() {
		return false;
	}
	function inlineAddAvailable() {
		return parent::inlineAddAvailable() && $this->mainPSet->isAllowToAdd( $this->mainField );
	}
	function inlineEditAvailable() {
		return false;
	}
	function viewAvailable() {
		return false;
	}
	function exportAvailable() {
		return false;
	}
	function printAvailable() {
		return false;
	}
	function advSearchAvailable() {
		return false;
	}
	
	function detailsInGridAvailable()
	{
		return false;
	}
	
	function updateSelectedAvailable() 
	{
		return false;
	}	
}
?>