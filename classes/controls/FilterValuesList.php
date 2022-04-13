<?php
class FilterValuesList extends FilterControl
{
	protected $useFormatedValueInSorting;
	
	protected $isDescendingSortOrder;
	
	protected $sortingType;
	
	protected $parentFiltersNames = array();
	
	protected $dependentFilterNames = array();
	
	protected $dependentFilterName;
	
	protected $hasDependent = false;
	
	protected $filteredFieldParentFiltersKeysToIgnore = null;

	protected $hideShowMore = false;
	
	/**
	 * The number of items that should be show 
	 * according to the 'Show firt N item' setting 
	 * @type Number
	 */
	protected $numberOfVisibleItems = 0;
	
	/**
	 * The flag indicating if to show 'Show N more' button
	 * @param Boolean
	 */
	protected $truncated = false;
	
	protected $numberOfExtraItemsToShow = 0;
	
	protected $hiddenExtraItemClassName = "filter-item-hidden";
	
	public function __construct($fName, $pageObject, $id, $viewControls)
	{
		parent::__construct($fName, $pageObject, $id, $viewControls);
		
		$this->filterFormat = FF_VALUE_LIST;
		$this->separator = "~equals~";
		
		$this->useApllyBtn = $this->multiSelect == FM_ALWAYS;
		
		$this->numberOfVisibleItems = $this->pSet->getNumberOfVisibleFilterItems($fName); 
		
		$this->parentFilterName = $this->pSet->getParentFilterName($fName);
		$this->dependent = $this->parentFilterName != "";
		
		$this->assignDependentFiltersData();
		$this->hasDependent = $this->dependentFilterName != "";
		
		
		$this->assignParentFiltersData();
		
		$this->setSortingParams();
		$this->setAggregateType();
		$this->buildSQL();
	}
	
	/**
	 * Assign 'parentFiltersNames', 'parentFiltersNamesData' properties to
	 * the corresponding data if the current filter is dependent
	 */
	protected function assignParentFiltersData()
	{
		if( !$this->dependent )
			return;
			
		$this->parentFiltersNames = FilterValuesList::getParentFilterFields( $this->fName, $this->pSet );
		$parentFiltersData = array();
		
		foreach($this->parentFiltersNames as $parentName)
		{
			$dbName = $this->getDbFieldName($parentName);
			$hasAlias = $dbName != $this->connection->addFieldWrappers($parentName);
			$rsName = $hasAlias ? $this->connection->addFieldWrappers($parentName) : $dbName;
				
			$parentFiltersData[$parentName]['dbName'] = $dbName;
			$parentFiltersData[$parentName]['rsName'] = $rsName;
			$parentFiltersData[$parentName]['hasAlias'] = $hasAlias;		
		}
		
		$this->parentFiltersNamesData = $parentFiltersData;		
	}

	public static function getParentFilterFields( $field, $pSet ) {
		$parents = array();
		FilterValuesList::findParentFilters( $field, $parents, $pSet );
		return array_keys( $parents );
	}
	
	/**
	 * Assign the 'dependentFilterNames' property to
	 * the corresponding data if the current filter has any dependent filter
	 */
	protected function assignDependentFiltersData()
	{
		$dependents = array();
		$this->findDependentFilters( $this->fName,  $this->pSet->getFilterFields(), $dependents );
		$this->dependentFilterNames = array_keys( $dependents );
	}

	public function hasDependentFilter() {
		return !!$this->dependentFilterName;
	}


	protected function findDependentFilters( $field, &$filterFields, &$dependents ) {
		foreach( $filterFields as $f ) {
			if( !isset( $dependents[$f] ) && $this->pSet->getParentFilterName( $f ) === $field ) {
				$dependents[ $f ] = true;
				if( $field == $this->fName )
					$this->dependentFilterName = $f;
					FilterValuesList::findDependentFilters( $f, $filterFields, $dependents );
				break;
			}
		}
	}
	protected static function findParentFilters( $field, &$parents, $pSet ) {
		$f = $pSet->getParentFilterName( $field );
		if( !$f )
			return;
		if( !isset( $parents[$f] ) ) {
			$parents[$f] = true;
			FilterValuesList::findParentFilters( $f, $parents, $pSet );
		}
	}
	
