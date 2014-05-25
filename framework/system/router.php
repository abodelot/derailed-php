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
		// Check for routes entry
		if ($path_info && is_array(self::$routes))
		{
			// path_info can be route + args
			$p = $path_info;
			$last_slash = strlen($p);
			while ($p)
			{
				// Search for a matching route
				if (array_key_exists($p, self::$routes))
				{
					// Reconstruct path_info with route and args (if any)
					$path_info = self::$routes[$p].substr($path_info, $last_slash);
					break;
				}
				// Trim to next slash
				$last_slash = strrpos($p, '/');
				$p = substr($p, 0, $last_slash);
			}
		}

		// Split path_info into non-empty segments
		self::$segments = array_filter(explode('/', $path_info));

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

	/**
	 * Parse REQUEST_URI and get the URI string
	 */
	static function get_request_uri()
	{
		if (!isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		$uri = parse_url($_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? rawurldecode($uri['path']) : '';

		if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
		{
			$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
		}
		elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
		{
			$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING server var and $_GET array.
		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0)
		{
			$query = explode('?', $query, 2);
			$uri = rawurldecode($query[0]);
			$_SERVER['QUERY_STRING'] = isset($query[1]) ? $query[1] : '';
		}
		else
		{
			$_SERVER['QUERY_STRING'] = $query;
		}

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		return $uri;
	}
}
