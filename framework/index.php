<?php

// The journey begins here.


function raise_error($msg)
{
	die('<p>FATAL ERROR: '.$msg.'</p>');
}
error_reporting(E_ALL | E_STRICT | E_NOTICE);

define('APPPATH', 'application/');

// Load system classes
require('system/config.php');
require('system/controller.php');
require('system/router.php');
require('system/html.php');

// Load application configuration file
if (!Config::load('application/config/config.php'))
	raise_error('Cannot load application configuration');

$uri_segments = array();
// If URL contains a path
if (isset($_SERVER['PATH_INFO']) && strlen($_SERVER['PATH_INFO']) > 1)
{
	// Ignore first '/' character
	$path_info = substr($_SERVER['PATH_INFO'], 1);
	
	// Ignore trailing '/' characters (if any)
	$path_info = rtrim($path_info, '/');
	
	// Remove dots for security reasons
	$path_info = str_replace('.', '', $path_info);
	
	$uri_segments = explode('/', $path_info);
}

// Load the requested controller
Router::initialize($uri_segments);
if (!Router::invoke_controller())
{
	raise_error('cannot invoke '.Router::get_controller().'/'.Router::get_method());
}

