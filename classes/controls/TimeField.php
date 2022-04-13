<?php
require_once getabspath('classes/controls/DateTimeControl.php');
class TimeField extends DateTimeControl
{
	protected $timeAttrs;
	
	function __construct($field, $pageObject, $id, $connection)
	{
		EditControl::__construct($field, $pageObject, $id, $connection);
		
		$this->format = EDIT_FORMAT_TIME;
		$this->timeAttrs = $this->pageObject->pSetEdit->getFormatTimeAttrs( $this->field );
	}

	function addJSFiles()
	{
		if( !count( $this->timeAttrs ) || !$this->timeAttrs["useTimePicker"] )
			return;
		
		if ( $this->pageObject->isBootstrap() )
		{
		}
		else
		{
			$this->pageObject->AddJSFile("include/timepickr_jquery.timepickr.js");	
		}	
	}

	function buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data)
	{
		if($this->container->pageType == PAGE_LIST || $this->container->pageType == PAGE_SEARCH)
			$value = prepare_for_db($this->field, $value, "time");

		parent::buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data);
		
		echo '<input id="'.$this->ctype.'" '.$this->inputStyle.' type="hidden" name="'.$this->ctype.'" value="time">';
		
		$resultHtml = '';

		if( count( $this->timeAttrs ) )
		{
			$type = $this->pageObject->mobileTemplateMode() ? "time" : "text";

			$classString = "";
			if ( $this->pageObject->isBootstrap() )
				$classString = 'class="form-control"';				
				
			$resultHtml = '<input '.$this->getPlaceholderAttr().' type="'.$type.'" '.$this->inputStyle.' name="'.$this->cfield.'" ' . $classString
					.(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $this->is508 == true ? 'alt="'.$this->strLabel.'" ' : '')
					.'id="'.$this->cfield.'" '.$this->pageObject->pSetEdit->getEditParams($this->field);
					
			if( $this->timeAttrs["useTimePicker"] && !$this->pageObject->mobileTemplateMode() )
			{
				$convention = $this->timeAttrs["hours"];
				$loc = getLacaleAmPmForTimePicker($convention, true);
				$tpVal = getValForTimePicker($this->type, $value, $loc['locale']);
				
				$resultHtml .= ' value="'.runner_htmlspecialchars($tpVal['val']).'">';

				if( $this->pageObject->isBootstrap() )
					$resultHtml .= '<span class="input-group-addon" id="trigger-test-'.$this->cfield.'"><span class="glyphicon glyphicon-time"></span></span>';
				else
					$resultHtml .= '&nbsp;<a class="rnr-imgclock" data-icon="timepicker" title="Time" style="display:inline-block; margin:4px 0 0 6px; visibility: hidden;" id="trigger-test-'.$this->cfield.'" /></a>';
			}
			else
				$resultHtml .=' value="'.runner_htmlspecialchars( $this->getOutputValue( $value ) ).'">';

			if( $this->pageObject->isBootstrap() )
			{
				if ( isRTL() )
				{
					$resultHtml .= "<span></span>"; // for bootstrap calend icon anomaly
				}
				$resultHtml = '<div class="input-group" '.$this->inputStyle.' >' . $resultHtml . '</div>';
			}

			echo $resultHtml;
		}
		
		$this->buildControlEnd($validate, $mode);
	}

	/**
	 * @param Mixed fieldValue
	 * @return String
	 */
	protected function getOutputValue( $fieldValue )
	{
		if ( IsDateFieldType( $this->type ) )
			return str_format_time( db2time( $fieldValue ) );

		$numbers = parsenumbers( $fieldValue );
		if( !count( $numbers ) )
			return "";
		
		while( count( $numbers ) < 3 )
		{
			$numbers[] = 0;
		}
		
		if( count( $numbers ) == 6 )
			return str_format_time( array(0, 0, 0, $numbers[3], $numbers[4], $numbers[5]) );

		if( !$this->pageObject->mobileTemplateMode() )
			return str_format_time( array(0, 0, 0, $numbers[0], $numbers[1], $numbers[2]) );

		return format_datetime_custom( array(0, 0, 0, $numbers[0], $numbers[1], $numbers[2]), "HH:mm:ss" );
	}
	
	function getFirstElementId()
	{
		return $this->cfield;
	}

	function SQLWhere($SearchFor, $strSearchOption, $SearchFor2, $etype, $isSuggest)
	{
		$hasDigits = false;
		for($i = 0; $i < strlen($SearchFor); $i++)
		{
			if(is_numeric($SearchFor[$i]))
			{
				$hasDigits = true;
				break;
			}
		}
		
		if( !$hasDigits )
		{
			for($i = 0; $i < strlen($SearchFor2); $i++)
			{
				if(is_numeric($SearchFor2[$i]))
				{
					$hasDigits = true;
					break;
				}
			}
		}
		
		if( !$hasDigits || $SearchFor == "" )
			return "";

		$SearchFor = prepare_for_db($this->field, $SearchFor, "time");
		$SearchFor2 = prepare_for_db($this->field, $SearchFor2, "time");
		
		return parent::SQLWhere($SearchFor, $strSearchOption, $SearchFor2, $etype, $isSuggest);
	}

	function addCSSFiles()
	{
		if ( $this->pageObject->isBootstrap() )
		{
			$this->pageObject->AddCSSFile("include/bootstrap/css/bootstrap-datetimepicker.min.css");
		}
	}
}
?>