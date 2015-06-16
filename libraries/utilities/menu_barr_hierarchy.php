<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraUtils/Menus
*
*
*/

//First element -> second element -> third element -> fourth element 
//                                                 -> fourth element 2 -> five element (name_get) 
// 'link0' => array('link1' => link1, 'link2'=> link2, 'link3' => link3)
// 'link1' => array('link4' => link4)
/*
function menu_barr_hierarchy($name_get, $value_get, $arr_menu, $first_father)
{

	settype($_GET[$name_get], 'integer');

	
	
	$arr_father=array();
	$arr_final_menu=array();
	$father=$first_father;
	
	//Make the parent tree
  
	$arr_father=obtain_fathers($arr_father, $arr_menu, $father);
 
	print_r($arr_father);
 
}

function obtain_fathers($arr_father, $arr_menu, $father)
{

	foreach($arr_menu as $key_menu => $menu)
	{
	
		$arr_father[$key_menu]=$father;
		
		$father=$key_menu;
		
		$arr_father=obtain_fathers($arr_father, $menu, $father);
	
	}
	
	return $arr_father;

}
*/

// $arr_menu[]=array(0 => text_menu, 1 => $arr_menu2)

// $arr_menu[1]=array(2 => menu3, 3 => menu4)

View::load_libraries_views('common/utilities/menu_barr_hierarchy', $func_views=array('linkhierarchy', 'nolinkhierarchy', 'menuhierarchy'));

function menu_barr_hierarchy($arr_menu, $name_get, $yes_last_link=0, $arr_final_menu=array(), $return_arr_menu=0)
{

	$_GET[$name_get]=Utils::slugify($_GET[$name_get]);

	foreach($arr_menu as $key_menu => $menu)
	{
	
		if(gettype($menu[1])!='array')
		{
			if($_GET[$name_get]==$key_menu && $yes_last_link==0)
			{
				
				$arr_final_menu[]=View::load_view(array($menu[0]), 'nolinkhierarchy');
			
				break;
			
			}
			else
			{
			
				$arr_final_menu[]=View::load_view(array($menu[1], $menu[0]), 'linkhierarchy');
			
			}
			
		}
		else
		{
		
			$arr_final_menu=menu_barr_hierarchy($arr_menu[1], $name_get, $yes_last_link, $arr_final_menu, 1);
		
		}
	
	}
	
	if($return_arr_menu==0)
	{
	
		return View::load_view(array($arr_final_menu), 'menuhierarchy');
		
	}
	else
	{
	
		return $arr_final_menu;
	
	}

}
/*
function menu_barr_hierarchy_urls($actual_url, $arr_links, $last_link=1)
{

	load_libraries_views('common/utilities/menu_barr_hierarchy', $func_views=array('linkhierarchy', 'nolinkhierarchy', 'menuhierarchy'));

	//Need the actual url.
	
	$url=PhangoVar::$urls[$actual_url[0]][$actual_url[1]];
	
	settype($url['label'], 'string');
	
	if($last_link==0)
	{
		
		$arr_get=array();
		
		$arr_extra_get=array();
	
		if(isset($user['callback_parameters']))
		{
		
			$func_callback=$user['callback_parameters'];
			
			list($arr_get, $arr_extra_get)=$func_callback();
		
		}
	
		$real_url=make_fancy_url(PhangoVar::$base_url, $actual_url[0], $actual_url[1], $arr_get, $arr_extra_get);
	
		$arr_links[]=load_view(array($real_url, $url['label']), 'linkhierarchy');
		
	}
	else
	{
	
		$arr_links[]=load_view(array($menu[0]), 'nolinkhierarchy');
	
	}
	
	if(isset($url['children_of']))
	{
	
		$arr_links=menu_barr_hierarchy_urls($url['children_of'], $arr_links, 0);
	
	}
	
	return $arr_links;
	

}*/
/*
function show_menu_barr()
{

	$arr_links=menu_barr_hierarchy_urls(PhangoVar::$actual_url, $arr_links=array());

	$arr_links=array_reverse($arr_links);

}*/

//$arr_menu[0]=array('url_folder' => 'url_folder', 'url_ident' => 'url_ident')



//$arr_menu[0]=array('module' => 'module', 'controller' => 'controller', 'text' => 'text', 'name_op' => , 'params' => array())

//$arr_menu[1]=array(0 => array('module' => 'module', 'controller' => 'controller', 'name_op' => name_op, 'text' => 'text', 'params' => array()), 1 => array('module' => 'module', 'controller' => 'controller', 'op' => op, 'text' => 'text', 'params' => array()) );



//With the hope that write a function that create a menu_barr_hierarchy automatically
/*
function menu_barr_hierarchy_control($arr_menus)
{

	load_libraries_views('common/utilities/menu_barr_hierarchy', $func_views=array('linkhierarchy', 'nolinkhierarchy', 'menuhierarchy'));

	//Begin process
	
	$arr_final_menu=array();
	
	foreach($arr_menus as $pos_menu => $arr_menu)
	{
	
		if(!isset($arr_menu[0]))
		{
		
			list($arr_final_menu, $return_break)=check_arr_menu($arr_menu, $arr_final_menu);
			
			if($return_break==1)
			{
			
				break;
			
			}
			
		}
		else
		{
		
			foreach($arr_menu as $menu)
			{
			
				list($arr_final_menu, $return_break)=check_arr_menu($arr_menu, $arr_final_menu);
				
				if($return_break==1)
				{
				
					break;
				
				}
			
			}
		
		}
		
		if($return_break==1)
		{
		
			break;
		
		}
		
		
		
	}
	
	return load_view(array($arr_final_menu), 'menuhierarchy'); //implode(' &gt;&gt; ', $arr_final_menu);

}

function check_arr_menu($arr_menu, $arr_final_menu)
{

	$return_break=0;

	if($arr_menu['module']==PhangoVar::$script_module && $arr_menu['controller']==PhangoVar::$script_controller && $arr_menu['action']==PhangoVar::$script_action && $arr_menu['params'][$arr_menu['name_op']]==$_GET[$arr_menu['name_op']])
	{
	
		$arr_final_menu[]=$arr_final_menu[]=load_view(array($arr_menu['text']), 'nolinkhierarchy'); //$arr_menu['text'];
		$return_break=1;
	
	}
	else
	{
	
		$arr_final_menu[]=load_view(array(make_fancy_url(PhangoVar::$base_url, $arr_menu['module'], $arr_menu['controller'], array($arr_menu['action']), $arr_menu['params']), $arr_menu['text']), 'linkhierarchy');//'<a href="'.make_fancy_url(PhangoVar::$base_url, $arr_menu['module'], $arr_menu['controller'], array($arr_menu['action']), $arr_menu['params']).'">'.$arr_menu['text'].'</a>';
	
	}
	
	return array($arr_final_menu, $return_break);

}
*/
?>