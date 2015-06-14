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
* Old function used for create parent listings based on a model. You now can use recursive_list_ng for the same thing
* @deprecated 
*/

function recursive_list($model_name, $idfather, $url_cat, $arr_fields, $arr_perm=array(), $sql_father='')
{

	$arr_list_father=array();
	$arr_cat=array();

	$query=Webmodel::$model[$model_name]->select($sql_father, array(Webmodel::$model[$model_name]->idmodel, $arr_fields['name_field'], $arr_fields['father_field']));

	while(list($idcat, $title, $idfather)=Webmodel::$model[$model_name]->fetch_row($query))
	{

		settype($arr_list_father[$idfather], 'array');
	
		$arr_list_father[$idfather][]=$idcat;
		$arr_cat[$idcat]=$title;

	}
	
	echo View::load_view(array($model_name, $arr_cat, $arr_list_father, $idfather, $url_cat, $arr_perm), 'common/utilities/parentlist');

}

/**
* Function for make a recursive array for use in listings.
*/

function recursive_select($arr_father, $arr_cat, $arr_list_father, $idfather, $separator='')
{

	$separator.=$separator;

	foreach($arr_list_father[$idfather] as $idcat)
	{
		
		$arr_father[]=$separator.$arr_cat[$idcat];
		$arr_father[]=$idcat;
		if(isset($arr_list_father[$idcat]))
		{
			$arr_father=recursive_select($arr_father, $arr_cat, $arr_list_father, $idcat, $separator.'--');
		}

	}
	
	return $arr_father;

}

/**
* Function for obtain an array with father and children from sql statement
*/

function obtain_parent_list($model_name, $title_field, $parent_field, $sql_father='')
{

	$arr_list_father=array();
	$arr_cat=array();
	//$sql_father.=' order by '.$parent_field.' ASC';
	
	$query=Webmodel::$model[$model_name]->select($sql_father, array(Webmodel::$model[$model_name]->idmodel, $title_field, $parent_field));

	while(list($idcat, $title, $idfather)=Webmodel::$model[$model_name]->fetch_row($query))
	{
		settype($arr_list_father[$idfather], 'array');
	
		$arr_list_father[$idfather][]=$idcat;
	
		$title=Webmodel::$model[$model_name]->components[$title_field]->show_formatted($title);

		$arr_cat[$idcat]=$title;

	}

	return array($arr_list_father, $arr_cat);

}

/**
* Function for create a list with fathers and childrens ordered
*/

function recursive_list_ng($model_name, $idfather, $name_field, $parent_field, $url_base, $id_ul='menu', $class_ul='menu', $name_ul='', $parent_list_view='common/utilities/parentlistng')
{
	
	$arr_list_father=array();
	
	$query=Webmodel::$model[$model_name]->select('', array(Webmodel::$model[$model_name]->idmodel, $name_field, $parent_field), 1);

	while(list($idmodel, $name, $parent)=Webmodel::$model[$model_name]->fetch_row($query))
	{
	
		if($idmodel==$idfather)
		{
		
			$arr_list_father[$parent][$idmodel]=Webmodel::$model[$model_name]->components[$name_field]->show_formatted($name);
		
		}
		else
		{
		
			$arr_list_father[$parent][$idmodel]='<a href="'.$url_base.''.$idmodel.'">'.Webmodel::$model[$model_name]->components[$name_field]->show_formatted($name).'</a>';
		
		}
	}
	
	
	settype($arr_list_father[$idfather], 'array');
	
	return View::load_view(array($idfather, $arr_list_father, $id_ul, $class_ul, $name_ul), $parent_list_view);
}

?>
