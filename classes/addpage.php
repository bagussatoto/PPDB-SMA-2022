<?php
class AddPage extends RunnerPage
{
	/**
	 * The page's message type
	 * @type Number
	 */	
	public $messageType = MESSAGE_ERROR;

	protected $auditObj = null;	
	
	/**
	 * The page's fields array
	 */
	protected $addFields = array();

	protected $readAddValues = false;	
	
	/**
	 * The record is being added through the 'List page with search' InlineAdd controls
	 */
	protected $insertedSuccessfully = false;
	
	protected $defvalues = array();
	
	protected $newRecordData = array();
	
	protected $newRecordBlobFields = array();

	/**
	 * It could be set up in ADD_MASTER mode only
	 */
	public $afterAdd_id = ''; 	
	
	/**
	 * The page's action
	 * @type String
	 */
	public $action = "";
	
	/**
	 * It's set up in inline add mode only
	 */
	public $screenWidth = 0;
	
	/**
	 * It's set up in inline add mode only
	 */	
	public $screenHeight = 0;
	
	/**
	 * It's set up in inline add mode only
	 */	
	public $orientation = '';	
			
	/**
	 * It's equal to true when a record is added
	 * through the 'List page with search' InlineAdd controls
	 */ 
	public $forListPageLookup = false;
	
	/**
	 * The short lookup table name
	 * It's set up properly when a record is being added 
	 * in a lookup 'Add new' popup 
	 * or through the 'List page with search' InlineAdd controls
	 */
	public $lookupTable = "";
	
	/**
	 * It's set up properly when a record is being added 
	 * in a lookup 'Add new' popup 
	 * or through the 'List page with search' InlineAdd controls
	 */
	public $lookupField = "";

	/**
	 * It's set up properly when a record is being added 
	 * in a lookup 'Add new' popup 
	 * or through the 'List page with search' InlineAdd controls
	 */
	public $lookupPageType = "";	

	/**
	 * @type Array
	 */
	public $parentCtrlsData;
	
	
	/**
	 * @type Number
	 */	
	protected $afterAddAction = null;
	
	/**
	 *
	 */
	public $fromDashboard = "";
	
	/**
	 * @constructor
	 */ 
	function __construct(&$params)
	{
		parent::__construct($params);
		
		$this->addFields = $this->getPageFields();
		$this->auditObj = GetAuditObject($this->tName);
			
		if( $this->getLayoutVersion() === PD_BS_LAYOUT ) 
		{
			$this->headerForms = array( "top" );
			$this->footerForms = array( "below-grid" );
			
			if ( $this->isMultistepped() ) 
				$this->bodyForms = array( "above-grid", "steps" );
			else		
				$this->bodyForms = array( "above-grid", "grid" );
		} 
		else 
		{
			$this->formBricks["header"] = "addheader";
			$this->formBricks["footer"] = "addbuttons";
			$this->assignFormFooterAndHeaderBricks( true );
		}
		
		$this->addPageSettings();
	}

	/**
	 * Add js page settings
	 */
	protected function addPageSettings()
	{
		if( $_SESSION[ $this->sessionPrefix . "_recordAdded" ] )
		{
			$this->setProxyValue( $this->shortTableName."_recordAdded", true );
			unset( $_SESSION[ $this->sessionPrefix . "_recordAdded" ] );			
		}
		else
			$this->setProxyValue( $this->shortTableName."_recordAdded", false );
		
		if( $this->mode != ADD_SIMPLE && $this->mode != ADD_POPUP )
			return;
			
		$afterAddAction = $this->getAfterAddAction();
		$this->jsSettings["tableSettings"][ $this->tName ]["afterAddAction"] = $afterAddAction;
		
		if ( $afterAddAction == AA_TO_DETAIL_LIST || $afterAddAction == AA_TO_DETAIL_ADD )
			$this->jsSettings["tableSettings"][ $this->tName ]["afterAddActionDetTable"] = GetTableURL( $this->pSet->getAADetailTable() );	
	}
	
	/**
	 * Get the correct after add action
	 * basing on the table settings
	 * @return Number 
	 */
	protected function getAfterAddAction()
	{
		if( isset( $this->afterAddAction ) && !is_null( $this->afterAddAction ) )
			return $this->afterAddAction;
			
		$action = $this->pSet->getAfterAddAction();
		
		if( $this->mode == ADD_POPUP && $this->pSet->checkClosePopupAfterAdd() 
			|| $action == AA_TO_VIEW && !$this->viewAvailable() 
			|| $action == AA_TO_EDIT && !$this->editAvailable() )
		{	
			$action = AA_TO_LIST;
		}
		
		if( $action == AA_TO_DETAIL_LIST || $action == AA_TO_DETAIL_ADD ) 
		{
			$dTName = $this->pSet->getAADetailTable();
			$dPset = new ProjectSettings( $dTName );
			$dPermissions = $this->getPermissions( $dTName );
			
			$listPageAllowed = $dPset->hasListPage() && $dPermissions["search"];
			
			if( !$dTName || $action == AA_TO_DETAIL_LIST && !$listPageAllowed
				|| $action == AA_TO_DETAIL_ADD && ( !$dPset->hasAddPage() || !$dPermissions["add"] && !$listPageAllowed ) )
			{
				$action = AA_TO_LIST;
			}
		}
			
		$this->afterAddAction = $action;	
		return $this->afterAddAction;
	}
	
	/**
	 * Assign session prefix
	 */
	protected function assignSessionPrefix()
	{	
		if( $this->mode == ADD_DASHBOARD || $this->mode == ADD_MASTER_DASH || ($this->mode == ADD_POPUP || $this->mode == ADD_INLINE || $this->fromDashboard != "" ) && $this->dashTName)
		{
			$this->sessionPrefix = $this->dashTName."_".$this->tName;
			return;
		}
		
		parent::assignSessionPrefix();
		
		if( $this->mode == ADD_ONTHEFLY )
			$this->sessionPrefix.= "_add";
	}
	
	/**
	 * Set template file
	 */
	public function setTemplateFile()
	{
		if( $this->mode == ADD_INLINE )
			$this->templatefile = GetTemplateName($this->shortTableName, "inline_add");
			
		parent::setTemplateFile();
	}	
	
	/**
	 * Get the page's fields list
	 * @return Array
	 */
	protected function getPageFields()
	{
		if( $this->mode == ADD_INLINE )
		{
			if( $this->masterTable && !$this->inlineAddAvailable() && $this->masterPageType == PAGE_ADD )  // #12518
			{
				if( $this->isPD() )
					return $this->pSet->getInlineAddFields();
				
				return $this->pSet->getAddFields();
			}
				
			return $this->pSet->getInlineAddFields();
		}
		
		return $this->pSet->getAddFields();
	}
	
	/**
	 * Process a broken request
	 */
	public static function handleBrokenRequest()
	{
		if( sizeof($_POST) != 0 || !postvalue('submit') )
			return;
		
		if( postvalue("inline") ) 
		{
			$returnJSON = array();
			$returnJSON['success'] = false;
			$returnJSON['message'] = "Terjadi kesalahan";
			$returnJSON['fatalError'] = true;
			echo printJSON($returnJSON);
			exit();
		}
		
		if( postvalue("fly") )
		{
			echo -1;
			exit();
		}
		 
		$_SESSION["message_add"] = "<< "."Error occurred"." >>";		
	}