	protected function buildSQL()
	{
		//	build query of the following form:
		//  select field, ,,, from ( <original query with search, security and filters> ) group by field
		
		$fullFieldName = RunnerPage::_getFieldSQLDecrypt( $this->fName, $this->connection, $this->pSet, $this->cipherer );
		
		$filterInterval = $this->pSet->getFilterByInterval($this->fName);
		$intervalExpression = $this->getFilterSQLExpr( $fullFieldName );
		$alias = "_grval_"  . $this->fName;
		
		$this->strSQL = "select " . $intervalExpression . " as " . $this->connection->addFieldWrappers( $alias );
		
		//	add totals		
		if($this->useTotals)
			$this->strSQL .= ", ".$this->getTotals();
		
		//	calculate add master filter fields
		if( $this->dependent )
		{
			$parentFields = array();
			$parentGroupBy = array();
			$parentAliases = array();
			foreach( $this->parentFiltersNames as $p ) {
				
				$fullParentFieldName = RunnerPage::_getFieldSQLDecrypt( $p, $this->connection, $this->pSet, $this->cipherer );
				$parentAlias = "_grval_"  . $p;
				$parentExpression = FilterValuesList::_getFilterSQLExpr( $p, $fullParentFieldName, $this->pSet, $this->connection );
				$parentFields[] = $parentExpression . " as " . $parentAlias;
				$parentGroupBy[] = $parentExpression;
			}
			
			$this->strSQL .= ", " . implode( ", ", $parentFields );
		}
		
		$this->strSQL .= " from ( " . $this->buildBasicSQL() . " ) a";

//	NOT NULL clause
		$this->strSQL .= " where " . implode( ' and ', $this->getNotNullWhere() );
			
		//	group by the field and the parents
		$this->strSQL .= " GROUP BY " . $intervalExpression;
		if( $this->dependent )
			$this->strSQL .= ", " . implode( ", ", $parentGroupBy );

		// add ORDER BY to sort the result records
		if( $this->sortingType != SORT_BY_DISP_VALUE ) 
		{
			$this->strSQL.= ' ORDER BY '.
				( $this->sortingType == SORT_BY_GR_VALUE && $this->useTotals ? "2" : "1" ).
				( $this->isDescendingSortOrder ? ' DESC' : ' ASC');
		}
	}
	
	
	protected function getFilterSQLExpr( $expr ) {
		return FilterValuesList::_getFilterSQLExpr( $this->fName, $expr, $this->pSet, $this->connection );
	}
	/**
	 * @return String
	 */
	protected static function _getFilterSQLExpr( $fName, $expr, $pSet, $connection ) 
	{
		$filterInterval = $pSet->getFilterByInterval($fName);
		if( !$filterInterval ) 
			return $expr;
		
		$ftype = $pSet->getFieldType( $fName );
		
		if( IsNumberType($ftype) )
		{
			return $connection->intervalExpressionNumber( $expr, $filterInterval );
		}
		
		if( IsCharType( $ftype ) )
		{
			return $connection->intervalExpressionString( $expr, $filterInterval );
		}
		
		if( IsDateFieldType( $ftype ) )
		{
			return $connection->intervalExpressionDate( $expr, $filterInterval );
		}
		
		return $expr;
	}
	
	/**
	 * Set the sorting params
	 */
	protected function setSortingParams()
	{
		$this->sortingType = $this->pSet->getFilterSortValueType($this->fName);
		$this->isDescendingSortOrder = $this->pSet->isFilterSortOrderDescending($this->fName);
		$this->useFormatedValueInSorting = $this->sortingType == SORT_BY_DISP_VALUE || IsCharType($this->fieldType) || $this->pSet->getEditFormat($this->fName) == EDIT_FORMAT_LOOKUP_WIZARD; 		
	}
	
	/**
	 * Set the type of the aggregate funtion
	 */
	protected function setAggregateType() 
	{
		$this->aggregate = $this->totalsOptions[ $this->totals ]; 
	}
	
	/**
	 * Get the sql string containing the filter totals
	 * to add it to the SELECT clause
	 * @return String
	 */
	protected function getTotals()
	{		
		return $this->aggregate."( ". $this->connection->addFieldWrappers( $this->totalsfName ) .") as ".$this->connection->addFieldWrappers( $this->fName."TOTAL" );
	}
	
	/**
	 * Get the view controls' value 
	 *
	 * @param String values
	 * @return String
	 */
	protected function getValueToShow($value) 
	{
		if( !$this->viewControl ) {
			return "";
		}
		$data = array($this->fName => $value); 	
		$showValue = $this->viewControl->showDBValue($data, "");

		return $showValue;
	}
	
