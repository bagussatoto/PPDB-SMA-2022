<?php
class EditSelectedPage extends EditPage
{
	public $rowIds = array();
	public $parsedSelection = array();
	
	public $updSelectedFields = null;
	public $selectedFields = null;

	public $nSelected = 0;
	public $nUpdated = 0;

	/**
	 *	The record data currently being updated
	 *	@type Array
	 */
	public $recordBeingUpdated;
	
	public $currentWhereExpr;
	
	/**
	 *	The record data currently being updated
	 */
	public $recordCount = 0;

	public $messages = array();

	protected $inlineReportData = array();
	
	
	/**
	 * @constructor
	 */
	function __construct(&$params)
	{
		parent::__construct($params);
		
		$keyFields = $this->pSet->getTableKeys();
		//	parse selection
		foreach( $this->selection as $s )
		{
			$arr = explode("&", refine($s));
			if( count($arr) != count($this->pSet->getTableKeys()) )
				continue;
			
			foreach($arr as $i => $v)
			{
				$parsed[ $keyFields[$i] ] = rawurldecode( $v );
			}
			$this->parsedSelection[] = $parsed;
		}
	}
	
	/**
	 * Get the page's fields list
	 * @return Array
	 */
	protected function getPageFields()
	{
		if( $this->updSelectedFields === NULL )
		{
			$this->updSelectedFields =  array_diff( $this->pSet->getUpdateSelectedFields(), $this->pSet->getTableKeys() );
			$denyDuplicateFields = array();
			foreach( $this->updSelectedFields as $f )
			{
				if( !$this->pSet->allowDuplicateValues($f) )
					$denyDuplicateFields[] = $f;
			}
			$this->updSelectedFields =  array_diff( $this->updSelectedFields, $denyDuplicateFields );
			
			$updateFields = array();
			foreach( $this->updSelectedFields as $f )
			{
				$editFormat = $this->pSet->getEditFormat( $f );
				if ( $editFormat != EDIT_FORMAT_FILE )
				{
					$updateFields[] = $f;
				}
			}
			$this->updSelectedFields = $updateFields;			
		}
		
		return $this->updSelectedFields;
	}

	/**
	 * Set keys values
	 * @param Array keys
	 */
	public function setKeys($keys) //?
	{
		$this->keys = $keys;
	}


	/**
	 * Get the correct after edit action
	 * basing on the table settings
	 * @return Number
	 */
	protected function getAfterEditAction()
	{
		if( isset( $this->afterEditAction ) && !is_null( $this->afterEditAction ) )
			return $this->afterEditAction;

		$action = $this->pSet->getAfterEditAction();
		if( $action != AE_TO_LIST )
			$action = AE_TO_EDIT;
		
		if( $this->isPopupMode() && $this->pSet->checkClosePopupAfterEdit() )
			$action = AE_TO_LIST;

		$this->afterEditAction = $action;
		return $this->afterEditAction;
	}	
	
	public function init()
	{
		$this->hideItemType("edit_view");		
		parent::init();
	}

	/**
	 *
	 */
	public function process()
	{
		if( $this->action == "edited" )
		{
			$this->processDataInput();
			$this->readEditValues = !$this->updatedSuccessfully;

			if( $this->isPopupMode() )
			{
				$this->reportInlineSaveStatus();
				return;
			}

			if( $this->updatedSuccessfully )
			{
				if( $this->afterEditActionRedirect() )
					return;
			}
			$this->cachedRecord = null;
		}

		if( $this->captchaExists() )
		{
			$this->displayCaptcha();
		}

		$this->prgReadMessage();

		//	get the record to edit
		if( !$this->readRecord() )
			return;
		
		$this->setPageTitle( GetTableCaption(GoodFieldName( $this->tName ) ) );

		$this->prepareReadonlyFields();

		$this->doCommonAssignments();
		$this->prepareButtons();
		$this->prepareSteps();
		$this->prepareEditControls();

		$this->prepareJsSettings();
		$this->addButtonHandlers();
		$this->addCommonJs();

		$this->fillSetCntrlMaps();

		$this->displayEditPage();
	}
	
	
	/**
	 * Add table settings
	 */
	protected function prepareJsSettings()
	{
		$this->jsSettings["tableSettings"][ $this->tName ]["selection"] = $this->getSelection();
		$this->jsSettings["tableSettings"][ $this->tName ]["keyFields"] = $this->pSet->getTableKeys();
		$this->jsSettings["tableSettings"][ $this->tName ]["masterKeys"] = $this->getMarkerMasterKeys( $this->getCurrentRecordInternal() );
		$this->jsSettings["tableSettings"][ $this->tName ]["captchaEditFieldName"] = $this->getCaptchaFieldName();

	}

