<?php

class BaseApiComponent
{
	protected $_properties = [];


	public function __get( $key )
	{
		if (array_key_exists($key, $this->_properties))
			return $this->_properties[$key];
		else
			throw new Exception('The property ' . $key . ' is not exists in class ' . __CLASS__);
	}

	public function __set( $key, $value )
	{
		if (array_key_exists($key, $this->_properties))
			$this->_properties[$key] = $value;
		else
			throw new Exception('The property ' . $key . ' is not exists in class ' . __CLASS__);
	}

	public function __isset( $key )
	{
		return isset($this->_properties[$key]);
	}

	public function __unset( $key )
	{
		unset($this->_properties[$key]);
	}

	public function getProperties()
	{
		return $this->_properties;
	}

	public function set( $key, $value )
	{
		$this->$key = $value;
		return $this;
	}

	public function curlGetContent( $url, $timeout = 10 )
	{
		if (!function_exists('curl_version'))
			throw new Exception('Sorry, but curl extension is not installed');

		$curl = curl_init();

		try
		{
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

			$content = curl_exec( $curl );
			return $content;
		}
		catch (Exception $e)
		{
		    echo $e->getMessage();
		}
		finally
		{
			curl_close($curl);
			unset($curl);
		}

		return false;
	}
} 