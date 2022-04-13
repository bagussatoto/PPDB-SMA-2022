<?php
class SQLite3Functions extends DBFunctions
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
			return "x''";
		return "x'".bin2hex($str)."'";
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
		return $this->addFieldWrappers($strName);
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
		return "SELECT last_insert_rowid()";
	}	

	public function queryPage( $connection, $strSQL, $pageStart, $pageSize, $applyLimit ) 
	{
		if( $applyLimit ) 
			$strSQL.= " limit ".(($pageStart - 1) * $pageSize).",".$pageSize;
	
		return $connection->query( $strSQL );
	}

	public function intervalExpressionString( $expr, $interval ) 
	{
		return DBFunctions::intervalExprSubstr( $expr, $interval );
	}

	public function intervalExpressionNumber( $expr, $interval ) 
	{
		return DBFunctions::intervalExprFloor( $expr, $interval );
	}
	
}
?>