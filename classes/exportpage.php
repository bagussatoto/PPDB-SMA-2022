<?php

class ExportPage extends RunnerPage
{
	public $exportType = "";

	public $action = "";

	public $records = "";

	protected $textFormattingType;

	public $useRawValues = false;

	public $csvDelimiter = ",";

	public $selectedFields = array();

	public $querySQL = "";

	/**
	 * @constructor
	 * @param &Array params
	 */
	function __construct( &$params )
	{
		parent::__construct( $params );

		if( $this->getLayoutVersion() === PD_BS_LAYOUT ) 
		{
			$this->headerForms = array( "top" );
			$this->footerForms = array( "footer" );
			$this->bodyForms = array( "grid" );
		} 
		else 
		{		
			$this->formBricks["header"] = "exportheader";
			$this->formBricks["footer"] = "exportbuttons";
			$this->assignFormFooterAndHeaderBricks( true );
		}
		
		if( $this->pSet->chekcExportDelimiterSelection() )
			$this->jsSettings["tableSettings"][ $this->tName ]["csvDelimiter"] = $this->pSet->getExportDelimiter();

		$this->textFormattingType = $this->pSet->getExportTxtFormattingType();

		$this->useRawValues = $this->useRawValues || $this->textFormattingType == EXPORT_RAW;

		if( $this->exportType && $this->useRawValues && $this->textFormattingType == EXPORT_FORMATTED )
			$this->useRawValues = false;

		if( !$this->selectedFields )
			$this->selectedFields = $this->pSet->getExportFields();

		if( !$this->searchClauseObj )
			$this->searchClauseObj = $this->getSearchObject();
			
		if( $this->selection )
			$this->jsSettings["tableSettings"][ $this->tName ]["selection"] = $this->getSelection();			
	}

	
	/**
	 *
	 */
	public function process()
	{
		if( $this->eventsObject->exists("BeforeProcessExport") )
			$this->eventsObject->BeforeProcessExport( $this );

		if( $this->exportType )
		{
			$this->buildSQL();
			$this->exportByType();
			exit();
			return;
		}

		$this->fillSettings();

		$this->doCommonAssignments();
		$this->addButtonHandlers();
		$this->addCommonJs();

		$this->displayExportPage();
	}

	/**
	 *
	 */
	function addCommonJs()
	{
		parent::addCommonJs();

		if( $this->pSet->checkExportFieldsSelection() && $this->isBootstrap() )
		{
			$this->AddCSSFile("include/chosen/bootstrap-chosen.css");
			$this->AddJSFile("include/chosen/chosen.jquery.js");
		}
	}

	/**
	 * Assign basic page's xt variables
	 */
	protected function doCommonAssignments()
	{
		$this->xt->assign("id", $this->id);

		if( $this->mode == EXPORT_SIMPLE )
		{
			$this->body["begin"] = GetBaseScriptsForPage( false );
			$this->body["end"] = XTempl::create_method_assignment( "assignBodyEnd", $this );
			$this->xt->assignbyref("body", $this->body);
		}
		else
			$this->xt->assign("cancel_button", true);

		$this->xt->assign("groupExcel", true);
		$this->xt->assign("exportlink_attrs", 'id="saveButton'.$this->id.'"');

		if( $this->pSet->checkExportFieldsSelection() && $this->isBootstrap() )
		{
			$this->xt->assign("choosefields", true);
			$this->xt->assign("exportFieldsCtrl", $this->getChooseFieldsCtrlMarkup() );
		}

		if( !$this->selection || !count( $this->selection ) )
		{
			$this->xt->assign("rangeheader_block", true);
			$this->xt->assign("range_block", true);
		}

		if( $this->textFormattingType == EXPORT_BOTH )
			$this->xt->assign("exportformat", true);
	}

	/**
	 * @return String
	 */
	protected function getChooseFieldsCtrlMarkup()
	{
		$options = array();
		foreach( $this->pSet->getExportFields() as $field )
		{
			$options[] = '<option value="'.runner_htmlspecialchars( $field ).'" selected="selected">'.runner_htmlspecialchars( $this->pSet->label( $field ) ).'</option>';
		}

		return '<select name="exportFields" multiple style="width: 100%;" data-placeholder="'."Silahkan pilih".'" id="exportFields'. $this->id .'">'. implode( "", $options ) .'</select>';
	}

