<?php
class OracleFunctions extends DBFunctions
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
	public function prepareString($str)
	{
		$ora_maxstring = 4000;
		
		if( strlen($str) < $ora_maxstring )
			return "'".$this->addSlashes( $str )."'";
			
	//	split ret to 4000-len substrings
		$i = 0;
		$out = "";
		while( $i < strlen($str) )
		{
			if( strlen($out) )
				$out.="||";
			$out.= "to_clob('".$this->addSlashes( substr($str, $i, $ora_maxstring) )."')";
			$i += $ora_maxstring;
		}
		return $out;
		
	}
	
	/**
	 * @param String str
	 * @return String		 
	 */	
	function addSlashes( $str )
	{
		return str_replace("'", "''", $str);
	}
	
	/**
	 * @param String str
	 * @return String		 
	 */	
	function addSlashesBinary( $str )
	{
		return $str;
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
	 * @param String
	 */
	public function upper( $dbval )
	{
		return "upper(".$dbval.")";
	}

	
	/**
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
	 *  Get the auto generated SQL string used in the last query
	 * @param String key (optional)	
	 * @param String table (optional)	
	 * @param String oraSequenceName (optional)	
	 * @return String
	 */
	public function getInsertedIdSQL( $key = null, $table = null, $oraSequenceName = false )
	{
		if ( $oraSequenceName )
			$lastIdSQL = "SELECT " . $oraSequenceName . ".CURRVAL FROM DUAL";
		else if ( !is_null($key) && !is_null($table) )
			$lastIdSQL = "SELECT MAX(" . $this->addFieldWrappers( $key ) . ") FROM " . $this->addTableWrappers( $table );
		else
			return false;

		return $lastIdSQL;
	}		

	public function crossDbSupported()
	{
		return false;
	}
	
	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) 
	{
		if( $applyLimit ) 
			$strSQL = AddRowNumber($strSQL, $pageStart * $pageSize);
		$qResult =  $connection->query( $strSQL );
		$qResult->seekPage( $pageSize, $pageStart );
	
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
			return "TO_CHAR(".$expr.", 'YYYY')*10000+0101";
		if($interval == 2) // DATE_INTERVAL_QUARTER
			return "TO_CHAR(".$expr.", 'YYYY')*10000+TO_CHAR(".$expr.",'Q')*100+1";
		if($interval == 3) // DATE_INTERVAL_MONTH
			return "TO_CHAR(".$expr.", 'YYYY')*10000+TO_CHAR(".$expr.",'MM')*100+1";
		if($interval == 4) // DATE_INTERVAL_WEEK
			return "TO_CHAR(".$expr.", 'YYYY')*10000+TO_CHAR(".$expr.",'W')*100+01";
		if($interval == 5) // DATE_INTERVAL_DAY
			return "TO_CHAR(".$expr.", 'YYYY')*10000+TO_CHAR(".$expr.",'MM')*100+TO_CHAR(".$expr.",'DD')";
		if($interval == 6) // DATE_INTERVAL_HOUR
			return "TO_CHAR(".$expr.", 'YYYY')*1000000+TO_CHAR(".$expr.",'MM')*10000+TO_CHAR(".$expr.",'DD')*100+TO_CHAR(".$expr.",'HH')";
		if($interval == 7) // DATE_INTERVAL_MINUTE
			return "TO_CHAR(".$expr.", 'YYYY')*100000000+TO_CHAR(".$expr.",'MM')*1000000+TO_CHAR(".$expr.",'DD')*10000+TO_CHAR(".$expr.",'HH')*100+TO_CHAR(".$expr.",'MI')";
		return $expr;
	}

}

?>