<?php

//use PhangoApp\Framework\StdModels\UserPhangoModel;

Utils::load_libraries('models/userphangomodel');
Utils::load_libraries('fields/passwordfield');

I18n::load_lang('admin');

Webmodel::$model['user_admin']=new UserPhangoModel('user_admin');

Webmodel::$model['user_admin']->register('username', 'CharField', array(25), 1);

Webmodel::$model['user_admin']->register('password', 'PasswordField', array(255), 1);

Webmodel::$model['user_admin']->register('email', 'EmailField', array(255), 1);

Webmodel::$model['user_admin']->register('privileges_user', 'ChoiceAdminField', array($size=11, $type='integer', $arr_values=array(0, 1), $default_value=1));

Webmodel::$model['user_admin']->register('token_client', 'CharField', array(255));

Webmodel::$model['user_admin']->register('token_recovery', 'CharField', array(255));

Webmodel::$model['user_admin']->username='email';

Webmodel::$model['login_tried_admin']=new Webmodel('login_tried_admin');

Webmodel::$model['login_tried_admin']->register('ip', 'CharField', array(255));

Webmodel::$model['login_tried_admin']->register('num_tried', 'IntegerField', array(11));
Webmodel::$model['login_tried_admin']->register('time', 'IntegerField', array(11));

Webmodel::$model['moderators_module']=new Webmodel('moderators_module');
Webmodel::$model['moderators_module']->register('moderator', 'ForeignKeyField', array(Webmodel::$model['user_admin']), 1);
Webmodel::$model['moderators_module']->components['moderator']->name_field_to_field='username';

Webmodel::$model['moderators_module']->components['moderator']->fields_related_model=array('username');

Webmodel::$model['moderators_module']->register('idmodule', 'CharField', array(255), 1);

Webmodel::$model['moderators_module']->components['idmodule']->unique=1;

class ChoiceAdminField extends ChoiceField {

	static public $arr_options_formated;
	
	static public $arr_options_select;

	public function show_formatted($value)
	{
	
		return ChoiceAdminField::$arr_options_formated[$value];
	
	}

}

ChoiceAdminField::$arr_options_formated=array(0 => I18n::lang('admin', 'administrator', 'Administrator'), 1 => I18n::lang('admin', 'moderator', 'Moderator'));

ChoiceAdminField::$arr_options_select=array(1, I18n::lang('admin', 'administrator', 'Administrator'), 0, I18n::lang('admin', 'moderator', 'Moderator'), 1);

class ModuleAdmin {

	//Example: 'admin' => array('admin', 'admin')

	static public $arr_modules_admin=array();

}

?>