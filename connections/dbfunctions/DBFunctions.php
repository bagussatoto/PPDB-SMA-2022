<?php
class DBFunctions
{
	/**
	 * The left db wrapper
	 * @type String
	 */
	protected $strLeftWrapper;
	
	/**
	 * The right db wrapper	
	 * @type String
	 */	
	protected $strRightWrapper;
	
	protected $escapeChars = array();

	
	function __construct( $params )
	{
		//	default identifier wrappers
		$this->strLeftWrapper = "\"";
		$this->strRightWrapper = "\"";
		
		$this->escapeChars[ '\'' ] = true;

		if( isset( $params["leftWrap"] ) && isset( $params["rightWrap"] ) ) 
		{
			$this->strLeftWrapper = $params["leftWrap"];
			$this->strRightWrapper = $params["rightWrap"];
		}
		
	}

	/**
	 * @param String strName
	 * @return String
	 */	
	public function addTableWrappers( $strName )
	{
		if( substr($strName, 0, 1) == $this->strLeftWrapper )
			return $strName;
			
		$arr = explode(".", $strName);
		$ret = "";
		foreach( $arr as $e )
		{
			if( $ret != "" )
				$ret .= ".";
			$ret .= $this->strLeftWrapper . $e . $this->strRightWrapper;
		}
		return $ret;
	}

	
    /**
     * Tells if the database supports schemas
     * @return boolean
     */
	public function schemaSupported()
	{
		return true;
	}

    /**
     * Tells if the database supports cross-database references:
	 * db.tablename
     * @return boolean
     */
	public function crossDbSupported()
	{
		return true;
	}

	
    /**
     * Parses the table name into database, schema and table name
     * @param string $name the table name
     */
    public function getTableNameComponents($name)
    {
        $strippedTableName = str_replace( array($this->strLeftWrapper,$this->strRightWrapper), '', $name ) ;
		$parts = explode('.', $strippedTableName );

    	$components = array();
		$components["fullName"] = $name;

        if( count($parts) == 3 && $this->crossDbSupported() && $this->schemaSupported()) {
            // DB name, schema name and table name passed
            $components["db"] = $parts[0];
            $components["schema"] = $parts[1];
            $components["table"] = $parts[2];
        } 
		elseif ( count($parts) == 2 && ( $this->crossDbSupported() || $this->schemaSupported() ) ) 
		{
            if( $this->schemaSupported() )
				$components["schema"] = $parts[0];
			else
				$components["db"] = $parts[0];
            $components["table"] = $parts[1];
        } 
		else 
		{
            $components["table"] = $name;
        }
 		
 		return $components;
    }

	/**
	 *  Get the auto generated SQL string used in the last query
	 * @param String key (optional)	
	 * @param String table (optional)	
	 * @param String oraSequenceName (optional)	
	 * @return String
	 */
	public function getInsertedIdSQL( $key = null, $table = null, $oraSequenceName = false )
	{
		return "SELECT MAX(" . $this->addFieldWrappers( $key ) . ") FROM " . $this->addTableWrappers( $table );
	}	

	/**
	 * @param String strName
	 * @return String
	 */	
	public function timeToSecWrapper( $strName )
	{
		return $this->addTableWrappers($strName);
	}

	/**
	 * @param String str
	 * @return String
	 */		
	public function prepareString( $str )
	{
		return "'".$this->addSlashes($str)."'";
	}
	
	/**
	 * @param String str
	 * @return String
	 */			
	public function addSlashes( $str )
	{
		return str_replace("'", "''", $str);
	}

	/**
	 * Alias for addSlashes
	 * @param String str
	 * @return String
	 */			
	public function escapeString( $str )
	{
		return $this->addSlashes( $str );
	}
	
	
	/**
	 * adds wrappers to field name if required	
	 * @param String strName
	 * @return String
	 */		
	public function addFieldWrappers( $strName )
	{
		if( substr($strName, 0, 1) == $this->strLeftWrapper )
			return $strName;
		return $this->strLeftWrapper.$strName.$this->strRightWrapper;
	}