	/**
	 * Assign basic page's xt variables
	 */
	protected function doCommonAssignments()
	{
		$this->message = $this->getMessages(); // ?
		parent::doCommonAssignments();
	}

	/**
	 * No details preview on the Edit Selected page
	 */
	protected function prepareDetailsTables()
	{
	}

	/**
	 * No next/prev buttons on the Edit Selected page
	 */
	protected function prepareNextPrevButtons() 
	{
	}
	
	/**
	 * Assign buttons xt variables
	 */
	protected function prepareButtons()
	{
		parent::prepareButtons();
		$this->xt->assign("save_button", false);
		$this->xt->assign("view_page_button", false );
		
		$this->xt->assign("updsel_button", true);
		$this->xt->assign("updselbutton_attrs", "id=\"saveButton".$this->id."\"" );
		
		if ( $this->isPD() )
		{
			foreach ( $this->pSet->updateSelectedButtons() as $itemId => $mLString )
			{			
				$label = str_replace( "%n%", $this->nSelected, GetMLString($mLString) );
				$this->xt->assign($itemId."_label", $label );
			}	
		}
		else
		{
			$label = str_replace( "%n%", $this->nSelected, "Update %n% records" );
			$this->xt->assign("update_selected", $label );
		}
		

		
	}

	/**
	 *	Locks record for editing.
	 * Returns false if the page can not continue processing. True otherwise.
	 */
	protected function lockRecord()
	{
		//	ignore locking
		return true;
	}

	/**
	 * Print JSON containing a saved record data on ajax-like request
	 */
	protected function reportInlineSaveStatus()
	{
		$returnJSON = $this->inlineReportData;
		$returnJSON["success"] = $this->updatedSuccessfully;
		
		if( !$this->isCaptchaOk )
		{
			$returnJSON['wrongCaptchaFieldName'] = $this->getCaptchaFieldName();
		}

		$returnJSON['message'] = $this->getMessages();

		echo printJSON( $returnJSON );
		exit();
	}

	/**
	 * Get an array containing the record save status
	 * @param Array
	 * @return Array
	 */
	protected function getRowSaveStatusJSON( $keys )
	{
		$returnJSON = array();

		if( $this->action != "edited" || $this->isSimpleMode() )
			return $returnJSON;

		$returnJSON['fNamesSelected'] = array_keys( $this->newRecordData );	
		$returnJSON['message'] = $this->getMessages();
		$returnJSON['lockMessage'] = $this->lockingMessageText;

		if( !$this->isCaptchaOk )
			$returnJSON['wrongCaptchaFieldName'] = $this->getCaptchaFieldName();

		//	successful update. Return new keys and field values
		$data = $this->getRecordByKeys( $keys );
		if( !$data )
			$data = $this->newRecordData;

		//	details tables keys
		$returnJSON['detKeys'] = array();
		foreach( $this->pSet->getDetailTablesArr() as $dt )
		{
			$dkeys = array();
			foreach( $dt["masterKeys"] as $idx => $mk )
			{
				$dkeys[ "masterkey".($idx + 1) ] = $data[ $mk ];
			}
			$returnJSON['detKeys'][ $dt['dDataSourceTable'] ] = $dkeys;
		}

		//	prepare field values
		//	keys
		$keyParams = array();
		foreach( $this->pSet->getTableKeys() as $i => $k )
		{
			$keyParams[] = "key" . ($i + 1) . "=" . rawurldecode( $keys[ $k ] );
		}
		$keylink = "&" . implode("&", $keyParams);

		//	values
		$values = array();
		$rawValues = array();
		$fields = $this->pSet->getFieldsList();
		foreach( $fields as $f )
		{
			$value = $this->showDBValue( $f, $data, $keylink );
			$values[ $f ] = $value;
			if( IsBinaryType( $this->pSet->getFieldType( $f ) ) )
				$rawValues[ $f ] = "";
			else
				$rawValues[ $f ] = runner_substr($data[ $f ], 0, 100);
		}

		$returnJSON['keys'] = $this->jsKeys;
		$returnJSON['masterKeys'] = $this->getMarkerMasterKeys($data);
		$returnJSON['keyFields'] = $this->pSet->getTableKeys();
		$returnJSON['oldKeys'] = array();
		//	add old keys
		$i = 0;
		foreach($this->oldKeys as $field => $value)
		{
			$returnJSON['oldKeys'][ $i++ ] = $value;
		}

		$returnJSON['vals'] = $values;
		$returnJSON['fields'] = $fields;
		$returnJSON['rawVals'] = $rawValues;
		$returnJSON['hrefs'] = $this->buildDetailGridLinks( $returnJSON['detKeys'] );

		//	the record might become non-editable after updating
		if( !$this->IsRecordEditable( false ) )
			$returnJSON['nonEditable'] = true;

		$dmapIconsData = $this->getDashMapsIconsData( $data );
		if( count( $dmapIconsData ) )
			$returnJSON['mapIconsData'] = $dmapIconsData;	

		$fieldsIconsData = $this->getFieldMapIconsData( $data );
		if( count( $fieldsIconsData ) )
			$returnJSON['fieldsMapIconsData'] = $fieldsIconsData;
			
		return $returnJSON;
	}

