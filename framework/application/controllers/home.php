<?php

class Home extends Controller
{
	function __construct()
	{
		parent::__construct('my_template');
	}
	
	function index()
	{
		$data['page_title'] = 'Home';
		$this->render($data);
	}
}
