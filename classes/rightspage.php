<?php
/**
 * Class for display admin_rights_list
 */
class RightsPage extends ListPage
{
	/**
	 * Array of non admin tables
	 *
	 * @var array
	 */
	var $tables = array();
	
	/**
	 * Array of all pages
	 *
	 * @var array
	 */
	var $pages = array();

	/**
	 * Array of non all possible permission masks for tables
	 * { "<table>": "<mask>" }
	 *
	 * @var array
	 */
	var $pageMasks = array();
	/**
	 * Array of non admin tables rights
	 *
	 * @var array
	 */
	var $rights = array();
	/**
	 * Array of page-level restrictions
	 *
	 * @var array
	 */
	var $pageRestrictions = array();
	/**
	 * Array with groups data from DB
	 *
	 * @var array
	 */
	var $groups = array();
	/**
	 * Array of smarty groups
	 *
	 * @var array
	 */
	var $smartyGroups = array();
	/**
	 * Array with checkboxes prefixes and access masks
	 * @var array
	 */
	var $cbxNames;

	var $permissionNames = array();

	var $sortedTables;
	var $menuOrderedTables;
	var $alphaOrderedTables;

	/**
	 * Contructor
	 *
	 * @param array $params
	 * @return RightsPage
	 */
	function __construct(&$params)
	{
		// copy properties to object
		RunnerPage::__construct($params);

		$this->permissionNames["A"] = true;
		$this->permissionNames["D"] = true;
		$this->permissionNames["E"] = true;
		$this->permissionNames["S"] = true;
		$this->permissionNames["P"] = true;
		$this->permissionNames["I"] = true;
		$this->permissionNames["M"] = true;

		$this->cbxNames = array(
			'add' => array('mask' => 'A', 'rightName' => 'add'),
			'edt' => array('mask' => 'E', 'rightName' => 'edit'),
			'del' => array('mask' => 'D', 'rightName' => 'delete'),
		 	'lst' => array('mask' => 'S', 'rightName' => 'list'),
			'exp' => array('mask' => 'P', 'rightName' => 'export'),
			'imp' => array('mask' => 'I', 'rightName' => 'import'),
			'adm' => array('mask' => 'M')
		);

		// Set language params, if have more than one language
		
		$this->initLogin();
		
		$this->setLangParams();

		$this->sortTables();

		$this->fillGroupsArr();

		$this->fillPagesArr();
	}

	function fillPagesArr() {
		$pages = allTablePages();
		foreach( $pages as $table => $_tablePages ) {
			$this->pages[ $table ] = array();
			foreach( $pages[ $table ] as $pageType => $pageIds ) {
				foreach( $pageIds as $p ) {
					$this->pages[ $table ][$p] = Security::pageType2permission( $pageType );
				}
			}
		}
	}

	/**
	 *	select groups list
	 */
	function fillGroupsArr()
	{
		global $cman;
		$grConnection = $cman->getForUserGroups();

		$this->groups[-1] = "<"."Admin".">";
		$this->groups[-2] = "<"."Default".">";
		$this->groups[-3] = "<"."Guest".">";

		$sql = "select ". 
			$grConnection->addFieldWrappers( "GroupID" ) .", ". 
			$grConnection->addFieldWrappers( "Label" ) 
			." from ". 
			$grConnection->addTableWrappers( "ppdb2022_uggroups" ) .
			" order by ". $grConnection->addFieldWrappers( "Label" );

		$qResult = $grConnection->query( $sql );
		while( $tdata = $qResult->fetchNumeric() )
		{
			$this->groups[ $tdata[0] ] = $tdata[1];
		}
	}

	/**
	 * Fill and prepare rights array
	 * Call it only after save new data, for get fresh data
	 */
	function fillSmartyAndRights()
	{
		$first = true;
		foreach($this->groups as $id => $name)
		{
			$sg = array();
			$sg["group_attrs"] = "value=\"".$id."\"";
			if( $first )
			{
				$sg["group_class"] = "active";
				$first = false;
			}		
			$sg["groupname"] = runner_htmlspecialchars($name);
			$this->smartyGroups[] = $sg;
		}
	}