	/**
	 * Get the filter blocks data using the database query
	 * and add it the the existing blocks
	 * @param &Array
	 */
	protected function addFilterBlocksFromDB( &$filterCtrlBlocks )
	{
		$filterInterval = $this->pSet->getFilterByInterval($this->fName);
		$alias = "_grval_"  . $this->fName;
		$containsFilteredDependentOnDemandFilter = !$this->dependent && !$this->filtered && $this->hasFilteredDependentOnDemandFilter();
		$visibilityClass = $this->filtered && $this->multiSelect == FM_ON_DEMAND ? $this->onDemandHiddenItemClassName : "";
		
		//query to database with current where settings
		$qResult = $this->connection->query( $this->strSQL );
		while( $data = $qResult->fetchAssoc() )
		{	
			$this->decryptDataRow($data);

			$rawValue = $data[ $alias ];
			
			$parentFiltersData = array();
			if( $this->dependent )
			{
				foreach($this->parentFiltersNames as $pName)
				{
					$parentFiltersData[ $pName ] = $data[ "_grval_" . $pName ];
				}
			}
				
			$this->valuesObtainedFromDB[] = $rawValue;
					
			$filterControl = $this->buildControl( $data, $parentFiltersData );
			if( $containsFilteredDependentOnDemandFilter )
				$filterControl = $this->getDelButtonHtml( $this->gfName, $this->id,  $rawValue ).$filterControl;

			$filterCtrlBlocks[] = $this->getFilterBlockStructure($filterControl, $visibilityClass, $rawValue, $parentFiltersData);
		}
	}

	/**
	 * Check if the current filter has any dependent filtered 'on demand' filter
	 * @return Boolean
	 */
	protected function hasFilteredDependentOnDemandFilter()
	{
		if( !$this->hasDependent )
			return false;
		
		foreach( $this->dependentFilterNames as $dName )
		{ 
			if( count( $this->filteredFields[ $dName ] ) && $this->pSet->getFilterFiledMultiSelect($dName) == FM_ON_DEMAND )
				return true;
		}
		
		return false;
	}
	
	/**
	 * Get the parent filters data
	 * @param String value
	 * @return Array
	 */
	protected function getParentFiltersDataForFilteredBlock( $value )
	{
		$parentFiltersData = array();
		
		if( !$this->filtered || !$this->dependent )
			return $parentFiltersData;
		
		$parentValuesData = $this->filteredFields[ $this->fName ]["parentValues"];
		if( count($parentValuesData) <= 1 )
		{		
			foreach($this->parentFiltersNames as $pName)
			{
				//parent filter is single selected
				$pValue = $this->filteredFields[ $pName ]["values"][0];
				//parent filter is not presented in filter params string
				if(	!isset($this->filteredFields[ $pName ]) && count( $parentValuesData[0] ) )
					$pValue = $parentValuesData[0][0];
					
				$parentFiltersData[ $pName ] = $pValue;
			}
			return $parentFiltersData;
		} 

		if( !$this->filteredFieldParentFiltersKeysToIgnore )
			$this->filteredFieldParentFiltersKeysToIgnore = array();
					
		foreach( $parentValuesData as $key => $parentValues )
		{
			if( $value != $this->filteredFields[ $this->fName ]['values'][ $key ] || in_array($key, $this->filteredFieldParentFiltersKeysToIgnore) )
				continue;
			
			$this->filteredFieldParentFiltersKeysToIgnore[] = $key;

			foreach($this->parentFiltersNames as $pKey => $pName)
			{
				$pValue = $parentValues[$pKey];
				$parentFiltersData[ $pName ] = $pValue;
			}
			
			return $parentFiltersData;
		}
		
		return $parentFiltersData;
	}
	
	/**
	 * Get the arrray with keys corresponding to filter blocks markup
	 * @param String filterControl
	 * @param String visibilityClass
	 * @param String value							The raw Db field's value
	 * @param Array parentFiltersData (optional)
	 * @return Array
	 */
	protected function getFilterBlockStructure( $filterControl, $visibilityClass, $value, $parentFiltersData = array() )
	{
		if( !$this->viewControl )
			return array();
		$sortValue = $value;
		if( $this->useFormatedValueInSorting )
		{
			$valueData = array($this->fName => $sortValue);
			$sortValue = $this->viewControl->showDBValue($valueData, "");	
		}
		
		if( $this->multiSelect != FM_ALWAYS )
			$visibilityClass.= " filter-link";
				
		return array(
			$this->gfName."_filter" => $filterControl, 
			"visibilityClass_".$this->gfName => $visibilityClass,
			"sortValue" => $sortValue,
			"dbValue" => $value,
			"mainValues" => $parentFiltersData
		);
	}
	
