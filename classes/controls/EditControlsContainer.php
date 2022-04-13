<?php
class EditControlsContainer
{
	public $controls = array();
	public $jsSettings = array();
	public $pSetEdit = null;
	public $pageType = "";
	public $cipherer = null;
	
	public $tName = "";
	
	public $pageObject = null;
	
	public $pageAddLikeInline = false;
	public $pageEditLikeInline = false;
	public $tableBasedSearchPanelAdded = false;
	
	public $searchPanelActivated = false;
	
	/**
	 * Associative array used like a container to exchange some data between controls on one page
	 * @var {array}
	 */
	public $globalVals = array();
	
	/**
	 * @type Connection 
	 */
	protected $connection;
	
	public $classNamesForEdit = array();
		
	public $classNamesForSearch = array();
	
	public function __construct($pageObject, $pSetEdit, $pageType, $cipherer = "")
	{
		if($pageObject != null)
		{
			$this->pageObject = $pageObject;
			$this->pageAddLikeInline = $pageObject->pageType == PAGE_ADD && $pageObject->mode == ADD_INLINE;
			$this->pageEditLikeInline = $pageObject->pageType == PAGE_EDIT && $pageObject->mode == EDIT_INLINE;
			$this->tName = $pageObject->tName;
		}
		else 
		{
			$this->tName = $pSetEdit->_table;
			$this->cipherer	= $cipherer;
		}
		
		$this->fillControlClassNames();

		$this->setEditControlsConnection();
		
		$this->pSetEdit = $pSetEdit;
		$this->pageType = $pageType;		
		$this->searchPanelActivated = true;
	}
	
	/**
	 * Set the connection property
	 */
	protected function setEditControlsConnection()
	{
		global $cman;
		
		if( $this->pageObject != null )
			$this->connection = $this->pageObject->connection;
		else
			$this->connection = $cman->byTable( $this->tName );
	}
	
	/**
	 * @return Boolean
	 */
	public function isSearchPanelActivated()
	{
		if( $this->pageObject != null )
			return $this->pageObject->isSearchPanelActivated();
			
		return $this->pageType == PAGE_SEARCH;
	}
	
	function addControlsJSAndCSS()
	{
		$pageTypes = array();
		switch ($this->pageType)
		{
			case PAGE_ADD:
				$pageTypeStr = "Add";
				break;
			case PAGE_EDIT:
				$pageTypeStr = "Edit";
				break;
			case PAGE_VIEW:	
			case PAGE_LIST:
			case PAGE_SEARCH:
				$pageTypeStr = "List";
				break;
			case PAGE_REGISTER:
				$pageTypeStr = "RegisterOrSearch";
				break;
			default:
				$pageTypeStr = "";
		}
		
		// the indicator showing if there is the Search panel on the page		
		$searchPanelActivated = $this->isSearchPanelActivated();
		
		if($pageTypeStr == "" && !$searchPanelActivated) 
			return;

		if($pageTypeStr != "" && $this->pageType != PAGE_SEARCH)
		{
			$getEditFieldsFunc = "get".(($this->pageAddLikeInline || $this->pageEditLikeInline) ? "Inline" : "").$pageTypeStr."Fields";
			if($this->pageAddLikeInline || $this->pageEditLikeInline)
				$appearOnPageFunc = "appearOnInline".$pageTypeStr;
			else 
				$appearOnPageFunc = "appearOn".$pageTypeStr."Page";
		}
		switch($this->pageType)
		{
			case PAGE_REGISTER:
				$fields = $this->pSetEdit->getPageFields();
				break;
			case PAGE_SEARCH:
				$fields = $this->pSetEdit->getAdvSearchFields();
				break;
			default:
				$fields = array();
				if($getEditFieldsFunc)
					$fields = $this->pSetEdit->$getEditFieldsFunc();	
		}
		
		// Addign fields that aren't appear at list page, but appear on search panel  
		$searchFields = array();
		if($searchPanelActivated)
		{
			$searchFields = $this->pSetEdit->getPanelSearchFields();
			$searchFields = array_merge($searchFields, $this->pSetEdit->getAllSearchFields());
			$fields = array_merge($searchFields, $fields);
			$fields = array_unique($fields);			
		}
		foreach( $fields as $i => $f )
		{
			$appear = false;
			if($this->pageType == PAGE_REGISTER || $this->pageType == PAGE_SEARCH || in_array($f, $searchFields))
				$appear = true;
			else if($appearOnPageFunc) 
				$appear = $this->pSetEdit->$appearOnPageFunc($f);
			if($appear)
			{
				$editControl = $this->getControl($f);
				$editControl->addJSFiles();
				$editControl->addCSSFiles();
			}
		}
	}
	
