<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraForms
*
*
*/

function SelectModelForm($name, $class, $value, $model_name, $identifier_field, $where='')
{
	
	$model_select=$model_name;
	
	if(strpos($model_name, '|')!==false)
	{
		
		$arr_model_name=explode('|', $model_name);

		$model_select=$arr_model_name[count($arr_model_name)-1];

		$model_name=$arr_model_name[0];
		

	}

	if(!isset(Webmodel::$model[$model_name]))
	{

		Webmodel::load_model($model_name);

	}
	
	$arr_model=array($value, I18n::lang('common', 'no_element_chosen', 'No element chosen'), 0);
	
	$query=Webmodel::$model[$model_select]->select($where, array(Webmodel::$model[$model_select]->idmodel, $identifier_field));

	while($arr_field=Webmodel::$model[$model_select]->fetch_array($query))
	{

		$arr_model[]=Webmodel::$model[$model_select]->components[$identifier_field]->show_formatted($arr_field[ $identifier_field ]);
		$arr_model[]=$arr_field[ Webmodel::$model[ $model_select]->idmodel ];

	}
	
	return SelectForm($name, $class, $arr_model);

}

/**
*
* @package ExtraForms
*
*/

function SelectModelFormSet($post, $value)
{

	return $value;

}

?>