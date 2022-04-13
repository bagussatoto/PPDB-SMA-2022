<?php

class OrderClause
{
	private $pSet = null;
	private $cipherer = null;
	private $sessionPrefix = "";
	private $connection = "";
	
	private $_cachedFields = null;
	private $_cachedSortBySettings = null;
	
	
	/**
	 * Constructor
	 *	params may include
	 *
	 * @param Object pSet
	 * @param Boolean needReadRequest. When true, the object will read sorting command from request and update settings in the session.
	 */
	function __construct( $_pSet, $_cipherer, $_sessionPrefix, $_connection, $needReadRequest = false )
	{
		$this->pSet = $_pSet;
		$this->cipherer = $_cipherer;
		$this->sessionPrefix = $_sessionPrefix;
		$this->connection = $_connection;
		
		if( $needReadRequest )
			$this->readRequest();
	}
	/**
	 * Interface functions
	 */

	static function createFromPage( $pageObject, $needReadRequest = false )
	{
		return new OrderClause($pageObject->pSet, $pageObject->cipherer, $pageObject->sessionPrefix, $pageObject->connection, $needReadRequest );
	}

	/**
	 * Returns array with all current sorting data
	 * Array in the form of
	 * [0] => array( 'column' => <columns name  from SQL query> 
					 'index' => <1-based index of a column in the query select-list. 0 if not available>
					 'expr' => <SQL expression representing the field> 
					 'dir' => 'ASC' or 'DESC' 
					 'hidden' => true or not set. Hidden sorting fields are added by the application and should not be reflected in the UI.
					 )
	   [1] => the same
	   Example 
	   SQL Query:
	   select id, first, [last], concat(first, last) as fullname from users order by 2,4,birthdate desc
	   
	   Corresponding OrderFields array:
	   [0] => array( 
				column => first
				index => 2
				expr => first
				dir => ASC
				)
	   [1] => array( 
				column => fullname
				index => 4
				expr => concat(first, last)
				dir => ASC
				)
	   [2] => array( 
				column => 
				index => 0
				expr => birthdate
				dir => DESC
				)
	   [3] => array( 
				column => id
				index => 1
				expr => id
				dir => ASC
				hidden => true
				)
	   
	 */
	public function getOrderFields() 
	{
		if( $this->_cachedFields !== null )
			return $this->_cachedFields;
		
		$ret = array();
		$columns = array();

		$pSet = $this->pSet;

		$saved =  $_SESSION[$this->sessionPrefix . "_orderby"];
		
		
		if( 0 != strlen( $saved['orderby'] ) )
		{
			//	orderby format:
			//	acolumn1;dcolumn2;acolumn3
			$fields = explode(';', $saved['orderby'] );
			foreach( $fields as $f )
			{
				$dir = substr($f, 0, 1);
				if( $dir!='a' && $dir != 'd' )
					continue;
				$goodField = substr($f, 1);
				$fieldName = $pSet->getFieldByGoodFieldName( $goodField );
				$index = $pSet->getFieldIndex( $fieldName );
				if( !$index )
					continue;
				$ret[] = array( 'column' => $fieldName,
								'index' => $index,
								'expr' => RunnerPage::_getFieldSQLDecrypt( $fieldName, $this->connection, $this->pSet, $this->cipherer ),
								'dir' => ($dir == 'a' ? 'ASC' : 'DESC')
							);
				$columns[ $fieldName ] = true;
			}
			
		}
		else if( 0 != strlen( $saved['sortby'] ) )
		{
			//	'Sort by' control
			//	$saved['sortby'] - 1-based index of selected value in the 'sort by' control
			
			$sortbySettings =& $this->getSortBySettings();
			$option = $sortbySettings[ $saved['sortby'] - 1 ];
			if( $option )
			{
				foreach( $option["fields"] as $f ) 
				{
					$ret[] = array( 'column' => $f["field"],
									'index' => $pSet->getFieldIndex( $f["field"] ),
									'expr' => RunnerPage::_getFieldSQLDecrypt( $f["field"], $this->connection, $this->pSet, $this->cipherer ),
									'dir' => ( $f["desc"] ? 'DESC' : 'ASC' )
								);
					$columns[ $f["field"] ] = true;
				}
			}
			
		}
		else
		{
			//	use SQL sorting
			$orderInfo = $pSet->getOrderIndexes();
			foreach( $orderInfo as $o )
			{
				$field = $pSet->GetFieldByIndex( $o[0] );
				$ret[] = array('column' => $field,
							 'index' => $o[0],
							 'expr' => $o[2],
						  	 'dir' => $o[1]
								);
				
				$columns[ $field ] = true;
			}
		}

		// add key fields to the list to ensure persistent records order
		foreach( $pSet->getTableKeys() as $k )
		{
			if( isset( $columns[$k] ) )
				continue;
			
			$ret[] = array( 'column' => $k,
							'index' => $pSet->getFieldIndex( $k ),
							'expr' => RunnerPage::_getFieldSQLDecrypt( $k, $this->connection, $this->pSet, $this->cipherer ),
							'dir' => 'ASC',
							'hidden' => true
						);
		}
		
		// group by sort
		$groupByRet = array();
		foreach( $pSet->getGroupFields() as $grField )
		{
			$grFieldPos = -1;
			foreach ( $ret as $key => $of )
			{
				if ( $of["column"] == $grField )
				{
					$grFieldPos = $key;
					break;
				}
			}

			if ( $grFieldPos != -1 )
			{
				$groupByRet[] = $ret[ $grFieldPos ];
				unset( $ret[ $grFieldPos ] );
			}
			else
			{
				$groupByRet[] = array(
					'column' => $grField,
					'index' => $pSet->getFieldIndex( $grField ),
					'expr' => RunnerPage::_getFieldSQLDecrypt( $grField, $this->connection, $this->pSet, $this->cipherer ),
					'dir' => 'ASC',
					'hidden' => true
				);
			}
		}
		
		$this->_cachedFields = array_merge($groupByRet, $ret);

		return $this->_cachedFields;
	}

