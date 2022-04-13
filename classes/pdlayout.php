<?php
class PDLayout
{
	var $bootstrapTheme = "";
	var $customCssPageName = "";
	var $page;
	var $table;
	var $version = 4;
	var $bootstrapSize;
	var $name="";
	var $style="";
	/**
	 * True when 'this page has custom settings' is checked off
	 */
	var $customSettings = false;

	function __construct( $table, $page, $theme, $size = "normal", $customSettings = false )
	{
		$this->page = $page;
		$this->table = $table;
		$this->bootstrapTheme = $theme;
		$this->bootstrapSize = $size;
		$this->customSettings = $customSettings;
	}

	
	/**
	 *  Returns list of CSS files required for displaying the layout
	 */
	public function getCSSFiles($rtl = false, $mobile = false, $pdf = false)
	{
		$files = array();
		$suffix = "";
		if( $rtl )
			$suffix = "RTL";
			
			$files[] = "styles/bootstrap/".$this->bootstrapTheme."/".$this->bootstrapSize."/style".$suffix.".css";

//		$files[] = "styles/bs".$suffix.".css";

		$files[] = "styles/font-awesome/css/font-awesome.min.css";

		if( !$this->customSettings ) {
			if( file_exists( getabspath( "styles/custom/custom.css" ) ) )
				$files[] = "styles/custom/custom".$suffix.".css";
		}

		$files[] = "styles/pages/".$this->table."_".$this->page["id"].$suffix.".css";


		return $files;
	}

	
	/**
     *	Hide items and grid cells that should be hidden 
 	 *	@param XTempl @xt	
	 *	@param array $itemsToHide
	 *	@param ProjectSettings ps
	 *	@return {object} copy of cell map with hidden rows and cols removed
	 */
	public function & prepareGrid( $xt, $itemsToHide, $recordItemsToHide, &$cellMap, $location, $pageObject )
	{
		$checkRecordItems = !!count( $recordItemsToHide );
		$recordsToCheck = array();
		//	mark cells that can be removed or hidden
		foreach( $cellMap->cells as $cell => $dummy ) {
			$cMapRef = &$cellMap->cells[$cell];
			if( $cMapRef["fixedAtClient"] )
				continue;
			$empty = false;
			if( !$cMapRef["fixedAtServer"] ) {
				$empty = true;
				foreach( $cMapRef["tags"] as $i => $item ) {
					if( $xt->getVar( $item ) )
					{
						$empty = false;
						break;
					}
				}
			} 
			$hidden = true;
			$visibleItems = array();
			foreach( $cMapRef["items"] as $i => $item ) {
				if( !$itemsToHide[ $item ] )
				{
					$hidden = false;
					if( $checkRecordItems ) {
						$visibleItems[ $item ] = true;
					}
					else
						break;
				}
			}
			if( $checkRecordItems && !$hidden && !$empty && $visibleItems ) {
				$cMapRef["hiddenRecords"] = $this->findHiddenRecords( $visibleItems, $recordItemsToHide );
				$recordsToCheck = addToAssocArray($recordsToCheck, $cMapRef["hiddenRecords"] );
			}
			if( $empty )
				$cMapRef["removable"] = true;
			if( $hidden )
				$cMapRef["hidable"] = true;
		}

		// mark rows and cols that can be removed
		$removedColsRows = $cellMap->removeRowsColumns("removable");

		// mark rows and cols that can be hidden
		$hidingMap = $cellMap->makeClone();
		$hiddenColsRows =  $hidingMap->removeRowsColumns("hidable" );

		
		
		//	PDF JSON needs this
		$visibleWidth = $cellMap->width - count($hiddenColsRows["cols"]);
		$xt->assign( "formwidth_" . $location, $visibleWidth );

		// do actual removal and hiding

		//	hide rows first
		if( !$pageObject->pdfJsonMode() ) {
			foreach( $removedColsRows["rows"] as $row ) {
				$xt->assign( "row_" . $location . "_" . $row, 'data-hidden' );
			}
			foreach( $hiddenColsRows["rows"] as $row ) {
				$xt->assign( "row_" . $location . "_" . $row, 'data-hidden' );
			}
		} else {
			
			for( $row = 0; $row < $cellMap->height; ++$row ) {
				$xt->assign( "row_" . $location . "_" . $row, true );
			}
			foreach( $removedColsRows["rows"] as $row ) {
				$xt->assign( "row_" . $location . "_" . $row, false );
			}
			foreach( $hiddenColsRows["rows"] as $row ) {
				$xt->assign( "row_" . $location . "_" . $row, false );
			}
		// columns
			for( $col = 0; $col < $cellMap->width; ++$col ) {
				$xt->assign( "col_" . $location . "_" . $col, true );
			}
			foreach( $removedColsRows["cols"] as $col ) {
				$xt->assign( "col_" . $location . "_" . $col, false );
			}
			foreach( $hiddenColsRows["cols"] as $col ) {
				$xt->assign( "col_" . $location . "_" . $col, false );
			}


		}

		foreach( $cellMap->cells as $cell => $cMap ) {
			
			if( 0 == count( $cMap["rows"] ) ||  0 == count( $cMap["cols"] )) {
				//	don't display cell
				continue;
			}
		
			//	display cell
			$xt->assign( "cellblock_" . $location . "_" . $cell, true );
			
			//	add cell attributes
			$dummyData = null;
			$this->assignCellAttrs( $hidingMap, $cell, $location, $pageObject, $xt, $dummyData );
			
		}

		if( $checkRecordItems ) {
			foreach( $recordsToCheck as $recId => $dummy ) {
				$recordHidingMap = $hidingMap->makeClone();
				$recordHidingMap->setRecordId( $recId );
				$hiddenRecordRows =  $recordHidingMap->removeRowsColumns("hidable", true );
				$recordData =& $pageObject->findRecordAssigns( $recId );

				// hide whole rows
				if( !$pageObject->pdfJsonMode() ) {
					foreach( $hiddenRecordRows["rows"] as $row ) {
						$recordData[ "row_" . $location . "_" . $row] = 'data-hidden';
					}
				} else {
					foreach( $hiddenRecordRows["rows"] as $row ) {
						$recordData[ "row_" . $location . "_" . $row ] = false;
					}
				}

				foreach( $cellMap->cells as $cell => $cMap ) {
			
					if( 0 == count( $cMap["rows"] ) ||  0 == count( $cMap["cols"] )) {
						//	don't display cell
						continue;
					}
					//	add cell attributes
					$this->assignCellAttrs( $recordHidingMap, $cell, $location, $pageObject, $xt, $recordData, true );
				}
						

			}
		}

		return $hidingMap;
		
	}
	
