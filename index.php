<?php

include('autoload.php');

use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaModels\ModelForm;
use PhangoApp\PhaView\View;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;

//Start session

session_start();

include('libraries/fields/corefields.php');
include('libraries/forms/coreforms.php');

class_alias('PhangoApp\PhaRouter\Controller', 'ControllerSwitchClass');
class_alias('PhangoApp\PhaRouter\Routes', 'Routes');
class_alias('PhangoApp\PhaModels\Webmodel', 'Webmodel');
class_alias('PhangoApp\PhaModels\ModelForm', 'ModelForm');
class_alias('PhangoApp\PhaView\View', 'View');
class_alias('PhangoApp\PhaUtils\Utils', 'Utils');
class_alias('PhangoApp\PhaI18n\I18n', 'I18n');

include('libraries/phangovar.php');
include('config.php');

//Set i18n base path

I18n::$base_path=PhangoVar::$base_path.'/';
I18n::$modules_path='modules';

I18n::load_lang('common');

//Define the timezone of php

date_default_timezone_set (MY_TIMEZONE);

settype($_GET['begin_page'], 'integer');

PhangoVar::$begin_page=$_GET['begin_page'];
PhangoVar::$base_url=substr(Routes::$root_url, 0, -1);

//Define simple variables in utils

Utils::$textbb_type=PhangoVar::$textbb_type;

//Define the view showmedia file.

View::$php_file=Routes::$root_url.'showmedia.php';

//Define the views folders based on app installed.

foreach(Routes::$apps as $app)
{

	View::$folder_env[]='modules/'.$app.'/views';
	
}

View::$root_path=PhangoVar::$base_path;

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