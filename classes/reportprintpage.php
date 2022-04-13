<?php
class ReportPrintPage extends ReportPage
{
	public $pageWidth = PDF_PAGE_WIDTH;
	public $pageHeight = PDF_PAGE_HEIGHT;
	public $pdfWidth = PDF_PAGE_WIDTH;
	public $splitAtServer = false;
	public $splitByGroups = 0;
	public $pages = array();

	public $arrPages = array();

	/**
	 *	PDF rendering mode.
	 *	empty - regular page display
	 * 	"build" - build page and return PDF
	 * 	"prepare" - build page and return HTML for browser post-processing
	 *	"convert" - convert post-processed HTML to PDF
	 */
	public $pdfContent = "";

	/**
	 *
	 */
	public $pdfFitToPage = 1;

	/**
	 *
	 */
	public $landscape = 0;

	/**
	 *
	 */
	public $isDetail = false;

	public $isReportEmpty = false;

	public $multipleDetails = false;
	
	public $exportPdf;
	
	/**
	 *
	 */
	function __construct(&$params)
	{
		parent::__construct($params);

		$this->jsSettings['tableSettings'][ $this->tName ]['reportType'] = $this->crossTable ? 1 : 0;

		if(	$this->pdfMode )
		{
			//	pdf mode
			if( $this->pSet->getReportPrintPDFGroupsPerPage() != 0 )
			{
				$this->splitAtServer = true;
				$this->splitByGroups = $this->pSet->getReportPrintPDFGroupsPerPage();
			}

		}
		else if( $this->format == "excel" || $this->format == "word")
		{
			//	export mode
			$this->splitAtServer = false;
			$this->splitByGroups = 0;
		}
		else if( !$this->pdfJsonMode() )
		{
			//	print mode
			if( $this->pSet->getReportPrintPartitionType() != 0 )
			{
				$this->splitAtServer = true;
				if( !$this->splitByGroups )
					$this->splitByGroups = $this->pSet->getReportPrintGroupsPerPage();
			}
			$this->pageData["printRecords"] = $this->splitByGroups;

		} else if ( $this->pdfJsonMode() ) {
			$this->splitAtServer = true;
			if( !$this->splitByGroups )
				$this->splitByGroups = 100000;
		}

		if( $this->isDetail )
		{
			$this->splitAtServer = false;
			$this->splitByGroups = 0;
		}

		//	Before Process event
		if( $this->eventsObject->exists("BeforeProcessReportPrint") )
			$this->eventsObject->BeforeProcessReportPrint( $this );


		if( isRTL() )
			$this->jsSettings['tableSettings'][ $this->tName ]['isRTL'] = true;

		$this->jsSettings['tableSettings'][ $this->tName ]['reportPrintPartitionType'] = $this->pSet->getReportPrintPartitionType();
		$this->jsSettings['tableSettings'][ $this->tName ]['reportPrintGroupsPerPage'] = $this->pSet->getReportPrintGroupsPerPage();
		$this->jsSettings['tableSettings'][ $this->tName ]['reportPrintLayout'] = $this->pSet->getReportPrintLayout();
		$this->jsSettings['tableSettings'][ $this->tName ]['lowGroup'] = $this->pSet->getLowGroup();

		$this->jsSettings['tableSettings'][ $this->tName ]['printerPagePDF'] = $this->pSet->isPrinterPagePDF();

		$this->jsSettings['tableSettings'][ $this->tName ]['printerPageOrientation'] = $this->pSet->getPrinterPageOrientation();
		$this->jsSettings['tableSettings'][ $this->tName ]['printerPageScale'] = $this->pSet->getPrinterPageScale();
		$this->jsSettings['tableSettings'][ $this->tName ]['isPrinterPageFitToPage'] = $this->pSet->isPrinterPageFitToPage();

		if( $this->pSet->getReportPrintPartitionType() == 0 )
			$this->jsSettings['tableSettings'][ $this->tName ]['printerSplitRecords'] = 0;
		else
			$this->jsSettings['tableSettings'][ $this->tName ]['printerSplitRecords'] = $this->pSet->getReportPrintGroupsPerPage();

		$this->jsSettings['tableSettings'][ $this->tName ]['printerPDFSplitRecords'] = $this->pSet->getReportPrintPDFGroupsPerPage();
		
		if ( $this->crossTable ) 
			$this->pageData["crossParams"] = $this->getCurrentCrossParams();		
	}

