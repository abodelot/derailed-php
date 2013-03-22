<?php

class Home extends Controller
{
	function __construct()
	{
		parent::__construct('template');
	}
	
	function index($name='World')
	{
		
		$this->render(array('name' => $name, 'title' => 'Index'));
	}
	
	function page2()
	{
		$this->render();	
	}
}