	/**
	 * Fill rights array
	 * Call it only after save new data, for get fresh data
	 */
	function getRights()
	{
		// It's expected that $this->tName is equal to 'admin_right' so the page's db connection is used #9875
		$sql = "select ". $this->connection->addFieldWrappers( "GroupID" )
			.", ". $this->connection->addFieldWrappers( "TableName" )
			.", ". $this->connection->addFieldWrappers( "AccessMask" )
			.", ". $this->connection->addFieldWrappers( "Page" )
			." from ". $this->connection->addTableWrappers( "ppdb2022_ugrights" )
			." order by ". $this->connection->addFieldWrappers( "GroupID" );

		$qResult = $this->connection->query( $sql );
		while( $tdata = $qResult->fetchNumeric() )
		{
			$group = $tdata[0];
			$table = $tdata[1];
			$mask = $tdata[2];
			$strPages = $tdata[3];

			$pages = array();
			if( $strPages )
				$pages = my_json_decode( $strPages );

			// check whether the table exists in the project
			if( !isset($this->tables[ $table ]) )
				continue;

			// check whether the group exists
			if( !isset($this->groups[ $group ]) )
				continue;

			//	add permissions
			if( !isset($this->rights[ $table ]) ) {
				$this->rights[ $table ] = array();
				$this->pageRestrictions[ $table ] = array();
			}
			$this->rights[ $table ][ $group ] = $this->fixMask($mask, $this->pageMasks[ $table ]);
			if( $pages )
				$this->pageRestrictions[ $table ][ $group ] = $pages;
		}
	}

	/**
	 * Prepare JS arrays with groups and tables data
	 */
	function addJsGroupsAndRights()
	{
		$this->jsSettings['tableSettings'][$this->tName]['warnOnLeaving'] = true;
		$this->jsSettings['tableSettings'][$this->tName]['rights'] = $this->rights;
		$this->jsSettings['tableSettings'][$this->tName]['pageRestrictions'] = $this->pageRestrictions;
		$this->jsSettings['tableSettings'][$this->tName]['groups'] = $this->groups;
		$this->jsSettings['tableSettings'][$this->tName]['tables'] = $this->tables;
		$this->jsSettings['tableSettings'][$this->tName]['allPages'] = $this->pages;
		$this->jsSettings['tableSettings'][$this->tName]['pageMasks'] = $this->pageMasks;
		$this->jsSettings['tableSettings'][$this->tName]['menuOrderedTables'] = $this->menuOrderedTables;
		$this->jsSettings['tableSettings'][$this->tName]['alphaOrderedTables'] = $this->alphaOrderedTables;
	}

	function commonAssign()
	{
		$this->xt->assign_loopsection("groups", $this->smartyGroups);

		parent::commonAssign();

		// assign headcheckboxes
		foreach( $this->permissionNames as $perm => $t )
		{
			$this->xt->assign( $perm."_headcheckbox", " id=\"colbox".$perm."\" data-perm=\"".$perm."\"");
		}

		// assign attrs
		$this->xt->assign("addgroup_attrs", "id=\"addGroupBtn\"");
		$this->xt->assign("delgroup_attrs", "id=\"delGroupBtn\"");
		$this->xt->assign("rengroup_attrs", "id=\"renGroupBtn\"");
		$this->xt->assign("savegroup_attrs", "id=\"saveGroupBtn\"");
		$this->xt->assign("savebutton_attrs", "id=\"saveBtn\"");
		$this->xt->assign("resetbutton_attrs", "id=\"resetBtn\"");
		$this->xt->assign("cancelgroup_attrs", "id=\"cancelBtn\"");

		// assign blocks
		$this->xt->assign("grid_block", true);
		$this->xt->assign("menu_block", true);
		$this->xt->assign("left_block", true);
		$this->xt->assign("rights_block", true);
		$this->xt->assign("message_block", true);
		$this->xt->assign("security_block", true);
		$this->xt->assign("logoutbutton",isSingleSign());
		$this->xt->assign("savebuttons_block", true);
		$this->xt->assign("search_records_block", true);
		$this->xt->assign("recordcontrols_block", true);

		// assign user settings
		// The user might rewrite $_SESSION["UserName"] value with HTML code in an event, so no encoding will be performed while printing this value.
		$this->xt->assign("username", $_SESSION["UserName"]);
		if ($this->createLoginPage)
			$this->xt->assign("userid", runner_htmlspecialchars($_SESSION["UserID"]));
			
		$this->hideElement("message");	
	}

	function getBreadcrumbMenuId() {
		return "adminarea";
	}

