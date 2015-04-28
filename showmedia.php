<?php

include('autoload.php');

use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;

include('config.php');

//Define the views folders based on app installed.

foreach(Routes::$apps as $app)
{

	View::$folder_env[]=$app.'/views';
	
}

$view=new View();

$view->loadMediaFile($_SERVER['REQUEST_URI']);

?>