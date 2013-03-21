<?php

/**
 * Holds informations relative to the requested URL
 */
class Router
{
	private static $_controller_name;
	private static $_method_name;

	static function initialize($path_args)
	{
		// Get controller and method names from path arguments
		self::$_controller_name = $path_args ? $path_args[0] : Config::get('default_controller');
		self::$_method_name = count($path_args) > 1 ? $path_args[1] : 'index';
	}
	
	static function invoke_controller()
	{
		// Try to fetch requested controller
		$controller_path = 'application/controllers/'.self::$_controller_name.'.php';
		if (is_readable($controller_path))
		{
			require($controller_path);
			$invoke = array(new self::$_controller_name, self::$_method_name);
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
		return self::$_controller_name;
	}
	
	static function get_method()
	{
		return self::$_method_name;
	}
}
