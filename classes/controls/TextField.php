<?php
require_once getabspath('classes/controls/TextControl.php');
class TextField extends TextControl
{
	function __construct($field, $pageObject, $id, $connection)
	{
		EditControl::__construct($field, $pageObject, $id, $connection);
		$this->format = EDIT_FORMAT_TEXT_FIELD;
	}

	function buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data)
	{
		parent::buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data);

		$inputType =  $this->pageObject->pSetEdit->getHTML5InputType( $this->field );
		$altAttr = ( $mode == MODE_INLINE_EDIT || $mode == MODE_INLINE_ADD ) && $this->is508 == true ? ' alt="'.$this->strLabel.'" ' : '';

		$classString = "";
		if( $this->pageObject->isBootstrap() )
			$classString = " class=\"form-control\"";
			
		global $cUserNameField, $cPasswordField;
		$autocomplete = true;
		if( $mode == MODE_SEARCH ||
			$this->pageObject->pageType == 'register' && ( $this->field == $cUserNameField || $this->field == $cPasswordField))
			$autocomplete = false;
		
		echo '<input id="'.$this->cfield.'" '. $classString . $this->inputStyle.' type="'.$inputType.'" '
			.(!$autocomplete ? 'autocomplete="off" ' : '').$altAttr
			.'name="'.$this->cfield.'" '.$this->pageObject->pSetEdit->getEditParams( $this->field )
			. $this->getPlaceholderAttr()
			.' value="'.runner_htmlspecialchars($value).'">';

		$this->buildControlEnd($validate, $mode);
	}

	function getFirstElementId()
	{
		return $this->cfield;
	}
}
?>