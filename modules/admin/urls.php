<?php

PhangoVar::$urls[ADMIN_FOLDER]['index']=array('pattern' => '/^'.ADMIN_FOLDER.'$/', 'url' => '/'.ADMIN_FOLDER, 'module' => 'admin', 'controller' => 'admin', 'action' => 'index', 'parameters' => array());

PhangoVar::$urls[ADMIN_FOLDER]['login']=array('pattern' => '/^'.ADMIN_FOLDER.'\/login$/', 'url' => '/'.ADMIN_FOLDER.'/login', 'module' => 'admin', 'controller' => 'login', 'action' => 'index', 'parameters' => array());

PhangoVar::$urls[ADMIN_FOLDER]['logout']=array('pattern' => '/^'.ADMIN_FOLDER.'\/logout$/', 'url' => '/'.ADMIN_FOLDER.'/logout', 'module' => 'admin', 'controller' => 'login', 'action' => 'logout', 'parameters' => array());

PhangoVar::$urls[ADMIN_FOLDER]['login_check']=array('pattern' => '/^'.ADMIN_FOLDER.'\/login_check$/', 'url' => '/'.ADMIN_FOLDER.'/login_check', 'module' => 'admin', 'controller' => 'login', 'action' => 'login', 'parameters' => array());

PhangoVar::$urls[ADMIN_FOLDER]['register']=array('pattern' => '/^'.ADMIN_FOLDER.'\/register$/', 'url' => '/'.ADMIN_FOLDER.'/register', 'module' => 'admin', 'controller' => 'login', 'action' => 'register', 'parameters' => array());

PhangoVar::$urls[ADMIN_FOLDER]['recovery_password']=array('pattern' => '/^'.ADMIN_FOLDER.'\/recovery_password$/', 'url' => '/'.ADMIN_FOLDER.'/recovery_password', 'module' => 'admin', 'controller' => 'login', 'action' => 'recovery', 'parameters' => array());

PhangoVar::$urls[ADMIN_FOLDER]['recovery_password_send']=array('pattern' => '/^'.ADMIN_FOLDER.'\/recovery_password_send$/', 'url' => '/'.ADMIN_FOLDER.'/recovery_password_send', 'module' => 'admin', 'controller' => 'login', 'action' => 'recovery_send', 'parameters' => array());

PhangoVar::$urls[ADMIN_FOLDER]['register_insert']=array('pattern' => '/^'.ADMIN_FOLDER.'\/register\/([0-9])$/', 'url' => '/'.ADMIN_FOLDER.'/register', 'module' => 'admin', 'controller' => 'login', 'action' => 'register', 'parameters' => array('$1' => 'integer'));

PhangoVar::$urls[ADMIN_FOLDER]['module']=array('pattern' => '/^'.ADMIN_FOLDER.'\/(\w+)$/', 'url' => '/'.ADMIN_FOLDER, 'module' => 'admin', 'controller' => 'admin', 'action' => 'index', 'parameters' => array('$1' => 'string'));

PhangoVar::$url_module_requires[]='lang';
PhangoVar::$url_module_requires[]='media';

?>