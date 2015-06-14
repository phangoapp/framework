<?php

class_alias('PhangoApp\PhaModels\WhereSql', 'WhereSql');

Webmodel::load_model('admin');
I18n::load_lang('users');
Utils::load_libraries(array('admin/generate_admin_class'));

function AusersAdmin()
{


	settype($_GET['op'], 'integer');
	
	Webmodel::$model['user_admin']->label=I18n::$lang['ausers_admin']['ausers_admin_name'];
	Webmodel::$model['user_admin']->components['username']->label=I18n::lang('users', 'username', 'Username');
	Webmodel::$model['user_admin']->components['privileges_user']->label=I18n::lang('users', 'privileges_user', 'Privileges');
	
	switch($_GET['op'])
	{
	
		default:
			
			Webmodel::$model['user_admin']->create_form();
			
			Webmodel::$model['user_admin']->forms['privileges_user']->parameters=array('privileges_user', '', ChoiceAdminField::$arr_options_select);
			
			Webmodel::$model['user_admin']->insert_after_field_form('password', 'repeat_password', new ModelForm('repeat_password', 'repeat_password', 'PasswordForm',  I18n::lang('users', 'repeat_password', 'Repeat password'), new PasswordField(), $required=1, $parameters=''));
			
			$admin=new GenerateAdminClass('user_admin');
			
			$admin->arr_fields=array('username', 'privileges_user');
			
			$admin->arr_fields_edit=array('username', 'password', 'repeat_password', 'email', 'privileges_user');
			
			$admin->set_url_post(set_admin_link('ausers', array('op' => 0)));
			
			$admin->options_func='UserOptionsListModel';
			
			$admin->show();
		
		break;
		
		case 1:
		
			Utils::load_libraries(array('forms/selectmodelform'));
			
			settype($_GET['IdUser_admin'], 'integer');
			
			$arr_user=Webmodel::$model['user_admin']->select_a_row($_GET['IdUser_admin'], array('IdUser_admin', 'username'));
			
			settype($arr_user['IdUser_admin'], 'integer');
			
			if($arr_user['IdUser_admin']>0)
			{
			
				echo '<h3>'.I18n::lang('admin', 'add_moderator_to_module', 'Add moderator to module').': <strong>'.$arr_user['username'].'</strong></h3>';
				
				$arr_fields=array('idmodule');
				$arr_fields_edit=array();
				
				//$url_options=make_fancy_url( $base_url, 'admin', 'index', 'edit_modules', $arr_data=array('IdModule' => $_GET['IdModule'], 'op' => 3, 'idmodule' => $_GET['idmodule']) );
				
				//$model['user_admin']->select
				
				Webmodel::$model['moderators_module']->create_form();
				
				Webmodel::$model['moderators_module']->forms['moderator']->form='HiddenForm';
				Webmodel::$model['moderators_module']->forms['moderator']->set_param_value_form($arr_user['IdUser_admin']);
				
				Webmodel::$model['moderators_module']->forms['moderator']->label=I18n::lang('admin', 'moderator', 'Moderator');
				
				Webmodel::$model['moderators_module']->forms['idmodule']->form='SelectForm';
				
				$arr_mod=array('');
				
				foreach(ModuleAdmin::$arr_modules_admin as $module => $arr_module)
				{
				
					$arr_mod[]=$module;
					$arr_mod[]=$module;
				
				}
				
				Webmodel::$model['moderators_module']->forms['idmodule']->set_parameter_value($arr_mod);
				
				$admin=new GenerateAdminClass('moderators_module');
			
				$admin->arr_fields=$arr_fields;
				
				$admin->set_url_post(set_admin_link('ausers', array('IdUser_admin' => $arr_user['IdUser_admin'], 'op' => 1)));
				
				//$admin->no_search=1;
				
				$arr_conditions['union1_AND']['AND'][]=array('moderator' => array('=', $arr_user['IdUser_admin']));
				
				$where_class=new WhereSql('moderators_module', $arr_conditions);
				
				$admin->where_sql=$where_class->get_where_sql();
				
				//$admin->where_sql=Webmodel::$model['moderators_module']->where($arr_where);
		
				//'where moderator='.$arr_user['IdUser_admin'];
				
				$admin->show();
			
				//generate_admin_model_ng('moderators_module', $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='where idmodule='.$_GET['idmodule'], $arr_fields_form=array(), $type_list='Basic');
				
				echo '<p><a href="'.set_admin_link('ausers', array()).'">'.I18n::lang('admin', 'go_back_home', 'go back to home').'</a></p>';
				
			}
		
		break;
		
	}

}

function UserOptionsListModel($url_options, $model_name, $id, $arr_row)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id, $arr_row);
	
	if($arr_row['privileges_user']==1)
	{
		
		$arr_options[]='<a href="'.set_admin_link('ausers', array('op' => 1, 'IdUser_admin' => $id)).'">'.I18n::lang('admin', 'change_user_modules', 'Change user modules').'</a>';
	
	}
	
	return $arr_options;

}

?>