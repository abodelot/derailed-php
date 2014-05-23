<?php

// The journey begins here.

define('APPPATH', __DIR__.'/application/');
define('SYSPATH', __DIR__.'/system/');

// Load system classes
require('system/config.php');
require('system/controller.php');
require('system/router.php');
require('system/system.php');
require('system/html.php');

// Load application configuration file
if (!Config::load(APPPATH.'config/config.php'))
	System::error('Cannot load application configuration');

// Load application routes
if (!Router::load_routes(APPPATH.'config/routes.php'))
	System::error('Cannot load application routes');

$path_info = '';
// If URL contains a path
if (isset($_SERVER['PATH_INFO']) && strlen($_SERVER['PATH_INFO']) > 1)
{
	// Ignore first '/' character
	$path_info = substr($_SERVER['PATH_INFO'], 1);
	
	// Ignore trailing '/' characters (if any)
	$path_info = rtrim($path_info, '/');
	
	// Remove dots for security reasons
	$path_info = str_replace('..', '', $path_info);
}

// Load the requested controller
Router::initialize($path_info);
if (!Router::invoke_controller())
{
	System::error('Cannot invoke '.Router::get_directory().Router::get_controller().'/'.Router::get_method());
}

