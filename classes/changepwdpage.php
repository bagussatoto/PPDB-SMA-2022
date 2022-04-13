<?php
class ChangePasswordPage extends RunnerPage
{
	protected $pwdStrong = false;
	
	public $token = "";
	
	public $action;	

	protected $passwordField;
	protected $usernameField;
	
	protected $auditObj = null;	
	
	protected $changePwdFields;
	
	protected $changedSuccess = false;
	
	/**
	 *
	 */
	function __construct(&$params = "")
	{
		parent::__construct($params);
		
		if( !$this->action && !$this->checkToken() )
		{
			Security::saveRedirectURL();
			HeaderRedirect("login"); 
			return;			
		}
	
		$this->passwordField = GetPasswordField();
		$this->usernameField = GetUserNameField();	
		
		$this->auditObj = GetAuditObject();
		
		if( $this->token )
		{
			$this->changePwdFields = array("newpass", "confirm");
			// to send it back with a form (user may delete session before submit)
			$this->setProxyValue("token", $this->token);
		}
		else
			$this->changePwdFields = array("oldpass", "newpass", "confirm");
		
		// fill global password settings
		$this->pwdStrong = GetGlobalData("pwdStrong", false);
		
		if( $this->pwdStrong )
		{
			$this->settingsMap["globalSettings"]["pwdStrong"] = true;	
			$this->settingsMap["globalSettings"]["pwdLen"] = GetGlobalData("pwdLen", 0);
			$this->settingsMap["globalSettings"]["pwdUnique"] = GetGlobalData("pwdUnique", 0);
			$this->settingsMap["globalSettings"]["pwdDigits"] = GetGlobalData("pwdDigits", 0);
			$this->settingsMap["globalSettings"]["pwdUpperLower"] = GetGlobalData("pwdUpperLower", false);
		}
		
		$this->formBricks["header"] = "changeheader";
		$this->formBricks["footer"] = "changebuttons";
		$this->assignFormFooterAndHeaderBricks( true );		
	}
	
	/**
	 * Set the connection property
	 */
	protected function setTableConnection()
	{
		global $cman;
		$this->connection = $cman->getForLogin();		
	}
	
	/**
	 *
	 */
	protected function assignCipherer()
	{
		$this->cipherer = RunnerCipherer::getForLogin();
	}

	/**
	 *
	 */
	protected function setReferer()
	{
		$referer = @$_SERVER["HTTP_REFERER"] != "" 
				&& strpos($_SERVER["HTTP_REFERER"], GetTableLink("changepwd")) != strlen($_SERVER["HTTP_REFERER"]) - strlen(GetTableLink("changepwd"))
				? $_SERVER["HTTP_REFERER"] : ""; 

		if( !isset($_SESSION["changepwd_referer"]) )
			$_SESSION["changepwd_referer"] = $referer != "" ? $referer : GetTableLink("menu");
		else if( $referer != "" )
			$_SESSION["changepwd_referer"] = $referer;	
	}
	
	/**
	 * @return String
	 */
	protected function getSQLWhere()
	{
		global $cUserNameFieldType;
		
		if( $this->token )
			return " where".$this->connection->addFieldWrappers( "" )."=". $this->connection->prepareString( $this->token );
				
		$value = $this->getSqlPreparedLoginTableValue( @$_SESSION["UserID"], $this->usernameField, $cUserNameFieldType );
		if( $this->pSet->usersTableInProject() ) {
			$sWhere = " where ". $this->connection->comparisonSQL( 
				$this->getFieldSQLDecrypt( $this->usernameField ), 
				$value, 
				$this->pSet->isCaseInsensitiveUsername() );	
		} else {
			$sWhere = " where ". $this->connection->comparisonSQL( 
				$this->connection->addFieldWrappers( $this->usernameField ), 
				$value, 
				$this->pSet->isCaseInsensitiveUsername() 
			);	
		}
		return $sWhere;
	}
	
	/**
	 * @param String
	 * @return String
	 */
	protected function getSelectSQL( $where )
	{
		global $cLoginTable;
		
		if( $this->pSet->usersTableInProject() )
			$strSQL = "select ".$this->getFieldSQLDecrypt( $this->passwordField );
		else
			$strSQL = "select ".$this->connection->addFieldWrappers( $this->passwordField );	

		return $strSQL ." from ".$this->connection->addTableWrappers( $cLoginTable ) . $where;
	}
	