	/**
	 * Redirect after details saved
	 */
	public function redirectAfterAdd()
	{
		if( isset($_SESSION['after_add_data'][ $this->afterAdd_id ]) && $_SESSION['after_add_data'][ $this->afterAdd_id ] )
		{
			$data = $_SESSION['after_add_data'][ $this->afterAdd_id ];
			$this->keys = $data['keys'];
			$this->newRecordData = $data['avalues'];
		}
		//HeaderRedirect($this->pSet->getShortTableName(), PAGE_ADD);
		$this->afterAddActionRedirect();
		if( $this->eventsObject->exists("AfterAdd") && isset($_SESSION['after_add_data'][ $this->afterAdd_id ]) && $_SESSION['after_add_data'][ $this->afterAdd_id ] )
		{
			$this->eventsObject->AfterAdd( $data['avalues'], $data['keys'], $data['inlineadd'], $this );
		
		}
		unset( $_SESSION['after_add_data'][ $this->afterAdd_id ] );
		
		foreach( is_array($_SESSION['after_add_data']) ? $_SESSION['after_add_data'] : array() as $k => $v)
		{
			if( !is_array($v) or !array_key_exists('time', $v) ) 
			{
				unset( $_SESSION['after_add_data'][ $k ] );
				continue;
			}
			
			if( $v['time'] < time() - 3600 )
				unset($_SESSION['after_add_data'][ $k ]);
		}
	}
	
	/**
	 * Process the page
	 */
	public function process()
	{
		if( strlen($this->afterAdd_id) )
		{
			$this->redirectAfterAdd();
			return;	
		}
		
		//	Before Process event
		if( $this->eventsObject->exists("BeforeProcessAdd") )
			 $this->eventsObject->BeforeProcessAdd( $this );		

		if( $this->action == "added" )
		{
			// insert new record if we have to
			$this->processDataInput();

			$this->readAddValues = !$this->insertedSuccessfully;
			
			if( $this->mode != ADD_SIMPLE && $this->mode != ADD_DASHBOARD && $this->mode != ADD_MASTER_DASH )
			{ 
				$this->reportSaveStatus();
				return; 
			}
			
			if( $this->insertedSuccessfully ) 
			{
				// VBScript fix! don't &&-join these two conditions
				if( $this->afterAddActionRedirect() )
					return; 
			}
		}

		if( $this->captchaExists() )
		{
			$this->displayCaptcha();
		}

		$this->prgReadMessage();
		
		$this->prepareDefvalues();

		if( $this->eventsObject->exists("ProcessValuesAdd") )
			$this->eventsObject->ProcessValuesAdd( $this->defvalues, $this );
		
		$this->prepareReadonlyFields();

		$this->prepareEditControls();

		$this->prepareButtons();

		$this->prepareSteps();

		$this->prepareDetailsTables(); 

		// add button events if exist
		if( $this->mode == ADD_SIMPLE || $this->mode == ADD_ONTHEFLY )
			$this->addButtonHandlers();		
		
		$this->addCommonJs();

		$this->doCommonAssignments();	
		$this->prepareBreadcrumbs();
		$this->prepareCollapseButton();
		
		$this->displayAddPage();
	}

	/**
	 * Add common javascript files and code
	 */
	function addCommonJs() 
	{
		parent::addCommonJs();
		
		/*
		if( $this->allDetailsTablesArr )
		{			
			$this->AddCSSFile("include/jquery-ui/smoothness/jquery-ui.min.css");
			$this->AddCSSFile("include/jquery-ui/smoothness/jquery-ui.theme.min.css");
		}
		*/
	}

	/**
	 * Insert a new record to db
	 */
	protected function processDataInput()
	{
		if( $this->action != "added" )
			return;
			
		$this->buildNewRecordData();
		
		if( !$this->checkCaptcha() )
			return false;
		
		if( !$this->recheckUserPermissions() )
			return;
		
		if( !$this->callBeforeAddEvent() )
			return;

		//add or set updated lat-lng values for all map fileds with 'UpdateLatLng' ticked
		$this->setUpdatedLatLng( $this->newRecordData );
			
		if( !$this->checkDeniedDuplicatedValues() )
			return;
		
		$performingRegularAdd = $this->callCustomAddEvent();
		if( $performingRegularAdd )	
			$this->insertedSuccessfully = DoInsertRecordOnAdd( $this );
		
		if( !$this->insertedSuccessfully )			
			return false;

		if( $this->getAfterAddAction() == AA_TO_ADD )	
			$_SESSION[ $this->sessionPrefix . "_recordAdded" ] = true;
		
		$this->ProcessFiles();
		
		if( $performingRegularAdd )	
			$this->prepareTableKeysAfterInsert();
		
		$this->mergeNewRecordData();
		
		if( $this->auditObj )
			$this->auditObj->LogAdd( $this->tName, $this->newRecordData, $this->keys );

		$this->callAfterSuccessfulSave();

		$this->callAfterAddEvent();	
		
		$this->messageType = MESSAGE_INFO;
		$this->setSuccessfulUpdateMessage();	
	}

	/**
	 * Fill newRecordData, newRecordBlobFields properties
	 */
	protected function buildNewRecordData()
	{
		$avalues = array();
		$blobfields = array();		
		$afilename_values = array();
			
		foreach($this->addFields as $f)
		{
			$control = $this->getControl( $f, $this->id );
			$control->readWebValue($avalues, $blobfields, NULL, NULL, $afilename_values);			
		}		

		$securityType = $this->pSet->getAdvancedSecurityType();
		if( !$this->isAdminTable() && ($securityType == ADVSECURITY_EDIT_OWN || $securityType == ADVSECURITY_VIEW_OWN) )
		{
			$tableOwnerIdField = $this->pSet->getTableOwnerIdField();
			// insert owner id value if it exists an It hasn't already set by user
			if( $this->checkIfToAddOwnerIdValue( $tableOwnerIdField, $avalues[ $tableOwnerIdField ] ) )
				$avalues[ $tableOwnerIdField ] = prepare_for_db( $tableOwnerIdField, $_SESSION["_".$this->tName."_OwnerID"] );		
		}
		$masterTables = $this->pSet->getMasterTablesArr( $this->tName );
		// insert master key value if exists and if not specified
		foreach( $masterTables as $mTableData )
		{
			if( @$_SESSION[$this->sessionPrefix."_mastertable"] == $mTableData["mDataSourceTable"] )
			{
				foreach( $mTableData["detailKeys"] as $idx => $dk )
				{
					$masterkeyIdx = "masterkey".($idx + 1);
					if( strlen( postvalue($masterkeyIdx) ) )
						$_SESSION[ $this->sessionPrefix."_".$masterkeyIdx ] = postvalue($masterkeyIdx);
					
					if( !isset( $avalues[ $dk ] ) || $avalues[ $dk ] == "" )
						$avalues[ $dk ] = prepare_for_db( $dk, $_SESSION[ $this->sessionPrefix."_".$masterkeyIdx ] );				
				}				
			}
		}	
		
		$this->addLookupFilterFieldValue( $avalues, $avalues );

		foreach($afilename_values as $fileFName => $value)
		{
			$avalues[ $fileFName ] = $value;
		}
		
		$this->newRecordData = $avalues;
		$this->newRecordBlobFields = $blobfields;		
	}
	
	/**
	 * Add to the values array the data about lookup filter field
	 * if it hasn't been set yet
	 * @param Array recordData
	 * @param &Array values
	 */
	protected function addLookupFilterFieldValue( $recordData, &$values )
	{
		$lookupPSet = getLookupMainTableSettings($this->tName, $this->lookupTable, $this->lookupField);
		if( !$lookupPSet )
			return;
		
		if( $lookupPSet->useCategory( $this->lookupField ) )
		{
			foreach( $lookupPSet->getParentFieldsData( $this->lookupField ) as $cData )
			{
				if( isset( $this->parentCtrlsData[ $cData['main'] ]) && !isset( $recordData[ $cData['lookup'] ] ) )
					$values[ $cData['lookup'] ]= $this->parentCtrlsData[ $cData['main'] ];
			}
		}
	}
	
