<?php

/**
 * Base class for application controllers
 */
abstract class Controller
{
	private $layout_path = null;
	private $view_path = null;
	private $view_depth = 0;
	
	function __construct($layout = null)
	{
		if ($layout)
			$this->set_layout($layout);
	}
	
	/**
	 * Load and display a view file
	 * The system will guess the file from the controller and method names
	 * @param data: variables extracted in the view
	 */
	function render($data = null)
	{
		$view_path = Router::get_controller().'/'.Router::get_method().'.php';
		$this->render_view($view_path, $data);
	}
	
	/**
	 * Load and display a view file
	 * @param view_path: file path to the view
	 * @param data: variables extracted in the view
	 */
	function render_view($view_path, $data = null)
	{
		$this->view_path = 'application/views/'.$view_path;
		if (!is_readable($this->view_path))
		{
			die('view not reachable: '.$this->view_path); // TODO: handle error
		}
				
		// Extract variables (if any)
		if (is_array($data))
			extract($data, EXTR_SKIP);

		// TODO: use a buffer		
		++$this->view_depth;
		if ($this->layout_path && $this->view_depth == 1)
			require($this->layout_path);
		
		else
			require($this->view_path);
	}
	
	function set_layout($layout_path)
	{
		$layout_path = 'application/layouts/'.$layout_path.'.php';
		if (is_readable($layout_path))
			$this->layout_path = $layout_path;
		
		else
			die('layout not reachable:' .$layout_path); // TODO: handle error
	}
	
	function get_view_path()
	{
		return $this->view_path;
	}
}
