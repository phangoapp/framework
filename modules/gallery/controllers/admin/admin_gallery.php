<?php

Utils::load_libraries('admin/generate_admin_class');
Utils::load_libraries('forms/selectmodelform');
Webmodel::load_model('gallery');

function GalleryAdmin()
{

	settype($_GET['op'], 'integer');
	
	switch($_GET['op'])
	{
	
		default:
		
			$admin=new GenerateAdminClass('cat_gallery');
			
			$admin->arr_fields=array('name');
			
			$admin->url_options=set_admin_link('gallery', array('op' => 0));
			
			$admin->show();
		
		break;
		
		case 1:
		
			//All images
			
			settype($_GET['category'], 'integer');
			
			Webmodel::$model['image_gallery']->create_form();
			
			Webmodel::$model['image_gallery']->forms['category']->form='SelectModelForm';
			
			Webmodel::$model['image_gallery']->forms['category']->set_parameters_form(array('', $_GET['category'], 'cat_gallery', 'name', $where=''));
			
			Webmodel::$model['image_gallery']->set_enctype_binary();
			
			$admin=new GenerateAdminClass('image_gallery');
			
			$admin->arr_fields=array('image');
			
			$admin->arr_fields_edit=array('image', 'category');
			
			$admin->url_options=set_admin_link('gallery', array('op' => 1));
			
			$admin->show();
		
		break;
	
	}
	
	$extra_data=array();
	
	$extra_data['extra_url']['gallery']['name_module'][]=I18n::lang('gallery', 'categories', 'Image categories');
	$extra_data['extra_url']['gallery']['url_module'][]=set_admin_link('gallery', array('op' => 0));
	
	$extra_data['extra_url']['gallery']['name_module'][]=I18n::lang('gallery', 'images', 'All Images');
	$extra_data['extra_url']['gallery']['url_module'][]=set_admin_link('gallery', array('op' => 1));
	
	return $extra_data;

}

?>