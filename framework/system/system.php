<?php

class System
{
	static function error($message)
	{
		if (Config::get('debug'))
		{
			die('<p class="system-error">Something very bad happened:<br/>'.$message.'</p>');
		}
		else
		{
			die('<p class="system-error">A fatal error has occurred.</p>');
		}
	}
}

?>
