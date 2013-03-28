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
	
	static function initialize($segments)
	{
		if (count($segments) > 0)
		{
			// If first segment is a controller
			if (file_exists(APPPATH.'controllers/'.$segments[0].'.php'))
			{
				self::$controller_name = array_shift($segments);
			}			
			elseif (count($segments) > 1)
			{
				// If the controller is a sub folder
				if (is_dir(APPPATH.'controllers/'.$segments[0]))
				{
					self::$directory = array_shift($segments);
					if (file_exists(APPPATH.'controllers/'.self::$directory.'/'.$segments[0].'.php'))
					{
						self::$controller_name = array_shift($segment);				
					}
				}
			}
		}
		else
		{
			// Default controller and method
			self::$controller_name = Config::get('default_controller');
		}
		
		self::$segments = $segments;
		
	}
	
	static function invoke_controller()
	{
		// Try to fetch requested controller
		$controller_path = APPPATH.'controllers/'.self::$controller_name.'.php';
		if (is_readable($controller_path))
		{
			require($controller_path);
			
			// Find the method name
			if (count(self::$segments) > 0)
			{
				if (method_exists(self::$controller_name, self::$segments[0]))
					self::$method_name = array_shift(self::$segments);
				else
					self::$method_name = 'index';
			}
			else
			{
				self::$method_name = 'index';
			}
			
			// Create controller instance and call the method
			try
			{
				$reflection_method = new ReflectionMethod(self::$controller_name, self::$method_name);

				if ($reflection_method->getNumberOfRequiredParameters() > count(self::$segments))
					die('not enough parameters for calling '.self::$controller_name.' '.self::$method_name);
			
				if ($reflection_method->getNumberOfParameters() < count(self::$segments))
					die('too many parameters for calling '.self::$controller_name.' '.self::$method_name);
			
				$reflection_method->invokeArgs(new self::$controller_name, self::$segments);
			}
			catch (ReflectionException $e)
			{
				die ($e->getMessage());
			}			
			return true;
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
