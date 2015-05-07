<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraForms\TextAreaBB
*
*
*/

function TextAreaBBForm($name="", $class='', $value='', $profile='all')
{

	ob_start();
	
	?>
	<?php

	$paragraph="\n<p></p>";

	if(PhangoVar::$textbb_type=='')
	{

		$paragraph='';

	}

	echo TextAreaForm($name, $class, $value.$paragraph)."<p />";

	if(PhangoVar::$textbb_type!='')
	{

		PhangoVar::$textbb_type=basename(str_replace('.php', '', PhangoVar::$textbb_type));

		load_libraries(array('textbb/'.PhangoVar::$textbb_type.'/'.PhangoVar::$textbb_type.'_'.$profile));
		
		//Load script profile for jscript, is a function called load_profile

		//load_libraries(array('textbb/profiles/'.$config_data['textbb_type'].'_'.$profile.'.php'));
		
		//Load editor
		
		$func_load_script='load_jscript_editor_'.PhangoVar::$textbb_type.'_'.$profile;
		
		$func_load_script($name, $value, $profile);

	}

	?>

	<?php
	
	//Inside decide javascript editor type

	

	$postform=ob_get_contents();

	ob_end_clean();

	return '<br clear="left" />'.$postform;


}

/**
*
* @package ExtraForms
*
*/

function TextAreaBBFormSet($post, $value)
{
	
	return $value;

}

?>
