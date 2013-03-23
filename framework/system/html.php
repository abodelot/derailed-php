<?php

class Html
{
	/**
	 * Anchor tag <a>
	 */
	static function a($uri, $name = '', $options = null)
	{
		if ($name === '')
			$name = $uri;

		$uri = self::build_link($uri);
		
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
	
	/**
	 * CSS link tag <link />
	 */
	static function link_css($uri, $options = null)
	{
		$uri = self::build_uri($uri);
		$attr = 'href="'.$uri.'" type="text/css" rel="stylesheet"';
		return self::build_html_tag('link', $attr, $options);
	}
	
	/**
	 * Javascript tag <script>
	 */
	static function link_js($uri)
	{
		$uri = self::build_uri($uri);
		return '<script type="text/javascript" src="'.$uri.'"></script>';
	}

	private static function build_uri($uri)
	{
		// If external link
		if (preg_match('#^(\w+:)?//#i', $uri) === 1)
			return $uri;
		
		$base_url = Config::get('base_url');
		if ($uri[0] != '/' && $base_url[strlen($base_url) - 1] != '/')
				$base_url .= '/';

		return $base_url.$uri;
	}

	private static function build_link($uri)
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

