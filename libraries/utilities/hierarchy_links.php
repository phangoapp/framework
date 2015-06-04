<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraUtils/Menus
*
*
*/

/**
* Function for create a menu based on father and children elements
*/

function hierarchy_links($model_name, $parentfield_name, $field_name, $idmodel)
{

	//Get the father and its father, and the father of its father
	
	//Obtain all id and fathers
	
	//Cache system?
	
	$arr_id_father=array(0 => 0);
	$arr_id_name=array(0 => I18n::lang('common', 'home', 'Home'));
	$arr_hierarchy=array();
	
	$query=Webmodel::$model[$model_name]->select('', array(Webmodel::$model[$model_name]->idmodel, $parentfield_name, $field_name), 1);
	
	while(list($id, $father, $name)=Webmodel::$model[$model_name]->fetch_row($query))
	{
		
		$arr_id_father[$id]=$father;
		$arr_id_name[$id]=Webmodel::$model[$model_name]->components[$field_name]->show_formatted($name);
	
	}
	
	$arr_hierarchy=recursive_obtain_father($arr_id_father, $idmodel, $arr_id_name, $arr_hierarchy);
	
	$arr_hierarchy=array_reverse($arr_hierarchy);
	
	return $arr_hierarchy;
	
	//echo load_view(array($arr_hierarchy), 'common/utilities/hierarchy_links');

}

/**
* Internal function used for hierarchy_links for order elements recursively
*/

function recursive_obtain_father($arr_id_father, $id, $arr_id_name, $arr_hierarchy)
{

	$arr_hierarchy[]=array('name' => $arr_id_name[$id], 'id' => $id);

	if($id!=0)
	{
	
		$arr_hierarchy=recursive_obtain_father($arr_id_father, $arr_id_father[$id], $arr_id_name, $arr_hierarchy);
	
	}
	
	return $arr_hierarchy;

}
/*
function hierarchy_links_ctl($arr_controller)
{

	

}
*/

?>