	/**
	 * Check is captcha exists on current page
	 *
	 * @intellisense
	 */	
	function captchaExists()
	{
		if ( $this->mode == ADD_ONTHEFLY || $this->mode == ADD_INLINE )
		{
			return false;
		}

		return $this->pSet->hasCaptcha();
	}

	/**
	 * Get captcha field name
	 *
	 * @intellisense
	 */
	function getCaptchaFieldName()
	{		
		return $this->captchaName;
	}

	/**
	 * @return Boolean
	 */
	protected function recheckUserPermissions()
	{
		if( CheckTablePermissions($this->tName, "A") )
			return true;
		
		if( isLoggedAsGuest() || !isLogged() ) 
		{
			$this->setMessage( "Your session has expired." 
				. "<a href='#' id='loginButtonContinue" . $this->id . "'>" . "Login" . "</a>" 
				. " to save data." );
		}
		else
		{
			$this->setMessage( 'You have no permissions to complete this action.' );
		}
		
		return false;	
	}

	/**
	 * Execute before Add event
	 * @return Boolean
	 */
	protected function callBeforeAddEvent()
	{
		if( !$this->eventsObject->exists("BeforeAdd") )
			return true;

		$usermessage = "";		
		$ret = $this->eventsObject->BeforeAdd( $this->newRecordData, $usermessage, $this->mode == ADD_INLINE, $this );
		if( $usermessage != "" )
			$this->setMessage( $usermessage );
		
		return $ret; 	
	}

	/**
	 * Check if some values are duplicated for the fields not allowing duplicates
	 * @return Boolean
	 */
	public function checkDeniedDuplicatedValues()
	{
		$usermessage = "";
		$ret = $this->hasDeniedDuplicateValues( $this->newRecordData, $usermessage );	
		if( $ret )
			$this->setMessage( $usermessage );
		
		return !$ret;	
	}	
	
	/**
	 * #7374
	 * @return Boolean	 
	 */
	protected function callCustomAddEvent()
	{
		if( !$this->eventsObject->exists("CustomAdd") )
			return true;
		
		$keys = array();		
		$customAddError = "";
		$ret = $this->eventsObject->CustomAdd( $this->newRecordData, $keys, $customAddError, $this->mode == ADD_INLINE, $this );
					
		if( strlen($customAddError) > 0 )
		{
			$this->insertedSuccessfully = false;
			$this->setMessage( $customAddError );
			$this->keys = array();
			return false;
		}

		//	do standard Add processing
		if( $ret )
			return true;
		

		$this->insertedSuccessfully = true;
		
		//	fill in key values
		$keyFields = $this->pSet->getTableKeys();
		
		if( is_array($keys) )
		{
			$allKeysFilled = true;
			foreach( $keyFields as $kf )
			{
				if( !strlen( $keys[$kf] ) )
				{
					$allKeysFilled = false;
					break;
				}
			}
			
			if( $allKeysFilled )
				$this->keys = $keys;	
			else
				$this->prepareTableKeysAfterInsert();
		}
		else
		{
			if( $keys && count($keyFields) == 1 )
				$this->keys = array( $keyFields[0] => $keys );
			else
				$this->prepareTableKeysAfterInsert();	
		}	
		
		return false;
	}
	
	/**
	 * Replace key fields values in newRecordData with current key values
	 */
	protected function mergeNewRecordData()
	{
		
		foreach($this->keys as $keyFName => $keyValue)
		{
			$this->newRecordData[ $keyFName ] = $keyValue;
		}			
	}

	/**
	 * Give possibility to all edit controls to clean their data
	 */
	protected function callAfterSuccessfulSave()
	{			
		foreach($this->addFields as $f)
		{
			$this->getControl( $f, $this->id )->afterSuccessfulSave();			
		}		
	}
	
	/**
	 * Execute After Add event or prepare all necessary data for its execution after redirect
	 */
	protected function callAfterAddEvent()
	{
		if( !$this->eventsObject->exists("AfterAdd") )
			return;
			
		if( $this->mode != ADD_MASTER )
		{
			$this->eventsObject->AfterAdd( $this->newRecordData, $this->keys, $this->mode == ADD_INLINE, $this );
			return;
		}
	
		$this->afterAdd_id = generatePassword(20);	
	
		$_SESSION['after_add_data'][ $this->afterAdd_id ] = array(
			'avalues' => $this->newRecordData,
			'keys'=> $this->keys,
			'inlineadd' => $this->mode == ADD_INLINE,
			'time' => time()
		);			
	}

	/**
	 * Set a successful update message.
	 * Add the corresponding edit/view links to the info message
	 */
	protected function setSuccessfulUpdateMessage()
	{
		if( $this->isMessageSet() )
			return;
		
		if( $this->mode == ADD_INLINE ) 
			$infoMessage = ""."Catatan telah ditambah"."";
		else
			$infoMessage = "<strong><<< "."Catatan telah ditambah"." >>></strong>";

		if( $this->mode != ADD_SIMPLE && $this->mode != ADD_MASTER || !count($this->keys) )
		{
			$this->setMessage( $infoMessage );
			return;
		}
		
		$k = 0;
		$keyParams = array();
		$keysArray = array();
		foreach( $this->keys as $idx => $val )
		{
			$keyParams[] = "editid".( ++$k )."=".runner_htmlspecialchars(rawurlencode(@$val));	
			$keysArray[] = $val;
		}						
		$keylink = implode("&", $keyParams);
		
		if ( $this->isBootstrap() && count($keysArray) > 0 && $this->mode == ADD_SIMPLE )
		{
			$_SESSION["successKeys"] = $keysArray;			
		}
		else 
		{
			$infoMessage.= "<br>";
			
			if( $this->editAvailable() )
				$infoMessage.= "&nbsp;<a href='".GetTableLink( $this->pSet->getShortTableName(), "edit", $keylink )."'>"."Edit"."</a>&nbsp;";
				
			if( $this->viewAvailable() )
				$infoMessage.= "&nbsp;<a href='".GetTableLink( $this->pSet->getShortTableName(), "view", $keylink )."'>"."Lihat"."</a>&nbsp;";
		}

		$this->setMessage( $infoMessage );				
	}	

	/**
	 * Print JSON containing a saved record data on ajax-like request
	 */
	protected function reportSaveStatus()
	{			
		echo printJSON( $this->getSaveStatusJSON() );
		exit();		
	}	