	/**
	 * It redirects to a new page
	 * according to the edit page settings
	 * @return Boolean
	 */
	protected function afterEditActionRedirect()
	{
		if( $this->isPopupMode() )
			return false;

		switch( $this->getAfterEditAction() )
		{
			case AE_TO_EDIT:
				return $this->prgRedirect();

			case AE_TO_LIST:
				if( $this->pSet->hasListPage() )
					HeaderRedirect($this->shortTableName, PAGE_LIST, "a=return");
				else
					HeaderRedirect("menu");
				return true;

			default:
				return false;
		}
	}


	/**
	 * Get the previous record keys
	 * @return Array
	 */
	protected function getPrevKeys()
	{
		return array();
	}

	/**
	 * Get the next record keys
	 * @return Array
	 */
	protected function getNextKeys()
	{
		return array();
	}


	/**
	 *	POST-REDIRECT-GET
	 *	Redirect after saving the data to avoid saving again on refresh.
	 */
	protected function prgRedirect()
	{
		if( $this->stopPRG )
			return false;
		if( !$this->updatedSuccessfully || !$this->isSimpleMode() || !no_output_done() )
			return false;

		$_SESSION["edit_seletion"] = $this->selection;
		$_SESSION["message_edit"] = $this->getMessages();
		$_SESSION["message_edit_type"] = $this->messageType;
		
		HeaderRedirect( $this->pSet->getShortTableName(), $this->getPageType() );
		exit();
		return true;
	}
	
	protected function getSingleRecordWhereClause( $keys ) 
	{
		$strWhereClause = KeyWhere($keys, $this->tName );
		
		if( $this->pSet->getAdvancedSecurityType() != ADVSECURITY_ALL )
		{
			// select only owned records
			$strWhereClause = whereAdd($strWhereClause, SecuritySQL("Edit", $this->tName));
		}
		
		return $strWhereClause;
	}

	public function getSelectedWhereClause() 
	{
		$strWhereClause = "";
		$keyFields = $this->pSet->getTableKeys();
		if( count( $keyFields ) == 1 ) {
			$selectionDecrypted = array();
			foreach( $this->parsedSelection as $p )
			{
				$selectionDecrypted[] = $this->cipherer->MakeDBValue( $keyFields[0], $p[ $keyFields[0] ] );
			}			
			$strWhereClause = $this->getFieldSQLDecrypt( $keyFields[0] ) . " in (" . implode(",", $selectionDecrypted ) . ")";
		}
		else 
		{
			//	composite key
			$components = array();
			foreach( $this->parsedSelection as $s )
			{
				$components[] = KeyWhere( $s, $this->tName );
			}
			$strWhereClause = implode(" or ", $components );
		}
		if( $this->pSet->getAdvancedSecurityType() != ADVSECURITY_ALL )
		{
			// select only owned records
			$strWhereClause = whereAdd($strWhereClause, SecuritySQL("Edit", $this->tName));
		}
		return $strWhereClause;
	}
	
	
	/**
	 * @param Boolean useOldKeys
	 * @return String
	 */
	public function getKeysWhereClause( $useOldKeys )
	{
		return $this->currentWhereExpr;
	}

