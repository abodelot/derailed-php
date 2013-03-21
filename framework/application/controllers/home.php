<?php

class Home extends Controller
{
	function index($name='World')
	{
	
		$this->render(array('name' => $name));
	}
	
	function page2()
	{
		$this->render();	
	}
}
