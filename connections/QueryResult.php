<?php
/**
 * A wrapper of the Connection class methods
 * basing on an SQL querty result hanle
 */
class QueryResult
{
	/**
	 * The basic Connection object
	 * @type Connection
	 */
	protected $connectionObj;
	
	/**
	 * the query result handle
	 * @type Mixed
	 */
	protected $handle;
	
	protected $data;
	
	//	list of column names in the fetched query
	protected $fieldNames = array();
	
	protected $upperMap = array();
	protected $fieldMap = array();
	
	/**
	 *	-1 - no data fetched. This is initial state. 
	 *	0 - some data fetched
	 *	1 - unsuccessful attempt to fetch data made. EOF
	 */
	protected $state = -1;
	
	
	function __construct( $connectionObj, $qHandle )
	{
		$this->connectionObj = $connectionObj;
		$this->handle = $qHandle;
	}
	
	/**
	 * Get the query result handle
	 * @return Mixed
	 */
	public function getQueryHandle()
	{
		return $this->handle;
	}
	
	/**
	 * A wrapper for the Connection::fetch_array method
	 * @return Mixed - associative Array with record data if data is available.
	 *	 Otherwise it returns FALSE or empty Array depending on data provider. Use conversion to boolean to check if data exists:
	 *	$data = $q->fetchAssoc();
	 *	if($data) 
	 *	...
	 */
	public function fetchAssoc()
	{
		if( $this->state == 1 )
			return null;
		
		if( $this->state == 0 )
		{
			$this->state = -1;
			return $this->numericToAssoc( $this->data );
		}
		
		$ret = $this->connectionObj->fetch_array( $this->handle );
		$this->state = $ret ? -1 : 1;
		return $ret;
	}
	
	/**
	 * A wrapper for the Connection::fetch_numarray method
	 * @return Mixed - integer-indexed Array with record data or empty Array or FALSE if no data available. 
	 *	See fetchAssoc description.
	 */	
	public function fetchNumeric()
	{
		if( $this->state == 1 )
			return null;
		
		if( $this->state == 0 )
		{
			$this->state = -1;
			return $this->data;
		}
		
		$ret = $this->connectionObj->fetch_numarray( $this->handle );
		$this->state = $ret ? -1 : 1;
		return $ret;
	}
	
	/**
	 * A wrapper for the Connection::closeQuery method
	 */		
	public function closeQuery()
	{
		$this->connectionObj->closeQuery( $this->handle );
	}
	
	/**
	 * A wrapper for the Connection::num_fields method
	 */		
	public function numFields()
	{
		return $this->connectionObj->num_fields( $this->handle );
	}
	
	/**
	 * A wrapper for the Connection::field_name method
	 */	
	public function fieldName( $offset )
	{
		return $this->connectionObj->field_name( $this->handle, $offset );
	}
	
	/**
	 * A wrapper for the Connection::seekPage method
	 */	
	public function seekPage( $pageSize, $pageStart )
	{
		$this->connectionObj->seekPage($this->handle, $pageSize, $pageStart);
	}
	
	public function eof() 
	{
		$this->prepareRecord();
		return $this->state == 1;
	}
	
	protected function internalFetch()
	{
		if( $this->state == 1 )
			return;
		$this->fillColumnNames();
		$this->data = $this->connectionObj->fetch_numarray( $this->handle );
		$this->state = $this->data ? 0 : 1;
	}
	
	protected function numericToAssoc( $data ) {
		$ret = array();
		$nFields = $this->numFields();
		for( $i = 0; $i < $nFields; ++$i )
			$ret[ $this->fieldNames[ $i ] ] = $data[ $i ];
		return $ret;
	}
	
	protected function fillColumnNames()
	{
		if( $this->fieldNames )
			return;
		$nFields = $this->numFields();
		for( $i = 0; $i < $nFields; ++$i )
		{
			$fname = $this->fieldName( $i );
			$this->fieldNames[] = $fname;
			$this->fieldMap[ $fname ] = $i;
			$this->upperMap[ strtoupper( $fname ) ] = $i;
		}
	}
	
	public function next()
	{
		$this->prepareRecord();
		$this->internalFetch();
	}
	
	protected function prepareRecord() 
	{
		if( $this->state == -1 )
			$this->internalFetch();
		return $this->state != 1;
	}
	
	public function value( $field ) 
	{
		if( !$this->prepareRecord() )
			return null;
		if( is_int($field) )
			return $this->data[ $field ];
		if( isset( $this->fieldMap[ $field ] ) )
			return $this->data[ $this->fieldMap[ $field ] ];
		if( isset( $this->upperMap[ strtoupper( $field ) ] ) )
			return $this->data[ $this->upperMap[ strtoupper( $field ) ] ];
		return null;
	}
	
	public function getData()
	{
		if( !$this->prepareRecord() )
			return null;
		return $this->numericToAssoc( $this->data );
	}

	public function getNumData()
	{
		if( !$this->prepareRecord() )
			return null;
		return $this->data;
	}
}
?>