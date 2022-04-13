<?php
class EditControl
{
	/**
	 * Reference to RunnerPage (or its descendant) instance 
	 */
	public $pageObject = null;
	/**
	 * Reference to EditControlsContainer instance
	 */
	public $container = null;
	
	public $id = "";
	public $field = "";
	public $goodFieldName = "";
	public $format = "";
	/**
	 * Field name prefix
	 * @var {string}
	 */
	public $cfieldname = "";
	/**
	 * Value field name
	 * @var {string}
	 */
	public $cfield = "";
	/**
	 * Type field name
	 * @var {string}
	 */
	public $ctype = "";
	/**
	 * A flag indicating whether the support for section 508 is on
	 * @var {bool}
	 */
	public $is508 = false;
	public $strLabel = "";
	public $type = "";
	public $inputStyle = "";
	public $iquery = "";
	public $keylink = "";
	public $webValue = null;
	public $webType = null;
	
	/**
	 * Storage for control settings. It fills in the init() function. 
	 * @var {array}
	 */
	public $settings = array();
	
	//Search params
	public $isOracle = false;
	public $ismssql = false;
	public $isdb2 = false;
	public $btexttype = false;
	public $isMysql = false;
	public $like = "like";
	
	public $searchOptions = array();
	
	public $searchPanelControl = false;
	public $data = array();
	
	/**
	 * @type Connection
	 */
	protected $connection;
	
	
	function __construct($field, $pageObject, $id, $connection)
	{
		$this->field = $field;
		$this->goodFieldName = GoodFieldName($field);
		$this->setID($id);
		$this->connection = $connection;

		$this->pageObject = $pageObject;

		$this->is508 = isEnableSection508();
		
		$this->strLabel = $pageObject->pSetEdit->label($field);
		$this->type = $pageObject->pSetEdit->getFieldType($this->field);
		
		if( $this->connection->dbType == nDATABASE_Oracle )
			$this->isOracle = true;

		if( $this->connection->dbType == nDATABASE_MSSQLServer )
			$this->ismssql=true;

		if( $this->connection->dbType == nDATABASE_DB2 )
			$this->isdb2=true;

		if( $this->connection->dbType == nDATABASE_MySQL )
			$this->isMysql = true;

		if( $this->connection->dbType == nDATABASE_PostgreSQL )
			$this->like = "ilike";

		$this->searchOptions[CONTAINS] = "Berisi";
		$this->searchOptions[EQUALS] = "Sama dengan";
		$this->searchOptions[STARTS_WITH] = "Mulai dengan";
		$this->searchOptions[MORE_THAN] = "Lebih dari";
		$this->searchOptions[LESS_THAN] = "Kurang dari";
		$this->searchOptions[BETWEEN] = "Antara";
		$this->searchOptions[EMPTY_SEARCH] = "Kosong";
		$this->searchOptions[NOT_CONTAINS] = "Doesn't contain";
		$this->searchOptions[NOT_EQUALS] = "Doesn't equal";
		$this->searchOptions[NOT_STARTS_WITH] = "Doesn't start with";
		$this->searchOptions[NOT_MORE_THAN] = "Is not more than";
		$this->searchOptions[NOT_LESS_THAN] = "Is not less than";
		$this->searchOptions[NOT_BETWEEN] = "Is not between";
		$this->searchOptions[NOT_EMPTY] = "Is not empty";
		
		$this->init();
	}
	
	function setID($id)
	{
		$this->id = $id;		
		$this->cfieldname = $this->goodFieldName."_".$id;
		$this->cfield = "value_".$this->goodFieldName."_".$id;
		$this->ctype = "type_".$this->goodFieldName."_".$id;
	}
	 
	/**
	 * addJSFiles
	 * Add control JS files to page object
	 */
	function addJSFiles()
	{
		//example
		// $this->pageObject->AddJSFile("include/mupload.js");
	}
	