	/**
	 * Decode binary value selected from the database
	 * @param String str
	 * @return String
	 */	
	public function stripSlashesBinary( $str )
	{
		return $str;
	}

	/**
	 *	Checks if character at position $pos in SQL string is inside quotes.
	 * 	Example:
	 *  select 'aaa\' 1', ' ' 2
	 *  Character 1 is on quotes, 2 - not
	 *  @return Boolean
	 */
	public function positionQuoted( $sql, $pos ) 
	{
		$inQuote = false;
		$afterEscape = false;			
		for ($i = 0; $i < $pos; $i++)
		{ 
			$c = substr( $sql, $i, 1);
			if( !$afterEscape )
			{
				if ( $c == '\'' )
					$inQuote = !$inQuote;
				else
					$afterEscape = !$inQuote && isset( $this->escapeChars[ $c ] );
			}
			else
				$afterEscape = false;
		}
		return $inQuote;
	}
	
	/**
	 * @param String str
	 * @return String
	 */		
	public function escapeLIKEpattern( $str )
	{
		return $str;
	}
	
	
	/**
	 * @param String str
	 * @return String
	 */	
	public function addSlashesBinary( $str )
	{
		return $this->addSlashes($str);
	}
	
	/**	
	 * @param String dbval
	 * @return String	 
	 */	
	public function upper( $dbval )
	{
		return $dbval;
	}

	/**	
	 * @param String dbval
	 * @return String	 
	 */	
	public function caseSensitiveComparison( $val1, $val2 )
	{
		return $val1 . ' = ' . $val2;
	}

	public function caseInsensitiveComparison( $val1, $val2 )
	{
		return $this->upper( $val1 ) . ' = ' . $this->upper( $val2 );
	}

	/**
	 * @param Mixed $val
	 * @return String
	 */
	public function addDateQuotes( $val )
	{
		if( $val == "" || $val === null )
			return 'null';
		return "'".$this->addSlashes($val)."'";
	}

	/**
	 * It's called for Contains and Starts with searches
	 * @param Mixed value
	 * @param Number type (optional)
	 * @return String	 
	 */
	public function field2char($value, $type = 3)
	{
		return $value;
	}
	
	/**
	 * @param Mixed value
	 * @param Number type
	 * @return String	 
	 */
	public function field2time($value, $type)
	{
		return $value;
	}	

	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) {
		$qResult =  $connection->query( $strSQL );
		$qResult->seekPage( $pageSize, $pageStart );
		return $qResult;
	}
	
	/**
	 * Applies group type to a character expression.
	 * <SQL expression> => substring(<SQL expression>, 0, N )
	 * @param String expr - SQL expression
	 * @param Integer interval - type of interval. 
	 * 	0 - whole expression, 1 first character, 2 - first two chars etc
	 * @return String
	 */
	public function intervalExpressionString( $expr, $interval ) 
	{
		//	default implementation
		return $expr;
	}

	/**
	 * Applies group type to a numeric expression.
	 * @param String expr - SQL expression
	 * @param Integer interval - type of interval. 
	 * @return String
	 */
	public function intervalExpressionNumber( $expr, $interval ) 
	{
		//	default implementation
		return $expr;
	}

	/**
	 * Applies group type to a DATE expression.
	 * @param String expr - SQL expression
	 * @param Integer interval - type of interval. 
	 * @return String
	 */
	public function intervalExpressionDate( $expr, $interval ) 
	{
		//	default implementation
		return $expr;
	}

	public static function intervalExprLeft( $expr, $interval ) {
		if( !$interval )
			return $expr;
		return "left(" . $expr . ", " . $interval . ")";
	}

	public static function intervalExprSubstr( $expr, $interval ) {
		if( !$interval )
			return $expr;
		return "substr(" . $expr . ", 1, " . $interval . ")";
	}

	public static function intervalExprSubstring( $expr, $interval ) {
		if( !$interval )
			return $expr;
		return "substring(" . $expr . " from 1 for " . $interval . ")";
	}

	public static function intervalExprFloor( $expr, $interval ) {
		if( !$interval )
			return $expr;
		return "floor( " . $expr . " / " . $interval . " ) * ".$interval;
	}

}
?>