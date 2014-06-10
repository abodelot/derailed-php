<?php

class Validation
{
	const REQUIRED    = 1;
	const VALID_EMAIL = 2;

	private $items;
	private $errors;

	function __construct()
	{
		$this->items = array();
		$this->errors = array();
	}

	function add_rule($field_name, $mask)
	{
		$this->items[$field_name] = $mask;
	}

	function run()
	{
		foreach ($this->items as $field_name => $mask)
		{
			$value = Input::post($field_name);

			if ($mask & Validation::REQUIRED)
			{
				if (empty($value))
					$this->errors[$field_name] = Validation::REQUIRED;
			}

			if ($mask & Validation::VALID_EMAIL)
			{
				if ($value && !Validation::valid_email($value))
					$this->errors[$field_name] = Validation::VALID_EMAIL;
			}
		}
		return empty($this->errors);
	}

	function get_errors()
	{
		return $this->errors;
	}

	function get_error($index)
	{
		return isset($this->errors[$index]) ? $this->errors[$index] : null;
	}


	static function valid_email($email)
	{
		return preg_match('/^([\w._-]+@[a-z._-]+?\.[a-z]{2,4})$/i', $email);
	}
}
