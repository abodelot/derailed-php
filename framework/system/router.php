<?php

/**
 * Holds informations relative to the requested URL
 */
class Router
{
	private static $controller_name;
	private static $method_name;

	static function initialize($path_args)
	{
		// Get controller and method names from path arguments
		self::$controller_name = $path_args ? $path_args[0] : Config::get('default_controller');
		self::$method_name = count($path_args) > 1 ? $path_args[1] : 'index';
	}
	
	static function invoke_controller()
	{
		// Try to fetch requested controller
		$controller_path = 'application/controllers/'.self::$controller_name.'.php';
		if (is_readable($controller_path))
		{
			require($controller_path);
			$invoke = array(new self::$controller_name, self::$method_name);
			if (is_callable($invoke))
			{
				call_user_func($invoke);
				return true;
			}
		}
		return false;
	}

	static function get_controller()
	{
		return self::$controller_name;
	}
	
	static function get_method()
	{
		return self::$method_name;
	}
}
