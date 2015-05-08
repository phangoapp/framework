<?php

function AdminIndexAdmin()
{
	
	echo View::loadView(array('title' => I18n::lang('admin', 'welcome_to_admin', 'Welcome to admin'), 'content' => 
	I18n::lang('admin', 'welcome_text', 'Welcome text')), 'content');

}

?>