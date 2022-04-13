<?php
class GenericFunctions extends DBFunctions
{	

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

}

?>