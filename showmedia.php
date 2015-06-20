<?php

include('autoload.php');

use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;

class_alias('PhangoApp\PhaRouter\Controller', 'ControllerSwitchClass');
class_alias('PhangoApp\PhaRouter\Routes', 'Routes');
class_alias('PhangoApp\PhaModels\Webmodel', 'Webmodel');
class_alias('PhangoApp\PhaView\View', 'View');
class_alias('PhangoApp\PhaUtils\Utils', 'Utils');
class_alias('PhangoApp\PhaI18n\I18n', 'I18n');

include('libraries/phangovar.php');
include('config.php');

View::$root_path=PhangoVar::$base_path;

//Define the views folders based on app installed.

View::$media_env=View::$folder_env;

foreach(Routes::$apps as $app)
{

	View::$media_env[]='modules/'.$app;
	
}

$view=new View();

$view->load_media_file($_SERVER['REQUEST_URI']);

?>