	function assignCellAttrs( &$cellMap, $cell, $location, $pageObject, $xt, &$recordData, $forceCellSpans = false ) {
		$cellAttrs = array();
		$hCell =& $cellMap->cells[$cell];

		if( !$pageObject->pdfJsonMode() ) {
			if( 0 == count( $hCell["rows"] ) ||  0 == count( $hCell["cols"] )) {
				//	display cell hidden
				$cellAttrs[] = 'data-hidden';
			}
			
			if( $forceCellSpans || count( $hCell["cols"] ) > 1 ) {
				$cellAttrs[] = 'colspan="' . count( $hCell["cols"] ) . '"';
			}
			if( $forceCellSpans || count( $hCell["rows"] ) > 1 ) {
				$cellAttrs[] = 'rowspan="' . count( $hCell["rows"] ) . '"';
			}
			//	specify which cols and rows are visible

			if( count( $cellAttrs ) ) {
				$this->assignPageVar( $recordData, $xt, "cell_".$location."_" . $cell, implode( " ", $cellAttrs ) );
			}
		} else {
			if( 0 == count( $hCell["rows"] ) ||  0 == count( $hCell["cols"] )) {
				$this->assignPageVar( $recordData, $xt, "cellblock_" . $location . "_" . $cell, false );
			}
			
			if( $forceCellSpans || count( $hCell["cols"] ) > 1 ) {
				$this->assignPageVar( $recordData, $xt, "colspan_" . $location . "_" . $cell, count( $hCell["cols"] ) );
			}
			if( $forceCellSpans || count( $hCell["rows"] ) > 1 ) {
				$this->assignPageVar( $recordData, $xt, "rowspan_" . $location . "_" . $cell, count( $hCell["rows"] ) );
			}
		}
	}