	/**
	 * @param String newpass
	 * @param String where
	 * @return String
	 */
	protected function getUpdateSQL( $newpass, $where )
	{
		global $cLoginTable, $cPasswordFieldType;
		
		if( !$this->cipherer->isFieldEncrypted( $this->passwordField ) )
			$newpass = $this->getPasswordHash( $newpass );
		
		$passvalue = $this->cipherer->AddDBQuotes( $this->passwordField, $newpass );
		
		$setPart = " set ".$this->connection->addFieldWrappers( $this->passwordField )."=".$passvalue;
		if( $this->token )
		{
			$setPart.= ", ".$this->connection->addFieldWrappers( "" )."=". $this->connection->prepareString( "" ).", "
				.$this->connection->addFieldWrappers( "" )."=". $this->connection->addDateQuotes( NULL );
				
		}
		
		return "update ".$this->connection->addTableWrappers( $cLoginTable ). $setPart .' '. $where;	
	}
	
	/**
	 * @return Array
	 */
	protected function getControlValues()
	{
		$filename_values = array();
		$blobfields = array();
		$values = array();		
		foreach( $this->changePwdFields as $fName )
		{
			$fControl = $this->getControl( $fName, $this->id );
			$fControl->readWebValue( $values, $blobfields, NULL, NULL, $filename_values );
		}
		
		return $values;
	}
	
	/**
	 * @param String oldPass
	 * @param Array row
	 * @param Boolean bcrypted
	 * @return Boolean
	 */
	protected function checkOldPasswordValue( $oldPass, $row, $bcrypted )
	{
		if( !$row )
			return false;
		
		if( $this->token )
			return true;
		
		if( $bcrypted )
			return passwordVerify( $oldPass, $row[ 0 ] );

		return $oldPass == $row[ 0 ];		
	}
	
	/**
	 * @return Boolean
	 */
	protected function changePassword()
	{
		global $globalEvents, $cLoginTable, $cPasswordField;
		
		$values = $this->getControlValues();			
		$oldPass = $values["oldpass"];
		
		$bcrypted = false;		
		if( strlen( $oldPass ) && !$this->cipherer->isFieldEncrypted( $this->passwordField ) )
		{
				$bcrypted = true;
		}
		
		$sqlWhere = $this->getSQLWhere();
		$qResult = $this->connection->query( $this->getSelectSQL( $sqlWhere ) );	
		
		$nData = $qResult->fetchNumeric();
		// DecryptFetchedArray requires fieldname to decrypt code-based encryptied field value
		$data = array( $cPasswordField => $nData[0] );
		$row = $this->cipherer->DecryptFetchedArray( $data ); 
		
		$_row = array();
		if ( $row ) 
			$_row = array( $row[ $cPasswordField ] );
		
		if( !$this->checkOldPasswordValue( $oldPass, $_row, $bcrypted ) )
		{
			$this->message = "Password tidak berlaku";
			return false;		
		}
		
		$oldPass = $_row[ 0 ];
			
		if( $this->pwdStrong && !checkpassword( $values["newpass"] ) )
		{	
			$this->message = $this->getPwdStrongFailedMessage();
			$this->jsSettings["tableSettings"][ $cLoginTable ]["msg_passwordError"] = $this->message;
			return false;
		}

		$retval = true;
		if( $globalEvents->exists("BeforeChangePassword") )
			$retval = $globalEvents->BeforeChangePassword( $oldPass, $values["newpass"], $this );
		
		if( $retval )
		{				
			$strSQL = $this->getUpdateSQL( $values["newpass"], $sqlWhere );		
			$this->connection->exec( $strSQL );

			if( $this->auditObj )
				$this->auditObj->LogChPassword();
				
			if( $globalEvents->exists("AfterChangePassword") )
				$globalEvents->AfterChangePassword( $oldPass, $values["newpass"], $this );
		}
		
		return $retval;		
	}

	/**
	 * @return String
	 */	
	protected function getPwdStrongFailedMessage()
	{
		$msg = "";
		$pwdLen = GetGlobalData("pwdLen", 0);
		if($pwdLen)
		{
			$fmt = "Password must be at least %% characters length.";
			$fmt = str_replace("%%", "".$pwdLen, $fmt);
			$msg.= "<br>".$fmt;
		}
		$pwdUnique = GetGlobalData("pwdUnique", 0);
		if($pwdUnique)
		{
			$fmt = "Password must contain %% unique characters.";
			$fmt = str_replace("%%", "".$pwdUnique, $fmt);
			$msg.= "<br>".$fmt;
		}
		$pwdDigits = GetGlobalData("pwdDigits", 0);
		if($pwdDigits)
		{
			$fmt = "Password must contain %% digits or symbols.";
			$fmt = str_replace("%%", "".$pwdDigits, $fmt);
			$msg.= "<br>".$fmt;
		}
		if(GetGlobalData("pwdUpperLower", false))
		{
			$fmt = "Password must contain letters in upper and lower case.";
			$msg.= "<br>".$fmt;
		}
		
		if($msg)
			$msg = substr($msg, 4);
			
		return $msg;
	}
	
