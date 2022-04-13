<?php
class MSSQLFunctions extends DBFunctions
{	

	function __construct( $params )
	{
		parent::__construct($params);
		$this->strLeftWrapper = "[";
		$this->strRightWrapper = "]";
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
	public function prepareString( $str )
	{
		return "N'".$this->addSlashes($str)."'";
	}
	

	/**
	 * @param String str
	 * @return String
	 */	
	public function addSlashesBinary( $str )
	{
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
	 * @param Mixed $val
	 * @return String
	 */
	public function addDateQuotes( $val )
	{
		if( $val == "" || $val === null )
			return 'null';
		return "convert(datetime,'".$this->addSlashes($val)."',120)";
	}
	
	/**
	 * It's called for Contains and Starts with searches
	 * @param Mixed value
	 * @param Number type (optional)
	 * @return String	 
	 */
	public function field2char($value, $type = 3)
	{
		if( IsCharType($type) )
			return $value;
			
		if( !IsDateFieldType($type) )
			return "convert(varchar(250),".$value.")";
		
		return "convert(varchar(50),".$value.", 120)";
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

	public function caseSensitiveComparison( $val1, $val2 )
	{
		return $val1 . ' = ' . $val2 .' COLLATE SQL_Latin1_General_CP1_CS_AS';
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
			return "datepart(yyyy,".$expr.")*10000+0101";
		if($interval == 2) // DATE_INTERVAL_QUARTER
			return "datepart(yyyy,".$expr.")*10000 + datepart(qq,".$expr.")*100+1";
		if($interval == 3) // DATE_INTERVAL_MONTH
			return "datepart(yyyy,".$expr.")*10000+datepart(mm,".$expr.")*100+1";
		if($interval == 4) // DATE_INTERVAL_WEEK
			return "datepart(yyyy,".$expr.")*10000+(datepart(ww,".$expr.")-1)*100+01";
		if($interval == 5) // DATE_INTERVAL_DAY
			return "datepart(yyyy,".$expr.")*10000+datepart(mm,".$expr.")*100+datepart(dd,".$expr.")";
		if($interval == 6) // DATE_INTERVAL_HOUR
			return "datepart(yyyy,".$expr.")*1000000+datepart(mm,".$expr.")*10000+datepart(dd,".$expr.")*100+datepart(hh,".$expr.")";
		if($interval == 7) // DATE_INTERVAL_MINUTE
			return "datepart(yyyy,".$expr.")*100000000+datepart(mm,".$expr.")*1000000+datepart(dd,".$expr.")*10000+datepart(hh,".$expr.")*100+datepart(mi,".$expr.")";
		return $expr;
	}

}
?>