	/**
	 * Get an array containing the record save status
	 * @return Array
	 */
	protected function getSaveStatusJSON()
	{
		global $globalEvents;
		$returnJSON = array();
		
		if( $this->action != "added" || $this->mode == ADD_SIMPLE )
			return $returnJSON;

		$returnJSON['success'] = $this->insertedSuccessfully;
		$returnJSON['message'] = $this->message;
		
		if( !$this->isCaptchaOk )
		{
			$returnJSON['wrongCaptchaFieldName'] = $this->getCaptchaFieldName();		
		}
		elseif( $this->mode == ADD_POPUP || $this->mode == ADD_MASTER || $this->mode == ADD_MASTER_POPUP || $this->mode == ADD_MASTER_DASH )
		{
			$sessPrefix = $this->tName . "_" . $this->pageType;
			if( isset($_SESSION["count_passes_captcha"]) || $_SESSION["count_passes_captcha"] > 0 || $_SESSION["count_passes_captcha"] < 5 )
				$returnJSON['hideCaptcha'] = true;						
		}

		if( !$this->insertedSuccessfully )
			return $returnJSON;		
			
		$jsKeys = array();
		$keyFields = $this->pSet->getTableKeys();
		foreach( $keyFields as $idx => $f)
		{
			$jsKeys[ $idx ] = $this->keys[ $f ]; 
		}
			
		if( $this->mode == ADD_ONTHEFLY ) 
		{			
			$addedData = $this->GetAddedDataLookupQuery( false );
			$data =& $addedData[0];	
			
			if( count($data) )
				$respData = array($addedData[1]["linkField"] => @$data[ $addedData[1]["linkFieldIndex"] ], $addedData[1]["displayField"] => @$data[ $addedData[1]["displayFieldIndex"] ]);
			else
				$respData = array($addedData[1]["linkField"] => @$this->newRecordData[ $addedData[1]["linkField"] ], $addedData[1]["displayField"] => @$this->newRecordData[ $addedData[1]["displayField"] ]);
			
			$returnJSON['linkValue'] = $respData[ $addedData[1]["linkField"] ];

			$lookupMainSettings = getLookupMainTableSettings($this->tName, $this->lookupTable, $this->lookupField, $this->lookupPageType);

			if (  in_array( $lookupMainSettings->getViewFormat( $this->lookupField ), array(FORMAT_DATE_SHORT, FORMAT_DATE_LONG, FORMAT_DATE_TIME) ) )
			{			
				$viewContainer = new ViewControlsContainer( $lookupMainSettings, PAGE_LIST, null );
				$ctrlData = array();
				$ctrlData[ $this->lookupField ] = $respData[ $addedData[1]["linkField"] ];
	
				$respData[ $addedData[1]["displayField"] ] = $viewContainer->getControl( $this->lookupField )->getTextValue( $ctrlData );
			}
			
			$returnJSON['displayValue'] = $respData[ $addedData[1]["displayField"] ];		
			
			$returnJSON['keys'] = $jsKeys;
			$returnJSON['vals'] = $respData;			
			$returnJSON['keyFields'] = $keyFields;
		
			return $returnJSON;
		}
		
		//	get current values and show edit controls
		$data = array();
		$haveData = true;
		$linkAndDispVals = array();
		if( count($this->keys) )
		{
			$where = $this->keysSQLExpression($this->keys);						
			
			if( $this->mode == ADD_INLINE && $this->forListPageLookup )
			{
				$addedData = $this->GetAddedDataLookupQuery( true );
				$data =& $addedData[0];
				
				$lookupMainSettings = getLookupMainTableSettings( $this->tName, $this->lookupTable, $this->lookupField, $this->lookupPageType );
				$displayFieldValue = $addedData[0][ $addedData[1]["displayField"] ];
				$linkFieldValue = $addedData[0][ $addedData[1]["linkField" ]];
				
				if (  in_array( $lookupMainSettings->getViewFormat( $this->lookupField ), array(FORMAT_DATE_SHORT, FORMAT_DATE_LONG, FORMAT_DATE_TIME) ) )
				{			
					$viewContainer = new ViewControlsContainer( $lookupMainSettings, PAGE_LIST, null );
					$ctrlData = array();
					$ctrlData[ $this->lookupField ] = $displayFieldValue;
					$displayFieldValue = $viewContainer->getControl( $this->lookupField )->getTextValue( $ctrlData );
				}

				$linkAndDispVals = array( 'linkField' => $linkFieldValue , 'displayField' => $displayFieldValue );
			}
			else
			{
				$strSQL = $this->gQuery->buildSQL_default( array( 
					SecuritySQL("Search", $this->tName), 
					$this->keysSQLExpression($this->keys)
				));
				$data = $this->cipherer->DecryptFetchedArray( $this->connection->query( $strSQL )->fetchAssoc() );
			}
		}
	
		if( !$data )
		{
			$data = $this->newRecordData;
			$haveData = false;
		}
		
		$showDetailKeys = $this->getShowDetailKeys( $data );	
	
		$keyParams = array();
		foreach( $this->pSet->getTableKeys() as $i => $kf )
		{
			$keyParams[] = "key".($i + 1). "=".runner_htmlspecialchars(rawurlencode( @$data[ $kf ] ));
		}
		$keylink = "&" . implode("&", $keyParams);
	
		$showValues = array();
		$showFields = array();
		$showRawValues = array();
		$allFields = $this->pSet->getFieldsList();
		foreach( $allFields as $f )
		{
			$showValues[ $f ] = $this->showDBValue($f, $data, $keylink);
			$showFields[] = $f;
			
			if( IsBinaryType( $this->pSet->getFieldType( $f ) ) )
				$showRawValues[ $f ] = "";
			else
				$showRawValues[ $f ] = runner_substr($data[ $f ], 0, 100);
		}	

		$returnJSON['keys'] = $jsKeys;
		$returnJSON['vals'] = $showValues;
		$returnJSON['fields'] = $showFields;
		$returnJSON['detKeys'] = $showDetailKeys;
		
		$dmapIconsData = $this->getDashMapsIconsData( $data );
		if( count( $dmapIconsData ) )
			$returnJSON['mapIconsData'] = $dmapIconsData;

		$fieldsIconsData = $this->getFieldMapIconsData( $data );
		if( count( $fieldsIconsData ) )
			$returnJSON['fieldsMapIconsData'] = $fieldsIconsData;		
		
		if( $this->mode == ADD_INLINE || $this->mode == ADD_POPUP || $this->mode == ADD_DASHBOARD )
		{		
			$returnJSON['noKeys'] = !$haveData;				
			$returnJSON['keyFields'] = $keyFields;
			$returnJSON['rawVals'] = $showRawValues;
			$returnJSON['hrefs'] = $this->buildDetailGridLinks($showDetailKeys);
			// add link and display value if list page is lookup with search
			if( count( $linkAndDispVals ) )
			{
				$returnJSON['linkValue'] = $linkAndDispVals['linkField'];
				$returnJSON['displayValue'] = $linkAndDispVals['displayField'];
			}
			if( $globalEvents->exists("IsRecordEditable", $this->tName) )
			{ 
				if( !$globalEvents->IsRecordEditable($showRawValues, true, $this->tName) )
					$returnJSON['nonEditable'] = true;
			}
			
			return $returnJSON;
		}		
		
		if( $this->mode == ADD_MASTER || $this->mode == ADD_MASTER_POPUP || $this->mode == ADD_MASTER_DASH ) 
		{
			$_SESSION["message_add"] = $this->message ? $this->message : "";
			$returnJSON['afterAddId'] = $this->afterAdd_id;
			$returnJSON['mKeys'] = $this->getDetailTablesMasterKeys();

			if( $this->mode == ADD_MASTER_POPUP || $this->mode == ADD_MASTER_DASH )
			{
				$returnJSON['noKeys'] = !$haveData;					
				$returnJSON['keyFields'] = $keyFields;
				$returnJSON['rawVals'] = $showRawValues;
				$returnJSON['hrefs'] = $this->buildDetailGridLinks($showDetailKeys);
				
				if( $globalEvents->exists("IsRecordEditable", $this->tName) )
				{
					if( !$globalEvents->IsRecordEditable($showRawValues, true, $this->tName) )
						$returnJSON['nonEditable'] = true;
				}
			}
					
			return $returnJSON;
		}		
	}
	
