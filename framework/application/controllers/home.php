<?php

class Home extends Controller
{
	function __construct()
	{
		parent::__construct('layout');
	}

	function index()
	{
		$this->render();
	}
}
