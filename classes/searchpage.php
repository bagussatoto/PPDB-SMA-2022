<?php
class SearchPage extends RunnerPage
{	
	public $searchControllerId;
	public $searchControlBuilder;
	public $tableSettings = array();
	
	public $reportName;
	public $chartName;
	public $layoutVersion;
	
	public $needSettings = false;
	
	/**
	 * The field that is to be loaded in the SEARCH_LOAD_CONTROL mode
	 */
	public $ctrlField;

	public $extraPageParams;
	
	
	/**
	 * @constructor
	 */
	function __construct(&$params)
	{
		parent::__construct($params);
		
		if( $this->getLayoutVersion() === PD_BS_LAYOUT ) 
		{
			$this->headerForms = array( "top" );
			$this->footerForms = array( "below-grid" );
			$this->bodyForms = array( "grid" );
		} 
		else 
		{		
			$this->formBricks["header"] = "searchheader";
			$this->formBricks["footer"] = "searchbuttons";
		}
		
		if( count( $this->extraPageParams ) )
			$this->jsSettings["tableSettings"][ $this->tName ]["extraSearchPageParams"] = $this->extraPageParams;
		
		$this->jsSettings["tableSettings"][ $this->tName ]["searchPageFields"] = $this->pSet->getAdvSearchFields();
	}
		
	public function init() 
	{
		parent::init(); 

		$this->searchControlBuilder = new AdvancedSearchControl($this->searchControllerId, $this->tName, $this->searchClauseObj, $this);
	}
	
	/**
	 * Assign seesion prefix
	 */
	protected function assignSessionPrefix() 
	{
		if( $this->mode == SEARCH_DASHBOARD )
			$this->sessionPrefix = $this->dashTName."_".$this->tName;
		else
			$this->sessionPrefix = $this->tName;
	}	
	
	public function displaySearchControl() 
	{
		$this->searchControlBuilder = new PanelSearchControl($this->searchControllerId, $this->tName, $this->searchClauseObj, $this);
		$defaultValue = $this->pSet->getDefaultValue( $this->ctrlField );
		$ctrlBlockArr = $this->searchControlBuilder->buildSearchCtrlBlockArr($this->id, $this->ctrlField, 0, '', false, true, $defaultValue, '');	
		
		// build array for encode
		$resArr = array();
		$resArr['control1'] = trim($this->xt->call_func($ctrlBlockArr['searchcontrol']));
		$resArr['control2'] = trim($this->xt->call_func($ctrlBlockArr['searchcontrol1']));
		$resArr['comboHtml'] = trim($ctrlBlockArr['searchtype']);
		$resArr['delButt'] = trim($ctrlBlockArr['delCtrlButt']);
		$resArr['delButtId'] = trim( $this->searchControlBuilder->getDelButtonId( $this->ctrlField, $this->id ) );
		$resArr['divInd'] = trim($this->id);	
		$resArr['fLabel'] = GetFieldLabel( GoodFieldName($this->tName), GoodFieldName( $this->ctrlField ) );
		$resArr['ctrlMap'] = $this->controlsMap['controls'];
		
		if( $this->needSettings == 'true' )
		{
			$this->fillSettings();
			$resArr['settings'] = $this->jsSettings;
		}
		
		// return JSON
		echo printJSON($resArr);
		exit();	
	}
	
	public function process() 
	{
		if( $this->eventsObject->exists("BeforeProcessSearch") )
			$this->eventsObject->BeforeProcessSearch( $this );		
		
		$this->prepareSearchRadio();
		$this->prepareFields();
		
		$this->addButtonHandlers();
		
		$this->addCommonJs();
		$this->doCommonAssignments();
		$this->prepareCollapseButton();

		$this->fillSetCntrlMaps();
		
		$this->displaySearchPage();
	}
	
