<?php
class InformixFunctions extends DBFunctions
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
	 * @param Mixed $val
	 * @return String
	 */
	public function addDateQuotes( $val )
	{
		if( $val == "" || $val === null )
			return 'null';
		$arrDate = db2time($val);
		return "'".$arrDate[0]."-".$arrDate[1]."-".$arrDate[2]." ".$arrDate[3].":".$arrDate[4].":".$arrDate[5]."'";
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

	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) 
	{
		if( $applyLimit ) 
			$strSQL = AddTopIfx($strSQL, $pageStart * $pageSize);
	
		$qResult =  $connection->query( $strSQL );
		$qResult->seekPage( $pageSize, $pageStart );
		
		return $qResult;
	}

	public function intervalExpressionString( $expr, $interval ) 
	{
		return DBFunctions::intervalExprSubstring( $expr, $interval );
	}

	public function intervalExpressionNumber( $expr, $interval ) 
	{
		return DBFunctions::intervalExprFloor( $expr, $interval );
	}
}
?>