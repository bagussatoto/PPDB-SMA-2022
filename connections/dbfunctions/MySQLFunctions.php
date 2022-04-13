<?php
class MySQLFunctions extends DBFunctions
{
	/**
	 *  A db connection link identifier
	 * @type Mixed
	 */
	protected $conn = null;
	
	
	function __construct( $params )
	{
		parent::__construct($params);
		$this->strLeftWrapper = "`";
		$this->strRightWrapper = "`";
		$this->escapeChars[ '\\' ] = true;
		
		$this->conn = $params["conn"];	
	}
	
	/**
	 * @param String str
	 * @return String
	 */
	public function escapeLIKEpattern( $str )
	{
		return str_replace(array('\\', '%', '_'), array('\\\\', '\\%', '\\_'), $str);
	}

	
	/**
	 * @param String str
	 * @return String
	 */
	public function addSlashes( $str )
	{
		if( useMySQLiLib() && $this->conn )
		{
			if( $this->conn )
				return mysqli_real_escape_string( $this->conn, $str );
		} 
		else
		{
			//	ODBC connection, no MySQL library included
			return str_replace(array('\\', '\''), array('\\\\', '\\\''), $str);
		}
	}
	
	/**
	 * @param String str
	 * @return String
	 */	
	public function addSlashesBinary( $str )
	{
		if( !strlen($str) )
			return "''";
			
		return "0x".bin2hex($str);
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
	 * @param String dbval
	 * @return String	 
	 */
	public function upper( $dbval )
	{
		return "upper(".$dbval.")";
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
		if( IsDateFieldType($type) )
			return "time(".$value.")";
			
		return $value;
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
		return "SELECT LAST_INSERT_ID()";
	}

	/**
	 * @param String strName
	 * @return String
	 */	
	public function timeToSecWrapper( $strName )
	{
		return "TIME_TO_SEC(" . $this->addTableWrappers($strName) . ")";
	}

	public function schemaSupported()
	{
		return false;
	}

	public function caseSensitiveComparison( $val1, $val2 )
	{
		return 'binary ' . $val1 . ' = ' . $val2;
	}
	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) 
	{
		if( $applyLimit ) 
			$strSQL.= " limit ".(($pageStart - 1) * $pageSize).",".$pageSize;
		return $connection->query( $strSQL );
	}

	public function intervalExpressionString( $expr, $interval ) 
	{
		return DBFunctions::intervalExprLeft( $expr, $interval );
	}

	public function intervalExpressionNumber( $expr, $interval ) 
	{
		return DBFunctions::intervalExprFloor( $expr, $interval );
	}

	public function intervalExpressionDate( $expr, $interval ) 
	{
		if($interval == 1) // DATE_INTERVAL_YEAR
			return "year(".$expr.")*10000 + 101"; // ???
		if($interval == 2) // DATE_INTERVAL_QUARTER
			return "year(".$expr.")*10000 + QUARTER(".$expr.")*100+1";
		if($interval == 3) // DATE_INTERVAL_MONTH
			return "year(".$expr.")*10000 + month(".$expr.")*100+1";
		if($interval == 4) // DATE_INTERVAL_WEEK
			return "year(".$expr.")*10000 + week(".$expr.")*100+01";
		if($interval == 5) // DATE_INTERVAL_DAY
			return "year(".$expr.")*10000 + month(".$expr.")*100 + day(".$expr.")";
		if($interval == 6) // DATE_INTERVAL_HOUR
			return "year(".$expr.")*1000000 + month(".$expr.")*10000 + day(".$expr.")*100 + HOUR(".$expr.")";
		if($interval == 7) // DATE_INTERVAL_MINUTE
			return "year(".$expr.")*100000000 + month(".$expr.")*1000000 + day(".$expr.")*10000 + HOUR(".$expr.")*100 + minute(".$expr.")";
		return $expr;
	}

	
}
?>