	protected function prepareSearchRadio()
	{
		$searchRadio = $this->searchControlBuilder->getSearchRadio();
		$this->xt->assign_section("all_checkbox_label", $searchRadio['all_checkbox_label'][0], $searchRadio['all_checkbox_label'][1]);
		$this->xt->assign_section("any_checkbox_label", $searchRadio['any_checkbox_label'][0], $searchRadio['any_checkbox_label'][1]);
		$this->xt->assignbyref("all_checkbox", $searchRadio['all_checkbox']);
		$this->xt->assignbyref("any_checkbox", $searchRadio['any_checkbox']);
	}	

	protected function prepareFields()
	{
		foreach( $this->pSet->getAdvSearchFields() as $field )
		{
			$gfield = GoodFieldName( $field );
			$lookupTable = $this->pSet->getLookupTable( $field );
			if( $lookupTable )
				$this->settingsMap["globalSettings"]['shortTNames'][ $lookupTable ] = GetTableURL( $lookupTable );
	
			$this->fillFieldToolTips( $field );	
	
			$srchFields = $this->searchClauseObj->getSearchCtrlParams( $field );
			$firstFieldParams = array();
			if( count( $srchFields ) )
			{
				$firstFieldParams = $srchFields[0];
			}
			else
			{
				$firstFieldParams['fName'] = $field;
				$firstFieldParams['eType'] = '';
				$firstFieldParams['value1'] = $this->pSet->getDefaultValue( $field );
				$firstFieldParams['value2'] = '';
				$firstFieldParams['not'] = false;
				$firstFieldParams['opt'] = $this->pSet->getDefaultSearchOption( $field );
				$firstFieldParams['not'] = false;
				if ( substr($firstFieldParams['opt'], 0, 4) == 'NOT ' )
				{
					$firstFieldParams['opt'] = substr($firstFieldParams['opt'], 4);
					$firstFieldParams['not'] = true;
				}
			}
	// create control	
			$ctrlBlockArr = $this->searchControlBuilder->buildSearchCtrlBlockArr($this->id, $firstFieldParams['fName'], 0, $firstFieldParams['opt'], $firstFieldParams['not'], false, $firstFieldParams['value1'], $firstFieldParams['value2']);	
			if($firstFieldParams['opt'] == "")
			{
				$firstFieldParams['opt'] = $this->pSet->getDefaultSearchOption($firstFieldParams['fName']);
			}
			$srchTypeFull = $this->searchControlBuilder->getCtrlSearchType($firstFieldParams['fName'], $this->id, 0, $firstFieldParams['opt'], $firstFieldParams['not'], true, true);
			
			if( $this->is508 )
				$this->xt->assign_section( $gfield . "_label", "<label for=\"". $this->getInputElementId( $field, $this->pSet )."\">", "</label>");
			else 
				$this->xt->assign( $gfield . "_label", true);

			if ( $this->isBootstrap() )
			{			
				$firstElementId = $this->getControl($field, $this->id)->getFirstElementId();
				if ( $firstElementId )
				{
					$this->xt->assign("labelfor_" . $gfield, $firstElementId);
				}
			}
			
			$this->xt->assign( $gfield . "_fieldblock", true);
			$this->xt->assignbyref( $gfield . "_editcontrol", $ctrlBlockArr['searchcontrol']);
			$this->xt->assign( $gfield . "_notbox", $ctrlBlockArr['notbox']);
			// create second control, if need it
			$this->xt->assignbyref( $gfield . "_editcontrol1", $ctrlBlockArr['searchcontrol1']);
			// create search type select
			$this->xt->assign("searchtype_" . $gfield, $ctrlBlockArr['searchtype']);
			$this->xt->assign("searchtypefull_" . $gfield, $srchTypeFull);
			
			$isFieldNeedSecCtrl = $this->searchControlBuilder->isNeedSecondCtrl( $field );
			$ctrlInd = 0;
			if( $isFieldNeedSecCtrl )
			{
				$this->controlsMap["search"]["searchBlocks"][] = array( 'fName'=> $field , 'recId'=> $this->id, 'ctrlsMap'=> array(0=> $ctrlInd, 1=> ($ctrlInd + 1)) );
				$ctrlInd+=2;
			}
			else
			{
				$this->controlsMap["search"]["searchBlocks"][] = array( 'fName'=> $field , 'recId'=> $this->id, 'ctrlsMap'=> array(0=> $ctrlInd) );			
				$ctrlInd++;
			}	
		}
	}

	
	protected function doCommonAssignments()
	{
		$this->xt->assign( "id", $this->id );

		if ( $this->isBootstrap() )
		{
			if ( $this->mode === SEARCH_SIMPLE )
			{
				$this->headerCommonAssign();
			}
			else
			{
				$this->xt->assign("menu_chiddenattr", "data-hidden" );
			}
		}
		
		$this->setLangParams();
		
		if( $this->mode !== SEARCH_DASHBOARD ) 
			$this->assignBody();
		
		$this->xt->assign("contents_block", true);
		$this->xt->assign("conditions_block", true);
		$this->xt->assign("search_button", true);
		$this->xt->assign("reset_button", true);
		
	
		$this->xt->assign("searchbutton_attrs", "id=\"searchButton".$this->id."\"");
		$this->xt->assign("resetbutton_attrs", "id=\"resetButton".$this->id."\"");
		
		$this->xt->assign( "searchheader", true );
		$this->xt->assign( "searchbuttons", true );
		
		if( $this->mode !== SEARCH_DASHBOARD ) 
		{ 
			$this->xt->assign("back_button", true);
			$this->xt->assign("backbutton_attrs", "id=\"backButton".$this->id."\"");
		}
		
		//	webreports assignments
		if( $this->reportName )
		{
			$this->xt->assign( "dynamic", "true" );
			$this->xt->assign( "rname", $this->reportName );
		}
		
		if( $this->chartName )
		{
			$this->xt->assign( "dynamic", "true" );
			$this->xt->assign( "cname", $this->chartName );
		}
		
		if( $this->isPD() )
		{
			$this->xt->assign( "cancel_button", $this->mode == SEARCH_POPUP );
			$this->xt->assign( "back_button", $this->mode != SEARCH_POPUP );
		}
	}
	
