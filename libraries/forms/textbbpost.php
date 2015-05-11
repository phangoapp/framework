<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraForms\TextAreaBB
*
*
*/

function TextAreaBBPostForm($name="", $class='', $value='')
{

	Utils::load_libraries(array('forms/textareabb'));
		
	 return TextAreaBBForm($name, $class, $value, 'comment');

}

/**
*
* @package ExtraForms
*
*/

function TextAreaBBPostFormSet($post, $value)
{
	
	return $value;

}

?>
