<?php

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

include('libraries/fields/corefields.php');
include('libraries/forms/coreforms.php');

class_alias('PhangoApp\PhaRouter\Controller', 'ControllerSwitchClass');
class_alias('PhangoApp\PhaRouter\Routes', 'Routes');
class_alias('PhangoApp\PhaModels\Webmodel', 'Webmodel');
class_alias('PhangoApp\PhaModels\ModelForm', 'ModelForm');
class_alias('PhangoApp\PhaView\View', 'View');
class_alias('PhangoApp\PhaUtils\Utils', 'Utils');
class_alias('PhangoApp\PhaI18n\I18n', 'I18n');

include('config.php');

//Start session

session_start();

settype($_GET['begin_page'], 'integer');

Utils::$begin_page=$_GET['begin_page'];

//Define the view showmedia file.

View::$php_file=Routes::$root_url.'showmedia.php';

//Define the views folders based on app installed.

foreach(Routes::$apps as $app)
{

	View::$folder_env[]='modules/'.$app.'/views';
	
}

//Define model and controllers paths

Webmodel::$model_path='./modules/';

//See the first element of uri next to index.php.

$route=new Routes();

$route->prefix_controller='Switch';

Routes::$base_path=getcwd();

Routes::$root_path=Routes::$root_path.'/modules';

//Define routes...

$route->addRoutesApps();

$route->response($_SERVER['REQUEST_URI']);

?>