	protected function displaySearchPage()
	{
		$templateFile = $this->templatefile;
		if( $this->eventsObject->exists("BeforeShowSearch") )
			$this->eventsObject->BeforeShowSearch( $this->xt, $templateFile, $this );
		
		if( $this->mode == SEARCH_SIMPLE )
		{
			$this->display( $templateFile );
			return;
		}
		
		$this->xt->assign("header", false);
		$this->xt->assign("footer", false);
		$this->xt->assign("body", $this->body);
		
		$this->displayAJAX( $templateFile, $this->flyId + 1 );
		exit();
	}
	
	function getLayoutVersion() 
	{
		if( $this->layoutVersion )
			return $this->layoutVersion;
			
		return parent::getLayoutVersion();
	}
	
	/**
	 * @return Array
	 */
	public static function getExtraPageParams()
	{
		$prms = array();
		
		//crosstable params
		$prms["x"] = postvalue("x");
		$prms["y"] = postvalue("y");
		$prms["data"] = postvalue("data");
		
		if( !strlen( $prms["x"] ) &&  !strlen( $prms["y"] ) && !strlen( $prms["data"] ) )
			return array();
			
		$prms["op"] = postvalue("op");
		$prms["xtype"] = postvalue("xtype");
		$prms["ytype"] = postvalue("ytype");	
	
		return $prms;
	}
	
	protected function checkShowBreadcrumbs() 
	{
		return $this->mode == SEARCH_SIMPLE;
	}

	public static function readSearchModeFromRequest()
	{
		$mode = postvalue("mode"); 
		
		if( $mode == "dashsearch" )
			return SEARCH_DASHBOARD;
		
		if( $mode == "inlineLoadCtrl" )
		{
			// load search panel control
			return SEARCH_LOAD_CONTROL;
		}

		if( postvalue("onFly") )
			return SEARCH_POPUP;
		
		return SEARCH_SIMPLE;
	}
}
?>