	/**
	 * @param &Array data
	 * @return Array
	 */
	protected function getShowDetailKeys( &$data )
	{		
		$showDetailKeys = array();			
		foreach( $this->pSet->getDetailTablesArr() as $dt )
		{
			foreach( $dt["masterKeys"] as $idx => $dk)
			{
				$showDetailKeys[ $dt['dDataSourceTable'] ][ "masterkey".($idx + 1) ] = $data[ $dk ];
					
			}
		}	
	
		if( $this->getAfterAddAction() == AA_TO_DETAIL_ADD )
		{	
			$AAdTName = $this->pSet->getAADetailTable();
			$dTUrl = GetTableUrl( $AAdTName );
			
			if( !isset( $showDetailKeys[ $dTUrl ] ) ) 
				$showDetailKeys[ $dTUrl ] = $showDetailKeys[ $AAdTName ];
		}
		
		return $showDetailKeys;
	}
	
	/**
	 * @return Array
	 */
	protected function getDetailTablesMasterKeys()
	{
		if( !$this->isShowDetailTables || $this->mobileTemplateMode() )
			return array();
			
		$dpParams = $this->getDetailsParams( $this->id );
		$data = $this->getAddedRecordFullData();
		
		$mKeysData = array();
		for($i = 0; $i < count($dpParams['ids']); $i++)
		{	
			$mKeysData[ $dpParams['strTableNames'][$i] ] = $this->getMasterKeysData( $dpParams['strTableNames'][$i], $data);
		}

		return $mKeysData;
	}
	
	/**
	 * @return Array
	 */
	protected function getAddedRecordFullData()
	{
		if( count($this->keys) )
		{
			
			$strSQL = $this->gQuery->buildSQL_default( array( 
				SecuritySQL("Search", $this->tName), 
				$this->keysSQLExpression( $this->keys )
			));
			$data = $this->cipherer->DecryptFetchedArray( $this->connection->query( $strSQL )->fetchAssoc() );
		} 
		
		if( !$data )
			$data = $this->newRecordData;
			
		return $data;	
	}
	
	/**
	 * @param String dTableName
	 * @param &Array data
	 */
	protected function getMasterKeysData( $dTableName, &$data )
	{
		$mKeyId = 1;
		$mKeysData = array();
		
		$mKeys = $this->pSet->getMasterKeysByDetailTable( $dTableName );
		foreach($mKeys as $mk)
		{
			if( strlen( $data[ $mk ] ) )
				$mKeysData[ 'masterkey'.$mKeyId++ ] = $data[ $mk ];
			else
				$mKeysData[ 'masterkey'.$mKeyId++ ] = '';
		}

		return $mKeysData;
	}
	
	/**
	 * It redirects to a new page 
	 * according to the add page settings
	 * @return Boolean
	 */
	protected function afterAddActionRedirect()
	{
		if( $this->mode != ADD_SIMPLE ) 
			return false;
			
		switch( $this->getAfterAddAction() )
		{
			case AA_TO_ADD:
				if ( $this->insertedSuccessfully ) 
					return $this->prgRedirect();

				$getParams = "";
				if ( $this->pageName )
				{
					$getParams .= "page=".$this->pageName;
				}				
				HeaderRedirect($this->shortTableName, PAGE_ADD, $getParams);
				return true;					
				
			case AA_TO_LIST:
				if( $this->pSet->hasListPage() )
					HeaderRedirect($this->shortTableName, PAGE_LIST, "a=return");
				else
					HeaderRedirect("menu");
				return true;
				
			case AA_TO_VIEW:
				HeaderRedirect( $this->shortTableName, PAGE_VIEW, $this->getKeyParams() );
				return true;
				
			case AA_TO_EDIT:
				HeaderRedirect( $this->shortTableName, PAGE_EDIT, $this->getKeyParams() );
				return true;	
				
			case AA_TO_DETAIL_LIST:
				$dTName = $this->pSet->getAADetailTable();
				HeaderRedirect( GetTableURL( $dTName ), PAGE_LIST, implode("&", $this->getNewRecordMasterKeys( $dTName ) ). "&mastertable=" .$this->tName );
				return true;
				
			case AA_TO_DETAIL_ADD:
				$_SESSION["message_add"] = $this->message ? $this->message : "";
				
				$dTName = $this->pSet->getAADetailTable();
				HeaderRedirect( GetTableURL( $dTName ), PAGE_ADD, implode("&", $this->getNewRecordMasterKeys( $dTName ) ). "&mastertable=" .$this->tName );
				return true;
				
			default:
				return false;
		}
	}
	
	function getNewRecordMasterKeys( $dTName ) 
	{
		$data = $this->getNewRecordData();
		
		$mKeys = array();
		foreach($this->pSet->getMasterKeysByDetailTable( $dTName ) as $i => $mk)
		{
			$mKeys[] = "masterkey". ($i + 1) . "=" .$data[ $mk ];
		}
		return $mKeys;
	}
	
	
	/**
	 *	POST-REDIRECT-GET 
	 *	Redirect after saving the data to avoid saving again on refresh.
	 */	 
	protected function prgRedirect()
	{
		if( $this->stopPRG )
			return false;
		if( !$this->insertedSuccessfully || $this->mode != ADD_SIMPLE || !no_output_done() )
			return false;
		// saving message		
		$_SESSION["message_add"] = $this->message ? $this->message : "";
		$_SESSION["message_add_type"] = $this->messageType;
		// redirect
		$getParams = "";
		if ( $this->pageName )
		{
			$getParams = "page=".$this->pageName;
		}				
		HeaderRedirect( $this->pSet->getShortTableName(), $this->pageType, $getParams );
		// turned on output buffering, so we need to stop script
		return true;	
	}	

	/**
	 *	POST-REDIRECT-GET 
	 *	Read the saved message on the GET step.
	 */	
	protected function prgReadMessage()
	{			
		// for PRG rule, to avoid POSTDATA resend. Saving mess in session
		if( $this->mode == ADD_SIMPLE && isset( $_SESSION["message_add"] ) )
		{
			$this->message = $_SESSION["message_add"];
			$this->messageType = $_SESSION["message_add_type"];
			unset( $_SESSION["message_add"] );
		}	
	}
	
	/**
	 * @return Array
	 */
	public function getCurrentRecord()
	{		
		$data = array();
		if ( $this->masterTable && count($this->masterKeysReq) > 0 )
		{
			foreach ($this->detailKeysByM  as $key => $detKey )
			{
				$data[$detKey] = $this->masterKeysReq[$key+1];
			}
		}

		return $data;
	}