	/**
	 *
	 */
	protected function exportByType()
	{
		//	Pagination:
		$mypage = 1;
		$nPageSize = 0;
		if( $this->records == "page" )
		{
			$mypage = (integer)@$_SESSION[ $this->tName."_pagenumber" ];
			if( !$mypage )
				$mypage = 1;
			
			$nPageSize = (integer)@$_SESSION[ $this->tName."_pagesize" ];
			if( !$nPageSize )
				$nPageSize = $this->pSet->getInitialPageSize();

			if( $nPageSize < 0 )
				$nPageSize = 0;
		}

		$listarray = null;
		if( $this->eventsObject->exists("ListQuery") )
		{
			require_once getabspath('classes/orderclause.php');
			$orderClause = OrderClause::createFromPage( $this );
			$orderFieldsData = $orderClause->getListQueryData();

			$listarray = $this->eventsObject->ListQuery( $this->searchClauseObj, $orderFieldsData["fieldsForSort"], $orderFieldsData["howToSortData"],
				$this->masterTable, $this->masterKeysReq, $this->getSelectedRecords(), $nPageSize, $mypage, $this );
		}

		if( $listarray != null )
			$rs = $listarray;
		else
		{
			$_rs = $this->connection->queryPage( $this->querySQL, $mypage, $nPageSize, $nPageSize > 0 );
			$rs = $_rs->getQueryHandle();
		}

		runner_set_page_timeout(300);

		if ( $this->pSet->getRecordsLimit()  )
			$nPageSize = $this->pSet->getRecordsLimit() - ( ($mypage-1) * $nPageSize);
		
		$this->exportTo( $this->exportType, $rs, $nPageSize );
		$this->connection->close();
	}

	/**
	 * @param String type
	 * @param Mixed rs
	 * @param Number nPageSize
	 */
	public function exportTo( $type, $rs, $nPageSize)
	{
		global $locale_info;

		if( substr(@$type, 0, 5) == "excel" )
		{
			//	remove grouping
			$locale_info["LOCALE_SGROUPING"] = "0";
			$locale_info["LOCALE_SMONGROUPING"] = "0";
			ExportToExcel($rs, $nPageSize, $this->eventsObject, $this->cipherer, $this);
			return;
		}

		if( $type == "word" )
		{
			$this->ExportToWord( $rs, $nPageSize );
			return;
		}

		if( $type == "xml" )
		{
			$this->ExportToXML( $rs, $nPageSize );
			return;
		}

		if( $type == "csv" )
		{
			$locale_info["LOCALE_SGROUPING"] = "0";
			$locale_info["LOCALE_SDECIMAL"] = ".";
			$locale_info["LOCALE_SMONGROUPING"] = "0";
			$locale_info["LOCALE_SMONDECIMALSEP"] = ".";

			$this->ExportToCSV( $rs, $nPageSize );
		}
	}

	/**
	 * @param Mixed rs
	 * @param Number nPageSize
	 */
	public function ExportToWord( $rs, $nPageSize )
	{
		global $cCharset;
		header("Content-Type: application/vnd.ms-word");
		header("Content-Disposition: attachment;Filename=".GetTableURL( $this->tName ).".doc");

		echo "<html>";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
		echo "<body>";
		echo "<table border=1>";

		$this->WriteTableData( $rs, $nPageSize );

		echo "</table>";
		echo "</body>";
		echo "</html>";
	}