	/**
	 * Read current values from the database
	 * @return Array 		The current record data
	 */
	public function getCurrentRecordInternal()
	{
		if( !is_null($this->cachedRecord) )
			return $this->cachedRecord;

		$this->nSelected = 0;
		$strWhereClause = $this->getSelectedWhereClause();
		$strSQL = $this->gQuery->gSQLWhere( $strWhereClause );

		$strSQLbak = $strSQL;
		$strWhereClauseBak = $strWhereClause;
		//	Before Query event
		if( $this->eventsObject->exists("BeforeQueryEdit") )
			$this->eventsObject->BeforeQueryEdit($strSQL, $strWhereClause, $this);

		if( $strSQLbak == $strSQL && $strWhereClauseBak != $strWhereClause )
		{
			$strSQL = $this->gQuery->gSQLWhere( $strWhereClause );
			if( !$keysSet )
				$strSQL = applyDBrecordLimit($strSQL.$orderClause, 1, $this->connection->dbType);
		}

		LogInfo($strSQL);

		$fields = $this->getPageFields();
		$rs = $this->connection->query( $strSQL );
		$diffValues = array();
		while( $fetchedArray = $rs->fetchAssoc() ) {
			$fetchedArray = $this->cipherer->DecryptFetchedArray( $fetchedArray );
			if( !$this->cachedRecord )
				$this->cachedRecord = $fetchedArray;
			else 
			{
				foreach( $fields as $f ) 
				{
					if( $this->cachedRecord[$f] != $fetchedArray[$f] )
						$diffValues[$f] = true;
				}
			}
			++$this->nSelected;

		}
		foreach( $diffValues as $f => $v )
		{
			unset( $this->cachedRecord[$f] );
		}
		
		
		if( $this->action != "edited" )
		{
			foreach($this->getPageFields() as $fName)
			{
				$aValue = $this->pSet->getAutoUpdateValue($fName);
				if( $aValue !== "" )
					$this->cachedRecord[ $fName ] = $this->pSet->getAutoUpdateValue($fName);
			}
		}


		// A successful function call shouldn't return empty array when 
		$this->cachedRecord["..."] = "";
		return $this->cachedRecord;
	}

	protected function readRecord()
	{
		$this->getCurrentRecordInternal();
		return true;
	}


	public function fillControlFlags( $field, $required = false )
	{
		$gf = GoodFieldName($field);
		$data = $this->getCurrentRecordInternal();
		
		$checkbox = "<input type=checkbox class=\"bs-updselbox\" id=updsel_".$gf.$this->id." data-field=\"".runner_htmlspecialchars( $field ) ."\">";
		
		$label = array();
		$label["begin"] = $checkbox;
		if( $required || $this->pSet->isRequired( $field ) ) 
			$label['end'] = '&nbsp;<span class="icon-required"></span>';
		
		$this->xt->assign($gf."_label", $label );
	}
	

	protected function buildNewRecordData()
	{
		// read field values with checkbpxes
		// define temporary arrays. These are required for ASP conversion
		$evalues = array();
		$efilename_values = array();
		$blobfields = array();
		$keys = $this->keys;
		
		$newFields = array_intersect( $this->getpageFields(), $this->selectedFields );
		foreach($newFields as $f)
		{
			$control = $this->getControl( $f, $this->id );
			$control->readWebValue($evalues, $blobfields, NULL, NULL, $efilename_values);
		}

		$this->newRecordData = $evalues;
		$this->newRecordBlobFields = $blobfields;
	}

	/**
	 * @return Array
	 */
	protected function getNewRecordCopy( $newRecordData )
	{
		return $newRecordData;
	}
	
