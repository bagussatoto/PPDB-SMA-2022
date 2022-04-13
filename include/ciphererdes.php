<?php
include_once("ciphereres.php");

class RunnerCiphererDES extends RunnerCiphererES
{
	function __construct($key)
	{
		$this->INITIALISATION_VECTOR = 'd27b358d';
		$this->SSLMethod = 'DES-CBC';
		$this->mcript_algorithm = MCRYPT_DES;
		$this->max_key_size = 8;

		parent::__construct($key);
	}
}
?>