	/**
	 * @param Mixed rs
	 * @param Number nPageSize
	 */
	public function ExportToXML( $rs, $nPageSize )
	{
		global $cCharset;

		header("Content-Type: text/xml");
		header("Content-Disposition: attachment;Filename=".GetTableURL( $this->tName ).".xml");

		if( $this->eventsObject->exists("ListFetchArray") )
			$row = $this->eventsObject->ListFetchArray( $rs, $this );
		else
			$row = $this->cipherer->DecryptFetchedArray( $this->connection->fetch_array( $rs ) );

		echo "<?xml version=\"1.0\" encoding=\"".$cCharset."\" standalone=\"yes\"?>\r\n";
		echo "<table>\r\n";

		$i = 0;
		$this->viewControls->setForExportVar("xml");
		while( (!$nPageSize || $i < $nPageSize) && $row )
		{
			$values = array();
			foreach( $this->selectedFields as $field )
			{
				$fType = $this->pSet->getFieldType( $field );
				if( IsBinaryType( $fType ) )
					$values[ $field ] = "Data Biner Panjang - tidak dapat ditampilkan";
				else
					$values[ $field ] = $this->getFormattedFieldValue( $field, $row );
			}

			$eventRes = true;
			if( $this->eventsObject->exists('BeforeOut') )
				$eventRes = $this->eventsObject->BeforeOut( $row, $values, $this );

			if( $eventRes )
			{
				$i++;
				echo "<row>\r\n";
				foreach( $values as $fName => $val )
				{
					$field = runner_htmlspecialchars( XMLNameEncode( $fName ) );

					echo "<".$field.">";
					echo xmlencode( $values[ $fName ] );
					echo "</".$field.">\r\n";
				}

				echo "</row>\r\n";
			}

			if( $this->eventsObject->exists("ListFetchArray") )
				$row = $this->eventsObject->ListFetchArray( $rs, $this );
			else
				$row = $this->cipherer->DecryptFetchedArray( $this->connection->fetch_array( $rs ) );
		}

		echo "</table>\r\n";
	}

	/**
	 * @param Mixed rs
	 * @param Number nPageSize
	 */
	public function ExportToCSV( $rs, $nPageSize )
	{
		if( $this->pSet->chekcExportDelimiterSelection() && strlen( $this->csvDelimiter ) )
			$delimiter = $this->csvDelimiter;
		else
			$delimiter = $this->pSet->getExportDelimiter();

		header("Content-Type: application/csv");
		header("Content-Disposition: attachment;Filename=".GetTableURL($this->tName).".csv");
		printBOM();

		if( $this->eventsObject->exists("ListFetchArray") )
			$row = $this->eventsObject->ListFetchArray( $rs, $this );
		else
			$row = $this->cipherer->DecryptFetchedArray( $this->connection->fetch_array( $rs ) );

		// write header
		$headerParts = array();
		foreach( $this->selectedFields as $field )
		{
			$headerParts[] = '"'.str_replace( '"', '""', $field ).'"';
		}
		echo implode( $delimiter, $headerParts );
		echo "\r\n";

		$this->viewControls->setForExportVar( "csv" ); //?

		// write data rows
		$iNumberOfRows = 0;
		while( (!$nPageSize || $iNumberOfRows < $nPageSize) && $row )
		{
			$values = array();
			foreach( $this->selectedFields as $field )
			{
				$fType = $this->pSet->getFieldType( $field );
				if( IsBinaryType( $fType ) )
					$values[ $field ] = "Data Biner Panjang - tidak dapat ditampilkan";
				else
					$values[ $field ] = $this->getFormattedFieldValue( $field, $row );
			}

			$eventRes = true;
			if( $this->eventsObject->exists('BeforeOut') )
				$eventRes = $this->eventsObject->BeforeOut( $row, $values, $this );

			if( $eventRes )
			{
				$dataRowParts = array();
				foreach( $this->selectedFields as $field )
				{
					$dataRowParts[] = '"'.str_replace( '"', '""', $values[ $field ] ).'"';
				}
				echo implode( $delimiter, $dataRowParts );
			}

			$iNumberOfRows++;
			if( $this->eventsObject->exists("ListFetchArray") )
				$row = $this->eventsObject->ListFetchArray( $rs, $this );
			else
				$row = $this->cipherer->DecryptFetchedArray( $this->connection->fetch_array( $rs ) );

			if( ( !$nPageSize || $iNumberOfRows < $nPageSize) && $row && $eventRes )
				echo "\r\n";
		}
	}

	/**
	 * @param String fName
	 * @param Array row
	 */
	public function getFormattedFieldValue( $fName, $row )
	{
		if( $this->useRawValues )
			return $row[ $fName ];

		return $this->getExportValue( $fName, $row );
	}

