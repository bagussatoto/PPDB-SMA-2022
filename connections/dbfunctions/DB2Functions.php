<?php
class DB2Functions extends DBFunctions
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
	public function addSlashes( $str )
	{
		return str_replace("'", "''", $str);
	}
	
	/**
	 * @param String str
	 * @return String
	 */	
	public function addSlashesBinary( $str )
	{
		return $str;
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
	 * @param Number type (oprional)
	 * @return String	 
	 */
	public function field2char($value, $type = 3)
	{
		if( IsCharType($type) )
			return $value;
		return "char(".$value.")";
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
		return "SELECT IDENTITY_VAL_LOCAL() FROM SYSIBM.SYSDUMMY1";
	}

	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) 
	{
		if( $applyLimit && $connection->dbType == nDATABASE_DB2 ) 
		{
			$strSQL = "with DB2_QUERY as (".$strSQL.") select * from DB2_QUERY where DB2_ROW_NUMBER between "
				.(($pageStart - 1) * $pageSize + 1)." and ".($pageStart * $pageSize);
		}
		$qResult = $connection->query( $strSQL );
		if( $applyLimit && $connection->dbType != nDATABASE_DB2 ) {
			$qResult->seekPage( $pageSize, $pageStart );
		}
		
		return $qResult;
	}
	public function intervalExpressionString( $expr, $interval ) 
	{
		return DBFunctions::intervalExprSubstr( $expr, $interval );
	}

	public function intervalExpressionNumber( $expr, $interval ) 
	{
		return DBFunctions::intervalExprFloor( $expr, $interval );
	}

	public function intervalExpressionDate( $expr, $interval ) 
	{
		if($interval == 1) // DATE_INTERVAL_YEAR
			return "year(".$expr.")*10000+0101";
		if($interval == 2) // DATE_INTERVAL_QUARTER
			return "year(".$expr.")*10000+QUARTER(".$expr.")*100+1";
		if($interval == 3) // DATE_INTERVAL_MONTH
			return "year(".$expr.")*10000+month(".$expr.")*100+1";
		if($interval == 4) // DATE_INTERVAL_WEEK
			return "year(".$expr.")*10000+week(".$expr.")*100+01";
		if($interval == 5) // DATE_INTERVAL_DAY
			return "year(".$expr.")*10000+month(".$expr.")*100+day(".$expr.")";
		if($interval == 6) // DATE_INTERVAL_HOUR
			return "year(".$expr.")*1000000+month(".$expr.")*10000+day(".$expr.")*100+HOUR(".$expr.")";
		if($interval == 7) // DATE_INTERVAL_MINUTE
			return "year(".$expr.")*100000000+month(".$expr.")*1000000+day(".$expr.")*10000+HOUR(".$expr.")*100+minute(".$expr.")";
		return $expr;
	}

}
?>