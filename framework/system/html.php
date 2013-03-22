<?php

class Html
{
	/**
	 * Anchor tag <a>
	 */
	static function a($uri, $name = '', $options = null)
	{
		$uri = self::build_uri($uri);
		
		if ($name === '')
			$name = $uri;
		
		
		return self::build_html_tag('a', 'href="'.$uri.'"', $options, $name);
	}
	
	/**
	 * Image tag <img />
	 */
	static function img($uri, $options = null)
	{
		$uri = self::build_uri($uri);
		
		return self::build_html_tag('img', 'src="'.$uri.'"', $options);
	}
	
	private static function build_uri($uri)
	{
		// If external link
		if (preg_match('#^(\w+:)?//#i', $uri) === 1)
			return $uri;
		
		// Prepend site url
		$absolute_uri = Config::site_url();
		if ($uri[0] != '/')
			$absolute_uri .= '/';
		
		return $absolute_uri.$uri;
	}
	
	private static function build_html_tag($tag_name, $str_attr, $options, $content = null)
	{
		if (is_array($options))
		{
			foreach ($options as $attr_name => $attr_value)
			{
				$str_attr .= ' '.$attr_name.'="'.$attr_value.'"';
			}
		}
		elseif (!empty($options))
		{
			$str_attr .= ' '.$options;
		}
		return '<'.$tag_name.' '.$str_attr.($content ? ('>'.$content.'</'.$tag_name.'>') : (' />'));
	}
}