	/**
	 * Sort tables array
	 * @param unknown_type $tables
	 */
	function sortTables()
	{
		//	build $this->alphaOrderedTables and $this->sortedTables
		$this->sortedTables = array();
		// order tables by caption
		foreach($this->tables as $table => $tbl)
		{
			$this->sortedTables[] = array($table, $tbl[1]);
		}
		usort( $this->sortedTables, "rightsSortFunc" );

		$this->alphaOrderedTables = array();
		foreach($this->sortedTables as $t)
		{
			$this->alphaOrderedTables[] = $t[0];
		}

		//	build $this->menuOrderedTables
		$this->menuOrderedTables = array();
		$menu = $this->getMenuNodes();
		$addedTables = array();
		$groupsMap = array();
		$allTables = GetTablesListWithoutSecurity();
		
		foreach($menu as $m)
		{
			$arr = array();
			if ( $m["pageType"] == "WebReports" || $m["type"] == "Separator" )
				continue;

			if( $m["table"] && !$addedTables[ $m["table"] ] && array_search( $m["table"], $allTables ) !== FALSE )
			{
				$addedTables[ $m["table"] ] = true;
				$arr["table"] = $m["table"];
			}

			if( $m["parent"] )
			{
				$arr["parent"] = $groupsMap[ $m["parent"] ];
				$this->menuOrderedTables[ $arr["parent"] ]["items"][] = count($this->menuOrderedTables);
			}

			if( true || $m["type"] == "Group" )
			{
				$groupsMap[ $m["id"] ] = count($this->menuOrderedTables);
				//	add all groups
				$arr["title"] = $m["title"];
				$arr["items"] = array();
				$arr["collapsed"] = true;
			}

			$this->menuOrderedTables[] = $arr;
		}
		//	add the rest of tables alphabetically
		if(count($this->alphaOrderedTables) > count($addedTables))
		{
			$unlistedId = count($this->menuOrderedTables);
			$arr = array();
			$arr["collapsed"] = true;
			$arr["title"] = "Unlisted tables";
			$arr["items"] = array();
			$this->menuOrderedTables[] = $arr;
			foreach( $this->alphaOrderedTables as $table)
			{
				if( !$addedTables[ $table ] )
				{
					$this->menuOrderedTables[$unlistedId]["items"][] = count( $this->menuOrderedTables );
					$this->menuOrderedTables[] = array( "table" => $table, "parent" => $unlistedId);
				}
			}
		}
	}

	/**
	 * Get items count in group
	 * @param item index
	 */
	function getItemsCount($itemIdx)
	{
		$count = 0;
		foreach($this->menuOrderedTables[$itemIdx]["items"] as $idx)
		{
			if(isset($this->menuOrderedTables[$idx]["items"]))
				$count += $this->getItemsCount($idx);
			if(isset($this->menuOrderedTables[$idx]["table"]))
				$count++;
		}
		return $count;
	}

	/**
	 * Fills info in array about grid.
	 * @param array $rowInfoArr array with total info, that assignes grid
	 */
	function fillTablesGrid(&$rowInfoArr)
	{
		//	fill $rowInfoArr array
		$rowClass = false;
		$recno = 1;
		$editlink = "";
		$copylink = "";
		$parentStack = array();
		foreach($this->menuOrderedTables as $idx => $tbl)
		{
			$table = @$tbl["table"];
			$parent = @$tbl["parent"];
			if( strlen($table) )
			{
				$caption = $this->tables[$table][1];
				$shortTable = $this->tables[$table][0];
				$row = array();
				if($caption == $table)
					$row["tablename"] = runner_htmlspecialchars($table);
				else
					$row["tablename"] = "<span dir='LTR'>".runner_htmlspecialchars($caption)."&nbsp;(".runner_htmlspecialchars($table).")</span>";

				$row["table_row_attrs"] = " id=\"row_".$shortTable."\"";
				$row["tablecheckbox_attrs"]= "id=\"rowbox".$shortTable."\" data-table=\"".$shortTable."\" data-checked=0";
				$row["tbl_cell"] = " id=\"tblcell".$shortTable."\"";

				// create permission controls
				$mask = $this->pageMasks[$table];
				foreach( $this->permissionNames as $perm => $x )
				{
					if( strpos($mask, $perm) === FALSE )
						continue;
					$row[$perm."_group"] = true;
					$row[$perm."_checkbox"] = " id=\"box".$perm.$shortTable."\" data-checked=0";
					$row[$perm."_cell"] = " id=\"cell".$perm.$shortTable."\"";
				}

				$row["hide_pages_attrs"] .= 'data-hide-pages data-hidden data-table="'.$shortTable.'"';
				$row["show_pages_attrs"] .= 'data-show-pages data-table="'.$shortTable.'"';
				$this->fillPageRows( $table, $shortTable, $row );

			}
			else
			{
				$title = $tbl["title"];
				$row = array();
				$row["tablename"] = runner_htmlspecialchars($title);

				$row["tablecheckbox_attrs"]= " data-checked=-2";
				$row["table_row_attrs"] = " id=\"grouprow_".$idx."\"";
				$row["hide_pages_attrs"] .= 'data-hidden';
				$row["show_pages_attrs"] .= 'data-hidden';
			}
			if(!isset($parent))
			{
			//	clear stack
				$parentStack = array();
			}
			else
			{
				$stackPos = array_search( $parent, $parentStack );
				if( $stackPos === FALSE )
					$parentStack[] = $parent;
				else
				{
					$parentStack = array_slice( $parentStack, 0, $stackPos + 1);
				}
				$row["tblrowclass"] .= "rightsindent" . count($parentStack);
			}

			$childrenCount = $this->getItemsCount($idx);
			if( isset($tbl["items"]) && $childrenCount )
			{
				$row["tablename"] .= "<span class='tablecount' dir='LTR'>&nbsp;(".$this->getItemsCount($idx).")</span>";
				$row["table_row_attrs"] .= " data-groupid=\"".$idx."\"";
				$row["groupControl"] = true;
				$row["groupControlState"] = " data-state='closed'";
				$row["groupControlClass"] = " data-state='closed'";
				$row["tblrowclass"] .= " menugroup";
				if( !strlen($table) )
				{
					//	the item is just a group
					//	add the class to hide it in alpha mode
					$row["tblrowclass"] .= " menugrouponly";
				}
			}
			else if( !strlen($table) )
			{
				// empty menu group
				continue;
			}
			// hide second-level tables initially
			if($parent)
			{
				$row["table_row_attrs"] .= " style='display:none;' data-ingroup='true' ";
			}

			$rowInfoArr[] = $row;
		}
	}

