<?php
/**
 * Class for list page with mode simple
 *
 */
class PrintPage_Master extends PrintPage 
{
	public $pdfJson = false;	
	/**
	 * Constructor, set initial params
	 *
	 * @param array $params
	 */	
	function __construct(&$params) 
	{
		// call parent constructor
		parent::__construct($params);	
		$this->pageType = "masterprint";
		$this->masterPageType = $params["masterPageType"];
		if( $this->masterPageType == "report")
			$this->pageType = "masterrprint";
	}
	/**
	 * Add common assign for simple mode on list page
	 */	
	function commonAssign() 
	{
		parent::commonAssign();
		
		
	}

	
	public function getMasterHeading() 
	{
			
		$this->xt->assign( "masterlist_title", true );
		return $this->xt->fetch_loaded( "masterlist_title" );

	}
	public function preparePage() 
	{
		if( !$this->masterRecordData || !count($this->masterRecordData) )
			return;
		
		$pageTypeTitle = $this->pageType;
		if ( $this->masterPageType == "report" )
		{
			$pageTypeTitle = "masterprint";
		}
		$this->xt->assign("pagetitlelabel", $this->getPageTitle( $pageTypeTitle, GoodFieldName($this->tName), $this->masterRecordData ));	

		$tKeys = $this->pSet->getTableKeys();
		$keylink = "";

				
		for($i = 0; $i < count($tKeys); $i ++) {
			$keylink.= "&key".($i + 1)."=".runner_htmlspecialchars(rawurlencode(@$this->masterRecordData[$tKeys[$i]]));
		}
		
		$fields = $this->pSet->getPageFields();
		$fields = array_merge( $fields, $tKeys);
		foreach( $fields as $f )
		{
			$this->xt->assign( GoodFieldname( $f ) . "_fieldheader", true );
			$fieldClassStr = $this->fieldClass($f);
			
			$value = $this->showDBValue( $f, $this->masterRecordData, $keylink);

			if( $this->pdfJsonMode() )
			{
				$this->xt->assign( GoodFieldName( $f ) . "_pdfvalue", $value );
			}
			else 
			{
				$wrappedValue = "<span class='". $fieldClassStr ."'>".$value."</span>";
				$this->xt->assign( GoodFieldName( $f ) . "_mastervalue", $wrappedValue );
				$this->xt->assign( GoodFieldName( $f ) . "_value", $wrappedValue );
			}

			$this->xt->assign( GoodFieldName( $f ) . "_class", $fieldClassStr); // add class for field header as field value
			//	page designer layout
			$this->xt->assign( GoodFieldName( $f ) . "_fieldcolumn", true );
			$this->xt->assign( GoodFieldName( $f ) . "_fieldheadercolumn", true );
			
		}
		$this->xt->assign("grid_header", true );
		$this->xt->assign("grid_row", true );
		$this->xt->assign("grid_record", true );
		$this->xt->assign("grid_vrecord", true );
			
		if( $this->pageLayout )
			$this->xt->assign("pageattrs", 'class="'.$this->pageLayout->style." page-".$this->pageLayout->name.'"');
		
		if( !$this->pdfJsonMode() ) 
			$this->xt->load_template( GetTemplateName($this->shortTableName, $this->pageType) );
	}

	public function showMaster( $params ) 
	{
		if( !$this->masterRecordData || !count($this->masterRecordData) )
			return;
		
		$this->xt->assign( "masterlist_title", false );
		
		if( $this->pdfJsonMode() ) 
		{
			$this->xt->assign("body", true );
			$this->xt->assign("embedded_grid", true );

			$this->xt->load_templateJSON( $this->templatefile);
			echo  $this->xt->fetch_loadedJSON("body");
		} 
		else
			$this->xt->display_loaded();
	}

	function pdfJsonMode() 
	{
		return $this->pdfJson;
	}
}

?>
