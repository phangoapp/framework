<?php

include('autoload.php');

use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaView\View;
use PhangoApp\Framework\Config\PhangoVar;

include('config.php');

//Start session

session_start();
		
if(!isset($_SESSION['csrf_token']))
{

	$_SESSION['csrf_token']=Utils::get_token();

}

//Define the view showmedia file.

View::$php_file=Routes::$root_url.'/showmedia.php';

//Define the views folders based on app installed.

foreach(Routes::$apps as $app)
{

	View::$folder_env[]='modules/'.$app.'/views';
	
}

//Define model and controllers paths

Webmodel::$model_path='./modules/';

//See the first element of uri next to index.php.

$route=new Routes();

Routes::$root_path=Routes::$root_path.'/modules';

//Define routes...

$route->addRoutesApps();

$route->response($_SERVER['REQUEST_URI']);

?>