	function fillPageRows($table, $shortTable, &$row ) {
		$allPages = tablePages( $table );
		$pages = array();
		foreach( $allPages as $ptype => $pids ) {
			foreach( $allPages[$ptype] as $p ) {
				$pages[$p] = $ptype;
			}
		}
		// second param for ASP
		ksort( $pages, SORT_STRING );

		$pageRows = array();
		foreach( $pages as $pageId => $pageType ) {
			$pageRow = array();
			
			$perm = Security::pageType2permission( $pageType );
			$pageRow[$perm."_pagebox"] = true;
			$pageRow["pagebox"] = true;
			$pageRow[$perm."_pagecheckbox"] = " data-table=\"".$shortTable."\" data-page=\"".$pageId."\" id=\"pagebox".$perm.$shortTable.'_'.$pageId."\" data-checked=0";
			$pageRow["pagecheckbox"] = "data-permission=\"".$perm."\" data-table=\"".$shortTable."\" data-page=\"".$pageId."\" id=\"wholepagebox_".$shortTable.'_'.$pageId."\" data-checked=0";
			$pageRow[$perm."_cell"] = " id=\"pagecell".$perm.$shortTable.'_'.$pageId."\"";
			$pageRow["rights_page"] = runner_htmlspecialchars($pageId);
			$pageRow["page_row_attrs"] = 'data-hidden data-table="'.$shortTable.'" data-page="'.$pageId.'"';
			$pageRows[] = $pageRow;
		}

		$row["page_row"] = array("data" => &$pageRows );
	}

	/**
	 * Fill premissions grid
	 */
	function fillGridData()
	{
		//	fill $rowinfo array
		$rowInfo = array();
		$this->fillTablesGrid($rowInfo);
		$this->xt->assign_loopsection("grid_row", $rowInfo);
	}

	/**
	 * Fill session vars, override parent, do nothing
	 */
	function setSessionVariables()
	{
	}

	/**
	 * Main function, call to build page
	 * Do not change methods call oreder!!
	 */
	function prepareForBuildPage()
	{
		// prepare array, only after save, for get new data
		$this->fillSmartyAndRights();
		// get rights, only after save, for fresh data
		$this->getRights();
		// fill grid data
		$this->fillGridData();
		// add common js code
		$this->addCommonJs();
		// add common html code
		$this->addCommonHtml();
		// Set common assign
		$this->commonAssign();
	}

	/**
	 * show page at the end of its proccess, depending on mode
	 */
	function showPage()
	{
		$this->display($this->templatefile);
	}

