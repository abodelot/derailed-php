<?php

class Input
{
	/**
	 * Fetch an item from $_POST
	 */
	static function post($index)
	{
		return isset($_POST[$index]) ? self::clean_str($_POST[$index]) : '';
	}

	/**
	 * Fretch an item from $_GET
	 */
	static function get($index)
	{
		return isset($_GET[$index]) ? self::clean_str($_GET[$index]) : '';
	}

	/**
	 * Check if request is AJAX
	 */
	static function is_ajax_request()
	{
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}


	static function clean_str($str)
	{
		$str = htmlspecialchars(trim($str));

		// Get ride of evil magic quotes
		if (get_magic_quotes_gpc())
			$str = stripslashes($str);

		return $str;
	}
}
