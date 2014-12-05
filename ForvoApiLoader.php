<?php


class ForvoApiLoader
{
	public static $paths = [
		'/classes'
	];

	/**
	 * Call this method before any operations.
	 */
	public static function registerAutoload()
	{
		spl_autoload_register(['\ForvoApiLoader', 'autoload']);
	}

	/**
	 * Call this method after all, to remove Forvo autoloader from spl_autoload stack.
	 */
	public static function unregisterAutoload()
	{
		spl_autoload_unregister(['\ForvoApiLoader', 'autoload']);
	}

	public static function autoload( $className )
	{
		foreach ( static::$paths as $path )
		{
			$classPath = dirname( __FILE__ ) . $path . '/' . $className . '.php';

			if ( file_exists($classPath) )
			{
				include_once $classPath;
				break;
			}
		}
	}

	/**
	 * @return ForvoApi
	 */
	public static function getApi()
	{
		return ForvoApi::getInstance();
	}
}