	public function getOrderUrlParams()
	{
		$arrParams = array();
		$orderFields = $this->getOrderFields();
		foreach ( $orderFields as $key => $field )
		{
			if ( !$field['hidden'] )
			{
				$dirChar = $field['dir'] == 'ASC' ? 'a' : 'd';
				$arrParams[] = $dirChar.GoodFieldName($field['column']);
			}
		}

		return implode(";", $arrParams);
	}	
	
	/**
	 *	Builds and returns SQL Order By expression based on current settings
	 *	@return String
	 */
	public function getOrderByExpression() 
	{
		$orderby = array();

		foreach( $this->getOrderFields() as $of )
		{
			/**
			 * 	'expr' is preferable to 'column' because it can work without the original field in the select-list
			 * Example:
			 *	   select id, last, concat(first, last) as full from users order by full *** ok
			 *	Selecting 'id' only:
			 *	   select id from users order by full *** error 
			 *	   select id from users order by concat(first, last) *** ok
			 *
			 * 	On the other hand, indices are preferred event more, because they can work in with subqueries and even UNIONs
			 */
			
			if( $of["index"] ) {
				$orderby[] = $of["index"] . " " . $of["dir"];
			} else {
				$orderby[] = $of["expr"] . " " . $of["dir"];
			}
		}
		if( $orderby )
			return " order by " . implode( ", ", $orderby );
		return $this->pSet->getStrOrderBy();
	}

	/**
	 * Returns Sort By conrtrol elements description
	 * @return Array
	 */
	public function getSortBySettings() 
	{
		if( $this->_cachedSortBySettings !== null )
			return $this->_cachedSortBySettings;
			
		$sortSettings = $this->pSet->getSortControlSettingsJSONString();
		$sortSettings = my_json_decode( $sortSettings );
		if( !$sortSettings || !count( $sortSettings ) )
		{
			$sortSettings = array();
			
			foreach( $this->pSet->getListFields() as $fName ) 
			{
				if( !$this->isFieldSortable( $fName ) )
					continue;
					
				$sortSettings[] = array( "label" => "", "fields" => array( array( "field" => $fName, "desc" => false, "labelOnly" => true ) ) );
			}
		}	
		
		$this->_cachedSortBySettings = $sortSettings;
		
		return $sortSettings;	
	}

	protected function isFieldSortable( $fName ) 
	{
		$type = $this->pSet->getFieldType( $fName );
		return !IsBinaryType( $type ) && ( $this->connection->dbType == nDATABASE_MySQL && $type != 203 || !IsTextType( $type ) );	
	}
	
	/**
	 * Returns selected index in the Sort By conrtrol based on current settings
	 * -1 if no match found
	 * @return Integer
	 */
	public function getSortByControlIdx() 
	{
		$sortbySettings =& $this->getSortBySettings();
		$saved = $_SESSION[$this->sessionPrefix . "_orderby"];
		if( strlen( $saved["sortby"] ) != 0 )
		{
			$idx = (int)( $saved["sortby"] ) - 1;
			if( isset( $sortbySettings[$idx] ) )
				return $idx;
		}
		
		//	try to match current order settings with one of the 'sort by' control elements
		
		//	make normalized string of current sort settings first
		$orderFields =& $this->getOrderFields();
		foreach( $orderFields as $o )
		{
			if( !$o['hidden'] )
				$normOrder[] = array( $o['column'], $o['dir'] );
		}
		$sortString = my_json_encode( $normOrder );
		
		
		// make normalized string of each $sortbySettings element and compare to $normOrder
		
		foreach( $sortbySettings as $i => $s )
		{
			$normOrder = array();
			foreach( $s["fields"] as $f )
				$normOrder[] = array( $f['field'], $f["desc"] ? 'DESC' : 'ASC' );
			if( my_json_encode( $normOrder ) == $sortString )
				return $i;
		}
		return -1;
	}
	
	/**
	 * DEPRECATED
	 * Returns simplified version of current sorting data for use specifically in the ListQuery event
	 * 
	 * @return Array
	 */
	public function getListQueryData() 
	{
		$arrFieldForSort = array();
		$arrHowFieldSort = array();

		foreach( $this->getOrderFields() as $of )
		{
			$arrFieldForSort[] = $of["index"];
			$arrHowFieldSort[] = $of["dir"];
		}
		
		return array( 
			"fieldsForSort" => $arrFieldForSort,
			"howToSortData" => $arrHowFieldSort
		);
	}

	/**
	 * Implementation functions
	 */

	
	/**
	 *	Read sort data from request and save it to the session
	 */
	protected function readRequest() {
		
		if( strlen( postvalue("orderby") )  || strlen( postvalue("sortby") ) )
			$_SESSION[ $this->sessionPrefix . "_orderby" ] = array( "orderby" => postvalue("orderby"), "sortby" => postvalue("sortby") );
	}
}
?>