	/**
	 * @param Mixed rs
	 * @param Number nPageSize
	 */
	protected function WriteTableData( $rs, $nPageSize )
	{
		$totalFieldsData = $this->pSet->getTotalsFields();

		if( $this->eventsObject->exists("ListFetchArray") )
			$row = $this->eventsObject->ListFetchArray( $rs, $this );
		else
			$row = $this->cipherer->DecryptFetchedArray( $this->connection->fetch_array( $rs ) );

		// write header
		echo "<tr>";
		if( $this->exportType == "excel" )
		{
			foreach( $this->selectedFields as $field )
			{
				echo '<td style="width: 100" x:str>'.PrepareForExcel( $this->pSet->label( $field ) ).'</td>';
			}
		}
		else
		{
			foreach( $this->selectedFields as $field )
			{
				echo "<td>".$this->pSet->label( $field )."</td>";
			}
		}
		echo "</tr>";

		$totals = array();
		$totalsFields = array();
		foreach( $totalFieldsData as $data )
		{
			if( !in_array( $data["fName"], $this->selectedFields ) )
				continue;

			$totals[ $data["fName"] ] = array("value" => 0, "numRows" => 0);
			$totalsFields[] = array('fName' => $data["fName"], 'totalsType' => $data["totalsType"], 'viewFormat' => $this->pSet->getViewFormat( $data["fName"] ));
		}

		// write data rows
		$iNumberOfRows = 0;
		$this->viewControls->setForExportVar( "export" );
		while( (!$nPageSize || $iNumberOfRows < $nPageSize) && $row )
		{
			countTotals( $totals, $totalsFields, $row );

			$values = array();

			foreach( $this->selectedFields as $field )
			{
				$fType = $this->pSet->getFieldType( $field );
				if( IsBinaryType( $fType ) )
					$values[ $field ] = "Data Biner Panjang - tidak dapat ditampilkan";
				else
					$values[ $field ] = $this->getFormattedFieldValue( $field, $row );
			}

			$eventRes = true;
			if( $this->eventsObject->exists('BeforeOut') )
			{
				$eventRes = $this->eventsObject->BeforeOut( $row, $values, $this );
			}

			if( $eventRes )
			{
				$iNumberOfRows++;
				echo "<tr>";

				foreach( $this->selectedFields as $field )
				{
					$fType = $this->pSet->getFieldType( $field );
					if( IsCharType( $fType ) )
					{
						if( $this->exportType == "excel" )
							echo '<td x:str>';
						else
							echo '<td>';
					}
					else
						echo '<td>';

					$editFormat = $this->pSet->getEditFormat( $field );
					if( $editFormat == EDIT_FORMAT_LOOKUP_WIZARD )
					{
						if( $this->pSet->NeedEncode($field) )
						{
							if( $this->exportType == "excel" )
								echo PrepareForExcel( $values[ $field ] );
							else
								echo $values[ $field ];
						}
						else
							echo $values[ $field ];
					}
					elseif( IsBinaryType( $fType ) )
					{
						echo $values[ $field ];
					}
					else
					{
						if( $editFormat == FORMAT_CUSTOM || $this->pSet->isUseRTE( $field ) )
							echo $values[ $field ];
						elseif( NeedQuotes( $field ) )
						{
							if( $this->exportType == "excel")
								echo PrepareForExcel( $values[ $field ] );
							else
								echo $values[ $field ];
						}
						else
							echo $values[ $field ];
					}

					echo '</td>';
				}

				echo "</tr>";
			}

			if( $this->eventsObject->exists("ListFetchArray") )
				$row = $this->eventsObject->ListFetchArray( $rs, $this );
			else
				$row = $this->cipherer->DecryptFetchedArray( $this->connection->fetch_array( $rs ) );
		}

		if( count( $totalFieldsData ) )
		{
			echo "<tr>";
			foreach( $totalFieldsData as $data )
			{
				if( !in_array( $data["fName"], $this->selectedFields ) )
					continue;

				echo "<td>";
				if( strlen( $data["totalsType"] ) )
				{
					if( $data["totalsType"] == "COUNT" )
						echo "Hitung".": ";
					elseif( $data["totalsType"] == "TOTAL" )
						echo "Total".": ";
					elseif( $data["totalsType"] == "AVERAGE" )
						echo "Rata-rata".": ";

					echo runner_htmlspecialchars( GetTotals($data["fName"],
						$totals[ $data["fName"] ]["value"],
						$data["totalsType"],
						$totals[ $data["fName"] ]["numRows"],
						$this->pSet->getViewFormat( $data["fName"] ),
						PAGE_EXPORT,
						$this->pSet, $this->useRawValues,
						$this ) );
				}

				echo "</td>";
			}

			echo "</tr>";
		}
	}

