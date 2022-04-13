<?php
require_once(getabspath("plugins/PHPExcel/IOFactory.php"));
require_once getabspath("include/export_functions_excel.php");

function ExportToExcel($rs, $nPageSize, $eventObj, $cipherer, $pageObj)
{
	if( $eventObj->exists("ListFetchArray") )
		$row = $eventObj->ListFetchArray( $rs, $pageObj );
	else
		$row = $cipherer->DecryptFetchedArray( $pageObj->connection->fetch_array( $rs ) );
	
	$totals = array();
	$arrLabel = array();
	$arrTotal = array();
	$arrFields = array();
	$arrColumnWidth = array();
	$arrTotalMessage = array();
	
	foreach( $pageObj->selectedFields as $field )
	{
		if( $pageObj->pSet->appearOnExportPage( $field ) )
			$arrFields[] = $field;
	}
	
	$arrTmpTotal = $pageObj->pSet->getTotalsFields();
	$pageObj->viewControls->setForExportVar( "excel" );
	foreach( $arrFields as $field )
	{
		$arrLabel[ $field ] = GetFieldLabel( GoodFieldName( $pageObj->tName ), GoodFieldName( $field ) ); 
		$arrColumnWidth[ $field ] = 10;
		$totals[ $field ] = array("value" => 0, "numRows" => 0);
		
		foreach( $arrTmpTotal as $tvalue )
		{
			if( $tvalue["fName"] == $field ) 
				$totalsFields[] = array( 'fName' => $field, 'totalsType' => $tvalue["totalsType"], 'viewFormat' => $pageObj->pSet->getViewFormat( $field ) );
		}
	}
	
// write data rows
	$iNumberOfRows = 0;
	
	$objPHPExcel = ExportExcelInit( $arrLabel, $arrColumnWidth );
	
	while( (!$nPageSize || $iNumberOfRows < $nPageSize) && $row )
	{
		countTotals($totals, $totalsFields, $row);
		
		$values = array();	
		foreach( $arrFields as $field )
		{
			if( IsBinaryType( $pageObj->pSet->getFieldType( $field ) ) )
				$values[ $field ] = $row[ $field ];
			else
				$values[ $field ] = $pageObj->getFormattedFieldValue( $field, $row );
		}
		
		$eventRes = true;
		if( $eventObj->exists('BeforeOut') )
			$eventRes = $eventObj->BeforeOut( $row, $values, $pageObj );
		
		if( $eventRes )
		{
			$arrData = array();
			$arrDataType = array();
			
			$iNumberOfRows++;
			$i = 0;
			foreach( $arrFields as $field )
			{
				$vFormat = $pageObj->pSet->getViewFormat( $field );
				
				if( IsBinaryType( $pageObj->pSet->getFieldType( $field ) ) )
					$arrDataType[ $field ] = "binary";
				elseif( $vFormat == FORMAT_DATE_SHORT || $vFormat == FORMAT_DATE_LONG || $vFormat == FORMAT_DATE_TIME )
					$arrDataType[ $field ] = "date";
				elseif( $vFormat == FORMAT_FILE_IMAGE )
					$arrDataType[ $field ] = "file";
				else
					$arrDataType[ $field ] = "";
					
				$arrData[ $field ] = $values[ $field ];
			}
			
			ExportExcelRecord( $arrData, $arrDataType, $iNumberOfRows, $objPHPExcel, $pageObj );
		}
		
		if( $eventObj->exists("ListFetchArray") )
			$row = $eventObj->ListFetchArray( $rs, $pageObj );
		else
			$row = $cipherer->DecryptFetchedArray( $pageObj->connection->fetch_array( $rs ) );
	}
	
	if( count( $arrTmpTotal ) )
	{
		foreach( $arrFields as $fName )
		{
			$value = array();
			foreach( $arrTmpTotal as $tvalue )
			{
				if( $tvalue["fName"] == $fName )
					$value = $tvalue;
			}
			
			$total = "";
			$totalMess = "";
			if( $value["totalsType"] )
			{
				if( $value["totalsType"] == "COUNT" )
					$totalMess = "Count".": ";
				elseif( $value["totalsType"] == "TOTAL" )
					$totalMess = "Total".": ";
				elseif( $value["totalsType"] == "AVERAGE" )
					$totalMess = "Average".": ";
					
				$total = GetTotals( $fName, $totals[ $fName ]["value"], $value["totalsType"], $totals[ $fName ]["numRows"], 
					$value["viewFormat"], "export", $pageObj->pSet, $pageObj->useRawValues, $pageObj );
			}
			
			$arrTotal[ $fName ] = $total;
			$arrTotalMessage[ $fName ] = $totalMess;
		}
	}

	ExportExcelTotals( $arrTotal, $arrTotalMessage, ++$iNumberOfRows, $objPHPExcel );
	ExportExcelSave( GoodFieldName( $pageObj->tName ).".xlsx", "Excel2007", $objPHPExcel );
}
?>