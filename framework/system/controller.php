<?php

class Controller
{
	function __construct()
	{
	}
	
	/**
	 * Load and display a view file
	 * The system will guess the file from the controller and method names
	 * @param data: variables extracted in the view
	 */
	function render($data=null)
	{
		$view_path = Router::get_controller().'/'.Router::get_method().'.php';
		$this->render_view($view_path, $data);
	}
	
	/**
	 * Load and display a view file
	 * @param view_path: file path to the view
	 * @param data: variables extracted in the view
	 */
	function render_view($view_path, $data=null)
	{
		$view_path = 'application/views/'.$view_path;
		if (!is_readable($view_path))
		{
			// TODO: raise error
			return;
		}
		
		// Extract variables (if any)
		if (is_array($data))
			extract($data, EXTR_SKIP);
		
		require($view_path);
	}
}