	/**
	 * Process user data input and save it to the database table
	 */
	public function processDataInput()
	{
		$this->buildNewRecordData();

		if( !$this->recheckUserPermissions() )
		{
			//	prevent the page from reading database values
			$this->oldRecordData = $this->newRecordData;
			$this->cachedRecord = $this->newRecordData;
			$this->recordValuesToEdit = null;
			return false;
		}

		if( !$this->checkCaptcha() )
		{
			$this->setMessage($this->message);
			return false;
		}

		//	check Deny Duplicate values
		foreach($this->newRecordData as $f => $value)
		{
			if( !$this->pSet->allowDuplicateValues($f) )
			{
				$this->errorFields[] = $f;
				$this->setMessage( $this->pSet->label( $f ) . " " . mlang_message("INLINE_DENY_DUPLICATES") );
				return false;
			}
		}

		$newRecordData = $this->getNewRecordCopy( $this->newRecordData );

		$noLockedIdxs = array();
		if ( $this->lockingObj )
		{
			foreach( $this->parsedSelection as $idx => $s )
			{
				if ( $this->lockingObj->LockRecord( $this->tName, $s) )
				{
					$noLockedIdxs[] = $idx;	
				}
			}
		}
		
		
		/* process the records and update 1 by one */
		foreach( $this->parsedSelection as $idx => $s ) 
		{
			if ( $this->lockingObj )
			{
				if ( in_array($idx, $noLockedIdxs) ) 
					$this->lockingObj->UnlockRecord( $this->tName, $s, "" );	
				else
					continue;
			}

			//restore initial new record data array
			
			/* ASP trick begin */
			// ASP assignments to class members don't create a new copy of array
			// We need to create a new copy explicitly - $newRecordDataTemp
			
			$newRecordDataTemp = $newRecordData;
			$this->newRecordData = $this->getNewRecordCopy( $newRecordDataTemp );
			/* ASP trick end */
			
			
			$this->currentWhereExpr = $this->getSingleRecordWhereClause( $s );
			$strSQL = $this->gQuery->gSQLWhere( $this->currentWhereExpr );
			LogInfo($strSQL);

			$fetchedArray = $this->connection->query( $strSQL )->fetchAssoc();
			if( !$fetchedArray )
				continue;
			$fetchedArray = $this->cipherer->DecryptFetchedArray( $fetchedArray );
			if( !$this->isRecordEditable( $fetchedArray ) )
				continue;
			$this->setUpdatedLatLng( $this->getNewRecordData(), $fetchedArray );

			$this->oldKeys = $s;
			$this->recordBeingUpdated = $fetchedArray;

			if( !$this->callBeforeEditEvent( ) )
				continue;
				
			if( $this->callCustomEditEvent( ) )
			{
				if( !DoUpdateRecord( $this ) )
					continue;
				//	success if at least one record updated
			}
			++$this->nUpdated;
			$this->mergeNewRecordData();
			$this->auditLogEdit( $s );
			$this->callAfterEditEvent();
			
			if( $this->isPopupMode() )
				$this->inlineReportData[ $this->rowIds[ $idx ] ] = $this->getRowSaveStatusJSON( $s );
		}
	
		$this->updatedSuccessfully = ($this->nUpdated > 0);

		//	do save the record

		if( !$this->updatedSuccessfully )
		{
			return false;
		}
		
		$this->messageType = MESSAGE_INFO;
		$this->setSuccessfulEditMessage();
		
		$this->callAfterSuccessfulSave();

		return true;
	}

	/**
	 * @param Array keys
	 * @return Array
	 */
	protected function getRecordByKeys( $keys )
	{
		$strWhereClause = whereAdd( $this->getSelectedWhereClause(), $this->getSingleRecordWhereClause( $keys ) );
		$strSQL = $this->gQuery->gSQLWhere( $strWhereClause );

		$strSQLbak = $strSQL;
		$strWhereClauseBak = $strWhereClause;
		//	Before Query event
		if( $this->eventsObject->exists("BeforeQueryEdit") )
			$this->eventsObject->BeforeQueryEdit($strSQL, $strWhereClause, $this);

		if( $strSQLbak == $strSQL && $strWhereClauseBak != $strWhereClause )
		{
			$strSQL = $this->gQuery->gSQLWhere( $strWhereClause );
			if( !$keysSet )
				$strSQL = applyDBrecordLimit($strSQL.$orderClause, 1, $this->connection->dbType);
		}

		LogInfo($strSQL);

		$fetchedArray = $this->connection->query( $strSQL )->fetchAssoc();
		return $this->cipherer->DecryptFetchedArray( $fetchedArray );
	}	
	