	/**
	 * Get the html markup representing the control on the page
	 * @param Array data
	 * @param Array parentFiltersData (optional)
	 * @return String
	 */
	protected function buildControl( $data, $parentFiltersData = array() )
	{
		$filterInterval = $this->pSet->getFilterByInterval($this->fName);
		$alias = "_grval_"  . $this->fName;
		$value = $data[ $alias ];		
		
		// pass to attributes 
		$dataValue = $value;	
		$showValue = $this->getControlCaption( $value );
		
		$totalValue = $this->getTotalValueToShow( $data[ $this->fName."TOTAL" ] );

		return $this->getControlHTML( $value, $showValue, $dataValue, $totalValue, $this->separator, $parentFiltersData );
	}
	
	/**
	 * @param Array data
	 * @return String
	 */
	protected function getControlCaption( $value )
	{	
		$intervalType = $this->pSet->getFilterByInterval($this->fName);
		if( !$intervalType )
			return $this->getValueToShow( $value );
		
		return $this->pageObject->formatGroupValue( $this->fName, $intervalType, $value );
	}
	
	/**
	 * Check if to show the 'Show N more' button
	 * for a not dependent filter
	 * @return Boolean
	 */
	protected function isTruncated()
	{
		return !$this->dependent && $this->truncated;
	}
	
	/**
	 * Get the extra data attributes for the control's HTML elements
	 * @param Array parentFiltersData 
	 * @return String
	 */
	protected function getExtraDataAttrs( $parentFiltersData )
	{
		if( !$this->dependent || is_null($parentFiltersData) )
			return '';
			
		return ' data-parent-filters-values="'.runner_htmlspecialchars( my_json_encode( $parentFiltersData ) ).'" ';
	}	
	
	/**
	 * Get the cheked attribute string for a multiselect filter control
	 * @param String value
	 * @param String parentFiltersData
	 * @return String	 
	 */
	protected function getCheckedAttr( $value, $parentFiltersData = null )
	{
		$filteredValues = $this->filteredFields[ $this->fName ]['values'];
		
		if( $this->multiSelect == FM_NONE || $this->filtered && !in_array($value, $filteredValues) )
			return '';
		
		if( $this->filtered && $this->dependent && $this->multiSelect == FM_ON_DEMAND && count($filteredValues) == 1 )
			return 'checked="checked"';
		
		if( !$this->filtered || !$this->dependent || is_null($parentFiltersData) )
			return 'checked="checked"';
			
		foreach( $this->filteredFields[ $this->fName ]["parentValues"] as $key => $parentValues )	
		{
			if( $value == $this->filteredFields[ $this->fName ]['values'][$key] && $this->isParentsValuesDataTheSame($parentFiltersData, $parentValues) )
				return 'checked="checked"';
		}
		return '';
	}
	
	/**
	 * Check if two data structures passed are similar
	 * @param Array parentFiltersData
	 * @param Array parentValues
	 * @return Boolean
	 */
	protected function isParentsValuesDataTheSame($parentFiltersData, $parentValues)
	{
		foreach($this->parentFiltersNames as $pKey => $pName)
		{
			if( $parentFiltersData[$pName] != $parentValues[$pKey] )
				return false;
		}
		return true;	
	}
	
	/**
	 * Get filter control's base ControlsMap array
	 * @return array
	 */	
	protected function getBaseContolsMapParams() 
	{
		$ctrlsMap = parent::getBaseContolsMapParams();
		
		if( $this->dependent )
		{
			$ctrlsMap['dependent'] = true;
			$ctrlsMap['parentFilterNames'] = $this->parentFiltersNames;
			$ctrlsMap['goodParentName'] = GoodFieldName( $this->parentFilterName );
			$ctrlsMap['goodOutermostParentName'] = GoodFieldName( $this->parentFiltersNames[ count($this->parentFiltersNames) - 1 ] );
		}
		
		if( $this->hasDependent )
		{
			$ctrlsMap['hasDependent'] = true;
			$ctrlsMap['dependentFilterNames'] = $this->dependentFilterNames;
		}
		
		return $ctrlsMap;	
	}