	function assignPageVar( &$recordData, $xt, $name, $value ) {
		if( $recordData === null ) {
			$xt->assign( $name, $value );
		} else {
			$recordData[ $name ] = $value;
		}
	}
	/**
 	 *	@param array $allItems associative array of items 
	 *	@param array $hiddenItems - associative array of items hidden in specific rows
	 *				 $hiddenItems[<itemid>] = array( <rowid>,<rowid>,<rowid> )
	 *	@return array Array of rowids where all items are hidden
	 */
	public function findHiddenRecords( $allItems, $hiddenItems) 
	{
		$result = null;
		foreach( $allItems as $item => $dummy ) {
			if( !$hiddenItems[ $item ] ) {
				return array();
			}
			if($result === null ) {
				$result = $hiddenItems[ $item ];
			} else {
				$result = array_intersect( $result, $hiddenItems[ $item ] );
			}
			if( count($result) == 0 )
				break;
		}
		return $result;
	}

	public function visibleOnMedia( $media, $visibilty ) {
		if( $media == 0 ) {
			return $visibilty == 0 
				|| $visibilty == 3
				|| $visibilty == 4
				|| $visibilty == 5;
		
		} else if( $media == 1 ) {
			return $visibilty == 0 
				|| $visibilty == 2
				|| $visibilty == 4;
		}
}

	/**
	 * @param {array} $itemToHide - array of items to be hidden. Pairs of [itemId] => true
	 */
	public function prepareForms( $xt, $itemsToHide, $recordItemsToHide, $pageObject ) {
		
		/* desktop=0, mobile=1 */

		if( $pageObject ) {
			$ps = $pageObject->pSet;
		} else {
			$ps = new ProjectSettings( GetTableByShort($this->table), $this->page["type"], $this->page["id"] );
		}

		$helper =& $ps->helperFormItems();

		//	make array of items hidden by application or media type
		$invisibleItems = $itemsToHide;
		$mediaType = $pageObject->pdfJsonMode() ? MEDIA_DESKTOP : getMediaType();
		foreach( $helper["itemVisiblity"] as $itemId => $visibility ) {
			if( !$this->visibleOnMedia( $mediaType, $visibility )) {
				$invisibleItems[ $itemId ] = true;
			}
		}

		$visibleCellsMap = array();
		$cellMaps =& $ps->helperCellMaps();
		foreach( array_keys($cellMaps) as $loc ) {
			$formRecordItemsToHide = array();
			foreach( $recordItemsToHide as $item => $itemRecords ) {
				if($helper["itemForms"][$item] == $loc )
					$formRecordItemsToHide[$item] = array_keys( $itemRecords );
			}
			$hMap =& $this->prepareGrid($xt, $invisibleItems, $formRecordItemsToHide, new CellMapPD( $cellMaps[ $loc ] ), $loc, $pageObject );
			if( $pageObject ) {
				$visibleCellsMap[ $loc ] = & $this->prepareClientCellMap( $cellMaps[ $loc ], $hMap );
			}
		}
		if( $pageObject ) {
			$pageObject->setPageData("cellMaps", $visibleCellsMap );
		}

		//	hide items 
		if( !$pageObject->pdfJsonMode() ) {
			foreach( array_keys($invisibleItems) as $item ) {
				if( $itemsToHide[ $item ] )
					$xt->assign( "item_" . $item, 'data-hidden' );
				else 
					$xt->assign( "item_" . $item, 'data-media-hidden' );
			}
		} else {
			foreach( array_keys($invisibleItems) as $item ) {
				$xt->assign( "item_hide_" . $item, '1' );
			}
		}

		//	hide items in records
		if( $pageObject ) {
			foreach( $recordItemsToHide as $item => $itemRecords ) {
				foreach( array_keys( $itemRecords ) as $recordId ) {
					$pageObject->hideRecordItem( $item, $recordId );
				}
			}
		}

		$xt->assign("firstAboveGridCell", true); 

		//	hide other cells & forms
		
		$formTags =& $helper["formXtTags"];
		foreach( array_keys($formTags) as $loc ) {
			$present = false;
			foreach( $formTags[$loc] as $tag ) {
				if( $xt->getvar( $tag ) ) {
					$present = true;
					break;
				}
			}
			if( !$present ) {
				// hide the whole form
				$xt->assign(  $loc."_block", false );
			}
	}



		$formItems =& $helper["formItems"];
		foreach( array_keys($formItems) as $loc ) {
			$present = false;
			foreach( $formItems[$loc] as $item ) {
				if( !isset( $invisibleItems[$item] ) ) {
					$present = true;
					break;
				}
			}
			if( !$present ) {
				// hide the whole form
				$xt->assign( "form_" . $loc, 'data-hidden' );
			}
		}
	}
	protected function & prepareClientCellMap( &$allCells, &$visibleCells ) {
		foreach( array_keys( $visibleCells->cells ) as $cellId )  {
			$allCells["cells"][$cellId]["visibleCols"] = $visibleCells->cells[$cellId]["cols"];
		}
		return $allCells;
	}

}