	/**
	 * @param String field
	 * @param String id	(optional)
	 * @param Array extraParmas (optional)
	 * @return Control
	 */
	function getControl($field, $id = "", $extraParmas = array())
	{
		if( count($extraParmas) && $extraParmas["getDetKeyReadOnlyCtrl"] ) 
		{
			include_once(getabspath("classes/controls/Control.php"));
			$className = $this->classNamesForEdit[ EDIT_FORMAT_READONLY ];
			
			$ctrl = createControlClass($className, $field, $this->pageObject != null ? $this->pageObject : $this, $id, $this->connection);
			$ctrl->container = $this;
			
			return $ctrl;
		}
		
		if( count($extraParmas) && $extraParmas["getConrirmFieldCtrl"] ) 
		{
			include_once(getabspath("classes/controls/Control.php"));
			$className = $this->classNamesForEdit[ EDIT_FORMAT_PASSWORD ];
			
			$ctrl = createControlClass($className, $field, $this->pageObject != null ? $this->pageObject : $this, $id, $this->connection);
			if($extraParmas['isConfirm'])
				$ctrl->field = GetPasswordField();
			$ctrl->container = $this;
			
			return $ctrl;
		}
		
		// if conrol does not created previously  
		if(!array_key_exists($field, $this->controls))
		{
			include_once(getabspath("classes/controls/Control.php"));
			
			$userControl = false;
			
			$editFormat = $this->pSetEdit->getEditFormat($field);
			if($editFormat == EDIT_FORMAT_TEXT_FIELD && IsDateFieldType($this->pSetEdit->getFieldType($field)))
				$editFormat = EDIT_FORMAT_DATE;
			
			if($this->pageType == PAGE_SEARCH || $this->pageType == PAGE_LIST)
			{
				// Text field may be Lookup field on some page
				$pageTypebyLookupFormat = $this->pSetEdit->getPageTypeByFieldEditFormat($field, EDIT_FORMAT_LOOKUP_WIZARD);
				
				if( $editFormat == EDIT_FORMAT_TEXT_FIELD && $pageTypebyLookupFormat != "" )
				{
					$localPSet = new ProjectSettings($this->pSetEdit->_table, $pageTypebyLookupFormat);
						
					if( $localPSet->getLinkField($field) != $localPSet->getDisplayField($field) )
						$className = "LookupTextField";
					else 
						$className = $this->classNamesForSearch[ $editFormat ];
				}
				else
					$className = $this->classNamesForSearch[ $editFormat ];
			}
			else
				$className = $this->classNamesForEdit[ $editFormat ];
			
			if( $className == $this->classNamesForEdit[ EDIT_FORMAT_FILE ] && $this->pSetEdit->isBasicUploadUsed($field) )
				$className = "FileFieldSingle";
			
			if( !$className )
			{
				if($editFormat != "")
				{
					$className = "Edit".$editFormat;
					$userControl = true;
					include_once(getabspath("classes/controls/UserControl.php"));
					if( !is_null($this->pageObject) )
						$this->pageObject->AddJSFile("include/runnerJS/controls/".$className.".js", "include/runnerJS/editControls/Control.js");
				}
				else
					$className = $this->classNamesForEdit[ EDIT_FORMAT_TEXT_FIELD ];
			}
					
			$this->controls[ $field ] = createControlClass($className, $field, $this->pageObject != null ? $this->pageObject : $this, $id, $this->connection);
			$this->controls[ $field ]->container = $this;
			if($userControl)
			{
				$this->controls[ $field ]->format = $className;
				$this->controls[ $field ]->initUserControl();
			}
		}
		
		if( $id !== "" )
			$this->controls[ $field ]->setID($id);
		
		return $this->controls[ $field ];
	}
	
