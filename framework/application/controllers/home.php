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

	function welcome($username)
	{
		$data['username'] = $username;
		echo 'Welcome '.$username.'!';
	}
}
