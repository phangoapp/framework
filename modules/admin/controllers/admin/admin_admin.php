<?php

function AdminIndexAdmin()
{
	
	echo load_view(array('title' => PhangoVar::$l_['admin']->lang('welcome_to_admin', 'Welcome to admin'), 'content' => PhangoVar::$l_['admin']->lang('welcome_text', 'Welcome text')), 'content');

}

?>