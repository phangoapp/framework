<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraForms
*
*
*/

function SelectModelFormByOrder($name, $class, $value, $model_name, $identifier_field, $field_parent, $where='', $null_yes=1)
{

	//Need here same thing that selectmodelform...

	if(!isset(Webmodel::$model[$model_name]))
	{

		load_model($model_name);

	}
	
	$arr_model=array($value);
	
	if($null_yes==1)
	{
	
		$arr_model[]=I18n::lang('common', 'no_element_chosen', 'No element chosen');
		$arr_model[]=0;
	
	}
	
	$arr_elements=array();
	
	$query=Webmodel::$model[$model_name]->select($where, array(Webmodel::$model[$model_name]->idmodel, $identifier_field, $field_parent));

	while($arr_field=Webmodel::$model[$model_name]->fetch_array($query))
	{
		
		$idparent=$arr_field[$field_parent];

		$element_model=Webmodel::$model[$model_name]->components[$identifier_field]->show_formatted($arr_field[ $identifier_field ]);

		$arr_elements[$idparent][]=array($element_model, $arr_field[ Webmodel::$model[ $model_name]->idmodel ]);

	}
	
	$arr_model=recursive_list_select($arr_elements, 0, $arr_model, '');
	

	return SelectForm($name, $class, $arr_model);

}

/**
*
* @package ExtraForms
*
*/

function recursive_list_select($arr_elements, $element_id, $arr_result, $separator)
{

	$separator.=$separator;
	
	if(isset($arr_elements[$element_id]))
	{

		foreach($arr_elements[$element_id] as $element)
		{
			
			$arr_result[]=$separator.$element[0];
			$arr_result[]=$element[1];
			
			if( isset($arr_elements[$element[1]] ) )
			{

				$arr_result=recursive_list_select($arr_elements, $element[1], $arr_result, $separator.'--');

			}

		}

	}

	return $arr_result;

}

/**
*
* @package ExtraForms
*
*/

function SelectModelFormByOrderSet($post, $value)
{

	return $value;

}

?>