<?php

include_once("ciphereres.php");

class RunnerCiphererAES extends RunnerCiphererES
{
	function __construct($key, $useSSL = false)
	{
		$this->INITIALISATION_VECTOR = 'A7EC0E8D9D35BFDA';
		$this->SSLMethod = 'AES-256-CBC';
		$this->mcript_algorithm = MCRYPT_RIJNDAEL_128;
		$this->max_key_size = 32;
		$this->useSSL = $useSSL;

		parent::__construct($key);
	}

	function openSSL_exist()
	{		
		return $this->useSSL;
	}

	function mcrypt_exist()
	{		
		return !$this->useSSL;
	}
}

?>