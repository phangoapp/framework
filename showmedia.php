<?php

include('autoload.php');

use PhangoApp\PhaView\View;

$view=new View();

$view->loadMediaFile($_SERVER['REQUEST_URI']);

?>