	/**
	 *
	 */
	public function assignPDFFormatSettings()
	{		
		if( $this->exportPdf )
			$this->jsSettings['tableSettings'][ $this->tName ]['exportPdf'] = 1;

		if( !$this->pdfMode )
			return;

		$this->landscape = $this->pSet->isLandscapePrinterPagePDFOrientation();
		$this->pdfFitToPage = $this->crossTable ? 1 : $this->pSet->isPrinterPagePDFFitToPage();

		$this->pageWidth = PDF_PAGE_WIDTH;
		$this->pageHeight = PDF_PAGE_HEIGHT;

		if( !$this->pdfFitToPage )
		{
			$PrinterPagePDFScale = $this->pSet->getPrinterPagePDFScale();
			$this->pageWidth = $this->pageWidth * 100 / $PrinterPagePDFScale;
			$this->pageHeight = $this->pageHeight * 100 / $PrinterPagePDFScale;
		}

		$this->jsSettings['tableSettings'][ $this->tName ]['pdfPrinterPageOrientation'] = $this->pSet->isLandscapePrinterPagePDFOrientation();
		$this->jsSettings['tableSettings'][ $this->tName ]['printerPageOrientation'] = $this->landscape;
		$this->jsSettings['tableSettings'][ $this->tName ]['createPdf'] = 1;
		$this->jsSettings['tableSettings'][ $this->tName ]['pdfFitToPage'] = $this->pdfFitToPage;

		if( $this->landscape )
		{
			$temp = $this->pageWidth;
			$this->pageWidth = $this->pageHeight;
			$this->pageHeight = $temp;
		}

		$this->jsSettings['tableSettings'][ $this->tName ]['pageWidth'] = $this->pageWidth;
		$this->jsSettings['tableSettings'][ $this->tName ]['pageHeight'] = $this->pageHeight;
	}

	/**
	 * @return Array
	 */
	public function getExtraReportParams()
	{
		$extraParams = parent::getExtraReportParams();
		
		if( $this->format == "excel" )
			$extraParams['forExport'] = "excel";
		elseif( $this->format == "word" )
			$extraParams['forExport'] = "word";
		else
			$extraParams['forExport'] = false;
		
		if( !$this->crossTable )
			$extraParams['mode'] = MODE_PRINT;

		return $extraParams;
	}

	/**
	 *
	 */
	public function process()
	{
		global $cCharset;
		
		$this->displayMasterTableInfo();
		
		$this->assignPDFFormatSettings();

		$forExport = false;
		if( $this->format == "excel" )
		{
			$forExport = "excel";
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment;Filename=".$this->shortTableName.".xls");
			echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
		}
		else if( $this->format == "word" )
		{
			$forExport = "word";
			header("Content-Type: application/vnd.ms-word");
			header("Content-Disposition: attachment;Filename=".$this->shortTableName.".doc");
			echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
		}

		$this->doCommonAssignments();
		
		// the table info params
		$extraParams = $this->getExtraReportParams();	
		$this->setGoogleMapsParams( $extraParams['fieldsArr'] );
		if( $this->pdfJsonMode() ) {
			$this->assignTotalsDefaults();
		}
		$this->setReportData( $extraParams );

		// add button events if exist
		$this->addButtonHandlers();
		$this->addCommonJs();

		$this->showPage();
	}
	
	/**
	 *
	 */
	public function doCommonAssignments()
	{
		$this->assignBody();
		
		$this->xt->assign("stylesheetlink", strlen( $this->format ) > 0);
		
		foreach( $this->pSet->getFieldsList() as $fName ) 
		{
			$this->xt->assign( $fName."_fieldheader", true );
		}

		if( $this->format )
		{
			$this->xt->assign("pdflink_block", false);
		}
		else if( !$this->isPD() || $this->crossTable )
		{
			$this->xt->assign("pdflink_block", $this->pSet->isPrinterPagePDF() && !$this->pdfMode );
		}

		if ( count($this->gridTabs) > 1 ) 
		{
			$curTabId = $this->getCurrentTabId();
			$this->xt->assign("printtabheader",true);
			$this->xt->assign("printtabheader_text", $this->getTabTitle($curTabId));
		}
	}

