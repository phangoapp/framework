<?php

Utils::load_libraries('admin/generate_admin_class');
Utils::load_libraries('forms/selectmodelform');
Utils::load_libraries('utilities/menu_barr_hierarchy');
Webmodel::load_model('gallery');
I18n::load_lang('gallery');

function GalleryAdmin()
{

	settype($_GET['op'], 'integer');
	
	switch($_GET['op'])
	{
	
		default:
			
			Webmodel::$model['cat_gallery']->label=I18n::lang('gallery', 'gallery', 'Gallery');
		
			$admin=new GenerateAdminClass('cat_gallery');
			
			$admin->arr_fields=array('name');
			
			$admin->url_options=set_admin_link('gallery', array('op' => 0));
			
			$admin->options_func='GalleryOptionsListModel';
			
			$admin->show();
		
		break;
		
		case 1:
		
			//All images
			
			settype($_GET['category'], 'integer');
			
			$where_sql='';
			
			$arr_menu[0]=array( I18n::lang('gallery', 'gallery_home', 'Gallery Home'), set_admin_link('gallery', array('op' => 0) ) );
			
			$arr_menu[1]=array(I18n::lang('gallery', 'gallery', 'Gallery'), set_admin_link('gallery', array('op' => 1, 'category' => $_GET['category']) ) );
			
			if($_GET['category']>0)
			{
				
				$where_sql='where category=\''.$_GET['category'].'\'';
				
				$arr_cat=Webmodel::$model['cat_gallery']->select_a_row($_GET['category']);
				
				$arr_menu[1][0].=': '.$arr_cat['name'];
				
			}
			
			//menu_barr_hierarchy($arr_menu, $name_get, $yes_last_link=0, $arr_final_menu=array(), $return_arr_menu=0)
			//$arr_menu[]=array(0 => text_menu, 1 => $arr_menu2)
			
			
			
			
			echo menu_barr_hierarchy($arr_menu, 'op', $yes_last_link=0, $arr_final_menu=array(), $return_arr_menu=0);
			
			Webmodel::$model['image_gallery']->label=I18n::lang('gallery', 'image', 'Image');
			
			Webmodel::$model['image_gallery']->create_form();
			
			Webmodel::$model['image_gallery']->forms['category']->form='SelectModelForm';
			
			Webmodel::$model['image_gallery']->forms['category']->set_parameters_form(array($_GET['category'], $_GET['category'], 'cat_gallery', 'name', ''));
			
			Webmodel::$model['image_gallery']->set_enctype_binary();
			
			$admin=new GenerateAdminClass('image_gallery');
			
			$admin->arr_fields=array('image');
			
			$admin->arr_fields_edit=array('image', 'category');
			
			$admin->url_options=set_admin_link('gallery', array('op' => 1, 'category' => $_GET['category']));
			
			$admin->where_sql=$where_sql;
			
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

function GalleryOptionsListModel($url_options, $model_name, $id)
{

	$arr_options=BasicOptionsListModel($url_options, $model_name, $id);
	
	$arr_options[]='<a href="'.set_admin_link('gallery', array('op' => 1, 'category' => $id)).'">'.I18n::lang('gallery', 'image_gallery', 'Image gallery').'</a>';
	
	return $arr_options;

}

?>