	/**
	 * addCSSFiles
	 * Add control CSS files to page object
	 */ 
	function addCSSFiles()
	{
		//example
		// $this->pageObject->AddCSSFile("include/mupload.css");
	}
	
	function getSetting($key)
	{
		return $this->pageObject->pSetEdit->getFieldData($this->field, $key);
	}
	
	function addJSSetting($key, $value)
	{
		$this->pageObject->jsSettings['tableSettings'][ $this->pageObject->tName ]['fieldSettings'][ $this->field ][ $this->container->pageType ][ $key ] = $value;
	}
	
	function buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data)
	{
		$this->searchPanelControl = $this->isSearchPanelControl( $mode, $additionalCtrlParams );
		$this->inputStyle = $this->getInputStyle( $mode );
	
		if($fieldNum)
		{
			$this->cfield="value".$fieldNum."_".$this->goodFieldName."_".$this->id;
			$this->ctype="type".$fieldNum."_".$this->goodFieldName."_".$this->id;
		}

		$this->iquery = "field=".rawurlencode($this->field);
	
		$arrKeys = $this->pageObject->pSetEdit->getTableKeys();
		for ($j = 0; $j < count($arrKeys); $j++) 
		{
			$this->keylink .= "&key".($j+1)."=".rawurlencode( $data[ $arrKeys[$j] ] );
		}
		$this->iquery .= $this->keylink;
					
		$isHidden = (isset($additionalCtrlParams['hidden']) && $additionalCtrlParams['hidden']);

		$additionalClass = "";
		if( $this->pageObject->isBootstrap() )
		{		
			if( $this->pageObject->isPD() ) {
				$additionalClass.= "bs-ctrlspan ";
			} else {
				$additionalClass.= "bs-ctrlspan rnr-nowrap ";
			}
			if( $this->format == EDIT_FORMAT_READONLY )
				$additionalClass.= "form-control-static ";
			
			if( count($validate['basicValidate']) && array_search('IsRequired', $validate['basicValidate']) !== false )	
				$additionalClass.= "bs-inlinerequired";
		}
		else
		{
			$additionalClass.= "rnr-nowrap ";
		}

		echo '<span id="edit'.$this->id.'_'.$this->goodFieldName.'_'.$fieldNum.'" class="'.$additionalClass.'"'.($isHidden ? ' style="display:none"' : '').'>';
	}

	function getFirstElementId()
	{
		return false;
	}
	
	/**
	 * Check if the control belongs to the Search Panel
	 * @param Number mode
	 * @param Array additionalCtrlParams
	 * @return Boolean
	 */
	function isSearchPanelControl( $mode, $additionalCtrlParams )
	{
		return $mode == MODE_SEARCH && isset( $additionalCtrlParams['searchPanelControl'] ) && $additionalCtrlParams['searchPanelControl'] && !$this->pageObject->mobileTemplateMode();
	}
	
	function buildControlEnd($validate, $mode)
	{
		if( $this->pageObject->isBootstrap() )
			echo '</span>';
		else if(count($validate['basicValidate']) && array_search('IsRequired', $validate['basicValidate'])!==false)
			echo'&nbsp;<font color="red">*</font></span>';
		else
			echo '</span>';
	}
	
	function getPostValueAndType()
	{
		$this->webValue = postvalue("value_".$this->goodFieldName."_".$this->id);
		$this->webType = postvalue("type_".$this->goodFieldName."_".$this->id);
	}
	
	function getWebValue()
	{
		return $this->webValue;
	}
	
	function readWebValue(&$avalues, &$blobfields, $legacy1, $legacy2, &$filename_values)
	{
		$this->getPostValueAndType();
		
		if (FieldSubmitted($this->goodFieldName."_".$this->id))
			$this->webValue = prepare_for_db($this->field, $this->webValue, $this->webType);
		else
			$this->webValue = false;
			
		if($this->pageObject->pageType == PAGE_EDIT && $this->pageObject->pSetEdit->isReadonly($this->field))
		{
			if( $this->pageObject->pSetEdit->getAutoUpdateValue($this->field) ) 
				$this->webValue = $this->pageObject->pSetEdit->getAutoUpdateValue($this->field);
			else
				if($this->pageObject->pSetEdit->getOwnerTable($this->field) != $this->pageObject->pSetEdit->getStrOriginalTableName())
					$this->webValue = false;
		}
		
		if(!($this->webValue===false))
		{
			if( $this->connection->dbType == nDATABASE_Informix )
			{
				if(IsTextType($this->pageObject->pSetEdit->getFieldType($this->field)))
					$blobfields[] = $this->field;
			}
			if($this->field == "password" && $this->pageObject->tName == "admin_users")
			{	
				$needHashPass = true;
				if ( $this->pageObject->pageType == PAGE_EDIT )
				{
					$oldData = $this->pageObject->getOldRecordData();
					$needHashPass = $oldData[$this->field] != $this->webValue;
				}

				if ( $needHashPass )
					$this->webValue = $this->pageObject->getPasswordHash( $this->webValue );	
			}
			$avalues[ $this->field ] = $this->webValue;
		}
	} 
	
	/**
	 * @param String strSearchOption
	 * @return String | Boolean
	 */
	function baseSQLWhere($strSearchOption)
	{
		if(IsBinaryType($this->type))
			return false;
		
		if( $this->connection->dbType != nDATABASE_MySQL )	
			$this->btexttype = IsTextType($this->type);
		
		if( $this->connection->dbType == nDATABASE_MSSQLServer )
		{
			if($this->btexttype && $strSearchOption!="Contains" && $strSearchOption!="Starts with" )
				return false;
		}
		
		if($strSearchOption != 'Empty')
			return "";
		
		$fullFieldName = $this->getFieldSQLDecrypt();
		
		if( IsCharType($this->type) && (!$this->ismssql || !$this->btexttype) && !$this->isOracle )
			return "(".$fullFieldName." is null or ".$fullFieldName."='')";
		
		if( $this->ismssql && $this->btexttype )
			return "(".$fullFieldName." is null or ".$fullFieldName." LIKE '')";
		
		return $fullFieldName." is null";
	}
	
	/**
	 * Get the WHERE clause conditions string for the search or suggest SQL query
	 * @param String SearchFor
	 * @param String strSearchOption
	 * @param String SearchFor2
	 * @param String etype
	 * @param Boolean isSuggest
	 */
	function SQLWhere($SearchFor, $strSearchOption, $SearchFor2, $etype, $isSuggest)
	{
		$baseResult = $this->baseSQLWhere($strSearchOption);
		
		if( $baseResult === false )
			return "";
		
		if( $baseResult != "" )
			return $baseResult;
		
		if( !strlen($SearchFor) && !strlen($SearchFor2) )
			return "";
		
		$value1 = $this->pageObject->cipherer->MakeDBValue($this->field, $SearchFor, $etype, true);
		$value2 = false;
		$cleanvalue2 = false;
		
		if( $strSearchOption == "Between" )
		{
			$cleanvalue2 = prepare_for_db($this->field, $SearchFor2, $etype);
			$value2 = make_db_value($this->field, $SearchFor2, $etype);
		}
			
		if( $strSearchOption != "Contains" && $strSearchOption != "Starts with" && ($value1 === "null" && $value2 === "null" )
			&& !$this->pageObject->cipherer->isFieldPHPEncrypted($this->field) )
		{	
			return "";
		}
		
		if( ($strSearchOption == "Contains" || $strSearchOption == "Starts with") && !$this->isStringValidForLike($SearchFor) )
			return "";
		
		$searchIsCaseInsensitive = $this->pageObject->pSetEdit->getNCSearch();
		
		if( IsCharType($this->type) && !$this->btexttype )
		{
			$gstrField = $this->getFieldSQLDecrypt();
			
			if( !$this->pageObject->cipherer->isFieldPHPEncrypted($this->field) && $searchIsCaseInsensitive )
			{
				if ( strlen($SearchFor) )
					$value1 = $this->connection->upper( $value1 );
				if ( strlen($SearchFor2) )
					$value2 = $this->connection->upper( $value2 );
				$gstrField = $this->connection->upper( $gstrField );
			}
		}	
		elseif( $strSearchOption == "Contains" || $strSearchOption == "Starts with" )
		{
			$gstrField = $this->connection->field2char($this->getFieldSQLDecrypt(), $this->type);
		}
		elseif( $this->pageObject->pSetEdit->getViewFormat($this->field) == FORMAT_TIME )
		{
			$gstrField = $this->connection->field2time($this->getFieldSQLDecrypt(), $this->type);
		}
		else 
		{
			$gstrField = $this->getFieldSQLDecrypt();
		}

		
		if( $strSearchOption == "Contains" )
		{
			if( $this->pageObject->cipherer->isFieldPHPEncrypted($this->field) )
				return $gstrField."=".$this->pageObject->cipherer->MakeDBValue($this->field, $SearchFor);
			
			$SearchFor = $this->connection->escapeLIKEpattern( $SearchFor );
			
			if( IsCharType($this->type) && !$this->btexttype && $searchIsCaseInsensitive )
				return $gstrField." ".$this->like." ".$this->connection->upper( $this->connection->prepareString("%".$SearchFor."%") );
			
			return $gstrField." ".$this->like." ".$this->connection->prepareString("%".$SearchFor."%");
		}
		
		if( $strSearchOption == "Equals" ) 
			return $gstrField."=".$value1;
		
		if( $strSearchOption == "Starts with" )
		{
			$SearchFor = $this->connection->escapeLIKEpattern( $SearchFor );
			
			if( IsCharType($this->type) && !$this->btexttype && $searchIsCaseInsensitive )
				return $gstrField." ".$this->like." ".$this->connection->upper( $this->connection->prepareString($SearchFor."%") );
			
			return $gstrField." ".$this->like." ".$this->connection->prepareString($SearchFor."%");
		}
		
		if( $strSearchOption == "More than" )
			return $gstrField.">".$value1;
		
		if( $strSearchOption == "Less than" ) 
			return $gstrField."<".$value1;
		
		if( $strSearchOption == "Equal or more than" ) 
			return $gstrField.">=".$value1;
			
		if( $strSearchOption == "Equal or less than" )
			return $gstrField."<=".$value1;
			
		if( $strSearchOption == "Between" )
		{
			$betweenRange = array();
			if ( $value1 !== "null" && strlen($SearchFor) )
			{
				$betweenRange["from"] = $gstrField.">=".$value1;
			}

			if ( $value2 !== "null" && strlen($SearchFor2) )
			{
				if (IsDateFieldType($this->type))
				{
					$timeArr = db2time($cleanvalue2);
					// for dates without time, add one day
					if ($timeArr[3] == 0 && $timeArr[4] == 0 && $timeArr[5] == 0)
					{
						$timeArr = adddays($timeArr, 1);

						$value2 = mysprintf("%02d-%02d-%02d",$timeArr);
						$value2 = add_db_quotes($this->field, $value2, $this->pageObject->tName);
						$betweenRange["to"] = $gstrField . "<" . $value2;
					}
					else
					{
						$betweenRange["to"] = $gstrField . "<=" . $value2;
					}
				}
				else 
				{
					$betweenRange["to"] = $gstrField . "<=" . $value2;
				}
			}

			return implode(" and ", $betweenRange);
		}
		
		return "";
	}
	
	/**
	 * A wrapper for the SearchClause SQLWhere method
	 */
	public function getSearchWhere($searchFor, $strSearchOption, $searchFor2, $etype)
	{
		return $this->SQLWhere($searchFor, $strSearchOption, $searchFor2, $etype, false);
	}
	
	/**
	 * Get an extra WHERE clause condtion	
	 * that helps to retrieve a field's search suggest value
	 * @param String searchOpt
	 * @param String searchFor
	 * @param Boolean isAggregateField (optional)
	 * @return String
	 */
	public function getSuggestWhere($searchOpt, $searchFor, $isAggregateField = false) 
	{
		if( $isAggregateField )
			return "";
		return $this->SQLWhere($searchFor, $searchOpt, "", "", true);
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
		if( $isAggregateField )
			return $this->SQLWhere($searchFor, $searchOpt, "", "", true);
		return "";
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
		return array(
			"selectColumns"=> $this->getFieldSQLDecrypt(),
			"joinFromPart"=> ""
		);
	}
	
	/**
	 * @param String strSearchOption
	 * @return Boolean
	 */
	public function checkIfDisplayFieldSearch( $strSearchOption )
	{
		return false;
	}	
	
	/**
	 * Form the control's search options markup basing on user's search options settings
	 * @param Array optionsArray	Control specified search options
	 * @param String selOpt		 	The control selected search option
	 * @param Boolean not			It indicates if the search option passed should be inverted ($selOpt should be considered as "NOT ".$selOpt)
	 * @param Boolean both			It indicates if control needs both positive and negative("NOT ...") search options
	 * @return String
	 */
	function buildSearchOptions($optionsArray, $selOpt, $not, $both)
	{
		$userSearchOptions = $this->pageObject->pSetEdit->getSearchOptionsList( $this->field );
		
		$currentOption = $not ? 'NOT '.$selOpt : $selOpt;
		if( count($userSearchOptions) && isset( $this->searchOptions[ $currentOption ] ) )
			$userSearchOptions[] = $currentOption;
			
		if( count($userSearchOptions) ) 
			$optionsArray = array_intersect($optionsArray, $userSearchOptions);

		$defaultOption = $this->pageObject->pSetEdit->getDefaultSearchOption( $this->field );	
		if( !$defaultOption )
			$defaultOption = $optionsArray[0];
			
		$result = ''; 
		foreach($optionsArray as $option)
		{
			if( !isset( $this->searchOptions[ $option ] ) || !$both && substr($option, 0, 4) == 'NOT ' )
				continue;

			$selected = $currentOption == $option ? 'selected' : '';
			$dataAttr = $defaultOption == $option ? ' data-default-option="true"' : '';
			$result.= '<option value="'.$option.'" '.$selected.$dataAttr.'>'.$this->searchOptions[ $option ].'</option>';
		}
		return $result;
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
		return $this->buildSearchOptions(array(EQUALS, NOT_EQUALS), $selOpt, $not, $both);
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
		$suggestStringSize = GetGlobalData("suggestStringSize", 40);
		
		if( $suggestStringSize <= runner_strlen($searchFor) )
		{
			$response[ "_".$searchFor ] = $searchFor;
			return;
		}
		
		$viewFormat = $this->pageObject->pSetEdit->getViewFormat($this->field);
		if( $viewFormat == FORMAT_NUMBER )
		{
			$dotPosition = strpos($value, '.'); 
			if($dotPosition !== FALSE)
			{
				for($i = strlen($value) - 1; $i > $dotPosition; $i--) 
				{
					if(substr($value, $i, 1) != '0')
					{
						if($i < strlen($value) - 1)
							$value = substr($value, 0, $i + 1);
						break;
					}
					if($i == $dotPosition + 1 && $dotPosition > 0)
					{
						$value = substr($value, 0, $dotPosition);
						break;
					}
				}
			}
		}
		
		$realValue = $value;
		
		if( $viewFormat == FORMAT_HTML )
		{
			// declarate patterns for regex
			$html_tags = '/<.*?>/i'.($useUTF8 ? 'u':'');
			$get_text = '/(.*<.*>|^.*?)([.]*'.preg_quote($searchFor, "/").'.*?)(<.*>|$)/i'.($useUTF8 ? 'u':'');
			
			// decode html entity and delete all html tags from value
			$value = preg_replace($html_tags, '', runner_html_entity_decode($value));
			
			// if not searchFor in value return
			if (stristr($value, $searchFor) === false)
				return;
			
			// get realValue (string between html tags)
			if (preg_match($get_text, $realValue, $match))
				$realValue = $match[2];
			else
				$realValue = $value;
		}
		
		// if large string cut value and add dots
		if( $suggestStringSize < runner_strlen($value) )
		{
            $startPos = 0;
            $valueLength = 0;
			$suggestValues = $this->cutSuggestString( $value, $searchFor );
			if( $suggestValues ) {
				if( $viewFormat == FORMAT_HTML ) {
					$suggestValues["search"] = $realValue;
				}
				$response[ $suggestValues["display"] ] = $suggestValues["search"];
			}
		} else {
			$response[ $value ] = $realValue;
		}
	}
	
	/**
	 * Reduce long field value to leave only the text relevant to search suggest
	 * ( "There was a time when Mary had a little lamb", "Mary" ) => "when Mary had"
	 * @return Array - array of ( 
	 * 	"search" => "when Mary had" - value that will be used for searching
	 * 	"display" => "...when Mary had..." - value to show to the user in the suggest list
	 * )
	 * Returns false if anything went wrong
	 */
	function cutSuggestString( $_value, $searchFor ) 
	{
		$suggestStringSize = GetGlobalData("suggestStringSize", 40);
		$caseInsensitive = $this->pageObject->pSetEdit->getNCSearch();
		
		//	split to lines. Line breaks shouldn't appear in the suggested values
		$lines = explode( "\n", $_value );
		$value = "";
		for( $lineIdx = 0; $lineIdx< count( $lines); ++$lineIdx ) {
			$line = $lines[ $lineIdx ];
			if( $this->pageObject->pSetEdit->getNCSearch() )
			{
					// case-insensitive search 
					$startPos = stripos($line, $searchFor);
					if( $startPos )
						$startPos = runner_strlen( substr($line, 0 , $startPos) ); //UTF-8 support
			}
			else 
			{
					$startPos = runner_strpos($line, $searchFor);
			}
			if( $startPos !== false )
			{
				$value = $line;
				break;
			}
		}
		if( $startPos === false ) {
			return false;
		}
		
		//	cut a chunk of the $value around the $searchFor. 
		//	Paddings are parts of the chunk before and after $searchFor
		//	There are two "gray zones" at the begining and end of the chunk.
		//	If there are stop symbols ( spaces, commas ) in the gray zone, cut it up to them
		//	"tion of the next occu" => "of the next"
		
		$grayZoneLength = 5;
		
		$leftPaddingLength = min( $suggestStringSize / 2, $startPos );
		$leftPadding = runner_substr( $value, $startPos - $leftPaddingLength, $leftPaddingLength );
		$leftGrayZoneLength = $leftPaddingLength < $suggestStringSize / 2
			? 0
			: $grayZoneLength;

		$rightPaddingLength = min( $suggestStringSize - $leftPaddingLength, runner_strlen( $value ) - $startPos - runner_strlen( $searchFor ) );
		$rightPadding = runner_substr( $value, $startPos + runner_strlen( $searchFor ), $rightPaddingLength );
		$rightGrayZoneLength = $rightPaddingLength < $suggestStringSize / 2
			? 0
			: $grayZoneLength;

		$leftGrayZone = runner_substr( $leftPadding, 0, $leftGrayZoneLength );
		$stopPos = $this->findFirstStop( $leftGrayZone, true );
		if( $stopPos !== false ) {
			$leftPadding = runner_substr( $leftPadding, $stopPos );
		}

		$rightGrayZone = runner_substr( $rightPadding, $rightPaddingLength - $rightGrayZoneLength );
		$stopPos = $this->findFirstStop( $rightGrayZone );
		if( $stopPos !== false ) {
			$rightPadding = runner_substr( $rightPadding, 0, runner_strlen( $rightPadding ) - $rightGrayZoneLength + $stopPos );
		}

		$leftEllipsis = $lineIdx > 0 || runner_strlen( $leftPadding ) < $startPos
			? "... "
			: "";
		$rightEllipsis = $lineIdx < count( $lines) - 1 || runner_strlen( $rightPadding ) < runner_strlen( $value ) - $startPos - runner_strlen( $searchFor )
			? " ..."
			: "";
		
		$searchValue = $leftPadding . runner_substr( $value, $startPos,  runner_strlen( $searchFor )) . $rightPadding;
		return array( 
			"search" => $searchValue,
			"display" => $leftEllipsis . $searchValue . $rightEllipsis
		);
	}

	function findFirstStop( $str, $reverse = false ) {
		$stopSymbols = " .,;:\"'?!|\\/=(){}[]*-+\n\r";
		$length = runner_strlen( $str);
		for( $i = 0; $i < $length; ++$i ) {
			$idx = $reverse ? $length - $i - 1 : $i;
			$c = runner_substr( $str, $idx, 1 );
			if( runner_strpos( $stopSymbols, $c ) !== false )
				return $idx;
		}
		return false;
	}
	
	/**
	 * This function ivokes after successful saving of added/edited record
	 */
	function afterSuccessfulSave()
	{
	}
	
	/**
	 * Control settings filling
	 */
	function init()
	{
	}
	
	/**
	 * Is the search string valid for LIKE search
	 */
	function isStringValidForLike($str)
	{
		if(!IsCharType($this->type) && hasNonAsciiSymbols($str))
			return false;
		
		return true;
	}
	
	/**
	 * Get the displayed control elemnt's style attribute string
	 * @return String
	 */
	function getInputStyle( $mode )
	{
		return "";
		if( $this->pageObject->isBootstrap() 
			&& ($this->pageObject->pageType != PAGE_ADD || $this->pageObject->mode != ADD_INLINE) 
			&& ($this->pageObject->pageType != PAGE_EDIT || $this->pageObject->mode != EDIT_INLINE) )
		{
			return "";
		}
		
		$width = $this->searchPanelControl ? 150 : $this->pageObject->pSetEdit->getControlWidth( $this->field );
		$style = $this->makeWidthStyle( $width );
		
		return 'style="'.$style.'"';		
	}
	
	/** 
	 * Create a CSS rule specifying the control's width
	 * @param Number widthPx
	 */
	function makeWidthStyle( $widthPx )
	{
		return "";
/*
		if( 0 == $widthPx )
			return "";
			
		return "width: ".$widthPx."px;";
*/		
	}
	
	public function loadLookupContent( $parentValuesData, $childVal = "", $doCategoryFilter = true, $initialLoad = true )
	{
		return ""; // .net compatibility
	}

	public function getLookupContentToReload( $isExistParent, $mode, $parentCtrlsData )
	{
		return ""; // .net compatibility
	}
	
	/**
	 * A stub
	 */
	public function getFieldValueCopy( $value )
	{
		return $value;
	}
	
	public function getFieldSQLDecrypt()
	{
		return RunnerPage::_getFieldSQLDecrypt( $this->field, $this->connection, $this->pageObject->pSetEdit,  $this->pageObject->cipherer );
	}
	
	/**
	 * @return String
	 */
	protected function getPlaceholderAttr()
	{
		if( !$this->searchPanelControl && $this->container->pageType != PAGE_SEARCH )
			return ' placeholder="'.GetFieldPlaceHolder( GoodFieldname( $this->pageObject->tName ), GoodFieldname( $this->field ) ).'"';
			
		return "";
	}
	
	/**
	 *
	 */
	public function getConnection()
	{
		return $this->connection();
	}
}
?>