<?php

namespace forvoapi;

use forvoapi\classes\ForvoApi;

class ForvoApiLoader
{
	/**
	 * Call this method before any operations.
	 */
	public static function registerAutoload()
	{
		spl_autoload_register(['forvoapi\ForvoApiLoader', 'autoload']);
	}

	/**
	 * Call this method after all, to remove Forvo autoloader from spl_autoload stack.
	 */
	public static function unregisterAutoload()
	{
		spl_autoload_unregister(['forvoapi\ForvoApiLoader', 'autoload']);
	}

	public static function autoload( $className )
	{
		if (class_exists($className))
			return true;

		$className = str_replace('forvoapi\\', '', $className);
		$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
		$classPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $className . '.php';

		if ( file_exists($classPath) )
		{
			include_once $classPath;
			return true;
		}

		return false;
	}

	/**
	 * @return ForvoApi
	 */
	public static function getApi()
	{
		return ForvoApi::getInstance();
	}
}