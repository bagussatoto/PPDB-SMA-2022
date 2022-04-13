<?php
class RunnerContextItem
{
	public $type; //?

	public $pageObj;
	public $data;
	public $oldData;
	public $newData;
	public $masterData;

	function __construct( $type, $params )
	{
		RunnerApply($this, $params);
		$this->type = $type;
	}

	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return Array
	 */
	public function getValues()
	{
		if( $this->data )
			return $this->data; 

		if( $this->pageObj )
			return $this->pageObj->getCurrentRecord();

		return array();
	}

	/**
	 * @param String field
	 * @return Mixed
	 */
	public function getFieldValue( $field )
	{
		$data = $this->getValues();
		return  getArrayElementNC( $data, $field );
	}

	/**
	 * @return Array
	 */
	public function getOldValues()
	{
		if( $this->oldData )
			return $this->oldData;

		if( $this->pageObj )
			return $this->pageObj->getOldRecordData();

		return array();
	}

	/**
	 * @param String field
	 * @return Mixed
	 */
	public function getOldFieldValue( $field )
	{
		$oldData = $this->getOldValues();
		return getArrayElementNC( $oldData, $field );
	}

	/**
	 * @param String field
	 * @return Mixed
	 */
	public function getNewFieldValue( $field )
	{
		if ( $this->newData )
			return getArrayElementNC( $this->newData, $field );

		return $this->getFieldValue( $field );
	}

	/**
	 * @return Array
	 */
	public function getMasterValues()
	{
		if( $this->masterData )
			return $this->masterData;

		if( $this->pageObj )
			return $this->pageObj->getMasterRecord();

		return array();
	}

	/**
	 * @param String field
	 * @return Mixed
	 */
	public function getMasterFieldValue( $field )
	{
		$masterData = $this->getMasterValues();
		return getArrayElementNC( $masterData, $field );
		
	}

	/**
	 * @param String key
	 * @return String
	 */
	public function getUserValue( $key )
	{
		return getArrayElementNC( Security::currentUserData(), $key );
	}

	/**
	 * @param String key
	 * @return Mixed
	 */
	public function getSessionValue( $key )
	{
		return getSessionElementNC( $key );
	}

	/**
	 * @param String key
	 * @return Mixed
	 */
	public function getValue( $key )
	{
		$prefix = "";
		$dotPos = strpos( $key, ".");
		if( $dotPos !== FALSE )
		{
			$prefix = strtolower( substr( $key, 0, $dotPos ) );
			$key = substr( $key, $dotPos + 1 );
		}

		if( $prefix == "master" )
			return $this->getMasterFieldValue( $key );

		if( $prefix == "session" )
			return $this->getSessionValue( $key );

		if( $prefix == "user" )
			return $this->getUserValue( $key );

		if( $prefix == "old" )
			return $this->getOldFieldValue( $key );

		if( $prefix == "new" )
			return $this->getNewFieldValue( $key );

		if ( $key == "language" )
			return mlang_getcurrentlang();

		return $this->getFieldValue( $key );
	}
}

/**
 *	Singletone. All public functions are static
 */
class RunnerContext
{
	protected $stack = array();

	public function __construct( )
	{
		$context = new RunnerContextItem( CONTEXT_GLOBAL, array() );
		$this->stack[ count($this->stack) ] = $context;
	}

	public static function push( $context )
	{
		global $contextStack;
		$contextStack->stack[ count($contextStack->stack) ] = $context;
	}

	public static function current( )
	{
		global $contextStack;
		return $contextStack->stack[ count($contextStack->stack) - 1 ];
	}

	public static function pop( )
	{
		global $contextStack;

		//	this sometimes happens during the error reporting
		if( !count($contextStack->stack) )
			return null;
		
		$context = $contextStack->stack[ count($contextStack->stack) - 1 ];
		unset( $contextStack->stack[ count($contextStack->stack) - 1 ] );

		return $context;
	}

	 // Utility functions
	/**
	 *  Shortcut for adding page-based context
	 */
	public static function pushPageContext( $pageObj ) {
		RunnerContext::push( new RunnerContextItem( CONTEXT_PAGE, array( "pageObj" => $pageObj ) ) );
	}
	/**
	 *  Shortcut for adding record-based context
	 */
	public static function pushRecordContext( $record, $pageObj ) {
		RunnerContext::push( new RunnerContextItem( CONTEXT_ROW, array( "pageObj" => $pageObj, "data" => $record ) ) ); //?
	}

	public static function getMasterValues() {
		$ctx = RunnerContext::current();
		return $ctx->getMasterValues();
	}

	public static function getValues() {
		$ctx = RunnerContext::current();
		return $ctx->getValues();
	}
}

/**
 * 	Push context in constructor and pop in destructor
 */
class TempContext
{
	function __construct( $context ) {
		RunnerContext::push( $context );
	}

	function __destruct() {
		RunnerContext::pop();
	}
}

?>