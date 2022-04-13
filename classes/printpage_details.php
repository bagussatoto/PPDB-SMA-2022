<?php
class PrintPage_Details extends PrintPage
{
	public $printMasterTable = array();
	public $printMasterKeys = array();
	
	public $multipleDetails = false;
	
	
	/**
	 * @constructor
	 */
	function __construct(&$params = "")
	{
		parent::__construct($params);
		//details
	}

	/**
	 * Process the page 
	 */
	public function process()
	{
		//	Before Process event
		if( $this->eventsObject->exists("BeforeProcessPrint") )
			$this->eventsObject->BeforeProcessPrint( $this );
			
		$this->commonAssign();
		$this->setMapParams();

		$this->splitByRecords = 0; // show all details in master list print page
		$this->allPagesMode = true;
		$this->buildSQL();
		$this->calcRowCount();
		$this->openQuery();
		
		$this->fillGridPage();
		$this->fillRenderedData( $this->pageBody["grid_row"]["data"] );
		
		$this->showTotals();

		$this->hideEmptyFields();
		
		$this->addCommonJs();
		
		$this->doCommonAssignments();
		$this->addCustomCss();
		$this->displayPrintPage();		
	}

	/**
	 *
	 */
	public function displayPrintPage()
	{
		if( !$this->fetchedRecordCount )
			return;

		$this->xt->bulk_assign( $this->pageBody );
		
		if( $this->pdfJsonMode() )
		{
			$this->xt->assign( "body", true );
			$this->xt->assign( "embedded_grid", true );
			
			$this->xt->load_templateJSON( $this->templatefile );
			echo  $this->xt->fetch_loadedJSON("body");
			return;
		}
			
		$this->xt->hideAllBricksExcept( array( "grid" ) );

		$this->xt->assign("grid_block", true);
		//	show table name only when several details are printed
		$this->xt->assign( "printheader", $this->multipleDetails );

		$this->xt->load_template($this->templatefile);
//		$this->xt->prepareContainers();

		if( $this->isPD() ) 
		{
			echo '<div class="panel panel-info details-grid">
				<div class="panel-heading">
					<h4 class="panel-title">' . $this->getPageTitle( $this->pageType, GoodFieldName($this->tName)) . '</h4>
				</div>
				<div class="panel-body">';
			echo $this->fetchForms( array( "grid" ) );	
			echo '</div>
			</div>';			
		} 
		else
		{
			echo "<div class='rnr-print-details'>";
			if( $this->multipleDetails )
			{
				echo "<div class='rnr-pd-title'>";
				echo $this->getPageTitle( $this->pageType, GoodFieldName($this->tName));
				echo "</div>";
			}
			echo "<div class='rnr-pd-grid'>";
			echo $this->xt->fetch_loaded("container_grid");
			echo "</div>";
			echo "</div>";
		}
	}
	
	/**
	 * returns where clause for active master-detail relationship
	 *
	 * @return string
	 */
	function getMasterTableSQLClause( $basedOnProp = false ) 
	{
		$where = "";
		$dKeys = $this->pSet->getDetailKeysByMasterTable( $this->printMasterTable );
		if( !$dKeys )
			return "1=0";
		
		foreach( $dKeys as $i => $key ) 
		{
			if($i != 0) 
				$where.= " and ";
				
			if($this->cipherer && $this->cipherer->isEncryptionByPHPEnabled())
				$mValue = $this->cipherer->MakeDBValue( $key, $this->printMasterKeys[$i] );
			else 
				$mValue = make_db_value( $key, $this->printMasterKeys[$i], "", "", $this->tName);
				
			if(strlen($mValue) != 0)
				$where.= $this->getFieldSQLDecrypt( $key ) . "=" . $mValue;
			else 
				$where.= "1=0";
		}
		return $where;
	}
	
	protected function prepareColumnOrderSettings() 
	{
	}
}
?>