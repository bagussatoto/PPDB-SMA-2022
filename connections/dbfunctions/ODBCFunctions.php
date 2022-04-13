<?php

/**
 * Ignore the ODBC name
 * This is MS Access functions class
 */
class ODBCFunctions extends DBFunctions
{	
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
		if( !strlen($str) )
			return "''";
		return "0x".bin2hex($str);
	}
	
	/**
	 * @param String str
	 * @return String
	 */	
	public function stripSlashesBinary( $str )
	{
		return db_stripslashesbinaryAccess($str);
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
	 * @param String strName
	 * @return String
	 */	
	public function addTableWrappers($strName)
	{
		if( $this->strLeftWrapper != "\"")
			return $this->addFieldWrappers($strName);
		
		return DBFunctions::addTableWrappers( $strName );
	}

	/**	
	 * @param String dbval
	 * @return String	 
	 */	
	public function upper( $dbval )
	{
		return "ucase(".$dbval.")";
	}

	/**
	 * @param Mixed $val
	 * @return String
	 */
	public function addDateQuotes( $val )
	{
		if( $val == "" || $val === null )
			return 'null';
		return "#".$this->addSlashes($val)."#";
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

	/**
	 * Get the auto generated SQL string used in the last query
	 * @param String key
	 * @param String table
	 * @param String oraSequenceName (optional)	
	 * @return String
	 */
	public function getInsertedIdSQL( $key = null, $table = null, $oraSequenceName = false )
	{
		return "SELECT @@IDENTITY";
	}

	/**
	 * @param String strName
	 * @return String
	 */	
	public function timeToSecWrapper( $strName )
	{
		$wrappedFieldName = $this->addTableWrappers($strName);
		return "(DATEPART(HOUR, " . $wrappedFieldName . ") * 3600) + (DATEPART(MINUTE, " . $wrappedFieldName . ") * 60) + (DATEPART(SECOND, " . $wrappedFieldName . "))";
	}	

	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) 
	{
		if( $applyLimit ) 
			$strSQL = AddTop($strSQL, $pageStart * $pageSize);
	
		$qResult = $connection->query( $strSQL );
		$qResult->seekPage( $pageSize, $pageStart );
		
		return $qResult;
	}

	public function intervalExpressionString( $expr, $interval ) 
	{
		return DBFunctions::intervalExprLeft( $expr, $interval );
	}

	public function intervalExpressionNumber( $expr, $interval ) 
	{
		if( !$interval )
			return $expr;
		return "int( " . $expr . " / " . $interval . " ) * ".$interval;
	}

	public function intervalExpressionDate( $expr, $interval ) 
	{
		if($interval == 1) // DATE_INTERVAL_YEAR
			return "datepart('yyyy',".$expr.")*10000+0101";
		if($interval == 2) // DATE_INTERVAL_QUARTER
			return "datepart('yyyy',".$expr.")*10000+datepart('q',".$expr.")*100+1";
		if($interval == 3) // DATE_INTERVAL_MONTH
			return "datepart('yyyy',".$expr.")*10000+datepart('m',".$expr.")*100+1";
		if($interval == 4) // DATE_INTERVAL_WEEK
			return "datepart('yyyy',".$expr.")*10000+(datepart('ww',".$expr.")-1)*100+01";
		if($interval == 5) // DATE_INTERVAL_DAY
			return "datepart('yyyy',".$expr.")*10000+datepart('m',".$expr.")*100+datepart('d',".$expr.")";
		if($interval == 6) // DATE_INTERVAL_HOUR
			return "datepart('yyyy',".$expr.")*1000000+datepart('m',".$expr.")*10000+datepart('d',".$expr.")*100+datepart('h',".$expr.")";
		if($interval == 7) // DATE_INTERVAL_MINUTE
			return "datepart('yyyy',".$expr.")*100000000+datepart('m',".$expr.")*1000000+datepart('d',".$expr.")*10000+datepart('h',".$expr.")*100+datepart('n',".$expr.")";
		return $expr;
	}
}

?>