	/**
	 * Set the defvalues property
	 */
	protected function prepareDefvalues()
	{
		if( ( array_key_exists("copyid1", $_REQUEST) || array_key_exists("editid1", $_REQUEST) ) && $this->mode != ADD_DASHBOARD )
		{
			//	copy record
			$copykeys = array();
			$keyFields = $this->pSet->getTableKeys();
			$prefix = array_key_exists("copyid1", $_REQUEST) ? "copyid" : "editid";
			
			foreach( $keyFields as $idx => $k )
			{
				$copykeys[ $k ] = postvalue( $prefix . ($idx + 1) );
			}

			$strSQL = $this->gQuery->buildSQL_default( array( 
				SecuritySQL("Search", $this->tName), 
				$this->keysSQLExpression( $copykeys )
			));
			
			
			$this->defvalues = $this->cipherer->DecryptFetchedArray( $this->connection->query( $strSQL )->fetchAssoc() );
			if( !$this->defvalues )
				$this->defvalues = array();
			
			if ( $prefix != "copyid" )
			{
				// clear key fields	
				foreach( $keyFields as $idx => $k )
				{
					$this->defvalues[ $k ] = "";
				}
			}
			
			foreach( $this->addFields as $f )
			{
				if( $this->pSet->getEditFormat( $f ) == EDIT_FORMAT_FILE ) //#10023
					$this->defvalues[ $f ] = $this->getControl($f, $this->id)->getFieldValueCopy( $this->defvalues[ $f ] );
			}
			
			// call CopyOnLoad event
			if( $this->eventsObject->exists("CopyOnLoad") )
				$this->eventsObject->CopyOnLoad( $this->defvalues, $this->keysSQLExpression( $copykeys ), $this );
		}
		else
		{
			foreach( $this->addFields as $f )
			{				
				$defaultValue = GetDefaultValue($f, PAGE_ADD);
				if( strlen($defaultValue) )
					$this->defvalues[ $f ] = $defaultValue;	
			}
		}

		$securityType = $this->pSet->getAdvancedSecurityType();
		if( !$this->isAdminTable() && ($securityType == ADVSECURITY_EDIT_OWN || $securityType == ADVSECURITY_VIEW_OWN) )
		{
			$tableOwnerIdField = $this->pSet->getTableOwnerIdField();
			// insert default owner id value if exists			
			if( $this->checkIfToAddOwnerIdValue( $tableOwnerIdField, '' ) )	
				$this->defvalues[ $tableOwnerIdField ] = prepare_for_db( $tableOwnerIdField, $_SESSION["_".$this->tName."_OwnerID"] );		
		}

		$masterTables = $this->pSet->getMasterTablesArr( $this->tName );
		// set default values for the foreign keys
		foreach( $masterTables as $mTableData )
		{
			if( @$_SESSION[$this->sessionPrefix."_mastertable"] == $mTableData["mDataSourceTable"] )
			{
				foreach( $mTableData["detailKeys"] as $idx => $dk )
				{
					$masterkeyIdx = "masterkey".($idx + 1);
					if( strlen( postvalue($masterkeyIdx) ) )
						$_SESSION[ $this->sessionPrefix."_".$masterkeyIdx ] = postvalue($masterkeyIdx);
					
					if( $this->masterPageType != PAGE_ADD )
						$this->defvalues[ $dk ] = @$_SESSION[ $this->sessionPrefix."_".$masterkeyIdx ];					
				}				
			}
		}

		$this->addLookupFilterFieldValue( $this->newRecordData, $this->defvalues );

		if( $this->readAddValues )
		{
			foreach( $this->addFields as $fName )
			{
				$editFormat = $this->pSet->getEditFormat($fName);
				if( $editFormat != EDIT_FORMAT_DATABASE_FILE && $editFormat != EDIT_FORMAT_DATABASE_IMAGE && $editFormat != EDIT_FORMAT_FILE )
					$this->defvalues[ $fName ] = @$this->newRecordData[ $fName ];
			}
		}			
	}

	/**
	 * Set read-only fields
	 */
	protected function prepareReadonlyFields()
	{
		foreach( $this->addFields as $f )
		{
			if( $this->pSet->getEditFormat( $f ) == EDIT_FORMAT_READONLY )
				$this->readOnlyFields[ $f ] = $this->showDBValue($f, $this->defvalues);
		}
	}

	/**
	 * Assign buttons xt variables
	 */
	protected function prepareButtons()
	{
		if( $this->mode == ADD_INLINE )
			return;
		
		$this->xt->assign("save_button", true);

		$addStyle = "";
		if ( $this->isMultistepped() )
		{
			$addStyle = " style=\"display: none;\"";	
		}
		
		//	legacy assignment used in the Invoice template
		$this->xt->assign("savebutton_attrs", "id=\"saveButton".$this->id."\"" . $addStyle );
		
		if( $this->mode == ADD_SIMPLE )
		{
			//	back to list/menu buttons
			if( $this->pSet->hasListPage() )
			{
				$this->xt->assign("back_button", true);
				$this->xt->assign("backbutton_attrs", "id=\"backButton".$this->id."\"");				
			}
			else if( $this->isShowMenu() )
			{
				$this->xt->assign("back_button", true);			
				$this->xt->assign("backbutton_attrs", "id=\"backToMenuButton".$this->id."\"");				
			}
		}
		
		if( $this->mode == ADD_DASHBOARD )
		{		
			$this->xt->assign("reset_button", true);			
			return;
		}
		
		if ( $this->isBootstrap() && isset($_SESSION["successKeys"]) )
		{
			$dataKeysAttr = 'data-keys="'.runner_htmlspecialchars(my_json_encode($_SESSION["successKeys"])).'"';
			unset($_SESSION["successKeys"]);

			if( $this->viewAvailable() )
			{
				$this->xt->assign("view_page_button", true);
				$this->xt->assign("view_page_button_attrs", 'id="viewPageButton'.$this->id.'" '.$dataKeysAttr);
			}
			if( $this->editAvailable() )
			{
				$this->xt->assign("edit_page_button", true);
				$this->xt->assign("edit_page_button_attrs", 'id="editPageButton'.$this->id.'" '.$dataKeysAttr);
			}
		}
		
		if( $this->mode != ADD_ONTHEFLY && $this->mode != ADD_POPUP && $this->mode != ADD_MASTER_DASH )
		{			
			if( $this->pSet->hasListPage() )
				$this->xt->assign("back_button", true);
			else if( $this->isShowMenu() )
				$this->xt->assign("backToMenu_button", true);
		}
		else
			$this->xt->assign("cancel_button", true);
	}

	/**
	 * Prepare edit controls 
	 */
	protected function prepareEditControls()
	{
		$controlFields = $this->addFields;
		
		if( $this->mode == ADD_INLINE ) //#9069
			$controlFields = $this->removeHiddenColumnsFromInlineFields( $controlFields, $this->screenWidth, $this->screenHeight, $this->orientation );			
		
		$control = array();
		
		foreach($controlFields as $fName)
		{
			$gfName = GoodFieldName($fName);
			$isDetKeyField = in_array($fName, $this->detailKeysByM);
			
			
			$controls = array();
			$controls["controls"] = array();
			$controls["controls"]["id"] = $this->id;
			$controls["controls"]["ctrlInd"] = 0;
			$controls["controls"]["fieldName"] = $fName;

			if ( in_array($fName, $this->errorFields))
			{
				$controls["controls"]["isInvalid"] = true;
			}

			$parameters = array();
			$parameters["id"] = $this->id;
			$parameters["ptype"] = PAGE_ADD;
			$parameters["field"] = $fName;
			$parameters["value"] = @$this->defvalues[ $fName ];
			$parameters["pageObj"] = $this;
			
			if( !$isDetKeyField )
			{				
				$parameters["validate"] = $this->pSet->getValidation($fName);
				
				if( $this->pSet->isUseRTE($fName) )
					$_SESSION[ $this->sessionPrefix."_".$fName."_rte" ] = @$this->defvalues[ $fName ];				
			}
			
			//if richEditor for field
			if( $this->pSet->isUseRTE($fName) )
			{
				$parameters["mode"] = "add";	
				$controls["controls"]["mode"] = "add";
			}
			else
			{
				$controlMode = $this->mode == ADD_INLINE ? "inline_add" : "add";
				$parameters["mode"] = $controlMode;					
				$controls["controls"]["mode"] = $controlMode;
			}
			
			if( $isDetKeyField && $fName )
			{
				$controls["controls"]["value"] = @$this->defvalues[ $fName ];
				
				$parameters["extraParams"] = array();
				$parameters["extraParams"]["getDetKeyReadOnlyCtrl"] = true;
			
				// to the ReadOnly control show the detail key control's value	
				$this->readOnlyFields[ $fName ] = $this->showDBValue($fName, $this->defvalues);				
			}
			
			if ( $this->isBootstrap() )
			{			
				$firstElementId = $this->getControl($fName, $this->id)->getFirstElementId();
				if ( $firstElementId )
				{
					$this->xt->assign("labelfor_" . goodFieldName($fName), $firstElementId);
				}
			}
			
			$this->xt->assign_function( $gfName."_editcontrol", "xt_buildeditcontrol", $parameters );
			 			
			$preload = $this->fillPreload($fName, $controlFields, $this->defvalues);
			if( $preload !== false )
			{
				$controls["controls"]["preloadData"] = $preload;
				if( !@$this->defvalues[ $fName ] && count($preload["vals"]) > 0 )
					$this->defvalues[ $fName ] = $preload["vals"][0];
			}
			
			$this->fillControlsMap( $controls );
			$this->fillFieldToolTips( $fName );
			$this->fillControlFlags( $fName );
			
			// fill special settings for a time picker
			if( $this->pSet->getEditFormat($fName) == "Time" )
				$this->fillTimePickSettings( $fName, @$this->defvalues[$fName] );
		}		
	}

