<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package CoreAdmin/Links
*
*
*/

function set_admin_link($text_admin, $parameters)
{
	
	/*if(!isset($parameters['IdModule']))
	{
		$parameters['IdModule']=$_GET['IdModule'];
	}*/
	
	//base_url/ADMIN_FOLDER/module_name/other_parameters, other parameters is moving to function admin.
	
	return Routes::make_module_url(ADMIN_FOLDER, 'index', 'index', array($text_admin), $get=$parameters);
	
	// make_fancy_url(PhangoVar::$base_url, ADMIN_FOLDER, 'module', array($text_admin), $parameters);

}

?>
