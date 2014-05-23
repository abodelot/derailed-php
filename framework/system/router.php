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
		// Check if path_info is a route entry
		if (is_array(self::$routes) && array_key_exists($path_info, self::$routes))
		{
			$path_info = self::$routes[$path_info];
		}

		// Split path_info into segments
		self::$segments = $path_info ? explode('/', $path_info) : array();


		// Extract nested sub directories if leading segments are matching
		$dirpath = '';
		while (count(self::$segments) > 0 && is_dir(APPPATH.'controllers/'.$dirpath.self::$segments[0]))
		{
			$dirpath .= array_shift(self::$segments).'/';
			self::$directory = $dirpath;
		}

		// Extract controller name
		if (count(self::$segments) > 0)
		{
			// First remaining segment is the controller
			self::$controller_name = array_shift(self::$segments);
		}
		else
		{
			// No more segments, using default controller
			self::$controller_name = Config::get('default_controller');
		}

		// Extract method name
		if (count(self::$segments) > 0)
		{
			// First remaining segment in the method name
			self::$method_name = array_shift(self::$segments);
		}
		else
		{
			// No more segments, using default 'index' method
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

	/**
	 * Load the controller and invoke the requested method
	 */
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
				System::error('controller <code>'.self::$controller_name.'</code> has no method named <code>'.self::$method_name.'</code>');

			// Create controller instance and call the method
			try
			{
				$reflection_method = new ReflectionMethod(self::$controller_name, self::$method_name);

				$argc = count(self::$segments);
				if ($reflection_method->getNumberOfRequiredParameters() > $argc)
					System::error('Not enough parameters for calling <code>'.self::$controller_name.'::'.self::$method_name.'()</code>,
					'.$argc.' provided, expected at least '.$reflection_method->getNumberOfRequiredParameters());

				if ($reflection_method->getNumberOfParameters() < $argc)
					System::error('Too many parameters for calling <code>'.self::$controller_name.'::'.self::$method_name.'()</code>,
					'.$argc.' provided, expected '.$reflection_method->getNumberOfParameters());

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