	protected function setRecordsId() {
		$pageCount = count( $this->arrReport['list'] );
		for( $i = 0; $i < $pageCount; ++$i ) {
			$page = &$this->arrReport['list'][ $i ];
			$recCount = count( $page );
			for( $j = 0; $j < $recCount; ++$j ) {
				$this->genId();
				$page[$j]["recId"] = $this->recId;
			}
		}
	}

	/**
	 *
	 */
	protected function assignBody()
	{		
		if( !$this->pdfJsonMode() ) {
			$this->body["begin"].= GetBaseScriptsForPage( false );
			$this->body["end"] = XTempl::create_method_assignment( "assignBodyEnd", $this);
		}

		$this->xt->assignbyref("body", $this->body);
		$this->xt->assign("grid_block", true);
		$this->xt->assign("grid_header", true);

		if( $this->format && $this->format != "pdf" )
		{
			$this->body["begin"] = "";
			$this->body["end"] = "";
			$this->xt->assignbyref("body", $this->body);
		}			
	}
	
	/**
	 * A stub
	 */
	protected function getnoRecOnFirstPageWhereCondition()
	{
		return "";
	}

	/**
	 * Assign values obtained from crossTable object to
	 * the basic cross table xt variables
	 * @param Boolean showSummary
	 */
	protected function crossTableCommonAssign( $showSummary )
	{
		$this->xt->assign( "report_cross_header", $this->crossTableObj->getPrintCrossHeader() );
		$this->xt->assign( "totals", $this->crossTableObj->getTotalsName() );
		
		$grid_row["data"] = $this->crossTableObj->getCrossTableData();

		if( count($grid_row["data"]) > 0 )
		{
			$this->xt->assign("grid_row", $grid_row);
			$group_headerArr = $this->crossTableObj->getCrossTableHeader();
			$this->xt->assignbyref("group_header", $group_headerArr );
			$this->xt->assign("group_x_count", count( $group_headerArr["data"] ) );
			$this->xt->assign("group_x_label", $this->crossTableObj->getXGroupLabel() );
			$this->xt->assign("group_y_label", $this->crossTableObj->getYGroupLabel() );
			$this->xt->assign("report_cross_field",  $this->crossTableObj->getDataFieldLabel() );
			$this->xt->assign("report_cross_type", $this->crossTableObj->getTotalsName() );

			$this->xt->assignbyref("col_summary", $this->crossTableObj->getCrossTableSummary());
			$this->xt->assignbyref("total_summary", $this->crossTableObj->getTotalSummary());
			$this->xt->assign("cross_totals", $showSummary);
			
			if( $this->pdfJsonMode() && count( $group_headerArr["data"] ) > 1 ) 
			{
				// add fake cells for pdfMake to make 'colSpan: group_x_count' work
				$cells = array();
				for( $i = 0; $i < count( $group_headerArr["data"] ) - 1; $i++ )
				{
					$cells[] = true;
				}

				$this->xt->assign("fake_header_cells", array( "data" => $cells ) );
			}			
		}
		else if ( $this->isDetail ) 
		{
			$this->isReportEmpty = true;
			return;
		}

		$pages = array();
		$pages[0]['grid_row'] = $grid_row;
		$pages[0]['begin'] = "<div name=page class=printpage>";
		$pages[0]['end'] = "</div>";

		$this->xt->assign("pageno", 1);
		$this->xt->assign("maxpages", 1);
		$this->xt->assign("printheader", true);
		$this->xt->assign_loopsection("pages", $pages);

		if( !$this->pdfMode )
			$this->xt->assign("printbuttons", true);
	}

