<?php

class RunnerCiphererES
{	
	var $key = '';
	var $INITIALISATION_VECTOR = '';
	var $mcript_algorithm = null;
	var $SSLMethod = null;
	var $mcryptModule = null;
	var $max_key_size = null;
	var $useSSL = false;

	function openSSL_exist()
	{
		//return false; // uncomment for test without openssl
		return function_exists('openssl_encrypt');
	}

	function mcrypt_exist()
	{
		//return false; // uncomment for test without mycript
		return function_exists('mcrypt_module_open');
	}

	function __construct($key) 
	{
		if ( !$this->openSSL_exist() && !$this->mcrypt_exist() )
		{
			if ( version_compare(phpversion(), '7.1', '>=') )
				throw new Exception("Install OpenSSL extension");
			else 
				throw new Exception("Install OpenSSL or Mcrypt extension");
		}

		if ( !$this->openSSL_exist() )
		{
			$this->mcryptModule = mcrypt_module_open($this->mcript_algorithm, '', MCRYPT_MODE_CBC, '');			
		}

		$this->key = substr($key, 0, $this->max_key_size);
	}



	/**
	 * Encrypt
	 * Encrypt string
	 * @param {string} plain value
	 * @return {string} encrypted value
	 */
	function Encrypt($source) {
		$result = '';
		if ( $source != '' )
		{
			if ( $this->openSSL_exist() )			
			{
				if (strlen($source) % $this->max_key_size)
				{
					$source = str_pad($source, strlen($source) + $this->max_key_size - strlen($source) % $this->max_key_size, "\0");
				}
				$result = bin2hex(openssl_encrypt($source, $this->SSLMethod, $this->key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $this->INITIALISATION_VECTOR));				
			}
			else if ( mcrypt_generic_init($this->mcryptModule, $this->key, $this->INITIALISATION_VECTOR) != -1 )
			{
				$result = bin2hex(mcrypt_generic($this->mcryptModule, $source));
			}
			
		}

		return $result;
	}
	
	/**
	 * Decrypt
	 * Decrypt ecncrypted string
	 * @param {string} encrypted value
	 * @return {string} decrypted value
	 */
	function Decrypt($source) {
		if (!is_string($source) || strlen($source) == 0 || strlen($source) % 2 > 0 || preg_match ("/[^0-9a-f]/", $source) == 1)
			return $source;
		
		$result = '';

		if ( $this->openSSL_exist() )
		{			
			$result = openssl_decrypt(hex2bin($source), $this->SSLMethod, $this->key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $this->INITIALISATION_VECTOR);
		}
		else if ( mcrypt_generic_init($this->mcryptModule, $this->key, $this->INITIALISATION_VECTOR) != -1)
		{
			$result = mdecrypt_generic($this->mcryptModule, hex2bin($source));
		}

		$result = str_replace("\0", '', $result);
				
		return $result;
	}
	
}


?>