	/**
	 * @deprecated
	 */
	function isSystemControl($className)
	{
		include_once(getabspath("classes/controls/Control.php"));
		if($this->pageType == PAGE_SEARCH || $this->pageType == PAGE_LIST)
			return isset($this->classNamesForSearch[$className]);
		else
			return isset($this->classNamesForEdit[$className]);
	}
	
	/**
	 * Check if the host page is table based
	 * @return Boolean
	 */
	function isPageTableBased()
	{
		if($this->pageType == PAGE_MENU || $this->pageType == PAGE_LOGIN || $this->pageType == PAGE_REMIND || $this->pageType == PAGE_CHANGEPASS)
		{
			return false;
		}		
		return true;
	}
	
	function mobileTemplateMode() 
	{
		return false;
	}
	
	protected function fillControlClassNames() 
	{
		$this->classNamesForEdit[EDIT_FORMAT_TEXT_FIELD] = "TextField";
		$this->classNamesForEdit[EDIT_FORMAT_TIME] = "TimeField";
		$this->classNamesForEdit[EDIT_FORMAT_TEXT_AREA] = "TextAreaField";
		$this->classNamesForEdit[EDIT_FORMAT_PASSWORD] = "PasswordField";
		$this->classNamesForEdit[EDIT_FORMAT_DATE] = "DateField";
		$this->classNamesForEdit[EDIT_FORMAT_CHECKBOX] = "CheckboxField";
		$this->classNamesForEdit[EDIT_FORMAT_DATABASE_IMAGE] = "DatabaseFileField";
		$this->classNamesForEdit[EDIT_FORMAT_DATABASE_FILE] = "DatabaseFileField";
		$this->classNamesForEdit[EDIT_FORMAT_HIDDEN] = "HiddenField";
		$this->classNamesForEdit[EDIT_FORMAT_READONLY] = "ReadOnlyField";
		$this->classNamesForEdit[EDIT_FORMAT_FILE] = "FileField";
		$this->classNamesForEdit[EDIT_FORMAT_LOOKUP_WIZARD] = "LookupField";
			
		$this->classNamesForSearch[EDIT_FORMAT_TEXT_FIELD] = "TextField";
		$this->classNamesForSearch[EDIT_FORMAT_TIME] = "TimeField";
		$this->classNamesForSearch[EDIT_FORMAT_TEXT_AREA] = "TextField";
		$this->classNamesForSearch[EDIT_FORMAT_PASSWORD] = "TextField";
		$this->classNamesForSearch[EDIT_FORMAT_DATE] = "DateField";
		$this->classNamesForSearch[EDIT_FORMAT_CHECKBOX] = "CheckboxField";
		$this->classNamesForSearch[EDIT_FORMAT_DATABASE_IMAGE] = "TextField";
		$this->classNamesForSearch[EDIT_FORMAT_DATABASE_FILE] = "TextField";
		$this->classNamesForSearch[EDIT_FORMAT_HIDDEN] = "TextField";
		$this->classNamesForSearch[EDIT_FORMAT_READONLY] = "TextField";
		$this->classNamesForSearch[EDIT_FORMAT_FILE] = "FileField";
		$this->classNamesForSearch[EDIT_FORMAT_LOOKUP_WIZARD] = "LookupField";
	
	}
}
?>