<?php

class Config
{
	private static $_items = array();
	
	/**
	 * Load config setting from a config file
	 * @param file_path
	 * @return true if config loaded, or false if failed
	 */
	static function load($file_path)
	{
		if (is_readable($file_path))
		{
			$temp = require($file_path);
			if (is_array($temp))
			{
				self::$_items = $temp;
				
				// Set the base url automatically if none was provided
				if (!self::get('base_url'))
					self::set_base_url();
				
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
		return array_key_exists($name, self::$_items) ? self::$_items[$name] : null;
	}
	
	/**
	 * Set a config setting
	 * @param name:  name of the config setting
	 * @param value: new value of the config setting
	 */
	static function set($name, $value)
	{
		self::$_items[$name] = $value;
	}
	
	private static function set_base_url()
	{
		if (isset($_SERVER['HTTP_HOST']))
		{
			$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' ? 'https' : 'http';
			$base_url .= '://'.$_SERVER['HTTP_HOST'];
			$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
		}
		else
		{
			$base_url = 'http://localhost';
		}
		self::set('base_url', $base_url);
	}

}

