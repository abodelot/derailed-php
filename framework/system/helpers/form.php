
<?php

class Form
{
	/**
	 *
	 */
	static function open($action=null)
	{

		return '<form method="POST" action="">';
	}

	/**
	 * Text input tag
	 */
	static function input($name, $value=null, $extra=null)
	{
		return '<input type="text" name="'.$name.'"'.Form::value_str($value).' />';
	}

	/**
	 * Textarea tag
	 */
	static function textarea($name, $value=null, $extra=null)
	{
		return '<textarea name="'.$name.'">'.$value.'</textarea>';
	}


	static function hidden($name, $value=null, $extra=null)
	{
		return '<input type="hidden" name="'.$name.'"'.Form::value_str($value).' />';
	}

	static function checkbox($name)
	{
	}

	static function submit($name=null, $value=null)
	{
		return '<input type="submit" name="'.$name.'"'.Form::value_str($value).' />';
	}

	static function close()
	{
		return '</form>';
	}

	static private function value_str($value)
	{
		return $value != null ? ' value="'.$value.'"' : '';
	}
}