	/**
	 * Set details preview on the add master page 
	 */
	protected function prepareDetailsTables()
	{		
		if( !$this->isShowDetailTables 
			|| $this->mode != ADD_SIMPLE && $this->mode != ADD_POPUP  &&  $this->mode != ADD_DASHBOARD && $this->mode != ADD_MASTER_DASH 
			|| $this->mobileTemplateMode() )
		{
			return;
		}
		
		$dpParams = $this->getDetailsParams( $this->id );
		
		$this->jsSettings['tableSettings'][ $this->tName ]['isShowDetails'] = count( $dpParams ) > 0;
		$this->jsSettings['tableSettings'][ $this->tName ]['dpParams'] = array('tableNames' => $dpParams['strTableNames'], 'ids' => $dpParams['ids']);			
		
		if( !count($dpParams['ids']) )
			return;
			
		$this->xt->assign("detail_tables", true);
		
		for($d = 0; $d < count($dpParams['ids']); $d++)
		{	
			$this->setDetailPreview( "list", $dpParams['strTableNames'][ $d ], $dpParams['ids'][ $d ], $this->defvalues );
			$this->displayDetailsButtons( $dpParams['type'][ $d ], $dpParams['strTableNames'][ $d ], $dpParams['ids'][ $d ] );
		}
	}

	/**
	 *
	 */
	protected function displayDetailsButtons( $dpType, $dpTableName, $dpId )
	{
		if ( !CheckTablePermissions($dpTableName, "S") )
			return;
		
		$listPageObject = $this->getDetailsPageObject( $dpTableName, $dpId );
		
		if ( $this->isBootstrap() && $this->isBootstrap() )
		{
			$listPageObject->assignButtonsOnMasterEdit( $this->xt );
		}
	}

	/**
	 * Assign basic page's xt variables
	 */
	protected function doCommonAssignments()
	{
		$this->xt->assign("id", $this->id);

		if ( $this->isBootstrap() )
		{
			if ( $this->mode === ADD_SIMPLE )
			{
				$this->headerCommonAssign();
			}
			else
			{
				$this->xt->assign("menu_chiddenattr", "data-hidden" );
			}
		}

		$this->setLangParams();
		
		$this->xt->assign("message_block", true);
		
		if( $this->isMessageSet() )
		{
			if( !$this->isBootstrap() )
			{
				$mesClass = $this->messageType == MESSAGE_ERROR ? "message rnr-error" : "message" ;
				$this->xt->assign("message", "<div class='".$mesClass."' >" . $this->message . "</div>" );
			} 
			else
			{
				$this->xt->assign("message", $this->message );
				$this->xt->assign("message_class", $this->messageType == MESSAGE_ERROR ? "alert alert-danger" : "alert alert-success" );
			}
		}
		else
		{		
			$this->hideElement("message");	
		}

		if( $this->mode != ADD_INLINE )
			$this->assignAddFieldsBlocksAndLabels();
		
		if( $this->mode == ADD_SIMPLE )
		{
			$this->assignBody();		
			$this->xt->assign("flybody", true);				
		}

		if( $this->mode == ADD_ONTHEFLY /*|| $this->mode == ADD_MASTER*/ || $this->mode == ADD_POPUP || $this->mode == ADD_DASHBOARD || $this->mode == ADD_MASTER_DASH )
		{ 
			$this->xt->assign("body", true);
			$this->xt->assign("footer", false);
			$this->xt->assign("header", false);
			$this->xt->assign("flybody", $this->body);		
		}			

	}

	/**
	 * Assign add fields' blocks and labels variables
	 */
	public function assignAddFieldsBlocksAndLabels()
	{		
		foreach($this->addFields as $fName)
		{
			$gfName = GoodFieldName($fName);
			
			$this->xt->assign($gfName."_fieldblock", true);
			$this->xt->assign($gfName."_tabfieldblock", true);
			
			if( $this->is508 && !$this->isBootstrap() )
				$this->xt->assign_section($gfName."_label", "<label for=\"" . $this->getInputElementId( $fName ) . "\">", "</label>");
		}			
	}	
	
	/**
	 * Display the add page basing on its mode
	 */
	protected function displayAddPage()
	{
		$templatefile = $this->templatefile;
		
		if( $this->eventsObject->exists("BeforeShowAdd") )
			$this->eventsObject->BeforeShowAdd($this->xt, $templatefile, $this);		

		$this->displayMasterTableInfo();
		// invoked after displayMasterTableInfo to add master viewcontrols maps
		$this->fillSetCntrlMaps();

		if( $this->mode == ADD_SIMPLE /*|| $this->mode ==ADD_MASTER */)	
		{
			$this->display( $templatefile );
			return;
		}

		if( $this->mode == ADD_ONTHEFLY || $this->mode == ADD_POPUP || $this->mode == ADD_DASHBOARD || $this->mode == ADD_MASTER_DASH )
		{
			$this->displayAJAX($templatefile, $this->id + 1);
			exit();
		}

		if( $this->mode == ADD_INLINE )
		{
			$returnJSON = array();						
			
			$this->xt->load_template( $templatefile );
			
			$returnJSON["html"] = array();
			foreach($this->addFields as $fName)
			{
				$returnJSON["html"][ $fName ] = $this->xt->fetchVar(GoodFieldName($fName)."_editcontrol");	
			}	
			
			global $pagesData;
			$returnJSON["pagesData"] = $pagesData;

			$returnJSON['settings'] = $this->jsSettings;	
			$returnJSON['controlsMap'] = $this->controlsHTMLMap;
			$returnJSON['viewControlsMap'] = $this->viewControlsHTMLMap;
			
			$returnJSON["additionalJS"] = $this->grabAllJsFiles();
			$returnJSON["additionalCSS"] = $this->grabAllCSSFiles();
			
			echo printJSON($returnJSON); 
			exit();
		}
	}
	
	/**
	 * Get extra JSON params to display the page on AJAX-like request
	 * @return Array
	 */
	protected function getExtraAjaxPageParams()
	{
		return $this->getSaveStatusJSON();
	}
	
