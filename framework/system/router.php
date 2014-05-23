<?php

/**
 * Holds informations relative to the requested URL
 */
class Router
{
	private static $directory;
	private static $controller_name;
	private static $method_name;
	private static $segments;
	private static $path_info;
	private static $routes;
	
	/**
	 * Initialize routing
	 * @param path_info: URL path
	 */
	static function initialize($path_info)
	{
		// Check if path info is a route entry
		if (array_key_exists($path_info, self::$routes))
		{
			$path_info = self::$routes[$path_info];
		}

		// Split path into segments
		self::$segments = $path_info ? explode('/', $path_info) : array();
		
		if (count(self::$segments) > 0)
		{
			// If leading segments are directories
			$path = self::$segments[0].'/';
			while (is_dir(APPPATH.'controllers/'.$path))
			{
				self::$directory = $path;
				$path .= array_shift(self::$segments).'/';
			}
		}
			
		if (count(self::$segments) > 0)
		{
			// First remaining segment is the controller
			self::$controller_name = array_shift(self::$segments);
		}
		else
		{
			// No more segments, use default controller
			self::$controller_name = Config::get('default_controller');
		}

		if (count(self::$segments) > 0)
		{
			// First remaining segment in the method name
			self::$method_name = array_shift(self::$segments);
		}
		else
		{
			// No more segments, use default method
			self::$method_name = 'index';
		}

		self::$path_info = $path_info;
	}

	/**
	 * Load routing table from configuration file
	 */
	static function load_routes($filename)
	{
		if (is_readable($filename))
		{
			self::$routes = require($filename);
			return true;
		}

		return false;
	}


	static function invoke_controller()
	{
		// Try to fetch requested controller
		$controller_path = APPPATH.'controllers/'.self::$directory.self::$controller_name.'.php';
		if (is_readable($controller_path))
		{
			require($controller_path);
			
			// Check if the controller class is defined
			if (!class_exists(self::$controller_name))
				System::error('controller <code>'.self::$controller_name.'</code> is not defined');

			// Check if the method exists in the controller
			if (!method_exists(self::$controller_name, self::$method_name))
				System::error('controller <code>'.self::$controller_name.'</code> has no method <code>'.self::$method_name.'</code>');
			
			// Create controller instance and call the method
			try
			{
				$reflection_method = new ReflectionMethod(self::$controller_name, self::$method_name);

				if ($reflection_method->getNumberOfRequiredParameters() > count(self::$segments))
					System::error('Not enough parameters for calling '.self::$controller_name.'::'.self::$method_name.'()');
			
				if ($reflection_method->getNumberOfParameters() < count(self::$segments))
					System::error('Too many parameters for calling '.self::$controller_name.'::'.self::$method_name.'()');
			
				$reflection_method->invokeArgs(new self::$controller_name, self::$segments);
			}
			catch (ReflectionException $e)
			{
				System::error($e->getMessage());
			}			
			return true;
		}
		return false;
	}


	static function get_directory()
	{
		return self::$directory;
	}
	
	
	static function get_controller()
	{
		return self::$controller_name;
	}


	static function get_method()
	{
		return self::$method_name;
	}


	static function get_path()
	{
		return self::$path_info;
	}
}