	/**
	 * Adds HTML and JS
	 */
	function addCommonHtml()
	{
		$this->body ["begin"] .= GetBaseScriptsForPage($this->isDisplayLoading);

		// assign body end
		$this->body['end'] = XTempl::create_method_assignment( "assignBodyEnd", $this );
	}

	/**
	 * A stub
	 */
	function prepareForResizeColumns()
	{
	}

	/**
	 * Add js files and scripts
	 */
	function addCommonJs() {
		// call parent if need RunnerJS API
		RunnerPage::addCommonJs();

		$this->addJsGroupsAndRights();
	}

	/**
	 *	Removes permissions from $mask that are not defined in $possibleMask
	 *	I.e. $mask = "ADE", $possibleMask = "AESP", return "AE"
	 */
	function fixMask($mask, $possibleMask)
	{
		$outMask = "";
		$l = strlen($possibleMask);
		for($i=0; $i < $l; ++$i)
		{
			if(strpos($mask, $possibleMask[$i]) !== FALSE)
				$outMask .= $possibleMask[$i];
		}
		return $outMask;
	}

	function saveRights( &$modifiedRights )
	{
		foreach($modifiedRights as $group => $rights)
		{
			foreach($modifiedRights[$group] as $table => $tableRights)
			{
				$this->updateTablePermissions( $table, $group, $tableRights );
			}
		}
		echo my_json_encode(array( 'success' => true ));
	}

	/**
	 * Save permissions for those pages only, that are defined in the project.
	 * This is required when using the same permission tables in several projects
	 * @param String table
	 * @param Number group
	 * @param Array tableRights array( 
	 * 						"permissions" => "<mask>",
	 * 						"pages" => array( <restricted pages> => true )
	 * 					)
	 */
	function updateTablePermissions( $table, $group, $tableRights )
	{
		$mask = $tableRights["permissions"];
		$rightWTableName = $this->connection->addTableWrappers( "ppdb2022_ugrights" );
		$accessMaskWFieldName = $this->connection->addFieldWrappers( "AccessMask" );
		$groupisWFieldName = $this->connection->addFieldWrappers( "GroupID" );
		$pageWFieldName = $this->connection->addFieldWrappers( "Page" );
		$tableNameWFieldName = $this->connection->addFieldWrappers( "TableName" );
		$groupWhere = $groupisWFieldName."=". $group 
			." and ". $tableNameWFieldName ."=". $this->connection->prepareString( $table );

		$strPages = "";
		$pages = $tableRights["pages"];
		if( $pages ) {
			$strPages = my_json_encode( $pages );
		}
		// It's expected that $this->tName is equal to 'admin_right' so the page's db connection is used #9875
		$sql = "select ". $accessMaskWFieldName ." from ". $rightWTableName. "where" . $groupWhere;
		// select rights from the database
		$data = $this->connection->query( $sql )->fetchNumeric();
		if( $data )
		{
			//	correct the mask according to the table's pageMask
			$savedMask = $data[0];
			$pageMask = $this->pageMasks[$table];
			$correctedMask = "";

			foreach( $this->permissionNames as $perm => $t )
			{
				if( strpos( $pageMask, $perm ) !== false )
				{
					if( strpos( $mask, $perm ) !== false )
						$correctedMask.= $perm;
				}
				else
				{
					if( strpos( $savedMask, $perm ) !== false )
						$correctedMask.= $perm;
				}
			}
			$mask = $correctedMask;

			if( strlen($mask) )
			{
				//	update the table name as well to address table renaming ( uppercase/lowercase ) issues
				$sql = "update ". $rightWTableName ." set ". 
					$accessMaskWFieldName ."='". $mask ."',".
					$tableNameWFieldName."=".$this->connection->prepareString( $table ).
					"," . $pageWFieldName . "=" . $this->connection->prepareString( $strPages ).
					" where ". $groupWhere;
			}
			else
				$sql = "delete from ". $rightWTableName	." where ". $groupWhere;

		}
		else
		{
			if( !strlen($mask) )
				return;

			$sql = "insert into ". $rightWTableName .
				" (". $groupisWFieldName .", ".$tableNameWFieldName.", ". $accessMaskWFieldName. ", ". $pageWFieldName .")"
				." values (". $group .", ".$this->connection->prepareString( $table ).", '". $mask ."', "
					.$this->connection->prepareString( $strPages ).")";
		}

		$this->connection->exec( $sql );
	}
}

function rightsSortFunc($a, $b)
{
	if($a[1]==$b[1])
		return 0;
	if($a[1]<$b[1])
		return -1;
	return 1;
}

?>