	/**
	 * Get the murkup of the control's delete button
	 * @param String gfName
	 * @param Number id
	 * @param String deleteValue
	 * @return String
	 */
	protected function getDelButtonHtml($gfName, $id, $deleteValue) 
	{
		if( $this->multiSelect == FM_ALWAYS || $this->dependent )
			return '';
		
		return parent::getDelButtonHtml($gfName, $id, $deleteValue);	
	}
	
	/**
	 * A stub
	 * @return Number
	 */
	protected function getNumberOfExtraItemsToShow()
	{
		return $this->numberOfExtraItemsToShow;
	}
	
	/**
	 * Update filter blocks structures
	 * @param &Array
	 */
	protected function extraBlocksProcessing( &$filterCtrlBlocks )
	{
		parent::extraBlocksProcessing($filterCtrlBlocks);
		
		if( !$this->numberOfVisibleItems || $this->dependent )
			return;
			
		$visbleItemsCounter = $hiddenItemsCounter = 0;
		foreach($filterCtrlBlocks as $index => $block)
		{
			$visible = ( strpos( $block[ "visibilityClass_".$this->gfName ], $this->onDemandHiddenItemClassName ) === FALSE );
			if( $visible )
				$visbleItemsCounter = $visbleItemsCounter + 1;
			else
				$hiddenItemsCounter = $hiddenItemsCounter + 1;
			
			if( $visible && $visbleItemsCounter > $this->numberOfVisibleItems || !$visible && $hiddenItemsCounter > $this->numberOfVisibleItems )
				$filterCtrlBlocks[ $index ][ "visibilityClass_".$this->gfName ].= " ".$this->hiddenExtraItemClassName;
		}
		
		if( $this->filtered && $this->multiSelect == FM_ON_DEMAND )
		{
			if( count($this->filteredFields[ $this->fName ]["values"]) < $this->numberOfVisibleItems && $hiddenItemsCounter > $this->numberOfVisibleItems )
			{
				$this->truncated = true;
				$this->hideShowMore = true;
				$this->numberOfExtraItemsToShow = $hiddenItemsCounter - $this->numberOfVisibleItems;
			}
		} 
		elseif( $visbleItemsCounter > $this->numberOfVisibleItems )
		{
			$this->truncated = true;
			$this->numberOfExtraItemsToShow = $visbleItemsCounter - $this->numberOfVisibleItems;
		}			
	}
	
	/**
     * Check if the "show more" button must be hidden by class attr
	 * @return Boolean
	 */
	protected function isShowMoreHidden()
	{
		return $this->hideShowMore;
	}
	
	/**
	 * Sort filter blocks depending on the field's type and format
	 * @param &Array filterCtrlBlocks
	 */
	protected function sortFilterBlocks(&$filterCtrlBlocks)
	{
		if( $this->sortingType != SORT_BY_DISP_VALUE )
			return;
			
		$compareFunction = IsNumberType($this->fieldType) ? "compareBlocksByNumericValues" : "compareBlocksByStringValues";	
		usort( $filterCtrlBlocks, array("FilterControl", $compareFunction) );
		
		if( $this->isDescendingSortOrder ) 
			$filterCtrlBlocks = array_reverse( $filterCtrlBlocks );
	}
	
	/**
	 * Get the Filter's control block data.
	 * @param Object pageObj
	 * @param Array $dFilterBlocks (optional)
	 * @return Array
	 */	
	public function buildFilterCtrlBlockArray( $pageObj, $dFilterBlocks = null )
	{
		$filterBlocks = parent::buildFilterCtrlBlockArray( $pageObj );
		
		if( !$this->hasDependent || is_null($dFilterBlocks) ) 
			return $filterBlocks;
		
		return $this->getCtrlBlocksMergeWithDependentFilterBlocks( $filterBlocks, $dFilterBlocks );
	}	

