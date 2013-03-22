<?php

/**
 * Application configuration
 * Configuration items can be read and set using the Config class
 * Config::get($name), Config::set($name, $value)
 */
return array
(
	/**
	 * Base URL
	 * URL to your application root. Leave empty for automated guess and the
	 * system will guess the path to your installation.
	 */
	'base_url' => '',

	/**
	 * Index page
	 * Typically this will be your index.php file, unless you've renamed it to
	 * something else. If you are using mod_rewrite to remove the page set this
	 * variable so that it is blank.
	 */
	'index_page' => 'index.php',
	
	/**
	 * Default controller
	 * Controller to be invokated when none is provided in the URL
	 */
	'default_controller' => 'home'
);