class CellMapPD {
	public $cells;
	public $height;
	public $width;
	
	function __construct( &$map ) {
		$this->cells = &$map["cells"];
		$this->height = $map["height"];
		$this->width = $map["width"];
	}
	
	function makeClone() {
		$newMap = array(
			"cells" => cloneArray( $this->cells ),
			"height" => $this->height,
			"width" => $this->width,
		);
		return new CellMapPD( $newMap );
	}

	/**
	 * Update "hidable" flag with values from "hiddenRecords"
	 */
	function setRecordId( $recId ) {
		foreach( $this->cells as $cell => $dummy ) {
			$cMapRef = &$this->cells[$cell];
			if( !$cMapRef["hiddenRecords"] || $cMapRef["hidable"] ) {
				continue;
			}
			foreach( $cMapRef["hiddenRecords"] as $rec ) {
				if( $rec === $recId ) {
					$cMapRef["hidable"] = true;
					break;
				}
			}
		}
	}
	
	public function getColumnCells( $col ) {
		$ret = array();
		foreach( $this->cells as $cell => $cMap ) {
			if( array_search( $col, $cMap["cols"] ) !== FALSE )
				$ret[] = $cell;
		}
		return $ret;
	}
	
	public function getRowCells( $row ) {
		$ret = array();
		foreach( $this->cells as $cell => $cMap ) {
			if( array_search( $row, $cMap["rows"] ) !== FALSE )
				$ret[] = $cell;
		}
		return $ret;
	}
	
	/**
	 * Returns array of row indices that can be removed
	 */
	public function removeRowsColumns( $cellRemoveFlag, $rowsOnly = false ) {
		
		$ret = array( "cols" => array(), "rows" => array() );
		//	remove unnecessary columns
		if( !$rowsOnly ) {
			for( $col = 0; $col < $this->width; ++$col ) {
				$colCells = $this->getColumnCells( $col );
				$canDeleteCol = true;
				foreach( $colCells as $cell ) {
					if( !$this->cells[$cell][ $cellRemoveFlag ] && count($this->cells[$cell]["cols"]) == 1  ) {
						$canDeleteCol = false;
						break;
					}
				}
				if( !$canDeleteCol )
					continue;
				for( $i = 0; $i < count($colCells); ++$i ) {
					$cell = $colCells[$i];
					$colIdx = array_search( $col, $this->cells[$cell]["cols"] );
									array_splice( $this->cells[$cell]["cols"], $colIdx, 1 );
				}
				$ret["cols"][] = $col;
			}
		}

		//	remove unnecessary rows
		for( $row = $this->height - 1; $row >= 0; --$row ) {
			$rowCells = $this->getRowCells( $row );
			$canDeleteRow = true;
			foreach( $rowCells as $cell ) {
				if( !$this->cells[$cell][ $cellRemoveFlag ] && 
					( count( $this->cells[$cell]["rows"]) == 1  
						/* can't delete the first row of a rowspanned cell */ || $this->cells[$cell]["rows"][0] === $row ) ) {
					$canDeleteRow = false;
					break;
				}
			}
			if( !$canDeleteRow )
				continue;
			for( $i = 0; $i < count($rowCells); ++$i ) {
				$cell = $rowCells[$i];
				$rowIdx = array_search( $row, $this->cells[$cell]["rows"] );

								array_splice( $this->cells[$cell]["rows"], $rowIdx, 1 );
			}
			$ret["rows"][] = $row;
		}
		return $ret;
	}

}
?>