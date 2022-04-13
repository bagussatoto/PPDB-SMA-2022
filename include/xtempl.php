<?php
// menuItem class
include_once(getabspath("include/menuitem.php"));
include(getabspath("include/testing.php"));
include_once(getabspath("classes/xtempl_base.php"));

/**
  * Xlinesoft Template Engine
  */
class XTempl extends XTempl_Base
{
	
	function report_error($message)
	{
		echo $message;
		exit();
	}
	
	protected function assign_headers() 
	{
		//check if headers are already assigned
		if( isset( $this->xt_vars['header'] ) )
			return;

		if ( !$this->mobileTemplateMode() )
		{
			xtempl_include_header($this,"header","include/header.php");
			xtempl_include_header($this,"footer","include/footer.php");
		}
		else
		{
			xtempl_include_header($this,"header","include/mheader.php");
			xtempl_include_header($this,"footer","include/mfooter.php");
		}
	}
	
	

	function xt_doevent($params)
	{
		if (isset($this->xt_events[@$params["custom1"]]))
		{
			$eventArr = $this->xt_events[@$params["custom1"]];
			
			if(isset($eventArr["method"]))
			{
				$params=array();
				if(isset($eventArr["params"]))
					$params=$eventArr["params"];
				$method=$eventArr["method"];
				$eventArr["object"]->$method($params);
				return;
			}
		}
		global $strTableName, $globalEvents;
		if($this->eventsObject)
			$eventObj = &$this->eventsObject;
		else
			$eventObj = &$globalEvents;
		if(!$eventObj)
			return;
		$eventName = $params["custom1"];
		if(!$eventObj->exists($eventName))
			return;
		$funcName = "event_".GoodFieldname( $eventName );
		$eventObj->$funcName($params);
	}
/*	
	function fetchVar($varName)
	{
		ob_start();
		$varParams = array();
		$this->processVar($this->getVar($varName), $varParams);	
		$out=ob_get_contents();
		ob_end_clean();
		return $out;
		
	}
*/

	


	function call_func($var)
	{
		if(!strlen(@$var["func"]))
			return "";
		ob_start();	
		$params=$var["params"];
		$func=$var["func"];
		xtempl_call_func($func,$params);
		$out=ob_get_contents();
		ob_end_clean();
		return $out;
	}


	function processVar(&$var, &$varparams)
	{
		if(!is_array($var))
		{
		//	just display a value
			echo $var;
		}
		elseif(isset($var["func"]))
		{
		//	call a function
			$params = array();
			if(isset($var["params"]))
				$params = $var["params"];
			$this->transformFuncParams( $varparams, $params );
			$func = $var["func"];
			xtempl_call_func($func,$params);
		}
		elseif(isset($var["method"]))
		{
			$params = array();
			if(isset($var["params"]))
				$params = $var["params"];
			$this->transformFuncParams( $varparams, $params );
			$method = $var["method"];
//			if(method_exists($var["object"],$method))
				$var["object"]->$method($params);
		}
		else
		{
			$this->report_error("Incorrect variable value");
			return;
		}
	}
	
	
	function display($template)
	{
		$this->load_template($template);
		xt_process_template($this,$this->template);
	}
}

?>