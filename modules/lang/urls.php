<?php

	PhangoVar::$urls['lang']['index']=array('pattern' => '/^lang\/([a-z]{0,2}-[A-Z]{0,2})$/', 'url' => '/lang', 'module' => 'lang', 'controller' => 'index', 'action' => 'index', 'parameters' => array('$1' => 'string'));

?>