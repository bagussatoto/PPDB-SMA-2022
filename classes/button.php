<?php
class Button
{
	var $keys = array();
	
	var $currentKeys = array();
	
	var $selectedKeys = array();
	
	var $isManyKeys = false;
	
	var $isGetNext = false;
	
	var $location = "";
	
	var $nextInd;

	var $tempFileNames = array();
	
	function __construct(&$params)
	{
		RunnerApply($this, $params);
		
		$this->nextInd = 0;
		$this->modifyKeys();
		$this->separateKeys();
	}
	/**
	 * Separate modified post keys to current and selected  
	 */
	function separateKeys()
	{
		if($this->location == 'grid')
		{
			if($this->isManyKeys) 
			{
				$this->currentKeys = $this->keys[0];
				for($i=1; $i<count($this->keys); $i++)
					$this->selectedKeys[$i-1] = $this->keys[$i]; 
			}
			else
				$this->currentKeys = $this->keys;
		}
		if($this->location == PAGE_LIST) {
			$this->selectedKeys = $this->keys;
			$this->currentKeys = $this->keys;
		}
		
		if($this->location == PAGE_EDIT || $this->location == PAGE_VIEW)
			$this->currentKeys = $this->keys;
	}
	/**
	 * Modify post keys array to associative 
	 */
	function modifyKeys()
	{
		global $strTableName, $gSettings;
		
		$keys = array();
		
		// if array of keys exists
		if(count($this->keys))
		{
			$tKeysNamesArr = $gSettings->getTableKeys();
			if($this->isManyKeys)
			{
				foreach ($this->keys as $ind => $value)
				{
					$keys[$ind] = array();
					$recKeyArr = explode('&', $value);
					for($j=0;$j<count($tKeysNamesArr);$j++)
					{
						if (isset($recKeyArr[$j])){
							$keys[$ind][$tKeysNamesArr[$j]] = urldecode($recKeyArr[$j]);
						}
					}
				}
			}
			elseif(count($this->keys))
			{
				for($j=0;$j<count($tKeysNamesArr);$j++)
				{
					$keys[$tKeysNamesArr[$j]] = urldecode(@$this->keys[$j]);
				}
			}
		}
		$this->keys = $keys;
	}
	/**
	 * Get keys
	 * @return {array} 
	 */
	function getKeys()
	{
		return $this->keys;
	}	
	/**
	 * Get current record data
	 *
	 * @return {mixed} array of next record data or false
	 */
	function getCurrentRecord()
	{
		return $this->getRecordData();
	}
	/**
	 * Get next selected record
	 *
	 * @return {mixed} array of next record data or false
	 */
	function getNextSelectedRecord()
	{
		if($this->nextInd < count($this->selectedKeys))
		{
			$this->isGetNext = true;
			return $this->getRecordData();
		}
		else
			return false;
	}
	/**
	 * Read values from the database by keys
	 *
	 * @return {mixed} array of current record data or false
	 */
	function getRecordData()
	{

		global $gSettings, $gQuery, $cipherer, $strTableName, $cman;
	
		if($this->location!=PAGE_EDIT && $this->location!=PAGE_VIEW && $this->location!=PAGE_LIST && $this->location!='grid' && !$next)
			return false;
		
		$connection = $cman->byTable( $strTableName );
		
		if($this->isGetNext)
		{
			$this->isGetNext = false;
			$keys = $this->selectedKeys[$this->nextInd];
			$this->nextInd=$this->nextInd+1;
		}
		else
			$keys = $this->currentKeys;
		
		$strSQL = $gQuery->buildSQL_default( array( KeyWhere($keys, $strTableName ), SecuritySQL("Search") ) );
		LogInfo($strSQL);
		
		$data = $cipherer->DecryptFetchedArray( $connection->query( $strSQL )->fetchAssoc() );
		return $data;
	}

	function getMasterData( $masterTable )
	{
		if ( isset($_SESSION[ $masterTable . "_masterRecordData" ]) )
		{
			return $_SESSION[ $masterTable . "_masterRecordData" ];
		}
		
		return false;
	}

	function saveTempFile( $contents ) {
		$filename = tempnam("", "");
		runner_save_file($filename, $contents);
		$this->tempFileNames[] = $filename;
		return $filename;
	}

	function deleteTempFiles() {
		foreach( $this->tempFileNames as $f ) {
			@unlink( $f );
		}
	}
}
?>