	/**
	 * @deprecated
	 * @param Mixed rs
	 * @param Number nPageSize
	 */
	public function ExportToExcel_old($rs, $nPageSize)
	{
		global $cCharset;
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment;Filename=".GetTableURL( $this->tName ).".xls");

		echo "<html>";
		echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">";

		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$cCharset."\">";
		echo "<body>";
		echo "<table border=1>";

		$this->WriteTableData( $rs, $nPageSize );

		echo "</table>";
		echo "</body>";
		echo "</html>";
	}

	/**
	 *
	 */
	protected function displayExportPage()
	{
		$templatefile = $this->templatefile;

		if( $this->eventsObject->exists("BeforeShowExport") )
			$this->eventsObject->BeforeShowExport( $this->xt, $templatefile, $this );

		if( $this->mode == EXPORT_POPUP )
		{
			$this->xt->assign("footer", false);
			$this->xt->assign("header", false);
			$this->xt->assign("body", $this->body);

			$this->displayAJAX( $templatefile, $this->id + 1 );
			exit();
		}

		$this->display( $templatefile );
	}

	/**
	 * @return Number
	 */
	public static function readModeFromRequest()
	{
		if( postvalue("onFly") )
			return EXPORT_POPUP;

		return EXPORT_SIMPLE;
	}

	/**
	 * @return Array
	 */
	protected function getSubsetSQLComponents()
	{
		$sql = array();

		$selectedRecords = $this->getSelectedRecords();
		if( $selectedRecords !== null )
		{
			//	export selected mode. Export only selected records, ignore search & filter
			$sql["sqlParts"] = $this->gQuery->getSqlComponents();
			
			$selectedWhereParts = array();
			foreach( $selectedRecords as $keys )
				$selectedWhereParts[] = KeyWhere( $keys, $this->tName );

			$sql["mandatoryWhere"][] = implode(" or ", $selectedWhereParts );
			if( 0 == count( $selectedRecords ) )
				$sql["mandatoryWhere"][] = "1=0";
		}
		else
		{
			//	add search, filter and master clauses
			$sql = parent::getSubsetSQLComponents();
		}
		
		if( $this->connection->dbType == nDATABASE_DB2 ) 
			$sql["sqlParts"]["head"] .= ", ROW_NUMBER() over () as DB2_ROW_NUMBER ";
	
		//	security
		$sql["mandatoryWhere"][] = $this->SecuritySQL("Export", $this->tName);

		return $sql;
	}

	/**
	 * Builds SQL query for retrieving data from DB
	 * @return String
	 */
	protected function buildSQL()
	{
		$sql = $this->getSubsetSQLComponents();
		$orderClause = $this->getOrderByClause();

		//	build SQL
		$strSQL = SQLQuery::buildSQL( $sql["sqlParts"], $sql["mandatoryWhere"], $sql["mandatoryHaving"], $sql["optionalWhere"], $sql["optionalHaving"] );
		$strSQL.= $orderClause;

		//	do Before SQL Query event
		$strSQLbak = $strSQL;
		$whereModifiedInEvent = false;
		$orderbyModifiedInEvent = false;

		if( $this->eventsObject->exists("BeforeQueryExport") )
		{
			$tstrWhereClause = SQLQuery::combineCases( array(
					SQLQuery::combineCases( $sql["mandatoryWhere"], "and" ),
					SQLQuery::combineCases( $sql["optionalWhere"], "or" )
				), "and" );

			$strWhereBak = $tstrWhereClause;
			$tstrOrderBy = $orderClause;

			$this->eventsObject->BeforeQueryExport($strSQL, $tstrWhereClause, $tstrOrderBy, $this);

			$whereModifiedInEvent = $tstrWhereClause != $strWhereBak;
			$orderbyModifiedInEvent = $tstrOrderBy != $orderClause;
			$orderClause = $tstrOrderBy;


			//	Rebuild SQL if needed
			if( $whereModifiedInEvent )
			{
				$strSQL = SQLQuery::buildSQL( $sql["sqlParts"], array( $tstrWhereClause ), $sql["mandatoryHaving"] );
				$strSQL .= $orderClause;
			}
			else if( $orderbyModifiedInEvent )
			{
				$strSQL = SQLQuery::buildSQL( $sql["sqlParts"], $sql["mandatoryWhere"], $sql["mandatoryHaving"], $sql["optionalWhere"], $sql["optionalHaving"] );
				$strSQL.= $orderClause;
			}
		}

		LogInfo($strSQL);
		$this->querySQL = $strSQL;
 	}
}