	/**
	 * @param Boolean forLookup
	 * @return Array
	 */
	protected function GetAddedDataLookupQuery( $forLookup )
	{	
		$lookupMainSettings = getLookupMainTableSettings($this->tName, $this->lookupTable, $this->lookupField, $this->lookupPageType);
		if( !$lookupMainSettings )
			return array();
	
		$LookupSQL = "";
		$mainTable = $lookupMainSettings->getTableName();
		$linkFieldName = $lookupMainSettings->getLinkField( $this->lookupField );
		$dispfield = $lookupMainSettings->getDisplayField( $this->lookupField );
		
		$lookupQueryObj = $this->pSet->getSQLQuery()->CloneObject();
		if( $lookupMainSettings->getCustomDisplay( $this->lookupField ) )
			$lookupQueryObj->AddCustomExpression($dispfield, $this->pSet, $mainTable, $this->lookupField);
			
		$data = array();
		$lookupIndexes = array("linkFieldIndex" => 0, "displayFieldIndex" => 0);
		if( count($this->keys) )
		{
			$LookupSQL = $lookupQueryObj->buildSQL_default( KeyWhere($this->keys, $this->tName ) );
				
			$lookupIndexes = GetLookupFieldsIndexes($lookupMainSettings, $this->lookupField);
			LogInfo($LookupSQL);
			
			if( $forLookup )
			{
				$data = $this->cipherer->DecryptFetchedArray( $this->connection->query( $LookupSQL )->fetchAssoc() );
			}
			else if( $LookupSQL )
			{
				$data = $this->connection->query( $LookupSQL )->fetchNumeric();
				$data[ $lookupIndexes["linkFieldIndex"] ] = $this->cipherer->DecryptField($linkFieldName, $data[ $lookupIndexes["linkFieldIndex"] ]);
				$data[ $lookupIndexes["displayFieldIndex"] ] = $this->cipherer->DecryptField($dispfield, $data[ $lookupIndexes["displayFieldIndex"] ]);		
			}
		}

		return array($data, array("linkField" => $linkFieldName, "displayField" => $dispfield
			, "linkFieldIndex" => $lookupIndexes["linkFieldIndex"], "displayFieldIndex" => $lookupIndexes["displayFieldIndex"]));
	}

	/**
	 * Check if to add session owner id value
	 * @param String ownerField
	 * @param String currentValue
	 * @return Boolean
	 */
	public function checkIfToAddOwnerIdValue( $ownerField, $currentValue )
	{
		return $this->pSet->getOriginalTableName() == $this->pSet->getOwnerTable( $ownerField ) && !$this->isAutoincPrimaryKey( $ownerField ) 
			&& ( !CheckTablePermissions($this->tName, 'M') || !strlen($currentValue) );
	}

	/**
	 * Check if field is auto-incremented primary key
	 * @param String field
	 * @return Boolean
	 */
	protected function isAutoincPrimaryKey( $field )
	{
		$keyFields = $this->pSet->getTableKeys();
		return count($keyFields) == 1 && in_array($field, $keyFields) && $this->pSet->isAutoincField( $field );
	}

	/**
	 * Set the keys array
	 */
	protected function prepareTableKeysAfterInsert()
	{
		$table = $this->pSet->getOriginalTableName();
		$keyFields = $this->pSet->getTableKeys();
		
		foreach($keyFields as $k)
		{
			if( array_key_exists($k, $this->newRecordData) )
			{
				$this->keys[ $k ] = $this->newRecordData[ $k ];	
			}
			elseif( $this->pSet->isAutoincField($k) )
			{
				$oraSequenceName = $this->pSet->getOraSequenceName($k);
				$this->keys[ $k ] = $this->connection->getInsertedId( $k, $table, $oraSequenceName );
			}
		}
	}
	
	/**
	 * Set the page's message
	 * @param String message
	 */
	public function setMessage( $message ) 
	{
		$this->message = $message;
	}
	
	/**
	 * Check if the page's message is set
	 * @return Boolean
	 */
	protected function isMessageSet()
	{
		return strlen( $this->message ) > 0;
	}
	
	/**
	 * Set a database error message
	 * @param String message
	 */
	public function setDatabaseError( $message )
	{
		if( $this->mode != ADD_INLINE )
		{
			$this->message = "<strong>&lt;&lt;&lt; "."Catatan TIDAK ditambahkan"."</strong> &gt;&gt;&gt;<br><br>".$message;
		}
		else
		{
			$this->message = "Catatan TIDAK ditambahkan".". ".$message;
		}
		
		$this->messageType = MESSAGE_ERROR;
	}
	
	/**
	 * @return Array
	 */
	public function getNewRecordData()
	{
		return $this->newRecordData;
	}
	
	/**
	 * @return Array
	 */	
	public function getBlobFields()
	{
		return $this->newRecordBlobFields;
	}

	/**
	 * @param String fName
	 * @return Boolean
	 */
	protected function checkFieldOnPage( $fName )
	{
		if( $this->mode == ADD_INLINE )
		return $this->pSet->appearOnInlineAdd( $fName );
			
		return $this->pSet->appearOnAddPage( $fName );			
	}
	
	/**
	 * @param String table
	 */
	public static function processAddPageSecurity( $table )
	{
		//	user has necessary permissions
		if( Security::checkPagePermissions( $table, "A" ) )
			return true;
			
		// display entered data. Give the user chance to relogin. Do nothing for now.
		if( postvalue("a") == "added" )
			return true;
		
		//	page can not be displayed. Redirect or return error
		
		//	return error if the page is requested by AJAX
		$pageMode = AddPage::readAddModeFromRequest();
		if( $pageMode != ADD_SIMPLE )
		{
			Security::sendPermissionError();
			return false;
		}
		
		// The user is logged in but lacks necessary permissions
		// redirect to List page or Menu.
		if( isLogged() && !isLoggedAsGuest() )
		{
			Security::redirectToList( $table );
			return false;
		}

		redirectToLogin();
		return false;
	}
	
	public static function readAddModeFromRequest()
	{
		$editType = postvalue("editType");
		
		if( $editType == "inline" )
			return ADD_INLINE;
		elseif( $editType == ADD_POPUP )
			return ADD_POPUP;
		elseif( $editType == ADD_MASTER )
			return ADD_MASTER;
		elseif( $editType == ADD_MASTER_POPUP )
			return ADD_MASTER_POPUP;
		elseif( $editType == ADD_MASTER_DASH )
			return ADD_MASTER_DASH;
		elseif( $editType == ADD_ONTHEFLY )
			return ADD_ONTHEFLY;
		elseif( postvalue("mode") == "dashrecord" )
			return ADD_DASHBOARD;
		else
			return ADD_SIMPLE;
	}

	function editAvailable() {
		return !$this->dashElementData && parent::editAvailable();
	}
	
	function viewAvailable() {
		return !$this->dashElementData && parent::viewAvailable();
	}
	
	/**
	 *	API
	 */
	public function setMessageType( $type ) 
	{
		$this->messageType = $type;
	}

	function element2Item( $name ) {
		if( $name == "message" ) {
			return array( "add_message" );
		}
		return parent::element2Item( $name );
	}
	
	protected function checkShowBreadcrumbs() 
	{
		return $this->mode == ADD_SIMPLE;
	}	
	
	function createProjectSettings() {
		
		$this->pSet = new ProjectSettings($this->tName, $this->pageType, $this->pageName, $this->pageTable );
		if( $this->mode == ADD_INLINE && $this->pSet->getPageType() !== PAGE_LIST ) 
		{
			$pageName = $this->pSet->getDefaultPage( "list" );
			$this->pSet = new ProjectSettings($this->tName, $this->pageType, $pageName, $this->pageTable );
		} 
		else if( $this->mode != ADD_INLINE && $this->pSet->getPageType() !== PAGE_ADD )
		{
			$this->pSet = new ProjectSettings($this->tName, $this->pageType, null, $this->pageTable );
		}
	}

}
?>