<?php
class ViewDatabaseFileField extends ViewControl
{
	public function getPdfValue(&$data, $keylink = "")
	{
		return my_json_encode( array(
			"text" => $this->getFileName($data)
		) );
	}

	public function getFileName($data)
	{
		$fileNameF = $this->container->pSet->getFilenameField($this->field);
		if($fileNameF) 
		{
			$fileName = $data[$fileNameF];
			if(!$fileName)
				$fileName = "file.bin";
		} 
		else 
			$fileName = "file.bin";
		
		return $fileName;
	}

	public function showDBValue(&$data, $keylink, $html = true )
	{
		$value = "";
		$fileName = $this->getFileName($data);
		
		if( strlen($data[$this->field]) ) 
		{
			$value = "<a href='".GetTableLink("getfile", "", 
				"table=".GetTableURL($this->container->pSet->_table)."&filename=".rawurlencode($fileName)
					.'&pagename='.runner_htmlspecialchars( $this->container->pSet->pageName() )."&field=".rawurlencode($this->field).$keylink)."'>";
			$value.= runner_htmlspecialchars($fileName);
			$value.= "</a>";
		}
		return $value;
	}

	/**
	 * @param &Array data
	 * @return String	 
	 */
	public function getTextValue(&$data)
	{
		if( !strlen( $data[ $this->field ] ) )
			return "";

		$fileNameField = $this->container->pSet->getFilenameField( $this->field );	

		if( $fileNameField && $data[ $fileNameField ] ) 
			return $data[ $fileNameField ]; 
		
		return "<<File>>";
	}	
	
	/**
	 * Get the field's content that will be exported
	 * @prarm &Array data
	 * @prarm String keylink
	 * @return String
	 */
	public function getExportValue(&$data, $keylink = "")
	{
		return "Data Biner Panjang - tidak dapat ditampilkan";
	}
}
?>