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
		self::$_item[$name] = $value;
	}
}