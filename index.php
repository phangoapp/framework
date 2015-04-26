<?php

include('autoload.php');

use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaView\View;

include('config.php');

//Define the view showmedia file.

View::$php_file=Routes::$root_url.'/showmedia.php';

//See the first element of uri next to index.php.

$route=new Routes();

//Define routes...

$route->addRoutesApps();

$route->response($_SERVER['REQUEST_URI']);

?>