<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraAdmin
*
* Simple functions and classes for use on admin system
*/

Utils::load_libraries(array('table_config', 'pages'));

function BasicOptionsListModel($url_options, $model_name, $id)
{

	?>
	<script language="javascript">
		function warning()
		{
			if(confirm('<?php echo I18n::lang('common', 'delete_model', 'Delete element'); ?>'))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	</script>
	<?php

	$url_options_edit=Routes::addGetParameters($url_options, array('op_edit' =>1, Webmodel::$model[$model_name]->idmodel => $id));
	$url_options_delete=Routes::addGetParameters($url_options, array('op_edit' =>2, Webmodel::$model[$model_name]->idmodel => $id));

	$arr_options=array('<a href="'.$url_options_edit.'">'.I18n::lang('common', 'edit', 'Edit').'</a>', 
'<a href="'.$url_options_delete.'" onclick="javascript: if(warning()==false) { return false; }">'.I18n::lang('common', 'delete', 'Delete').'</a>');

	return $arr_options;

}


function SearchInField($model_name, $arr_fields_order, $arr_fields_search, $where_sql, $url_options, $yes_id=1, $show_form=1)
{
	
	Utils::load_libraries(array('search_in_field'));

	if(count(Webmodel::$model[$model_name]->forms)==0)
	{

		$arr_error_sql[0]='Do you need create a form for this model';    
		$arr_error_sql[1]='Do you need create a form for this model '.$model_name.' for use SearchInField function';
		ob_end_clean();
		echo View::load_view(array('title' => 'Phango site is down', 'content' => '<p>'.$arr_error_sql[DEBUG].'</p>'), 'common/common');
		die();

	}

	if(!in_array(Webmodel::$model[$model_name]->idmodel, $arr_fields_order) && $yes_id==1)
	{

		array_unshift($arr_fields_order, Webmodel::$model[$model_name]->idmodel);
		array_unshift($arr_fields_search, Webmodel::$model[$model_name]->idmodel);
		
		Webmodel::$model[$model_name]->forms[ Webmodel::$model[$model_name]->idmodel ]->label='#Id.';

	}

	//Set order

	$_GET['order_field']=@form_text($_GET['order_field']);
	$_GET['search_word']=@form_text($_GET['search_word']);
	$_GET['search_field']=@form_text($_GET['search_field']);
	
	$arr_order_select=array();

	if( !in_array($_GET['order_field'], $arr_fields_order) )
	{

		$_GET['order_field']=$arr_fields_order[0]; //Webmodel::$model[$model_name]->idmodel;

	}
	
	if( !in_array($_GET['search_field'], $arr_fields_search) )
	{

		$_GET['search_field']=$arr_fields_search[0]; //Webmodel::$model[$model_name]->idmodel;

	}
	
	//0=DESC
	//1=ASC

	settype($_GET['order_desc'], 'integer');
		
	$arr_order[$_GET['order_desc']]='ASC';

	$arr_order[0]='ASC';
	$arr_order[1]='DESC';

	$arr_order_select[]=$_GET['order_desc'];
	$arr_order_select[]=I18n::lang('common', 'ascent', 'Ascendent');
	$arr_order_select[]=0;
	$arr_order_select[]=I18n::lang('common', 'descent', 'Descendent');
	$arr_order_select[]=1;

	$arr_order_field=array($_GET['order_field']);
	$arr_search_field=array($_GET['search_field']);

	foreach($arr_fields_order as $field_label)
	{

		$arr_order_field[]=Webmodel::$model[$model_name]->forms[$field_label]->label;
		$arr_order_field[]=$field_label;

	}
	
	foreach($arr_fields_search as $field_label)
	{

		$arr_search_field[]=Webmodel::$model[$model_name]->forms[$field_label]->label;
		$arr_search_field[]=$field_label;

	}
	
	if($show_form==1)
	{
		echo View::load_view(array($arr_search_field, $arr_order_field, $arr_order_select, $url_options), 'common/forms/searchform');
	}
	//Query for order

	//Query for search_by
	
	/*list($location, $arr_where_sql)=search_in_field($model_name, array($_GET['search_field']), $_GET['search_word']);
	
	if($location!='')
	{

		$location=$location.' DESC ,';

	}*/
	
	$location='';
	
	$arr_where_sql='';
	
	if(isset(Webmodel::$model[$model_name]->components[$_GET['search_field']]) && $_GET['search_word']!='')
	{
	
		$value_search=Webmodel::$model[$model_name]->components[$_GET['search_field']]->search_field($_GET['search_word']);
		
		if(get_class(Webmodel::$model[$model_name]->components[$_GET['search_field']])!='ForeignKeyField')
		{
		
			$arr_where_sql='`'.$model_name.'`.`'.$_GET['search_field'].'` LIKE \'%'.$value_search.'%\'';
			
		}
		else
		{
		
			$model_related_name=Webmodel::$model[$model_name]->components[$_GET['search_field']]->related_model;
			
			if(Webmodel::$model[$model_name]->components[$_GET['search_field']]->name_field_to_field!='')
			{
			
				$field_related_name=Webmodel::$model[$model_name]->components[$_GET['search_field']]->name_field_to_field;
				
				$arr_where_sql='`'.$model_related_name.'`.`'.$field_related_name.'` LIKE \'%'.$value_search.'%\'';
				
			}
		
		}
	
	}

	if($where_sql=='' && $arr_where_sql!='')
	{
		
		$where_sql='where ';

	}
	else if($where_sql!='' && $arr_where_sql!='')
	{

		$where_sql.=' AND ';

	}
	
	return array($where_sql, $arr_where_sql, $location, $arr_order);

}



function GeneratePositionModel($model_name, $field_name, $field_position, $url, $where='')
{

	settype($_GET['action_field'], 'integer');
	
	$num_order=Webmodel::$model[$model_name]->select_count($where, Webmodel::$model[$model_name]->idmodel );

	if($num_order>0)
	{
	
		switch($_GET['action_field'])
		{
		default:

			ob_start();

			$url_post=Routes::addGetParameters($url, array('action_field' => 1));

			echo '<form method="post" action="'.$url_post.'">';
			set_csrf_key();
			echo '<div class="form">';

			$query=Webmodel::$model[$model_name]->select($where.' order by `'.$field_position.'` ASC', array(Webmodel::$model[$model_name]->idmodel, $field_name, $field_position));

			while(list($id, $name, $position)=webtsys_fetch_row($query))
			{
				$name=Webmodel::$model[$model_name]->components[$field_name]->show_formatted($name);

				echo '<p><label for="'.$field_position.'">'.$name.'</label><input type="text" name="position['.$id.']" value="'.$position.'" size="3"/></p>';

			}
			echo '<input type="submit" value="'.I18n::lang('common', 'send', 'Send').'"/>';
			echo '</div>';
			echo '</form>';
			
			$cont_order=ob_get_contents();

			ob_end_clean();

			echo View::load_view(array(I18n::lang('common', 'order', 'Order'), $cont_order), 'content');

		break;

		case 1:

			$arr_position=$_POST['position'];

			foreach($arr_position as $key => $value)
			{
				
				settype($key, 'integer');
				settype($value, 'integer');
				
				$where='where '.Webmodel::$model[$model_name]->idmodel.'='.$key;

				//Clean required...

				Webmodel::$model[$model_name]->reset_require();
				
				$query=Webmodel::$model[$model_name]->update(array($field_position => $value), $where);
				
			}
			
			ob_end_clean();

			Utils::load_libraries(array('redirect'));
			
			Route::redirect($url);

		break;

		}

	}
	else
	{

		echo '<p>'.I18n::lang('common', 'no_exists_elements_to_order', 'There is no item to order').'</p>';

	}

}

//Class for search, the function is deprecated when need many arguments, is more easy use classes for its.

class SearchInFieldClass {

	public $model_name, $arr_fields_order, $arr_fields_search, $where_sql, $url_options, $yes_id=1, $show_form=1, $lang_asc;

	function __construct($model_name, $arr_fields_order, $arr_fields_search, $where_sql, $url_options, $yes_id=1, $show_form=1)
	{
	
		$this->model_name=$model_name;
		$this->arr_fields_order=$arr_fields_order;
		$this->arr_fields_search=$arr_fields_search;
		$this->where_sql=$where_sql;
		$this->url_options=$url_options;
		$this->yes_id=$yes_id;
		$this->show_form=$show_form;
		$this->lang_asc=I18n::lang('common', 'ascent', 'Ascendent');
		$this->lang_desc=I18n::lang('common', 'descent', 'Descendent');
	
	
	}
	
	public function search()
	{
		
		Utils::load_libraries(array('search_in_field'));

		if(count(Webmodel::$model[$this->model_name]->forms)==0)
		{

			$arr_error_sql[0]='Do you need create a form for this model';    
			$arr_error_sql[1]='Do you need create a form for this model '.$this->model_name.' for use SearchInField function';
			ob_end_clean();
			echo View::load_view(array('title' => 'Phango site is down', 'content' => '<p>'.$arr_error_sql[DEBUG].'</p>'), 'common/common');
			die();

		}

		if(!in_array(Webmodel::$model[$this->model_name]->idmodel, $this->arr_fields_order) && $this->yes_id==1)
		{

			array_unshift($this->arr_fields_order, Webmodel::$model[$this->model_name]->idmodel);
			array_unshift($this->arr_fields_search, Webmodel::$model[$this->model_name]->idmodel);
			
			Webmodel::$model[$this->model_name]->forms[ Webmodel::$model[$this->model_name]->idmodel ]->label='#Id.';

		}

		//Set order
		
		$_GET['order_field']=@Utils::form_text($_GET['order_field']);
		$_GET['search_word']=@Utils::form_text($_GET['search_word']);
		$_GET['search_field']=@Utils::form_text($_GET['search_field']);
		
		$arr_order_select=array();

		if( !in_array($_GET['order_field'], $this->arr_fields_order) )
		{

			$_GET['order_field']=$this->arr_fields_order[0]; //Webmodel::$model[$model_name]->idmodel;

		}
		
		if( !in_array($_GET['search_field'], $this->arr_fields_search) )
		{
			
			$_GET['search_field']=$this->arr_fields_search[0]; //Webmodel::$model[$model_name]->idmodel;

		}
		
		//0=DESC
		//1=ASC

		settype($_GET['order_desc'], 'integer');
			
		$arr_order[$_GET['order_desc']]='ASC';

		$arr_order[0]='ASC';
		$arr_order[1]='DESC';

		$arr_order_select[]=$_GET['order_desc'];
		$arr_order_select[]=$this->lang_asc;
		$arr_order_select[]=0;
		$arr_order_select[]=$this->lang_desc;
		$arr_order_select[]=1;

		$arr_order_field=array($_GET['order_field']);
		$arr_search_field=array($_GET['search_field']);

		foreach($this->arr_fields_order as $field_label)
		{

			$arr_order_field[]=Webmodel::$model[$this->model_name]->forms[$field_label]->label;
			$arr_order_field[]=$field_label;

		}
		
		foreach($this->arr_fields_search as $field_label)
		{

			$arr_search_field[]=Webmodel::$model[$this->model_name]->forms[$field_label]->label;
			$arr_search_field[]=$field_label;

		}
		
		if($this->show_form==1)
		{
			echo View::load_view(array($arr_search_field, $arr_order_field, $arr_order_select, $this->url_options), 'common/forms/searchform');
		}
		//Query for order

		//Query for search_by
		
		/*list($location, $arr_where_sql)=search_in_field($model_name, array($_GET['search_field']), $_GET['search_word']);
		
		if($location!='')
		{

			$location=$location.' DESC ,';

		}*/
		
		$location='';
		
		$arr_where_sql='';
		
		if(isset(Webmodel::$model[$this->model_name]->components[$_GET['search_field']]) && $_GET['search_word']!='')
		{
		
			$value_search=Webmodel::$model[$this->model_name]->components[$_GET['search_field']]->search_field($_GET['search_word']);
			
			if(get_class(Webmodel::$model[$this->model_name]->components[$_GET['search_field']])!='ForeignKeyField')
			{
			
				$arr_where_sql='`'.$this->model_name.'`.`'.$_GET['search_field'].'` LIKE \'%'.$value_search.'%\'';
				
			}
			else
			{
			
				$model_related_name=Webmodel::$model[$this->model_name]->components[$_GET['search_field']]->related_model;
				
				if(Webmodel::$model[$this->model_name]->components[$_GET['search_field']]->name_field_to_field!='')
				{
				
					$field_related_name=Webmodel::$model[$this->model_name]->components[$_GET['search_field']]->name_field_to_field;
					
					$arr_where_sql='`'.$model_related_name.'`.`'.$field_related_name.'` LIKE \'%'.$value_search.'%\'';
					
				}
			
			}
		
		}

		if($this->where_sql=='' && $arr_where_sql!='')
		{
			
			$this->where_sql='where ';

		}
		else if($this->where_sql!='' && $arr_where_sql!='')
		{

			$this->where_sql.=' AND ';

		}
		
		return array($this->where_sql, $arr_where_sql, $location, $arr_order);
	
	}

}

class remove_idrow {

	function no_remove($arr_row, $model_idmodel)
	{

		return $arr_row;

	}

	function remove($arr_row, $model_idmodel)
	{

		unset($arr_row[$model_idmodel]);	

		return $arr_row;

	}

}

class add_options {

	function yes_add_options($arr_row, $arr_row_raw, $options_func, $url_options, $model_name, $model_idmodel, $separator_element)
	{

		$arr_row[]=implode($separator_element, $options_func($url_options, $model_name, $model_idmodel, $arr_row_raw) );

		return $arr_row;

	}



	function no_add_options($arr_row, $arr_row_raw, $options_func, $url_options, $model_name, $model_idmodel, $separator_element)
	{

		return $arr_row;

	}

}

?>