	/**
	 * Get data for standart report and assign with xt
	 * @param &Array _options
	 */
	public function setStandartData(&$_options)
	{
		include_once(getabspath('classes/reportlib.php'));

		if( !$_SESSION[ $this->sessionPrefix."_pagesize" ] )
			$_SESSION[ $this->sessionPrefix."_pagesize" ] = -1; // a temporary fix

		if( !$_SESSION[ $this->sessionPrefix."_pagenumber" ] )
			$_SESSION[ $this->sessionPrefix."_pagenumber" ] = 1;


		if( isset($_REQUEST["all"]) && $_REQUEST["all"] || $this->isDetail )
		{
			$this->pageData["printAll"] = true;
			$PageSize = 0;
			$pagestart = 0;
			$this->jsSettings['tableSettings'][$this->tName]['reportPrintMode'] = 1;
			$this->controlsMap["pdfSettings"]["allPagesMode"] = 1;			
		}
		else
		{
			$PageSize = $_SESSION[ $this->sessionPrefix."_pagesize" ];
			$pagestart = ($_SESSION[ $this->sessionPrefix."_pagenumber" ] - 1) * $PageSize;
		}


		$whereComponents = $this->getWhereComponents();
		$sqlArray = $this->getReportSQLData();
		

		$rb = new Report($sqlArray, $this->pSet->getOrderIndexes(), $this->connection
			, $PageSize, $this->splitByGroups, $_options, $whereComponents["searchWhere"], $whereComponents["searchHaving"], $this);

		$this->arrReport = $rb->getReport( $pagestart );
		$this->arrPages = $rb->getPages();
		$this->standardReportCommonAssign();
	}

	/**
	 * Assign the basic cross table xt variables
	 */
	protected function standardReportCommonAssign()
	{
		$this->xt->assign( "printheader", true );
		if( $this->splitAtServer )
		{
			$this->standardReportCommonAssignSplit();
			return;
		}
		
		foreach($this->arrReport['page'] as $key => $value)
		{
			$this->xt->assign($key, $value);
		}

		// for print as details
		if ( $this->isDetail && !count($this->arrReport['list']) )
		{
			$this->isReportEmpty = true;
			return;
		}


		$this->xt->assign_loopsection("grid_row", $this->arrReport['list']);

		if( $this->arrReport['global'] )
		{
			foreach($this->arrReport['global'] as $key => $value)
			{
				$this->xt->assign($key, $value);
			}
		}
		$this->xt->assign("pageno", 1);
		$this->xt->assign("maxpages", 1);
		if( !$this->pdfMode )
			$this->xt->assign("printbuttons", true);
		$this->xt->assign("global_summary", true);
		//	legacy assignment
		$this->xt->assign("pages", true);
	}
	
	/**
	 *
	 */
	protected function standardReportCommonAssignSplit()
	{
		$page = array( 'grid_row' => array("data" => array()) );
		$pageno = 1;
		
		$this->setRecordsId();
		foreach( $this->arrReport['list'] as $pagerecords)
		{
			$page['grid_row']['data'] = $pagerecords;
			$this->addPage( $page, $pageno );
			++$pageno;
			$page = array( 'grid_row' => array("data" => array()) );
		}

		if( $this->arrReport['global'] )
		{
			$lastPage = &$this->pages[ count($this->pages) - 1 ];
			foreach($this->arrReport['global'] as $key => $value)
			{
				$lastPage[$key] = $value;
			}

			$lastPage['global_summary'] = true;
		}

		$this->xt->assign("maxpages", $pageno);
		
		if ( $this->isPD() )
		{
			// update %total% value
			$total = count( $this->pages );
			foreach ( $this->pSet->printPagesLabelsData() as $itemId => $mLString )
			{			
				foreach( $this->pages as $idx => $pageBody ) 
				{
					$this->pages[$idx][ "print_pages_label".$itemId ] = str_replace( "%total%", $total, $pageBody["print_pages_label".$itemId] );
				}
			}
		}		
		
		$this->body[ "data" ] = $this->pages;
		$this->xt->assign( "page_number", true );

		$this->xt->assign( "pagecount", $pageno - 1 );
		if( !$this->pdfMode )
			$this->xt->assign("printbuttons", true);
		//	legacy assignment
		$this->xt->assign("pages", true);
	}
	
	/**
	 *
	 */
	protected function addPage(&$page, $pageno)
	{
		//	hide buttons on second and other pages. xt->assign("printbuttons", true) is required to display the container
		$page["printbuttons"] = ($pageno == 1 && !$this->pdfMode);

		if( $this->isPD() && $pageno == 1 )
		{
			$page["pdflink_block"] = $this->pSet->isPrinterPagePDF();
		}		
		
		
		if( !$this->pdfJsonMode() ) {
			if( !$this->pdfMode )
			{
				$page['begin'] = "<div class=\"rp-presplitpage rp-page\">";
			}
			else
			{
				$page['begin'] = "<div class=\"rp-page\">";
			}
			$page['end'] = "</div>";
		}
		$page["pageno"] = $pageno;
		if( is_array( $this->arrPages[$pageno - 1] ) )
		foreach($this->arrPages[$pageno - 1] as $key => $value)
		{
			$page[$key] = $value;
		}
		$page["pageno"] = $pageno;
		
		if ( $this->isPD() )
		{
			$this->xt->assign( "print_pages", true );
			foreach ( $this->pSet->printPagesLabelsData() as $itemId => $mLString )
			{			
				$label = str_replace( "%current%", $pageno, GetMLString( $mLString ) );
				$page[ "print_pages_label".$itemId ] = $label;
			}	
		}		
		
		$this->pages[] = $page;
	}

