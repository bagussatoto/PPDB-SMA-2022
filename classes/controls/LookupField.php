<?php
class LookupField extends EditControl
{
	/**
	 * Name of table which is lookup source 
	 * @var unknown_type
	 */
	public $lookupTable = "";
	/**
	 * Type of lookup source (db table, project table, etc.)
	 * @var int
	 */
	public $lookupType = 0;
	/**
	 * Type of lookup control (Dropdown, List page with search, etc.)
	 * @var int
	 */
	public $LCType = 0;

	/**
	 * RunnerCipherer entity for lookup table
	 * @var RunnerCipherer
	 */
	public $ciphererLookup = null;
	
	public $displayFieldName = "";
	public $linkFieldName = "";
	public $linkAndDisplaySame = false;
	
	public $linkFieldIndex = 0;
	public $displayFieldIndex = 0;
	
	public $lookupSize = 1;
	public $multiple = "";
	public $postfix = "";
	public $alt = "";
	
	public $clookupfield = "";
	public $openlookup = "";

	public $bUseCategory = false;
	public $horizontalLookup = false;
	public $addNewItem = false;
	
	public $isLinkFieldEncrypted = false;
	public $isDisplayFieldEncrypted = false;
	
	public $lookupPageType = "";
	
	public $lookupPSet = null;
	
	public $multiselect = false;
	public $lwLinkField = "";
	public $lwDisplayFieldWrapped = "";
	public $customDisplay = "";
	public $tName = "";
	
	/**
	 * Generated lookup table aliases' array
	 */
	protected $lookupTableAliases = array();
	/**
	 * Generated link field aliases' array
	 */	
	protected $linkFieldAliases = array();
	/**
	 * AbnGenerated displayed field aliases' array
	 */	
	protected $displayFieldAliases = array();
	/**
	 * A flag indicating that search by displayed field is allowed
	 * @type Boolean
	 */
	protected $searchByDisplayedFieldIsAllowed = null;
	
	/**
	 * @type Connection
	 */ 
	protected $lookupConnection;
	
	
	function __construct($field, $pageObject, $id, $connection)
	{
		parent::__construct($field, $pageObject, $id, $connection);
		
		$this->tName = $this->pageObject->tName;
		if($this->pageObject->tableBasedSearchPanelAdded)
			$this->tName = $this->pageObject->searchTableName;
		
		$this->format = EDIT_FORMAT_TEXT_FIELD;
		
		if($pageObject->pageType == PAGE_LIST || !$pageObject->isPageTableBased())
			$this->lookupPageType = PAGE_SEARCH;
		else 
			$this->lookupPageType = $pageObject->pageType; 
			
		$this->lookupTable = $this->pageObject->pSetEdit->getLookupTable($this->field);		
		$this->lookupType = $this->pageObject->pSetEdit->getLookupType($this->field);
		$this->setLookupConnection();
		
		if($this->lookupType == LT_QUERY)
			$this->lookupPSet = new ProjectSettings($this->lookupTable);
			
		$this->displayFieldName = $this->pageObject->pSetEdit->getDisplayField($this->field);
		$this->linkFieldName = $this->pageObject->pSetEdit->getLinkField($this->field);
		$this->linkAndDisplaySame = $this->displayFieldName == $this->linkFieldName;
		
		if($this->lookupType == LT_QUERY)
			$this->ciphererLookup = new RunnerCipherer($this->lookupTable);
			
		$this->LCType = $this->pageObject->pSetEdit->lookupControlType($this->field);
		
		$this->multiselect = $this->pageObject->pSetEdit->multiSelect($this->field);
		$this->customDisplay = $this->pageObject->pSetEdit->getCustomDisplay($this->field);

		$this->lwLinkField = $this->lookupConnection->addFieldWrappers( $this->linkFieldName );
		$this->lwDisplayFieldWrapped = RunnerPage::sqlFormattedDisplayField($this->field, $this->lookupConnection, $this->pageObject->pSetEdit);
			
		// The number of rows in a multiline lookup
		$this->lookupSize = $this->pageObject->pSetEdit->selectSize($this->field);
		$this->bUseCategory = $this->pageObject->pSetEdit->useCategory($this->field);		
	}

	/**
	 * Set the lookupConnection property
	 */
	protected function setLookupConnection()
	{
		global $cman;
			
		if( $this->lookupType == LT_QUERY )
		{
			$this->lookupConnection = $cman->byTable( $this->lookupTable );	
			return;
		}
		
		$connId = $this->pageObject->pSetEdit->getNotProjectLookupTableConnId( $this->field );
		$this->lookupConnection = strlen( $connId ) ? $cman->byId( $connId ) : $cman->getDefault();
	}
	
	/** 
	 * Create a CSS rule specifying the control's width
	 * @param Number widthPx
	 * @return String
	 */
	function makeWidthStyle($widthPx)
	{
		if( $this->LCType !== LCT_DROPDOWN )
			return parent::makeWidthStyle( $widthPx );
		
		if( 0 == $widthPx )
			return "";
			
		return "width: ".( $widthPx + 7 )."px";
	}

	/**
	 * Add control JS files to page object
	 */
	function addJSFiles()
	{
		if( $this->multiselect && ( $this->LCType == LCT_DROPDOWN && $this->lookupSize == 1 || $this->LCType == LCT_AJAX || $this->LCType == LCT_LIST ) )
			$this->pageObject->AddJSFile("include/chosen/chosen.jquery.js");
	}
	
	/**
	 * Add control CSS files to page object
	 */ 
	function addCSSFiles()
	{
		if( $this->multiselect && ( $this->LCType == LCT_DROPDOWN && $this->lookupSize == 1 || $this->LCType == LCT_AJAX || $this->LCType == LCT_LIST ) )
		{
			if ( $this->pageObject->isBootstrap() )
				$this->pageObject->AddCSSFile("include/chosen/bootstrap-chosen.css");
			else
				$this->pageObject->AddCSSFile("include/chosen/chosen.css");
		}					
	}
	
