<?php

class ListPage_DPPopup extends ListPage_DPInline
{	

	
	/**
	 * Show the page.
	 * It's supposed to be displayed in response on an ajax-like request
	 */
	public function showPage()
	{				
		$returnJSON = array();

		$this->xt->assign("view_column", false);
		$this->xt->assign("edit_column", false);
		$this->xt->assign("inlineedit_column", false);
		$this->xt->assign("inlinesave_column", false);
		$this->xt->assign("inlinecancel_column", false);
		$this->xt->assign("copy_column", false);
		$this->xt->assign("checkbox_column", false);
		$this->xt->assign("dtable_column", false);
		
		$this->xt->prepare_template($this->templatefile);

	
		$returnJSON["success"] = true;
		$returnJSON["counter"] = postvalue("counter");
		$detFoundMessage = "Rincian ditemukan";
		$returnJSON["body"] = "<span>" . $detFoundMessage . ": <strong>" . $this->numRowsFromSQL . "</strong></span>" .$this->xt->fetch_loaded("grid_block");
		
		echo printJSON($returnJSON);
		exit;
	}
	
	protected function assignSessionPrefix() 
	{
		$this->sessionPrefix = $this->tName."_preview";	
	}

	function assignColumnHeaders() 
	{
		//	show headers
		foreach( $this->listFields as $f) 
		{
			$this->xt->assign( GoodFieldname( $f['fName'] ) . "_fieldheader", true );			
		}
	}
	
}
?>