	/**
	 *
	 */
	public function prepareWordOrExcelTemplate($contents)
	{
		$pos1 = 0;
		while($pos1 !== false)
		{
			$pos1 = stripos($contents, "<link ", $pos1);
			if( $pos1 !== false )
			{
				$pos2 = strpos($contents, ">", $pos1);
				if( !$pos2 == false)
					$contents = substr($contents, 0, $pos1).substr($contents, $pos2 + 1);
			}
		}

		$contents = str_ireplace("<img src=\"/".GetRootPathForResources("images/spacer.gif")."\">", "", $contents);
		$contents = str_ireplace("<img src=\"/".GetRootPathForResources("images/spacer.gif")."\"/>", "", $contents);
		$contents = str_ireplace("<img src=\"@webRootPath/images/spacer.gif\" />", "", $contents); // .net template compatibility

		return $contents;
	}

	/**
	 *
	 */
	public function processDetailPrint()
	{
		if( $this->crossTable && !$this->checkCrossParams() )	
			$this->setDefaultParams();		
		
		// array with extra report params
		$extraParams = $this->getExtraReportParams();

		$this->setReportData( $extraParams );

		if ( $this->isReportEmpty )
			return;

		$this->showDetailPrint();
	}

	/**
	 * Display the report page
	 */
	public function showDetailPrint()
	{
		if( $this->pdfJsonMode() ) 
		{
			$this->xt->assign( "body", true );
			$this->xt->assign( "embedded_grid", true );
			
			$this->xt->load_templateJSON( $this->templatefile );
			echo  $this->xt->fetch_loadedJSON("body");
			return;
		}		
		
		$this->xt->hideAllBricksExcept( array( "grid" ) );
		$this->xt->assign( "grid_block", true );

		$this->xt->load_template( $this->templatefile );
		
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
	 *
	 */
	public function showPage()
	{
		if( $this->eventsObject->exists("BeforeShowReportPrint") )
			$this->eventsObject->BeforeShowReportPrint($this->xt, $this->templatefile, $this);

		if( $this->pdfJsonMode() )
		{
			$this->xt->assign( "standalone_page", true );
			$this->xt->displayJSON($this->templatefile);
			return;
		}

		if( $this->format == "excel" || $this->format == "word" )
		{
			$this->xt->load_template($this->templatefile);
			$contents = $this->prepareWordOrExcelTemplate($this->xt->template);
			$this->xt->template = $contents;
			$this->xt->display_loaded();
		}
		else
		{
			if( $this->format == "pdf" )
			{
				$this->hideElement("printpdf");
				$this->AddCSSFile("styles/defaultPDF.css");
				$this->assignStyleFiles();
				$this->xt->load_template($this->templatefile);
				$this->xt->display_loaded();
			}
			else
				$this->display($this->templatefile);
		}
	}

	function element2Item( $name ) {
		if( $name == "printpdf" ) {
			return array( "print_pdf" );
		}
		return parent::element2Item( $name );
	}

	function pdfJsonMode() {
		return $this->mode == PRINT_PDFJSON;
	}

	function assignTotalsDefaults() {
		$totals = array('min', 'max', 'avg', 'sum' );
		$summaries = array();
		foreach( $this->pSet->getReportGroupFieldsData() as $idx => $group ) {
			$summaries[] = "group" . $group["strGroupField"];
		}
		$summaries[]= "page";
		$summaries[]= "global";
		$fields = array();
		foreach( $this->pSet->getFieldsList() as $field ) {
			$fields[] = GoodFieldName( $field );
		}

		foreach( $fields as $f ) {
			foreach( $summaries as $s ) {
				foreach( $totals as $t ) {
					$this->xt->assign( $s . '_total' . $f . '_' . $t, "''" );
				}
			}
		}
	}
}
?>