	/**
	 * Set a successful edit message
	 */
	protected function setSuccessfulEditMessage()
	{
		$message = str_replace( array("%succeed%", "%total%"), array( "<strong>".$this->nUpdated."</strong>", "<strong>".$this->nSelected."</strong>" ), "%succeed% out of %total% records updated successfully.");
		$this->setMessage( $message );
		
		if( $this->nUpdated != $this->nSelected ) {
			$message = str_replace( "%failed%", "<strong>".($this->nSelected - $this->nUpdated)."</strong>" , "%failed% records failed.");
			$this->setMessage( $message );
		}
	}


	/**
	 *	Add missing values from oldRecordData to newRecordData
	 *	This is required for the Audit and the AfterEdit event
	 */
	protected function mergeNewRecordData()
	{
		if( !$this->auditObj && !$this->eventsObject->exists("AfterEdit") )
			return;

		foreach($this->getOldRecordData() as $f => $v)
		{
			if( !isset( $this->newRecordData[ $f ] ) )
				$this->newRecordData[ $f ] = $v;
		}
	}

	/**
	 *	Call After Record Updated event
	 */
	protected function callAfterEditEvent()
	{
		if( !$this->eventsObject->exists("AfterEdit") )
			return;

		$this->eventsObject->AfterEdit( $this->newRecordData,
			$this->getKeysWhereClause( false ),
			$this->getOldRecordData(),
			$this->keys,
			$this->mode == EDIT_INLINE,
			$this );
	}

	/**
	 *	Call each control's afterSuccessfulSave method
	 */
	protected function callAfterSuccessfulSave()
	{
		foreach($this->editFields as $f)
		{
			$this->getControl($f, $this->id)->afterSuccessfulSave();
		}
	}


	/**
	 *	Call Custom Edit event
	 */
	protected function callCustomEditEvent()
	{
		if( !$this->eventsObject->exists("CustomEdit") )
			return true;

		$usermessage = "";
		$ret = $this->eventsObject->CustomEdit( $this->newRecordData,
			$this->getKeysWhereClause( true ),
			$this->getOldRecordData(),
			$this->oldKeys,
			$usermessage,
			$this->mode == EDIT_INLINE,
			$this );

		//	this is required for the ASP conversion
		if( !$ret )
		{
			if ( 0 == strlen( $usermessage ) ) {
				++$this->nUpdated;
			}
			else
				$this->setMessage( $usermessage );
		}
		return $ret;
	}

	/**
	 * @return Boolean
	 */
	protected function isRecordEditable( $data )
	{
		global $globalEvents;
		if( $globalEvents->exists("IsRecordEditable", $this->tName) )
		{
			if( !$globalEvents->IsRecordEditable($data, true, $this->tName) )
				return false;
		}

		return true;
	}

	/**
	 * @param String fName
	 * @return Boolean
	 */
	protected function checkFieldOnPage( $fName )
	{
		return $this->pSet->appearOnUpdateSelected( $fName );
	}
	
	/**
	 * @return Array
	 */
	public function getOldRecordData()
	{
		return $this->recordBeingUpdated;
	}
	
	/**
	 * @param String message
	 */
	public function setMessage( $message )
	{
		$this->messages[] = $message;
	}

	/**
	 * @return String
	 */
	public function getMessages() 
	{
		return implode( "<br>", $this->messages );
	}
	
	/**
	 * @return Boolean
	 */
	protected function isPopupMode() 
	{
		return $this->mode == EDIT_SELECTED_POPUP;
	}

	/**
	 * @return Boolean
	 */
	protected function isSimpleMode() 
	{
		return $this->mode == EDIT_SELECTED_SIMPLE;
	}	
}
?>