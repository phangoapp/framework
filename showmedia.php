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

include('config.php');

//Define the views folders based on app installed.

foreach(Routes::$apps as $app)
{

	View::$folder_env[]='modules/'.$app.'/views';
	
}

$view=new View();

$view->loadMediaFile($_SERVER['REQUEST_URI']);

?>