	/**
	 *
	 */
	public function process()
	{
		global $globalEvents;

		$this->setReferer();

		//	Before Process event
		if( $globalEvents->exists("BeforeProcessChangePwd") )
			$globalEvents->BeforeProcessChangePwd( $this );

		if( $this->action == "Change" )	
			$this->changedSuccess = $this->changePassword();
			
		if( !$this->changedSuccess )
		{
			$this->prepareEditControls();
		} 
		else
		{			
			$this->pageName = $this->pSet->getDefaultPage( $this->successPageType() );				
			$this->pSet = new ProjectSettings( $this->tName, $this->successPageType(), $this->pageName, $this->pageTable );
			
			$this->pageData["buttons"] = array_merge( $this->pageData["buttons"], $this->pSet->buttons() );
			foreach( $this->pSet->buttons() as $b ) {
				$this->AddJSFile( "include/button_".$b.".js" );
			}
		}
		
		$this->addCommonJs();
		$this->fillSetCntrlMaps();
		$this->addButtonHandlers();
		$this->doCommonAssignments();
		
		$this->showPage();
	}
	
	/**
	 *
	 */
	protected function prepareEditControls()
	{
		foreach($this->changePwdFields as $fName)
		{
			$parameters = array();
			$parameters["id"] = $this->id;
			$parameters["mode"] = "add";
			$parameters["field"] = $fName;
			$parameters["format"] = "Password";
			$parameters["pageObj"] = $this;
			$parameters["suggest"] = true;
			$parameters["validate"] = array('basicValidate' => array('IsRequired')); 
			
			$parameters["extraParams"] = array();
			$parameters["extraParams"]["getConrirmFieldCtrl"] = true;
							
			$controls = array('controls' => array());	
			$controls["controls"]['id'] = $this->id;
			$controls["controls"]['mode'] = "add";
			$controls["controls"]['ctrlInd'] = 0;
			$controls["controls"]['fieldName'] = $fName;
			$controls["controls"]['suggest'] = $parameters["suggest"];
			
			$this->xt->assign_function( $fName."_editcontrol", "xt_buildeditcontrol", $parameters );
			$this->xt->assign($fName."_label", true);
			
			if ( $this->isBootstrap() )
			{
				$this->xt->assign("labelfor_" . goodFieldName($fName), "value_".$fName."_".$this->id);
			}		
			
			if( $this->is508 )
				$this->xt->assign_section($fName."_label", "<label for=\"value_".$fName."_".$this->id."\">", "</label>");
			
			$this->xt->assign($fName."_block", true);
					
			$this->fillControlsMap($controls);
		}	
	}
	
	/**
	 *
	 */
	protected function assignBody()
	{
		$this->body["begin"] .= GetBaseScriptsForPage(false);
		$this->body["end"] = XTempl::create_method_assignment( "assignBodyEnd", $this );

		$this->xt->assignbyref("body", $this->body);
	}	
	
	/**
	 *
	 */
	protected function doCommonAssignments()
	{
		$this->xt->assign("id", $this->id);	
		$this->xt->assign("submit_attrs", "id=\"saveButton".$this->id."\"");
		$this->xt->assign("backlink_attrs", "href=\"". runner_htmlspecialchars( $_SESSION["changepwd_referer"] )."\"");

		if( $this->message )
		{
			if( $this->isBootstrap() )
			{
				$this->xt->assign("message_class", "alert-danger" );
				$this->xt->assign("message", $this->message);
			}
			else
			{
				$this->xt->assign("message", "<div class='message rnr-error'>".$this->message."</div>");
			}
			
			$this->xt->assign("message_block", true);
		}
		
		$this->assignBody();
	}
	
	/**
	 *
	 */
	protected function showPage()
	{
		global $globalEvents;


		if( $this->changedSuccess )
			$this->switchToSuccessPage();
	
		if( $globalEvents->exists("BeforeShowChangePwd") )
			$globalEvents->BeforeShowChangePwd( $this->xt, $this->templatefile, $this );

		$this->display( $this->templatefile );		
	}
	
	/**
	 * @return String
	 */
	public static function readActionFromRequest()
	{				
		if( @$_POST["btnSubmit"] )
			return @$_POST["btnSubmit"];
			
		return "";
	}
	
	/**
	 * @return Boolean
	 */
	protected function checkToken()
	{
		if( !$this->token )
			return true;
			
		$sqlSelect = "select ".$this->connection->addFieldWrappers( "" )." from".$this->connection->addTableWrappers("user")
			." where".$this->connection->addFieldWrappers( "" )."=". $this->connection->prepareString( $this->token );
			
		$data = $this->cipherer->DecryptFetchedArray( $this->connection->query( $sqlSelect )->fetchAssoc() );
		if( $data )
			return secondsPassedFrom( $data[""] ) < 86400;
		
		return false;
	}
}
?>