<?php

class Config
{
	private static $items = array();
	private static $site_url = '';
	
	/**
	 * Load config setting from a config file
	 * @param file_path
	 * @return true if config loaded, or false if failed
	 */
	static function load($file_path)
	{
		if (is_readable($file_path))
		{
			$items = require($file_path);
			if (is_array($items))
			{
				self::$items = $items;
				
				// Application Debug Mode
				if (self::get('debug'))
				{
					ini_set('display_errors', 1);
					error_reporting(E_ALL | E_NOTICE);
				}
				else
				{
					ini_set('display_errors', 0);
					error_reporting(0);
				}
				
				
				// Set the base url automatically if none was provided
				if (!self::get('base_url'))
					self::guess_base_url();
				
				// Set the site url
				self::$site_url = self::get('base_url').self::get('index_page');
				
				// Configure the default timezone
				date_default_timezone_set(self::get('timezone'));
				
				// Configure the system locale
				setlocale(LC_ALL, self::get('locale'));
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Return a config setting
	 * @param name: name of the config setting
	 * @return the config setting or null if not found
	 */
	static function get($name)
	{
		return array_key_exists($name, self::$items) ? self::$items[$name] : null;
	}
	
	/**
	 * Set a config setting
	 * @param name:  name of the config setting
	 * @param value: new value of the config setting
	 */
	static function set($name, $value)
	{
		self::$items[$name] = $value;
	}
	
	/**
	 * Get site URL
	 */
	static function site_url()
	{
		return self::$site_url;
	}

	/**
	 * Resolve localhost's base url
	 */
	private static function guess_base_url()
	{
		if (isset($_SERVER['HTTP_HOST']))
		{
			$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' ? 'https' : 'http';
			$base_url .= '://'.$_SERVER['HTTP_HOST'];
			$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		}
		else
		{
			$base_url = 'http://localhost/';
		}
		self::set('base_url', $base_url);
	}

}

