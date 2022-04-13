<?php
class PostgreFunctions extends DBFunctions
{
	/**
	 * db version
	 * @type Number
	 */
	protected $postgreDbVersion;
	
	
	function __construct( $params )
	{
		parent::__construct( $params  );
		
		$this->escapeChars[ '\\' ] = true;
		$this->postgreDbVersion = $extraParams["postgreDbVersion"];
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
	public function addSlashes( $str )
	{
		##if @ext=="php"##
		return pg_escape_string( $str );
		##endif##
		return parent::addSlashes( $str );
	}

	/**
	 * @param String str
	 * @return String
	 */		
	public function addSlashesBinary( $str )
	{		
		##if @ext=="php"##
		if( $this->postgreDbVersion < 9 )
			return "'".pg_escape_bytea($str)."'";
		##endif##
		
		if( !strlen($str) )
			return "''";
			
		return "E'\\\\x".bin2hex($str)."'";
		
	}
	
	/**
	 * @param String str
	 * @return String
	 */	
	public function stripSlashesBinary( $str )
	{
		##if @ext=="php"##
		if( $this->postgreDbVersion < 9 )	
			return pg_unescape_bytea($str);
		##endif##
			
		return hex2bin( substr($str, 2) );	
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
	public function field2char( $value, $type = 3 )
	{
		if( IsCharType($type) )
			return $value;
		
		return "''||(".$value.")";
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

	/**
	 * Get the auto generated SQL string used in the last query
	 * @param String key (optional)
	 * @param String table (optional)
	 * @param String oraSequenceName (optional)
	 * @return String
	 */
	public function getInsertedIdSQL( $key = null, $table = null, $oraSequenceName = false )
	{
		return "SELECT LASTVAL()";
	}	

	public function crossDbSupported()
	{
		return false;
	}
	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) 
	{
		if( $applyLimit ) {
			$strSQL.= " limit ".$pageSize." offset ".(($pageStart - 1) * $pageSize);
		}
		return $connection->query( $strSQL );
	}

	public function intervalExpressionString( $expr, $interval ) 
	{
		return DBFunctions::intervalExprSubstring( $expr, $interval );
	}

	public function intervalExpressionNumber( $expr, $interval ) 
	{
		return DBFunctions::intervalExprFloor( $expr, $interval );
	}

	public function intervalExpressionDate( $expr, $interval ) 
	{
		if($interval == 1) // DATE_INTERVAL_YEAR
			return "date_part('year',".$expr.")*10000+0101";
		if($interval == 2) // DATE_INTERVAL_QUARTER
			return "date_part('year',".$expr.")*10000+date_part('quarter',".$expr.")*100+1";
		if($interval == 3) // DATE_INTERVAL_MONTH
			return "date_part('year',".$expr.")*10000+date_part('month',".$expr.")*100+1";
		if($interval == 4) // DATE_INTERVAL_WEEK
			return "date_part('year',".$expr.")*10000+(date_part('week',".$expr.")-1)*100+01";
		if($interval == 5) // DATE_INTERVAL_DAY
			return "date_part('year',".$expr.")*10000+date_part('month',".$expr.")*100+date_part('days',".$expr.")";
		if($interval == 6) // DATE_INTERVAL_HOUR
			return "date_part('year',".$expr.")*1000000+date_part('month',".$expr.")*10000+date_part('days',".$expr.")*100+date_part('hour',".$expr.")";
		if($interval == 7) // DATE_INTERVAL_MINUTE
			return "date_part('year',".$expr.")*100000000+date_part('month',".$expr.")*1000000+date_part('days',".$expr.")*10000+date_part('hour',".$expr.")*100+date_part('minute',".$expr.")";
		return $expr;
	}

}
?>