	/**
	 * Get the main filter's and dependent blocks merged
	 * @param Array mFilterBlocks
	 * @param Array dFilterBlocks
	 * @return Array 
	 */
	protected function getCtrlBlocksMergeWithDependentFilterBlocks( $mFilterBlocks, $dFilterBlocks )
	{	
		$dgName = GoodFieldName( $this->dependentFilterName ); 	
		$dBlockName = "filterCtrlBlock_".$dgName;
		$showMoreBlockName = "filter_button_showmore_".$dgName;
		
		//Get 'Show first ...' settings
		$numberOfdItemsToShow = $this->pSet->getNumberOfVisibleFilterItems( $this->dependentFilterName );
		
		foreach($mFilterBlocks as $key => $block)
		{
			$mMainValues = $block["mainValues"];
			$visibleItemsCounter = 0;			
			$invisibleItemsCounter = 0;
			
			foreach($dFilterBlocks as $dBlock)
			{
				$dMainValues = $dBlock["mainValues"];
				if( $dMainValues[ $this->fName ] != $block["dbValue"] )
					continue;
				
				$addDependentBlock = true;
				
				foreach($mMainValues as $fName=>$value)
				{
					if( $mMainValues[ $fName ] != $dMainValues[ $fName ] )
					{
						$addDependentBlock = false;
						break;
					}
				}

				if( $addDependentBlock )
				{
					if( $numberOfdItemsToShow ) 
					{
						$visible = $dBlock["visibilityClass_".$dgName] != $this->onDemandHiddenItemClassName;
						if( $visible )
							$visibleItemsCounter = $visibleItemsCounter + 1;
						else
							$invisibleItemsCounter = $invisibleItemsCounter + 1;
							
						if(  $visible && $visibleItemsCounter > $numberOfdItemsToShow || !$visible && $invisibleItemsCounter > $numberOfdItemsToShow )
							$dBlock["visibilityClass_".$dgName] = $this->hiddenExtraItemClassName;
					}
						
					$mFilterBlocks[ $key ][ $dBlockName ]["data"][] = $dBlock;
				}
				
				
				$mFilterBlocks[ $key ]["show_n_more_".$dgName] = str_replace( "%n%", $visibleItemsCounter - $numberOfdItemsToShow, "Show %n% more" );
				$mFilterBlocks[ $key ][ $showMoreBlockName ] = $numberOfdItemsToShow && $numberOfdItemsToShow < $visibleItemsCounter;		
			}
		}
		
		return $mFilterBlocks;	
	}	
	
	/**
	 * Get the filter's buttons parameters such as buttons' labels, 
	 * class names and attributes
	 * @param Array dBtnParams (optional)	 
	 * @return Array
	 */
	public function getFilterButtonParams( $dBtnParams = null )
	{
		$mBtnParams = parent::getFilterButtonParams();
		
		if( $this->hasDependent && !is_null($dBtnParams) )
		{
			$mBtnParams['hasMultiselectBtn'] = $mBtnParams['hasMultiselectBtn'] || $dBtnParams['hasMultiselectBtn'];
			$mBtnParams['hasApplyBtn'] = $mBtnParams['hasApplyBtn'] || $dBtnParams['hasApplyBtn'];
		}
		
		return $mBtnParams;		
	}

	/**
	 * Get the filter's extra controlls parameters
	 * @param Array dBtnParams (dExtraCtrls)	 
	 * @return Array
	 */	
	public function getFilterExtraControls( $dExtraCtrls = null )
	{
		$mExtraCtrls = parent::getFilterExtraControls();
		
		if( !$this->hasDependent || is_null($dExtraCtrls) )
			return $mExtraCtrls;
			
		if( !$mExtraCtrls['selectAllAttrs'] || $this->multiSelect == FM_ON_DEMAND && $dExtraCtrls['selectAllAttrs'] )
			$mExtraCtrls['selectAllAttrs'] = $dExtraCtrls['selectAllAttrs'];
		
		if( !$mExtraCtrls['clearLink'] )
			$mExtraCtrls['clearLink'] = $dExtraCtrls['clearLink'];		
			
		return $mExtraCtrls;
	}

	public static function getFilterWhere( $fName, $value, $pSet, $fullFieldName, $cipherer, $connection ) {
		
		$filterInterval = $pSet->getFilterByInterval($fName);
		$expression = FilterValuesList::_getFilterSQLExpr( $fName, $fullFieldName, $pSet, $connection );
		if( !$filterInterval || !IsDateFieldType( $pSet->getFieldType( $fName )) ) {
			$fValue = $cipherer->MakeDBValue($fName, $value, "", true);
		} else 
			$fValue = (0 + $value);
		return $expression . " = " . $fValue;
	}
	
}
?>