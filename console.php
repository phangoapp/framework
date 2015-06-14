<?php

//Loading libraries with includes, don't need more sofisticated methods...

ini_set('html_errors', 0);

include('autoload.php');

use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaModels\ModelForm;
use PhangoApp\PhaView\View;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;

/*
include('vendor/phangoapp/pharouter/src/Routes.php');
include('vendor/phangoapp/pharouter/src/Controller.php');
include('vendor/phangoapp/phamodels/src/Webmodel.php');
include('vendor/phangoapp/phamodels/src/ModelForm.php');
include('vendor/phangoapp/phaview/src/View.php');
include('vendor/phangoapp/phai18n/src/I18n.php');
include('vendor/phangoapp/phautils/src/Utils.php');*/

class_alias('PhangoApp\PhaRouter\Controller', 'ControllerSwitchClass');
class_alias('PhangoApp\PhaRouter\Routes', 'Routes');
class_alias('PhangoApp\PhaModels\Webmodel', 'Webmodel');
class_alias('PhangoApp\PhaModels\ModelForm', 'ModelForm');
class_alias('PhangoApp\PhaView\View', 'View');
class_alias('PhangoApp\PhaUtils\Utils', 'Utils');
class_alias('PhangoApp\PhaI18n\I18n', 'I18n');

include('libraries/phangovar.php');
include('config.php');

include('libraries/fields/corefields.php');
include('libraries/forms/coreforms.php');

I18n::load_lang('common');

Routes::$base_path=getcwd();

//Load extra libraries

/*Utils::load_libraries(array('fields/corefields'));
Utils::load_libraries(array('forms/coreforms'));*/

date_default_timezone_set (MY_TIMEZONE);

$utility_console=1;

//load_lang('common', 'user');

$model=array();

//Check arguments

define('OPTS', 'm:c:');

$longopts=array();

$options = getopt(OPTS, $longopts);

$climate = new League\CLImate\CLImate;

if(!isset($options['m']) && !isset($options['c']))
{

	//die("Use: php console.php -m=module -c=console_controller [more arguments for daemon]\n");
	
	$climate->white()->backgroundBlack()->out("Use: php console.php -m=module -c=console_controller [more arguments for daemon]");
	die;
}

$module=@Utils::form_text(basename($options['m']));

$console_controller=@Utils::form_text(basename($options['c']));

//Include console_controller

$controller='./modules/'.$module.'/console/controller_'.$console_controller.'.php';

if(file_exists($controller))
{

	include($controller);
	
	$script_base_controller=$module;

	$function_console=$console_controller.'Console';

	if( function_exists($function_console) )
	{
	
		$function_console();
	
	}
	else
	{
		$climate->white()->backgroundRed()->out("Error: Don't exists the function with name ".$function_console." in ".$controller."...");
		die();
	
	}

}
else
{

	$climate->white()->backgroundRed()->out("Error: Don't exists the controller ".$controller." for console statement...");
	die();

}

/**
*  Function for obtain options from console. The opts use the format of getopt function
*/

function get_opts_console($my_opts, $arr_opts=array())
{

	return getopt(OPTS.$my_opts, $arr_opts);

}

?>