	/**
	 * This function need to bypass buildControl function of this class. It calls from LookupTextField class only.
	 */
	function parentBuildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data)
	{
		parent::buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data);
	}
	
	/**
	 * Get the control's settings and build its HTML markup
	 */
	function buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data)
	{
		parent::buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data);
		
		$this->alt = ($mode == MODE_INLINE_EDIT || $mode == MODE_INLINE_ADD) && $this->is508 ? ' alt="'.runner_htmlspecialchars($this->strLabel).'" ' : "";
		
		$suffix = "_".GoodFieldName($this->field)."_".$this->id;
		$this->clookupfield = "display_value".($fieldNum ? $fieldNum : '').$suffix;
		$this->openlookup = "open_lookup".$suffix;
		
		$this->cfield = "value".$suffix;
		$this->ctype = "type".$suffix;
		if($fieldNum)
		{
			$this->cfield = "value".$fieldNum.$suffix;
			$this->ctype = "type".$fieldNum.$suffix;
		}
			
		if( $this->ciphererLookup )
			$this->isLinkFieldEncrypted = $this->ciphererLookup->isFieldPHPEncrypted( $this->linkFieldName );
			
		$this->horizontalLookup = $this->pageObject->pSetEdit->isHorizontalLookup($this->field);

		$this->addMainFieldsSettings();
		
		//	alter "add on the fly" settings	
		$this->addNewItem = $this->isAllowToAdd( $mode );

		// prepare multi-select attributes
		$this->multiple = $this->multiselect ? " multiple" : "";
		$this->postfix = $this->multiselect ? "[]" : "";
		if( $this->multiselect )
			$avalue = splitvalues($value);
		else 
			$avalue = array((string)$value);
					
		$searchOption = $additionalCtrlParams["option"];		
	
		//	build the control
		if( $this->lookupType == LT_LISTOFVALUES )
		{
			$this->buildListOfValues($avalue, $value, $mode, $searchOption);
		}
		else
		{	
			// build a table-based lookup
			if( $this->ciphererLookup )
				$this->isDisplayFieldEncrypted = $this->ciphererLookup->isFieldPHPEncrypted( $this->displayFieldName );	
	
			if( $this->LCType == LCT_AJAX || $this->LCType == LCT_LIST )
			{
				$this->buildAJAXLookup($avalue, $value, $mode, $searchOption);
			}
			else
			{
				$this->buildClassicLookup($avalue, $value, $mode, $searchOption);
			}
		}
		
		$this->buildControlEnd($validate, $mode);
	}
	
	/**
	 * //	alter "add on the fly" settings
	 * @param String mode
	 * @return Boolean
	 */
	protected function isAllowToAdd( $mode )
	{		
		$addNewItem = false;
		$strPerm = GetUserPermissions($this->lookupTable);
		if( strpos($strPerm,"A") !== false && $this->LCType != LCT_LIST && $mode != MODE_SEARCH )
		{
			$addNewItem = $this->pageObject->pSetEdit->isAllowToAdd($this->field);
			$advancedadd = !$this->pageObject->pSetEdit->isSimpleAdd($this->field);
			if(!$advancedadd || $this->pageObject->pageType == PAGE_REGISTER)
				$addNewItem = false;
		}
		
		return $addNewItem;
	}
	
	/**
	 *
	 */
	protected function addMainFieldsSettings()
	{
		if( $this->pageObject->pSetEdit->isLookupWhereCode( $this->field ) )
			return;
		
		$mainMasterFields = array();
		$mainFields = array();

		$where = $this->pageObject->pSetEdit->getLookupWhere( $this->field );
		foreach( DB::readSQLTokens( $where ) as $token )
		{
			$prefix = "";
			$field = $token;
			$dotPos = strpos( $token, ".");
			
			if( $dotPos !== FALSE )
			{
				$prefix = strtolower( substr( $token, 0, $dotPos ) );
				$field = substr( $token, $dotPos + 1 );
			}
			
			if( $prefix == "master" )
				$mainMasterFields[] = $field;
			else if( !$prefix )
				$mainFields[] = $field;
		}
		
		$this->addJSSetting( "mainFields", $mainFields );
		$this->addJSSetting( "mainMasterFields", $mainMasterFields );
	}
	
	/**
	 * Get indexes of link and display fields
	 */
	public function fillLookupFieldsIndexes()
	{
		$lookupIndexes = GetLookupFieldsIndexes($this->pageObject->pSetEdit, $this->field);
		$this->linkFieldIndex = $lookupIndexes["linkFieldIndex"];
		$this->displayFieldIndex = $lookupIndexes["displayFieldIndex"];
	}
	
	/**
	 * Build HTML markup fo the 'List of values' lookup field
	 */
	public function buildListOfValues($avalue, $value, $mode, $searchOption)
	{
		// read lookup values
		$arr = $this->pageObject->pSetEdit->getLookupValues($this->field);
		$display_values = $arr;

		if (in_array( $this->pageObject->pSet->getViewFormat($this->field), array(FORMAT_DATE_SHORT, FORMAT_DATE_LONG, FORMAT_DATE_TIME)))
		{			
			$container = new ViewControlsContainer($this->pageObject->pSet, PAGE_LIST, null);
			foreach ( $arr as $key => $opt )
			{
				$data = array();
				$data[$this->field] = $opt;				
				$display_values[$key] = $container->getControl($this->field)->getTextValue($data);
			}
		}

		$dropDownHasSimpleBox = $this->LCType == LCT_DROPDOWN && !$this->multiselect && $mode == MODE_SEARCH;
		$optionContains = $dropDownHasSimpleBox && $this->isSearchOpitonForSimpleBox( $searchOption );
		
		//	print Type control to allow selecting nothing
		if( $this->multiselect )
			echo '<input id="'.$this->ctype.'" type="hidden" name="'.$this->ctype.'" value="multiselect">';
		
		switch($this->LCType)
		{ 
			case LCT_DROPDOWN:
				$dataAttr = $selectClass = '';
				if( $dropDownHasSimpleBox )
				{
					$dataAttr = ' data-usesuggests="true"';
					$selectClass = $optionContains ? ' class="rnr-hiddenControlSubelem" ' : '';
					$simpleBoxClass = $optionContains ? '' : ' class="rnr-hiddenControlSubelem" ';
					$simpleBoxStyle = $this->getWidthStyleForAdditionalControl();
					echo '<input id="'.$this->cfield.'_simpleSearchBox" type="text" value="'.runner_htmlspecialchars($value).'" autocomplete="off"'.$simpleBoxClass.' '.$simpleBoxStyle.'>';
				}
			
				echo '<select id="'.$this->cfield.'" size="'.$this->lookupSize.'" '.$dataAttr.$selectClass.' name="'.$this->cfield.$this->postfix.'" '
					.$this->multiple.' '.$this->inputStyle.'>';
				if( !$this->multiselect )
					echo '<option value="">'."Silahkan pilih".'</option>';
				else if($mode == MODE_SEARCH)
					echo '<option value=""> </option>';
					
				foreach($arr as $key => $opt)
				{
					$res = array_search((string)$opt, $avalue);
					if( $res !== FALSE )
						echo '<option value="'.runner_htmlspecialchars($opt).'" selected>'.runner_htmlspecialchars($display_values[$key]).'</option>';
					else
						echo '<option value="'.runner_htmlspecialchars($opt).'">'.runner_htmlspecialchars($display_values[$key]).'</option>';
				}
				echo "</select>";
				break;
				
			case LCT_CBLIST:
				echo '<div data-lookup-options class="'. ( $this->horizontalLookup ? 'rnr-horizontal-lookup' : 'rnr-vertical-lookup' ).'">';		
				$spacer = '<br/>';
				if($this->horizontalLookup)
					$spacer = '&nbsp;&nbsp;';
				$i = 0;
				foreach($arr as $key => $opt)
				{
					echo '<span class="checkbox"><label>';
					echo '<input id="'.$this->cfield.'_'.$i.'" class="rnr-checkbox" type="checkbox" '.$this->alt.' name="'.$this->cfield.$this->postfix.'" value="'
						.runner_htmlspecialchars($opt).'"';
					$res = array_search((string)$opt, $avalue);
					if( $res !== FALSE )
						echo ' checked="checked" ';
					echo '/>';

					echo '&nbsp;<span class="rnr-checkbox-label" id="data_'.$this->cfield.'_'.$i.'">'.runner_htmlspecialchars($display_values[$key]).'</span>'.$spacer;
					echo '</label></span>';
					$i++;
				}
				echo '</div>';
				break;
				
			case LCT_RADIO:
//				$spacer = $this->horizontalLookup ? "&nbsp;&nbsp;" : "<br/>";
				echo '<div data-lookup-options class="'. ( $this->horizontalLookup ? 'rnr-horizontal-lookup' : 'rnr-vertical-lookup' ).'">';		
				echo '<input id="'.$this->cfield.'" type="hidden" name="'.$this->cfield.'" value="'.runner_htmlspecialchars($value).'">';
				$i = 0;
				foreach($arr as $key => $opt)
				{
					$checked = "";
					if($opt == $value)
						$checked = ' checked="checked" ';
					echo '<span class="radio"><label>';
					echo '<input type="Radio" class="rnr-radio-button" id="radio_'.$this->cfieldname.'_'.$i.'" '
						.$this->alt.' name="radio_'.$this->cfieldname.'" '.$checked.' value="'.runner_htmlspecialchars($opt).'">'
						.' <span id="label_radio_'.$this->cfieldname.'_'.$i.'" class="rnr-radio-label">'
						.runner_htmlspecialchars($display_values[$key]).'</span >';
					echo '</label></span>';						
					$i++;
				}
				echo '</div>';		
				break;
		}
	} 
	
	/** 
	 * Build the markup for the 'Edit box with AJAX popup' or 'List page with search' lookup field	
	 * @param Array avalue			An array of the control values
	 * @param String value			A control's value string representation
	 * @param String mode			The control's mode (MODE_INLINE_EDIT, MODE_INLINE_ADD)
	 * @param String searchOption 	The control's search option
	 */
	public function buildAJAXLookup($avalue, $value, $mode, $searchOption)
	{		
		if( $this->multiselect )
		{
			$this->buildMultiselectAJAXLookup($avalue, $value, $mode, $searchOption);
			return;
		}
		
		$listSearchHasSimpleBox = $mode == MODE_SEARCH && $this->isAdditionalControlRequired();
		$optionContains = $this->isSearchOpitonForSimpleBox( $searchOption );
		$listOptionContains = $listSearchHasSimpleBox && $optionContains;
		
		$dataAttr = '';
		if( $this->LCType == LCT_LIST )
		{
			$dataAttr = $listSearchHasSimpleBox ? ' data-usesuggests="true"' : '';	
		}
		else if( $this->LCType == LCT_AJAX && $optionContains )
		{
			$dataAttr = ' data-simple-search-mode="true" ';		
		}
		
		if($this->bUseCategory)
		{
			$valueAttr = '';
			if( $this->LCType == LCT_AJAX && $optionContains || $this->LCType == LCT_LIST && $listOptionContains )
				$valueAttr = ' value="'.runner_htmlspecialchars($value).'"';
				
			// dependent ajax-lookup control	
			$inputParams = '" autocomplete="off" id="'.$this->clookupfield.'" '.$valueAttr.' name="'.$this->clookupfield.'" '.$this->inputStyle;
			$inputParams.= $this->LCType == LCT_LIST && !$listOptionContains ? 'readonly': '';
			echo '<input type="text" '.$inputParams.'>';		
			
			echo '<input type="hidden" id="'.$this->cfield.'" '.$valueAttr.' name="'.$this->cfield.'"'.$dataAttr.'>';
			
			echo $this->getLookupLinks( $listOptionContains );	
			return;
		}
		
		//	get the initial value
		$lookup_value = "";
		$lookupSQL = $this->getLookupSQL( array(), $value, false, true );

		$this->fillLookupFieldsIndexes();
		
		$qResult = $this->lookupConnection->query( $lookupSQL );
		
		if( $data = $qResult->fetchNumeric() )
		{
			if($this->isDisplayFieldEncrypted) 
				$lookup_value = $this->ciphererLookup->DecryptField( $this->displayFieldName , $data[ $this->displayFieldIndex ]);
			else
				$lookup_value = $data[ $this->displayFieldIndex ];
		}
		elseif( $this->pageObject->pSetEdit->isLookupWhereSet( $this->field ) )
		{
			// try w/o WHERE expression
			$lookupSQL = $this->getLookupSQL(array(), $value, false, true, false);
			$qResult = $this->lookupConnection->query( $lookupSQL );	
			if( $data = $qResult->fetchNumeric() )
			{
				if($this->isDisplayFieldEncrypted)
					$lookup_value = $this->ciphererLookup->DecryptField( $this->displayFieldName , $data[ $this->displayFieldIndex ]);
				else
					$lookup_value = $data[ $this->displayFieldIndex ];
			}
		}
		
		// build the regular ajax-lookup control
		if( $this->LCType == LCT_AJAX && !strlen($lookup_value) && ($this->pageObject->pSetEdit->isfreeInput($this->field) || $this->lookupPageType == PAGE_SEARCH) 
			|| $this->LCType == LCT_LIST && $listOptionContains )
		{
			$lookup_value = $value;
		}
		
		if ( in_array( $this->pageObject->pSet->getViewFormat($this->field), array(FORMAT_DATE_SHORT, FORMAT_DATE_LONG, FORMAT_DATE_TIME) ) )
		{			
			$container = new ViewControlsContainer($this->pageObject->pSet, PAGE_LIST, null);
			
			$ctrlData = array();
			$ctrlData[ $this->field ] = $lookup_value;				
			$lookup_value = $container->getControl( $this->field )->getTextValue( $ctrlData );
		}				
		
		$inputParams = 'autocomplete="off" id="'.$this->clookupfield.'" name="'.$this->clookupfield.'" '.$this->inputStyle.$this->alt;	
		$inputParams.= ' value="'.runner_htmlspecialchars($lookup_value).'"';;
		
		if( $this->LCType == LCT_LIST && !$listOptionContains )
			$inputParams.= ' readonly ';
		
		if( $this->LCType == LCT_LIST && !$this->pageObject->pSetEdit->isRequired($this->field))
			$inputParams.= ' class="clearable" ';
		
		$inputTag = '<input type="text" '.$inputParams.'>';
		if ( $this->LCType == LCT_LIST )
		{
			echo '<span class="bs-list-lookup">'.$inputTag.'</span>';
		}
		else
		{
			echo $inputTag;
		}	
		
		echo '<input type="hidden" id="'.$this->cfield.'" name="'.$this->cfield.'" value="'.runner_htmlspecialchars($value).'"'.$dataAttr.'>';

		echo $this->getLookupLinks( $listOptionContains );	
	}
		
	/**
	 * Build a multiselect control html markup to apply JS 'chosen' plugin 
	 * @param Array avalue			An array of the control values
	 * @param String value			A control's value string representation
	 * @param String mode			The control's mode (MODE_INLINE_EDIT, MODE_INLINE_ADD)
	 * @param String searchOption 	The control's search option
	 */	
	protected function buildMultiselectAJAXLookup($avalue, $value, $mode, $searchOption)
	{
		echo '<select '.$this->multiple.' id="'.$this->cfield.'" name="'.$this->cfield.$this->postfix.'" '.$this->inputStyle.$this->alt.'>';

		if( !$this->bUseCategory && strlen($value) )
			$this->buildMultiselectAJAXLookupRows($avalue, $value, $mode, $searchOption);
		
		echo '</select>';

		echo $this->getLookupLinks();			
	}
	
	/**
	 * Build the control's 'option' elements. If the link and displayed fields are not the same
	 * a db query is used to retrieve displayed values corresponding to the link values ($avalue)
	 * @param Array avalue			An array of the control values
	 * @param String value			A control's value string representation
	 * @param String mode			The control's mode (MODE_INLINE_EDIT, MODE_INLINE_ADD)
	 * @param String searchOption 	The control's search option
	 */	
	protected function buildMultiselectAJAXLookupRows($avalue, $value, $mode, $searchOption)
	{
		$this->fillLookupFieldsIndexes();
		
		if( $this->linkAndDisplaySame || $this->lookupPageType == PAGE_SEARCH ) 
		{
			foreach($avalue as $mKey => $mValue)
			{
				$data = array();
				$data[ $this->linkFieldIndex ] = $mValue;
				$data[ $this->displayFieldIndex ] = $mValue;
				
				$this->buildLookupRow( $mode, $data, ' selected', $mKey );
			}
			return;
		} 

		$lookupSQL = $this->getLookupSQL( array(), $value, false, true);
		$qResult = $this->lookupConnection->query( $lookupSQL );
		
		$options = 0;
		while( $data = $qResult->fetchNumeric() )
		{
			$this->decryptDataRow( $data );
			if( array_search( $data[ $this->linkFieldIndex ], $avalue ) !== FALSE )
			{
				$this->buildLookupRow( $mode, $data, ' selected', $options );
				$options++;
			}
		}	
		
		// try the same query w/o WHERE clause if options were not found
		if( $options == 0 && strlen($value) && $mode == MODE_EDIT && $this->pageObject->pSetEdit->isLookupWhereSet( $this->field ) )
		{
			//one record mode true
			$lookupSQL = $this->getLookupSQL( array(), $value, false, true, false, true);
			$qResult = $this->lookupConnection->query( $lookupSQL );
			
			if( $data = $qResult->fetchNumeric() )
			{
				$this->decryptDataRow( $data );
				$this->buildLookupRow( $mode, $data, ' selected', $options );
			}
		}				
	}
	
	/**
	 * Build HTML markup for the 'dropdown box', 'checkbox list' or 'radio button' lookup field
	 * @param Array avalue
	 * @param String value
	 * @param String mode
	 * @param String searchOption
	 */
	public function buildClassicLookup($avalue, $value, $mode, $searchOption)
	{		
		$dropDownHasSimpleBox = $this->LCType == LCT_DROPDOWN && $mode == MODE_SEARCH && $this->isAdditionalControlRequired();
		$optionContains = $dropDownHasSimpleBox && $this->isSearchOpitonForSimpleBox( $searchOption );
		
		if( $this->multiselect ) //	print Type control to allow selecting nothing
			echo '<input id="'.$this->ctype.'" type="hidden" name="'.$this->ctype.'" value="multiselect">';	

		if($this->bUseCategory)
		{
			//	dependent classic lookup						
			switch ($this->LCType) 
			{
				case LCT_CBLIST:
					echo '<div data-lookup-options>';
					echo '<input id="'.$this->cfield.'" type="checkbox" name="'.$this->cfield.'" style="display:none;">';
					echo '</div>';
					break;
					
				case LCT_RADIO:
					echo '<input id="'.$this->cfield.'" type="hidden" name="'.$this->cfield.'" value="'.runner_htmlspecialchars($value).'">';
					echo '<div data-lookup-options>';
					echo '</div>';
					break;
					
				case LCT_DROPDOWN:
					$dataAttr = '';
					$selectClass = $this->pageObject->isBootstrap() ? 'form-control' : '';
					$simpleBoxClass = $this->pageObject->isBootstrap() ? 'form-control' : '';
					if( $dropDownHasSimpleBox )
					{
						$dataAttr = ' data-usesuggests="true"';
						$selectClass .= $optionContains ? ' rnr-hiddenControlSubelem' : '';
						$simpleBoxClass .= $optionContains ? '' : ' rnr-hiddenControlSubelem';
						$simpleBoxStyle = $this->pageObject->isBootstrap() ? '' : $this->getWidthStyleForAdditionalControl();
						echo '<input id="'.$this->cfield.'_simpleSearchBox" type="text" value="'.runner_htmlspecialchars($value).'" autocomplete="off" class="'.$simpleBoxClass.'" '.$simpleBoxStyle.'>';
					}
				
					echo '<select size="'.$this->lookupSize.'" id="'.$this->cfield.'" name="'.$this->cfield.$this->postfix.'" class="'.$selectClass.'" '.$dataAttr.
						$this->multiple.' '.$this->inputStyle.'>';
					echo '<option value="">'."Silahkan pilih".'</option>';
					echo '</select>';
					break;
			}
			
			echo $this->getLookupLinks();			
			return;
		}		
		
		$lookupSQL = $this->getLookupSQL( array(), "", false);
		$qResult = $this->lookupConnection->query( $lookupSQL );
		$this->fillLookupFieldsIndexes();
			
		//	simple classic lookup		
		if($this->LCType == LCT_DROPDOWN)
		{
			$dataAttr = '';
			$selectClass = $this->pageObject->isBootstrap() ? 'form-control' : '';
			$simpleBoxClass = $this->pageObject->isBootstrap() ? 'form-control' : '';

			if( $dropDownHasSimpleBox )
			{
				$dataAttr = ' data-usesuggests="true"';
				$selectClass .= $optionContains ? ' rnr-hiddenControlSubelem' : '';
				$simpleBoxClass .= $optionContains ? '' : ' rnr-hiddenControlSubelem';
				$simpleBoxStyle = $this->pageObject->isBootstrap() ? '' : $this->getWidthStyleForAdditionalControl();
				echo '<input id="'.$this->cfield.'_simpleSearchBox" type="text" value="'.runner_htmlspecialchars($value).'" autocomplete="off" class="'.$simpleBoxClass.'" '.$simpleBoxStyle.'>';
			}
			
			echo '<select size="'.$this->lookupSize.'" id="'.$this->cfield.'" '
				.$this->alt.' name="'.$this->cfield.$this->postfix.'"'.$dataAttr.' class="'.$selectClass.'" '.$this->multiple.' '.$this->inputStyle.'>';
			if( !$this->multiselect )
				echo '<option value="">'."Silahkan pilih".'</option>';
			else if($mode == MODE_SEARCH)
				echo '<option value=""> </option>';
		}
		else
		{
			if($this->LCType == LCT_RADIO)
				echo '<input id="'.$this->cfield.'" type="hidden" name="'.$this->cfield.'" value="'.runner_htmlspecialchars($value).'">';

			echo '<div data-lookup-options class="'. ( $this->horizontalLookup ? 'rnr-horizontal-lookup' : 'rnr-vertical-lookup' ).'">';		
		}
		
		//	print lookup data
	 	$found = false;
		$i = 0;
		$isLookupUnique = $this->lookupType == LT_QUERY && $this->pageObject->pSetEdit->isLookupUnique($this->field);
		$uniqueArray = array();	
		while( $data = $qResult->fetchNumeric() )
		{
			if($isLookupUnique)
			{
				if( in_array($data[ $this->linkFieldIndex ], $uniqueArray) )
					continue;
				$uniqueArray[] = $data[ $this->linkFieldIndex ];
			}
			$this->decryptDataRow($data);
			$res = array_search((string)$data[ $this->linkFieldIndex ],$avalue);
			$checked = "";
			if( $res !== NULL && $res !== FALSE )
			{
				$found = true;
				$checked = $this->LCType == LCT_CBLIST || $this->LCType == LCT_RADIO ? ' checked="checked"' : ' selected';
			}
			$this->buildLookupRow($mode, $data, $checked, $i);
			$i++;
		}
	
		//	try the same query w/o WHERE clause if current value not found
		if(!$found && strlen($value) && $mode == MODE_EDIT && $this->pageObject->pSetEdit->isLookupWhereSet( $this->field ) )
		{
			$lookupSQL = $this->getLookupSQL( array(), $value, false, true, false, true);
			$this->fillLookupFieldsIndexes();
			$qResult = $this->lookupConnection->query( $lookupSQL );
			if( $data = $qResult->fetchNumeric() )
			{
				$this->decryptDataRow($data);
				$checked = $this->LCType == LCT_CBLIST || $this->LCType == LCT_RADIO ? ' checked="checked"' : ' selected';
				$this->buildLookupRow($mode, $data, $checked, $i);
			}
		}
		
		// print footer
		$footer = $this->LCType == LCT_DROPDOWN ? '</select>' : '</div>';
		echo $footer;
		
		echo $this->getLookupLinks();
	}
	
	/**
	 * @param &Array data
	 */
	public function decryptDataRow(&$data)
	{
		if($this->isLinkFieldEncrypted)
			$data[$this->linkFieldIndex] = $this->ciphererLookup->DecryptField( $this->linkFieldName, $data[$this->linkFieldIndex] );
		if($this->isDisplayFieldEncrypted)
			$data[$this->displayFieldIndex] = $this->ciphererLookup->DecryptField( $this->displayFieldName, $data[$this->displayFieldIndex] );
	}
	
	/**
	 * Build HTML markup for a lookup item
	 * @param String mode
	 * @param Array data
	 * @param String checked
	 * @param Number i
	 * @return String
	 */
	public function buildLookupRow($mode, $data, $checked, $i)
	{
		$display_value = $data[ $this->displayFieldIndex ];

		if( in_array( $this->pageObject->pSet->getViewFormat($this->field), array(FORMAT_DATE_SHORT, FORMAT_DATE_LONG, FORMAT_DATE_TIME) ) )
		{			
			$container = new ViewControlsContainer($this->pageObject->pSet, PAGE_LIST, null);			
			
			$ctrlData = array();

			$ctrlData[ $this->field ] = $data[ $this->linkFieldIndex ];
			//	$ctrlData[ $this->field ] = $display_value;
			
			$display_value = $container->getControl( $this->field )->getTextValue( $ctrlData );			
		}

		switch($this->LCType)
		{ 
			case LCT_DROPDOWN:
			case LCT_LIST:
			case LCT_AJAX:	
				echo '<option value="'.runner_htmlspecialchars($data[ $this->linkFieldIndex ]).'"'.$checked.'>'
						.runner_htmlspecialchars($display_value).'</option>';
				break;
				
			case LCT_CBLIST:
				echo '<span class="checkbox"><label>'
						.'<input id="'.$this->cfield.'_'.$i.'" class="rnr-checkbox" type="checkbox" '.$this->alt.' name="'.$this->cfield.$this->postfix
							.'" value="'.runner_htmlspecialchars($data[ $this->linkFieldIndex ]).'"'.$checked.'/>&nbsp;'
						.'<span class="rnr-checkbox-label" id="data_'.$this->cfield.'_'.$i.'">'
							.runner_htmlspecialchars($display_value)
						.'</span>'
					.'</label></span>';
				break;
				
			case LCT_RADIO:
				echo '<span class="radio"><label>'
						.'<input type="Radio" class="rnr-radio-button" id="radio_'.$this->cfieldname.'_'.$i.'" '
							.$this->alt.' name="radio_'.$this->cfieldname.'" '.$checked.' value="'.runner_htmlspecialchars($data[ $this->linkFieldIndex ]).'">'
						.' <span id="label_radio_'.$this->cfieldname.'_'.$i.'" class="rnr-radio-label">'
							.runner_htmlspecialchars($display_value)
						.'</span>'
					.'</label></span>';
				break;
		}
	} 

	function getFirstElementId()
	{
		switch($this->LCType)
		{ 
			case LCT_AJAX:	
				return "display_value_" . $this->goodFieldName . "_" . $this->id;
			break;
			default:
				return $this->cfield;
			break;
		}
	}

	/**
	 * Check if a simple search box should be displayed
	 * for a particular search options
	 * @param String searchOption
	 * @return Boolean
	 */
	public function isSearchOpitonForSimpleBox( $searchOption )
	{
		if( $searchOption == 'Contains' || $searchOption == 'Starts with' )
			return true;
			
		if( $searchOption != '' )
			return false;
			
		$userSearchOptions = $this->pageObject->pSetEdit->getSearchOptionsList( $this->field );
		return !count($userSearchOptions) || in_array('Contains', $userSearchOptions) || in_array('Starts with', $userSearchOptions); 
	}
	
	/**
	 * Check if an additional simple search box control should be added to 
	 * the ordinary control's markup
	 * @return Boolean
	 */
	protected function isAdditionalControlRequired() 
	{		
		if( $this->multiselect )
			return false;
			
		$hostPageType = $this->pageObject->pSetEdit->getTableType();
		if( $hostPageType == "report" || $hostPageType == "chart" )
			return false;

		$userSearchOptions = $this->pageObject->pSetEdit->getSearchOptionsList( $this->field );
		if( count($userSearchOptions) && !in_array('Contains', $userSearchOptions) && !in_array('Starts with', $userSearchOptions) ) 
			return false;
			
		if( $this->lookupType == LT_LISTOFVALUES || $this->linkAndDisplaySame )
			return true;
	
		// #9875 connection and lookupConnection props must be the same
		if( $this->connection->connId != $this->lookupConnection->connId )  
			return false;
	
		if( !$this->connection->checkIfJoinSubqueriesOptimized() && $this->LCType == LCT_LIST )
			return false;
		
		return $this->isLookupSQLquerySimple() && $this->isMainTableSQLquerySimple();
	}
	
	/**
	 * Get the additional control element's style attribute string
	 * @return String
	 */
	protected function getWidthStyleForAdditionalControl()
	{
		$width = $this->searchPanelControl ? 150 : $this->pageObject->pSetEdit->getControlWidth( $this->field ); 
		//$style = parent::makeWidthStyle( $width );
		
		return 'style="'.$style.'"';		
	}
	
	/**
	 * Check if the lookup table doesn't have encription and its SQL query doesn't have HAVING, 
	 * ORDER BY clauses and FROM clause with subqueries. #8564	
	 * @return Boolean
	 */
	protected function isLookupSQLquerySimple()
	{
		if( $this->lookupConnection->dbType == nDATABASE_DB2 || $this->lookupConnection->dbType == nDATABASE_Informix || $this->lookupConnection->dbType == nDATABASE_SQLite3 )
			return false;

		if( $this->lookupType == LT_LOOKUPTABLE || $this->lookupType == LT_LISTOFVALUES )
			return true;

		// encription is turned on
		if( $this->lookupPSet->hasEncryptedFields() ) 
			return false;
		
		$lookupSqlQuery = $this->lookupPSet->getSQLQuery();
		
		if( $lookupSqlQuery->HasGroupBy() || $lookupSqlQuery->HavingToSql() != "" || $lookupSqlQuery->HasSubQueryInFromClause() )
			return false;

		if( $this->lookupConnection->dbType != nDATABASE_MySQL )
		{		
			$linkFieldType = $this->lookupPSet->getFieldType( $this->linkFieldName );
			if( !(IsNumberType($this->type) && IsNumberType($linkFieldType) || IsCharType($this->type) && IsCharType($linkFieldType) || IsDateFieldType($this->type) && IsDateFieldType($linkFieldType)) )
				return false;
		}		
		return true;		
	}
	
	/**
	 * Check if the main table doesn't have encryption and its SQL query doesn't have HAVING, 
	 * ORDER BY clauses and FROM clause with sub-queries. #8564	
	 * @return Boolean
	 */	
	protected function isMainTableSQLquerySimple()
	{
		if( $this->connection->dbType != nDATABASE_MySQL 
			&& $this->connection->dbType != nDATABASE_MSSQLServer 
			&& $this->connection->dbType != nDATABASE_Oracle 
			&& $this->connection->dbType != nDATABASE_PostgreSQL )
			return false;

		// encription is turned on
		if( $this->pageObject->pSetEdit->hasEncryptedFields() ) 
			return false;
		
		$sqlQuery = $this->pageObject->pSetEdit->getSQLQueryByField( $this->field );
		
		if( $sqlQuery->HasGroupBy() || $sqlQuery->HavingToSql() != "" || $sqlQuery->HasSubQueryInFromClause() )
			return false;		
		
		return true;		
	}
	
	/**
	 * Check if searching by displayed field is allowed
	 * @return Boolean
	 */
	protected function isSearchByDispalyedFieldAllowed()
	{	
		if ( !is_null( $this->searchByDisplayedFieldIsAllowed ) ) 
			return $this->searchByDisplayedFieldIsAllowed;

		// #9875 connection and lookupConnection props must be the same	
		if( $this->connection->connId != $this->lookupConnection->connId )
		{
			$this->searchByDisplayedFieldIsAllowed = false;
			return $this->searchByDisplayedFieldIsAllowed;
		}			
			
		if( !$this->connection->checkIfJoinSubqueriesOptimized() && ( $this->LCType == LCT_LIST || $this->LCType == LCT_AJAX ) )
		{
			$this->searchByDisplayedFieldIsAllowed = false;
			return $this->searchByDisplayedFieldIsAllowed;
		}		

		$hostPageType = $this->pageObject->pSetEdit->getTableType();
		
		$this->searchByDisplayedFieldIsAllowed = $hostPageType != "report" && $hostPageType != "chart" && !$this->linkAndDisplaySame 
			&& !$this->multiselect && ( $this->LCType == LCT_LIST || $this->LCType == LCT_DROPDOWN || $this->LCType == LCT_AJAX ) 
			&& $this->lookupType != LT_LISTOFVALUES && $this->isLookupSQLquerySimple() && $this->isMainTableSQLquerySimple();
			
		return $this->searchByDisplayedFieldIsAllowed;
	}

	/**
	 * The wrapper for the SearchClause 'SQLWhere' method 
	 */
	public function getSearchWhere($searchFor, $strSearchOption, $searchFor2, $etype)
	{
		if( !$this->isSearchByDispalyedFieldAllowed() || ( $strSearchOption !== "Starts with" && $strSearchOption !== "Contains" ) )
			return $this->SQLWhere($searchFor, $strSearchOption, $searchFor2, $etype, false);

		$searchIsCaseInsensitive = $this->pageObject->pSetEdit->getNCSearch();

		$searchFor = $this->connection->escapeLIKEpattern( $searchFor ); 
		$searchFor = $this->connection->prepareString( $strSearchOption == 'Contains' ? '%'.$searchFor.'%' : $searchFor.'%' );


		if( $searchIsCaseInsensitive )
			$displayFieldSearchClause = $this->connection->upper( $this->lwDisplayFieldWrapped )." ".$this->like." ". $this->connection->upper( $searchFor );
		else
			$displayFieldSearchClause = $this->lwDisplayFieldWrapped ." ".$this->like." ". $searchFor;
		
		return $this->getFieldSQLDecrypt() . " in (" . $this->getSearchSubquerySQL( $displayFieldSearchClause ) . ")";
	}
	
	/**
	 * @param String strSearchOption
	 * @return Boolean
	 */
	public function checkIfDisplayFieldSearch( $strSearchOption )
	{
		return $this->isSearchByDispalyedFieldAllowed() && ( $strSearchOption === "Starts with" || $strSearchOption === "Contains" );
	}
	
	/**
	 * Get an extra WHERE clause condtion	
	 * that helps to retrieve a field's search suggest value
	 * @param String searchOpt
	 * @param String searchFor
	 * @param Boolean isAggregateField (optional)
	 * @return String
	 */
	public function getSuggestWhere($strSearchOption, $searchFor, $isAggregateField = false) 
	{
		if( !$this->isSearchByDispalyedFieldAllowed() || ( $strSearchOption !== "Starts with" && $strSearchOption !== "Contains" ) )
			return $this->SQLWhere($searchFor, $strSearchOption, "", "", false);
		
		$this->initializeLookupTableAliases();
		
		$searchIsCaseInsensitive = $this->pageObject->pSetEdit->getNCSearch();
		
		$searchFor = $this->connection->escapeLIKEpattern( $searchFor ); 
		$searchFor = $this->connection->prepareString( $strSearchOption == 'Contains' ? '%'.$searchFor.'%' : $searchFor.'%' );
		$searchForPrepared = $searchIsCaseInsensitive ?  $this->connection->upper( $searchFor ) : $searchFor;
		
		$displayFieldName = $this->lookupTableAliases[ $this->id ].".".$this->displayFieldAliases[ $this->id ];
		
		if( $searchIsCaseInsensitive )
			$likeCondition = $this->connection->upper( $displayFieldName )." ".$this->like." ". $searchForPrepared;
		else
			$likeCondition = $displayFieldName." ".$this->like." ". $searchForPrepared;
			
		return $likeCondition;			
	}
	
	/**
	 * Get an extra HAVING clause condtion	
	 * that helps to retrieve a field's search suggest value
	 * @param String searchOpt
	 * @param String searchFor
	 * @param Boolean isAggregateField (optional)
	 * @return String
	 */
	public function getSuggestHaving($searchOpt, $searchFor, $isAggregateField = true)
	{
		if( !$this->isSearchByDispalyedFieldAllowed() )
			return parent::getSuggestHaving($searchOpt, $searchFor, $isAggregateField);
			
		$this->initializeLookupTableAliases();
		
		return $isAggregateField ? $this->lookupTableAliases[ $this->id ].".".$this->linkFieldAliases[ $this->id ]." is not NULL" : "";
	}

	/**
	 * Get the substitute columns list for the SELECT Clause and the FORM clause part 
	 * that will be joined to the basic page's FROM clause 	 
	 * @param String searchFor
	 * @param String searchOpt
	 * @param Boolean isSuggest
	 * @return Array
	 */
	public function getSelectColumnsAndJoinFromPart($searchFor, $searchOpt, $isSuggest)
	{
		if( !$isSuggest || !$this->isSearchByDispalyedFieldAllowed() )
			return parent::getSelectColumnsAndJoinFromPart($searchFor, $searchOpt, $isSuggest);
 
		$this->initializeLookupTableAliases();
 
		return array(
			"selectColumns"=> $this->getSelectColumns( $isSuggest ),
			"joinFromPart"=> $this->getFromClauseJoinPart( $searchFor, $searchOpt, $isSuggest)
		);		
	}
	
	/**
	 * Initialize the lookup table aliases
	 * The connection and lookupConnection props must be the same #9875 
	 */
	protected function initializeLookupTableAliases()  
	{
		if( isset( $this->lookupTableAliases[ $this->id ] ) )
			return;

		$this->lookupTableAliases[ $this->id ] = $this->connection->addTableWrappers( "lt".generatePassword(14) );
		$this->linkFieldAliases[ $this->id ] = $this->connection->addFieldWrappers( "lf".generatePassword(14) );	
		$this->displayFieldAliases[ $this->id ] = $this->connection->addFieldWrappers( "df".generatePassword(14) );
	}
	/**
	 * Get the FORM clause part that will be joined 
	 * to the basic page's FROM clause 
	 * The connection and lookupConnection props must be the same #9875 
	 * @param String searchFor
	 * @param String searchOpt	
	 * @param Boolean isSuggest
	 * @return String
	 */	

	protected function getFromClauseJoinPart( $searchFor, $searchOpt, $isSuggest )
	{		
		if( !$this->isSearchByDispalyedFieldAllowed() )
			return "";
			
		$lookUpFieldName = RunnerPage::_getFieldSQL($this->field, $this->connection, $this->pageObject->pSetEdit);
		
		if($this->lookupType != LT_LOOKUPTABLE )
		{
			$linkFieldName = RunnerPage::_getFieldSQL($this->linkFieldName, $this->connection, $this->lookupPSet);
			
			if( !$this->customDisplay )
				$displayFieldName = RunnerPage::_getFieldSQL($this->displayFieldName, $this->connection, $this->lookupPSet);
			else 
				$displayFieldName = $this->displayFieldName;
				
			$lookupFromExpression = $this->lookupPSet->getSQLQuery()->FromToSql();
		}
		else
		{
			$linkFieldName = $this->lwLinkField;
			$displayFieldName = $this->lwDisplayFieldWrapped;
			$lookupFromExpression = " from " . $this->connection->addTableWrappers( $this->lookupTable );
		}
		
		$subqueryColumns = $linkFieldName." as ".$this->linkFieldAliases[ $this->id ].", ".$displayFieldName." as".$this->displayFieldAliases[ $this->id ];
		$subquery = "select ".$subqueryColumns.$lookupFromExpression;	
		
		return " left join (".$subquery.") ".$this->lookupTableAliases[ $this->id ]." on ".$this->lookupTableAliases[ $this->id ].".".$this->linkFieldAliases[ $this->id ]."=".$lookUpFieldName;
	}
	
	/**
	 * Get the substitute columns list for the SELECT Clause
	 * @param Boolean isSuggest	
	 * @return String
	 */
	protected function getSelectColumns( $isSuggest )
	{		
		if( $isSuggest )
			return $this->lookupTableAliases[ $this->id ].".".$this->displayFieldAliases[ $this->id ];
		return "";
	}
	
	/**
	 * Get the WHERE clause conditions string for the search or suggest SQL query
	 * @param String SearchFor
	 * @param String strSearchOption
	 * @param String SearchFor2
	 * @param String etype
	 * @param Boolean isSuggest
	 * @return String
	 */
	function SQLWhere( $SearchFor, $strSearchOption, $SearchFor2, $etype, $isSuggest )
	{
		if( $this->lookupType == LT_LISTOFVALUES )
			return parent::SQLWhere($SearchFor, $strSearchOption, $SearchFor2, $etype, $isSuggest);
			
		$baseResult = $this->baseSQLWhere($strSearchOption);
		if( $baseResult === false )
			return "";
			
		if( $baseResult !== "" )
			return $baseResult;
				
		if( $this->connection->dbType != nDATABASE_MySQL )
			$this->btexttype = IsTextType($this->type);	
			
		if( $this->multiselect && $strSearchOption != "Equals" )
			$SearchFor = splitvalues($SearchFor);
		else
			$SearchFor = array($SearchFor);
			
		$gstrField = $this->getFieldSQLDecrypt();
		if( ($strSearchOption == "Starts with" || $strSearchOption == "Contains") && (!IsCharType($this->type) || $this->btexttype) )
			$gstrField = $this->connection->field2char($gstrField, $this->type);

		$ret = "";			
		foreach($SearchFor as $searchItem)
		{
			$value = $searchItem;
			if( $value == "null" || $value == "Null" || $value == "" )
				continue;
			
			if( strlen(trim($ret)) )
				$ret.=" or ";

			if( ($strSearchOption == "Starts with" || $strSearchOption == "Contains") && !$this->multiselect )
			{
				$value = $this->connection->escapeLIKEpattern( $value );
				
				if( $strSearchOption == "Starts with" ) 
					$value.= '%';
				if( $strSearchOption == "Contains" ) 
					$value = '%'.$value.'%';
			}

			if( $strSearchOption != "Starts with" && $strSearchOption != "Contains" )	
				$value = make_db_value($this->field, $value);
				
			$searchIsCaseInsensitive = $this->pageObject->pSetEdit->getNCSearch();	
				
			if( $strSearchOption == "Equals" && !($value == "null" || $value == "Null") )
			{
				$condition = $gstrField.'='.$value;	
			}
			else if( ($strSearchOption=="Starts with" || $strSearchOption=="Contains") && !$this->multiselect) 
			{
				$condition = $gstrField." ".$this->like." ".$this->connection->prepareString( $value );
			}
			else if( $strSearchOption == "More than" )
			{			
				$condition = $gstrField." > ".$value;
			}
			else if( $strSearchOption == "Less than" )
			{			
				$condition = $gstrField."<".$value;
			}
			else if( $strSearchOption == "Equal or more than" )
			{			
				$condition = $gstrField.">=".$value1;
			}
			else if( $strSearchOption == "Equal or less than" )
			{
				$condition = $gstrField."<=".$value1;
			}
			else if( $strSearchOption == "Between" )
			{					
				$value2 = $this->connection->prepareString( $SearchFor2 );
				
				if( $this->lookupType == LT_QUERY && IsCharType($this->type) && !$this->btexttype && $searchIsCaseInsensitive )
					$value2 = $this->connection->upper( $value2 );				
					
				$condition = $gstrField.">=".$value." and ";
				if (IsDateFieldType($this->type))
				{
					$timeArr = db2time($SearchFor2);
					// for dates without time, add one day
					if ($timeArr[3] == 0 && $timeArr[4] == 0 && $timeArr[5] == 0)
					{
						$timeArr = adddays($timeArr, 1);
						$SearchFor2 = $timeArr[0]."-".$timeArr[1]."-".$timeArr[2];
						$SearchFor2 = add_db_quotes($this->field, $SearchFor2, $this->tName);
						$condition .= $gstrField."<".$SearchFor2;
					}
					else
						$condition .= $gstrField."<=".$value2;
				}
				else 
					$condition .= $gstrField."<=".$value2;	
			}
			else if( $this->multiselect ) 
			{
				if( strpos($value, ",") !== false || strpos($value, '"') !== false )
					$value = '"'.str_replace('"', '""', $value).'"';
				
				$fullFieldName = $gstrField;
				$value = $this->connection->escapeLIKEpattern( $value );
				
				//for search by multiply Lookup wizard field
				$ret .= $fullFieldName." = ". $this->connection->prepareString( $value );
				$ret .= " or ".$fullFieldName." ".$this->like." ". $this->connection->prepareString( "%,".$value.",%" );
				$ret .= " or ".$fullFieldName." ".$this->like." ". $this->connection->prepareString( "%,".$value );
				$ret .= " or ".$fullFieldName." ".$this->like." ". $this->connection->prepareString( $value.",%" );
			}
			
			if( $condition != "" && ($isSuggest || $strSearchOption == "Contains" || $strSearchOption == "Equals" ||$strSearchOption == "Starts with" 
				|| $strSearchOption == "More than" || $strSearchOption == "Less than" || $strSearchOption == "Equal or more than" 
				|| $strSearchOption == "Equal or less than" || $strSearchOption == "Between") )
			{
				if( $this->linkAndDisplaySame || ($strSearchOption != "Contains" && $strSearchOption != "Starts with") )
					$ret .= " ".$condition;
				else
					return "";
			}
		}
		
		$ret = trim($ret);
		if( strlen($ret) )
			$ret = "(".$ret.")";

		return $ret;
	}
	
	/**
	 * Form the control specified search options array and built the control's search options markup
	 * @param String selOpt		The search option value	
	 * @param Boolean not		It indicates if the search option negation is set 	
	 * @param Boolean both		It indicates if the control needs 'NOT'-options
	 * @return String			A string containing options markup
	 */
	function getSearchOptions($selOpt, $not, $both)
	{
		$optionsArray = array();
		if ($this->multiselect)
			$optionsArray[] = CONTAINS;	
		else
		{
			if($this->lookupType == LT_QUERY)
				$this->ciphererLookup = new RunnerCipherer($this->lookupTable);
				
			if( $this->ciphererLookup )
				$this->isDisplayFieldEncrypted = $this->ciphererLookup->isFieldPHPEncrypted( $this->displayFieldName );	
			
			if($this->LCType == LCT_AJAX && !$this->isDisplayFieldEncrypted)
			{
				if( $this->isSearchByDispalyedFieldAllowed() || $this->linkAndDisplaySame )
				{
				$optionsArray[] = CONTAINS;
				$optionsArray[] = STARTS_WITH;
				}
				$optionsArray[] = MORE_THAN;
				$optionsArray[] = LESS_THAN;
				$optionsArray[] = BETWEEN;
			}
			
			if( ($this->LCType == LCT_LIST || $this->LCType == LCT_DROPDOWN) && $this->isAdditionalControlRequired() )
			{
				$optionsArray[] = CONTAINS;
				$optionsArray[] = STARTS_WITH;
			}				
		}
		$optionsArray[] = EQUALS;
		$optionsArray[] = EMPTY_SEARCH;	
		
		if($both)
		{
			if ($this->multiselect)
				$optionsArray[] = NOT_CONTAINS;
			else 
			{
				if($this->LCType == LCT_AJAX && !$this->isDisplayFieldEncrypted) 
				{
					if( $this->isSearchByDispalyedFieldAllowed() || $this->linkAndDisplaySame )
					{					
					$optionsArray[] = NOT_CONTAINS;
					$optionsArray[] = NOT_STARTS_WITH;
					}
					$optionsArray[] = NOT_MORE_THAN;
					$optionsArray[] = NOT_LESS_THAN;
					$optionsArray[] = NOT_BETWEEN;
				}
				if( ($this->LCType == LCT_LIST || $this->LCType == LCT_DROPDOWN) && $this->isAdditionalControlRequired() )
				{
					$optionsArray[] = NOT_CONTAINS;
					$optionsArray[] = NOT_STARTS_WITH;				
				}
			}
			
			$optionsArray[] = NOT_EQUALS;
			$optionsArray[] = NOT_EMPTY;
		}
		
		return $this->buildSearchOptions($optionsArray, $selOpt, $not, $both);
	}
	
	/**
	 * Fill the response array with the suggest values
	 *
	 * @param String value	
	 *		Note: value is preceeded with "_" 
	 * @param String searchFor
	 * @param &Array response
	 * @param &Array row
	 */		
	function suggestValue($value, $searchFor, &$response, &$row)
	{
		if( !GetGlobalData("handleSearchSuggestInLookup", true) || $this->lookupType == LT_LISTOFVALUES || $this->isSearchByDispalyedFieldAllowed() )
		{
			parent::suggestValue($value, $searchFor, $response, $row);
			return;
		}
		
		// "_" is added to convert number type to string in lookupsuggest.php ($value)
		$lookupSQL = $this->getLookupSQL( array(), substr($value, 1), false, true, true, true);
		$this->fillLookupFieldsIndexes();
		
		$qResult = $this->lookupConnection->query( $lookupSQL );
		if( $data = $qResult->fetchNumeric() )
		{
			// "_" is added to convert number type to string
			if( $this->isDisplayFieldEncrypted )
			{			
				$lookup_value = "_" . $this->ciphererLookup->DecryptField( $this->displayFieldName , $data[ $this->displayFieldIndex ] );
			}
			else
				$lookup_value = "_" . $data[ $this->displayFieldIndex ];
			
			parent::suggestValue($lookup_value, $searchFor, $response, $row);
		}
	}


	/**
	 * Get the lookup SQL Query string used in the Display field search:
	 * SELECT linkField FROM lookupTable WHERE displayField LIKE '%aaa%'
	 *
	 * @param String dispFieldSearchClause - prepared WHERE clause for the display field filtering. 
	 * Sample:
	 *	 displayField LIKE '%aaa%'
	 * @return String	
	 */
	protected function getSearchSubquerySQL( $dispFieldSearchClause )
	{	
		if( $this->lookupType != LT_LOOKUPTABLE && $this->lookupType != LT_QUERY )
			return "";
			
		$pSet = $this->pageObject->pSetEdit;	
		
		if( $this->lookupType == LT_QUERY )
		{
			$this->fillLookupFieldsIndexes();
			
			$lookupQueryObj = $this->lookupPSet->getSQLQuery()->CloneObject();
			$lookupQueryObj->deleteAllFieldsExcept( $this->linkFieldIndex );
			return $lookupQueryObj->buildSQL_default( $dispFieldSearchClause );
		}		

		return "SELECT ".
			$this->lwLinkField.
			" FROM ".$this->lookupConnection->addTableWrappers( $this->lookupTable ).
			" WHERE ".$dispFieldSearchClause;
	}

	/**
	 * @return Array
	 */
	protected function getSQLWhereParts( $doValueFilter, $childVal, $doWhereFilter, $doCategoryFilter, $parentValuesData, $readyCategoryWhere, $oneRecordMode  )
	{
		$mandatoryWhere = array();	
		$mandatoryWhere[] = $doValueFilter ? $this->getChildWhere( $childVal ) : "";		
		
		if( $doWhereFilter )
		{
			$mandatoryWhere[] = prepareLookupWhere( $this->field, $this->pageObject->pSetEdit );
			
			if( $this->lookupType == LT_QUERY )
			{
				// #12975 - apply security clause with ADVSECURITY_VIEW_OWN only
				if( $this->lookupPSet->getAdvancedSecurityType() == ADVSECURITY_VIEW_OWN )
					$mandatoryWhere[] = SecuritySQL("Search", $this->lookupTable);
			}
		}	
		
		if( $doCategoryFilter ) 
		{
			if( $readyCategoryWhere )
				$mandatoryWhere[] = $readyCategoryWhere;
			else if( count( $parentValuesData ) > 0 )
				$mandatoryWhere[] = $this->getCategoryWhere( $parentValuesData );
		}
			
		if( $oneRecordMode && $this->lookupConnection->dbType == nDATABASE_Oracle ) 
			$mandatoryWhere[] = "rownum < 2";	
			
		return $mandatoryWhere;	
	}
	
	/**
	 * @param Array parentValuesData
	 * @param String childVal
	 * @param Boolean doCategoryFilter
	 * @param Boolean doValueFilter
	 * @param Boolean doWhereFilter
	 * @param Boolean oneRecordMode
	 * @param String readyCategoryWhere
	 *
	 * @return String
	 */
	protected function getProjectTableLookupSQL( $parentValuesData, $childVal, $doCategoryFilter, $doValueFilter, $doWhereFilter, $oneRecordMode, $readyCategoryWhere )
	{
		if( $this->lookupType != LT_QUERY )
			return "";	
		
		// build Order By clause
		$strOrderBy = $this->pageObject->pSetEdit->getLookupOrderBy( $this->field );
		$orderByClause = "";
		if( strlen($strOrderBy) )
		{
			$strOrderBy = RunnerPage::_getFieldSQLDecrypt( $strOrderBy, $this->lookupConnection, $this->lookupPSet, $this->ciphererLookup );			
			if( $this->pageObject->pSetEdit->isLookupDesc( $this->field ) )
				$strOrderBy.= ' DESC';
				
			$orderByClause = ' ORDER BY '.$strOrderBy;	
		}	
		
		$lookupQueryObj = $this->lookupPSet->getSQLQuery();
		$lookupQueryObj->ReplaceFieldsWithDummies( $this->lookupPSet->getBinaryFieldsIndices() );

		if ( trim($orderByClause) === "" ) {
			$orderByClause = $lookupQueryObj->OrderByToSql();
		}
		
		if( $this->customDisplay )
			$lookupQueryObj->AddCustomExpression($this->displayFieldName, $this->lookupPSet, $this->tName, $this->field);
	
		$sqlParts = $lookupQueryObj->getSqlComponents( $oneRecordMode );
		$mandatoryWhere = $this->getSQLWhereParts( $doValueFilter, $childVal, $doWhereFilter, $doCategoryFilter, $parentValuesData, $readyCategoryWhere, $oneRecordMode );
		
		$strSQL = SQLQuery::buildSQL( $sqlParts, $mandatoryWhere );
		$strSQL.= " ".trim( $orderByClause );

		if( $oneRecordMode )
			$strSQL.= $this->getOneRecordModeTailPart();
		
		return $strSQL;
	}
	
	/**
	 * @param Array parentValuesData
	 * @param String childVal
	 * @param Boolean doCategoryFilter
	 * @param Boolean doValueFilter
	 * @param Boolean doWhereFilter
	 * @param Boolean oneRecordMode
	 * @param String readyCategoryWhere
	 *
	 * @return String
	 */
	protected function getNotProjectTableLookupSQL( $parentValuesData, $childVal, $doCategoryFilter, $doValueFilter, $doWhereFilter, $oneRecordMode, $readyCategoryWhere )
	{
		if( $this->lookupType != LT_LOOKUPTABLE )
			return "";
			
		$pSet = $this->pageObject->pSetEdit;	
		
		// build Order By clause
		$strOrderBy = $pSet->getLookupOrderBy($this->field);
		if( $this->lookupConnection->dbType == nDATABASE_MSSQLServer )
			$strUniqueOrderBy = $strOrderBy;

		if( strlen($strOrderBy) )
		{
			$strOrderBy = $this->lookupConnection->addFieldWrappers($strOrderBy);
				
			if( $pSet->isLookupDesc($this->field) )
				$strOrderBy .= ' DESC';
		}
		
		// build Where clause
		$mandatoryWhere = $this->getSQLWhereParts( $doValueFilter, $childVal, $doWhereFilter, $doCategoryFilter, $parentValuesData, $readyCategoryWhere, $oneRecordMode );
		$strWhere = combineSQLCriteria( $mandatoryWhere );

			
		$LookupSQL = "SELECT ";
		if( $this->lookupConnection->dbType == nDATABASE_MSSQLServer || $this->lookupConnection->dbType == nDATABASE_Access )
		{
			if( $oneRecordMode )
				$LookupSQL.= "top 1 ";
		}
		$bUnique = $pSet->isLookupUnique($this->field);
		
		if( $bUnique && !$oneRecordMode )
			$LookupSQL.= "DISTINCT ";
			
		$LookupSQL.= $this->lwLinkField;
		
		if( !$this->linkAndDisplaySame )
			$LookupSQL.= ",".RunnerPage::sqlFormattedDisplayField($this->field, $this->lookupConnection, $pSet);
		
		if( $this->lookupConnection->dbType == nDATABASE_MSSQLServer )
		{
			if( $strUniqueOrderBy && $bUnique && !$oneRecordMode )
				$LookupSQL.= ",".$this->lookupConnection->addFieldWrappers( $strUniqueOrderBy );
		}
		
		$LookupSQL.= " FROM ".$this->lookupConnection->addTableWrappers( $this->lookupTable );
	
		if( strlen($strWhere) )
			$LookupSQL.=" WHERE ".$strWhere;

		if( strlen($strOrderBy) )
			$LookupSQL.= " ORDER BY ".$this->lookupConnection->addTableWrappers( $this->lookupTable ).".".$strOrderBy;
		
		if( $oneRecordMode )
			$LookupSQL.= $this->getOneRecordModeTailPart();
		
		return $LookupSQL;		
	}
	
	/**
	 * @return Strings
	 */
	protected function getOneRecordModeTailPart()
	{
		if( $this->lookupConnection->dbType == nDATABASE_MySQL )
			return " limit 0,1";
		
		if( $this->lookupConnection->dbType == nDATABASE_PostgreSQL )
			return " limit 1";
		
		if( $this->lookupConnection->dbType == nDATABASE_DB2 )
			return " fetch first 1 rows only";

		return "";
	}
	
	/**
	 * Get the lookup SQL Query string
	 *
	 * @param Array parentValuesData
	 * @param String childVal
	 * @param Boolean doCategoryFilter (optional)
	 * @param Boolean doValueFilter (optional)
	 * @param Boolean doWhereFilter (optional)
	 * @param Boolean oneRecordMode (optional)
	 * @param String readyCategoryWhere (optional)
	 * @return String	
	 */
	public function getLookupSQL( $parentValuesData, $childVal = "", $doCategoryFilter = true, $doValueFilter = false, $doWhereFilter = true, $oneRecordMode = false, $readyCategoryWhere = "" )
	{
		if( $this->lookupType == LT_QUERY )
			return $this->getProjectTableLookupSQL( $parentValuesData, $childVal, $doCategoryFilter, $doValueFilter, $doWhereFilter, $oneRecordMode, $readyCategoryWhere );
		
		if( $this->lookupType == LT_LOOKUPTABLE )
			return $this->getNotProjectTableLookupSQL( $parentValuesData, $childVal, $doCategoryFilter, $doValueFilter, $doWhereFilter, $oneRecordMode, $readyCategoryWhere );

		return "";
	}

	
	/**
	 * @user API
	 * @param String mainControlName
	 * @param String filterFieldName
	 * @param String mainControlVal
	 * @return String
	 */
	public function getCategoryCondition( $mainControlName, $filterFieldName, $mainControlVal )
	{
		$parentValsPlain = $this->pageObject->pSetEdit->multiSelect( $mainControlName ) ? splitvalues( $mainControlVal ) : array( $mainControlVal );

		$parentVals = array();			
		foreach($parentValsPlain as $arKey => $arElement)
		{
			if( $this->lookupType == LT_QUERY )
				$parentVals[ $arKey ] = $this->ciphererLookup->MakeDBValue( $filterFieldName, $arElement, '', true );
			else
				$parentVals[ $arKey ] = make_db_value( $mainControlName, $arElement, '', '', $this->tName );
		}
		
		$categoryWhere = array();
		foreach($parentVals as $arKey => $arValue)
		{
			$condition = $arValue === "null" ? " is null" : "=".$arValue;
			
			if( $this->lookupType == LT_QUERY )
				$categoryWhere[] = $this->ciphererLookup->GetFieldName( RunnerPage::_getFieldSQL( $filterFieldName, $this->lookupConnection, $this->lookupPSet ), $filterFieldName ).$condition;
			else
				$categoryWhere[] = $this->lookupConnection->addFieldWrappers( $filterFieldName ).$condition;
		}

		return (count($categoryWhere) == 1) ? $categoryWhere[0] : "(".implode(" OR ", $categoryWhere).")";			
	}
	
	/**
	 * Get a where condition for a dependent lookup
	 * @param Array parentValueData
	 * @return String
	 */
	protected function getCategoryWhere( $parentValueData )
	{
		if( !$this->bUseCategory )
			return "";
		
		$whereParts = array();
		
		foreach( $this->pageObject->pSetEdit->getParentFieldsData( $this->field ) as $cdata )
		{
			$mainControlName = $cdata['main'];
			$filterFieldName = $cdata['lookup'];
			$mainControlVal = $parentValueData[ $mainControlName ];
			
			if( $this->pageObject->pSetEdit->multiSelect( $mainControlName ) || strlen( $mainControlVal ) )
				$whereParts[] = $this->getCategoryCondition(  $mainControlName, $filterFieldName, $mainControlVal );	
		}
		
		if( !count( $whereParts ) )
			return "";
		
		return "(".implode(" AND ", $whereParts).")";			
	}
	
	/**
	 * Get a where condition basing on curren't lookup control's values
	 * @param String childVal
	 * @return String
	 */
	protected function getChildWhere( $childVal ) 
	{				
		if( $this->lookupType == LT_QUERY )
		{
			$fullLinkFieldName = RunnerPage::_getFieldSQLDecrypt( $this->linkFieldName, 
				$this->lookupConnection, 
				$this->lookupPSet, 
				$this->ciphererLookup );
		}
		else
			$fullLinkFieldName = $this->lwLinkField;
			
		$childValues = $this->multiselect ? splitvalues( $childVal ) : array( $childVal );
		$childWheres = array();

		foreach( $childValues as $childValue ) 
		{
			if( $this->lookupType == LT_QUERY )
				$dbValue = $this->ciphererLookup->MakeDBValue( $this->linkFieldName, $childValue, "", true );
			else
				$dbValue = make_db_value($this->field, $childValue, '', '', $this->tName);
				
			$childWheres[] = $fullLinkFieldName.( $dbValue === "null" ? " is null" : "=".$dbValue );	
		}				
		
		return implode(' OR ', $childWheres);
	}	

	/**
	 * @param Array parentValuesData
	 * @return Boolean
	 */
	protected function needCategoryFiltering( $parentValuesData )
	{
		foreach( $this->pageObject->pSetEdit->getParentFieldsData( $this->field ) as $cData ) 
		{
			$strCategoryControl = $cData['main'];

			if( !isset( $parentValuesData[ $cData['main'] ] ) )
				continue ;
				
			$parentValue = $parentValuesData[ $cData['main'] ];	
			$parentVals = $this->pageObject->pSetEdit->multiSelect($strCategoryControl) ? splitvalues( $parentValue ) : array( $parentValue );
			
			foreach( $parentVals as $parentVal ) 
			{
				if( !strlen($parentVal) )
					continue;
				
				if( $this->lookupType == LT_QUERY )
					$tempParentVal = $this->ciphererLookup->MakeDBValue( $cData['main'], $parentVal, "", true );
				else
					$tempParentVal = make_db_value($this->field, $parentVal);

				if( $tempParentVal !== "null" )			
					return true;
			}				
		}
		
		return false;
	}
	
	/**
	 * Get for the dependent lookup an array containing the link field values with even indices
	 * and the corresponding displayed values with odd indices
	 *
	 * @intellisense
	 * @param Array parentValuesData
	 * @param String childVal
	 * @param Boolean doCategoryFilter
	 * @param Boolean initialLoad
	 * @return Array
	 */
	public function loadLookupContent( $parentValuesData, $childVal = "", $doCategoryFilter = true, $initialLoad = true )
	{	
		if( $this->bUseCategory && $doCategoryFilter )
		{
			if( !$this->needCategoryFiltering( $parentValuesData ) )
				return array();
		}
		
		$lookupSQL = $this->getLookupSQL( $parentValuesData, $childVal, $doCategoryFilter, $this->LCType == LCT_AJAX && $initialLoad );	
		return $this->getLookupContentDataBySql( $lookupSQL, $childVal );
	}
	
	/**
	 * @param String lookupSQL
	 * @param String childVal
	 * @return Array
	 */
	public function getLookupContentDataBySql( $lookupSQL, $childVal = "" )
	{
		$responce = array();
		$lookupIndexes = GetLookupFieldsIndexes( $this->pageObject->pSetEdit, $this->field );
		
		$qResult = $this->lookupConnection->query( $lookupSQL );

		if( $this->LCType !== LCT_AJAX || $this->multiselect )
		{
			$isUnique = $this->pageObject->pSetEdit->isLookupUnique( $this->field );
			$uniqueArray = array();
			while( $data = $qResult->fetchNumeric() ) 
			{
				if( $this->lookupType == LT_QUERY && $isUnique )
				{
					if( in_array($data[ $lookupIndexes["displayFieldIndex"] ], $uniqueArray) )
						continue;
						
					$uniqueArray[] = $data[ $lookupIndexes["displayFieldIndex"] ];
				}
				$response[] = $data[ $lookupIndexes["linkFieldIndex"] ];
				$response[] = $data[ $lookupIndexes["displayFieldIndex"] ];
			}
		}
		else
		{
			$data = $qResult->fetchNumeric();
			// one record only
			if( $data && ( !$this->multiselect && strlen( $childVal ) || !$qResult->fetchNumeric() ) )
			{
				$response[] = $data[ $lookupIndexes["linkFieldIndex"] ];
				$response[] = $data[ $lookupIndexes["displayFieldIndex"] ];
			}
		}
		
		return $response;
	}
	
	/**
	 * @param Boolean isExistParent
	 * @param Number mode
	 * @param Array parentCtrlsData
	 * @return Mixed
	 */
	public function getLookupContentToReload( $isExistParent, $mode, $parentCtrlsData )
	{
		// there are parent lookups on the page
		if( $isExistParent )
		{
			$hasEmptyParent = false;
			foreach($parentCtrlsData as $value)
			{
				if( $value === '' ) 
				{
					$hasEmptyParent = true;
					break;
				}
			}
	
			if( !$hasEmptyParent ) 
			{
				// there are parent lookups on the page none of them is empty
				return $this->loadLookupContent( $parentCtrlsData, '', true, false );			
			}
			
			if( $mode == MODE_SEARCH || $mode == MODE_EDIT || $mode == MODE_INLINE_EDIT || $mode == MODE_INLINE_ADD || $mode == MODE_ADD )
				return '';
			
			// which mode is this?
			return $this->loadLookupContent( $parentCtrlsData, '', true, false );
		}		
		// there are not parent lookups on the page 
		else
		{
			if( $mode == MODE_SEARCH || $mode == MODE_INLINE_ADD || $mode == MODE_ADD || $mode == MODE_EDIT || $mode == MODE_INLINE_EDIT )
				return $this->loadLookupContent( array(), '', false, false ); 
			
			return $this->loadLookupContent( array(), '', true, false );
		}
		
		return '';
	}
	
	/**
	 * @param String linkFieldVal
	 * @return Array
	 */
	public function getAutoFillData( $linkFieldVal )
	{		
		$data = array();
		
		if( $this->lookupType == LT_QUERY )
		{		
			$strSQL = $this->getLookupSQL( array(), $linkFieldVal, false, true, true );		
			$row = $this->lookupConnection->query( $strSQL )->fetchAssoc();
			$data =  $this->ciphererLookup->DecryptFetchedArray( $row );
		}
		else
		{
			$strSQL = $this->getLookupSQL( array(), $linkFieldVal, false, true, true );	
			$row = $this->lookupConnection->query( $strSQL )->fetchAssoc();
			
			$autoCompleteFields = $this->pageObject->pSetEdit->getAutoCompleteFields( $this->field );
			foreach( $autoCompleteFields as $aData )
			{
				$data[ $aData["lookupF"] ] = $row[ $aData["lookupF"] ];
			}	
		}

		$this->lookupConnection->close();	
			
		if( !count( $data ) )
			$data = array( $this->field => '' );
		
		return $data;
	}
	
	
	/**
	 *
	 */
	public function getConnection()
	{
		return $this->lookupConnection;
	}
	
	function getInputStyle( $mode ) 
	{
		if ($this->pageObject->isBootstrap())
			return "class='form-control'";
		else
			return parent::getInputStyle($mode);
	}
	
	/**
	 * Select & Add new links block
	 * @param Boolean hiddenSelect
	 * @return String
	 */
	protected function getLookupLinks( $hiddenSelect = false )
	{		
		$links = array();
		
		if( $this->LCType == LCT_LIST )
		{
			$visibility = $hiddenSelect ? ' style="visibility: hidden;"' : '';
			$links[] = '<a href="#" id="'.$this->openlookup.'"'.$visibility.'>'."Select".'</a>';
		}
		
		if( $this->addNewItem )
			$links[] = '<a href="#" id="addnew_'.$this->cfield.'">'."Tambah baru".'</a>';
		
		if( !count($links) )
			return "";
			
		if( $this->pageObject->isBootstrap() )
			return '<div class="bs-lookup-links">'.implode("", $links).'</div>';

		return '&nbsp;'.implode('&nbsp;', $links);
	}
}
?>