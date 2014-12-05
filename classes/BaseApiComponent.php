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
} 