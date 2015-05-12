<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package CoreAdmin
*
* A set of classes for create listings and simple admin interfaces for models.
*
*/

Utils::load_libraries(array('utilities/menu_barr_hierarchy', 'simplelist', 'generate_admin_ng'));

/**
* Class used for create beauty and simple model admin interfaces
*
*/

class GenerateAdminClass {

	/**
	* The webmodel class instance used
	*/

	public $class;
	
	/**
	* The fields showed in listings
	*/
	
	public $arr_fields;
	
	/**
	* The fields used in forms for create and update the model.
	*/
	
	public $arr_fields_edit;
	
	/**
	* The base url used for generate the links for the admin interface.
	*/
	
	public $url_options;
	
	/**
	* A callback to use in options field in listings.
	*/
	
	public $options_func;
	
	/**
	* A where sql statement used for generate listings.
	*/
	
	public $where_sql;
	
	/**
	* A deprecated array for selected fields for show model form with values
	* @deprecated
	*/
	
	public $arr_fields_form;
	
	/**
	* A deprecated integer used on old version of admin model class
	* @deprecated
	*/
	
	public $type_list;
	public $url_back;
	public $no_search;
	public $txt_list_new;
	public $txt_add_new_item;
	public $txt_edit_item;
	public $simple_redirect;
	public $class_add;
	public $separator_element_opt;
	public $extra_menu_create;
	public $listmodel;
	public $number_id;
	public $arr_fields_no_showed;
	public $where_sql_class;
	public $num_by_page;

	public $search_asc;
	public $search_desc;
	public $arr_categories;
	
	function __construct($model_name)
	{
		
		$this->model_name=$model_name;
		$this->arr_fields=array(); 
		$this->arr_fields_edit=array();
		$this->url_options=''; 
		//make_fancy_url(PhangoVar::$base_url, PHANGO_SCRIPT_BASE_CONTROLLER, PHANGO_SCRIPT_FUNC_NAME, TEXT_FUNCTION_CONTROLLER, array());
		$this->url_back=$this->url_options;
		$this->no_search=false;
		$this->options_func='BasicOptionsListModel';
		$this->options_func_extra_args=array();
		$this->num_by_page=20;
		$this->where_sql='';
		$this->where_sql_class=new WhereSql($this->model_name, $arr_conditions=array(), $order_by=array(), $limit=array(PhangoVar::$begin_page, $this->num_by_page));
		$this->arr_fields_form=array();
		$this->type_list='Basic';
		$this->show_id=1;
		$this->yes_options=1;
		$this->extra_fields=array();
		$this->txt_list_new=I18n::lang('common', 'listing_new', 'List of').': '.Webmodel::$model[$this->model_name]->label;
		$this->txt_add_new_item=I18n::lang('common', 'add_new_item', 'Add new element').': '.Webmodel::$model[$this->model_name]->label;
		$this->txt_edit_item=I18n::lang('common', 'edit', 'Edit');
		$this->simple_redirect=0;
		$this->class_add='add_class_item_link';
		$this->goback_class='add_class_item_link';
		$this->separator_element_opt='<br />';
		$this->extra_menu_create='';
		$this->search_asc=I18n::lang('common', 'ascent', 'Ascendent');
		$this->search_desc=I18n::lang('common', 'descent', 'Descendent');
		$this->show_goback=1;
		$this->arr_fields_order=array();
		$this->arr_fields_search=array();
		$this->number_id=0;
		$this->arr_categories=array('default' => array());
		$this->arr_fields_no_showed=array();
	
	}
	
	function set_where_sql()
	{
	
		/*if($this->where_sql=='')
		{*/
		
			$this->where_sql=$this->where_sql_class->obtain_sql();
		
		//}
	  
	}
	
	function show()
	{
	
		//Webmodel::$model[$this->model_name]->generate_admin($this->arr_fields, $this->arr_fields_edit, $this->url_options, $this->options_func, $this->where_sql, $this->arr_fields_form, $this->type_list, $this->no_search);
		
		settype($_GET['op_edit'], 'integer');
		settype($_GET['op_action'], 'integer');
		settype($_GET[Webmodel::$model[$this->model_name]->idmodel], 'integer');

		$url_admin=Routes::addGetParameters($this->url_options, array('op_action' => 1));
		
		$arr_menu=array( 0 => array($this->txt_list_new, $this->url_options), 1 => array($this->txt_add_new_item, $url_admin) );
		
		$arr_menu_edit=array( 0 => array($this->txt_list_new, $this->url_options), 1 => array($this->txt_edit_item, '') );
		
		switch($_GET['op_action'])
		{

			default:
				
				if($_GET['op_edit']==0)
				{

					echo '<p>'.menu_barr_hierarchy($arr_menu, 'op_action').'</p>';
				
					echo '<p class="add_new_item"><a class="'.$this->class_add.'" href="'.Routes::addGetParameters($this->url_options, array('op_action' => 1)).'">'.$this->txt_add_new_item.'</a> '.$this->extra_menu_create.'</p>';
					
				}
				else
				{
				
					echo '<p>'.menu_barr_hierarchy($arr_menu_edit, 'op_edit').'</p>';
				
				}
				
				//ListModel($this->model_name, $this->arr_fields, $this->url_options, $this->options_func, $this->where_sql, $this->arr_fields_edit, $this->type_list, $this->no_search, $this->show_id, $this->yes_options, $this->extra_fields, $this->separator_element_opt);
				
				//$this->url_back=$this->url_options;
				
				//$this->url_options=Routes::addGetParameters($this->url_options, array('op_edit' => $_GET['op_edit']));
				
				//$this->set_where_sql();
				
				$listmodel=new ListModelClass($this->model_name, $this->arr_fields, $this->url_options, $this->options_func,$this->options_func_extra_args, $this->where_sql, $this->arr_fields_edit, $this->type_list, $this->no_search, $this->show_id, $this->yes_options, $this->extra_fields, $this->separator_element_opt);
				
				$listmodel->url_back=$this->url_back;
				
				$listmodel->arr_fields_no_showed=$this->arr_fields_no_showed;
				
				if($listmodel->url_back=='')
				{
					
					$listmodel->url_back=$listmodel->url_options;
					
				}
				
				//echo $listmodel->url_back; die;
				$listmodel->simple_redirect=$this->simple_redirect;
				
				$listmodel->admin_class=&$this;
				
				if(count($this->arr_fields_order)>0)
				{
				
					$listmodel->arr_fields_order=$this->arr_fields_order;
				
				}
				
				if(count($this->arr_fields_search)>0)
				{
				
					$listmodel->arr_fields_search=$this->arr_fields_search;
				
				}
				
				$listmodel->search_asc=$this->search_asc;
				$listmodel->search_desc=$this->search_desc;
				$listmodel->show_goback=$this->show_goback;
				
				$listmodel->show();

			break;

			case 1:
			
			
				if($_GET['op_edit']==0)
				{

					echo '<p>'.menu_barr_hierarchy($arr_menu, 'op_action').'</p>';
					
				}
				
				echo '<h3>'.$this->txt_add_new_item.'</h3>';

				//InsertModelForm($this->model_name, $url_admin, $this->url_options, $this->arr_fields_edit, $id=0, $this->show_goback, $this->simple_redirect);
				
				$this->url_back=$this->url_options;
				
				$this->url_options=Routes::addGetParameters($this->url_options, array('op_action' => 1));
				
				$this->insert_model_form();
				
			break;

		}
	
	}
	
	function show_config_mode($post=array())
	{
	
		Webmodel::$model[$this->model_name]->func_update='config';

		if(count(Webmodel::$model[$this->model_name]->forms)==0)
		{
		
			Webmodel::$model[$this->model_name]->create_form();
		
		}
		//Obtain data
		
		if(count($post)==0)
		{
		
			//$this->set_where_sql();
		
			$post=Webmodel::$model[$this->model_name]->select_a_row_where($this->where_sql, $this->arr_fields_edit, 1);
		
		}
		
		ModelForm::set_values_form($post, Webmodel::$model[$this->model_name]->forms, 0);
		
		//nsertModelForm($model_name, $url_admin, $url_back, $arr_fields=array(), $id=0, $goback=1)
		
		//InsertModelForm($this->model_name, $this->url_options, $this->url_back, $this->arr_fields_edit, $id=0, $this->show_goback, $this->simple_redirect, $this->where_sql);
		
		if($this->url_back=='')
		{
			$this->url_back=$this->url_options;
		}
		
		$this->url_options=Routes::addGetParameters($this->url_options, array('op_action' => 1));
	
		$this->insert_model_form();
		
	}
	

	function insert_model_form()
	{
	
		//Setting op variable to integer for use in switch
		
		//function InsertModelForm($model_name, $url_admin, $url_back, $arr_fields=array(), $id=0, $goback=1, $simple_redirect=0, $where_sql='')
		
		//$this->set_where_sql();
		
		$model_name=$this->model_name;
		$url_admin=$this->url_options; 
		
		$url_back=$this->url_back;
		$arr_fields=$this->arr_fields_edit;
		$id=$this->number_id; 
		$goback=$this->show_goback; 
		$simple_redirect=$this->simple_redirect;
		$where_sql=$this->where_sql;
		
		if(isset(Webmodel::$model[$model_name]))
		{

			settype($_GET['op_update'], 'integer');
			settype($_GET['success'], 'integer');

			$url_post=Routes::addGetParameters($url_admin, array('op_update' =>1));
			
			if( count(Webmodel::$model[$model_name]->forms)==0)
			{	
				Webmodel::$model[$model_name]->create_form();
			}
			
			//UpdateModelFormView($model_form, $arr_fields=array(), $url_post)

			if(count($arr_fields)==0)
			{

				$arr_fields=array_keys(Webmodel::$model[$model_name]->forms);

			}
			
			switch($_GET['op_update'])
			{

				default:

					ob_start();
					
					echo View::loadView(array(Webmodel::$model[$model_name]->forms, $arr_fields, $url_post, Webmodel::$model[$model_name]->enctype, '_generate_admin_'.$model_name, $this->arr_categories), 'common/forms/updatemodelform');

					$cont_index=ob_get_contents();

					ob_end_clean();

					echo View::loadView(array(I18n::lang('common', 'edit', 'Edit'), $cont_index), 'content');
					
				break;

				case 1:
			
					$arr_update[$id]=Webmodel::$model[$model_name]->func_update.'_update_model';
					$arr_update[0]=Webmodel::$model[$model_name]->func_update.'_insert_model';

					$func_update=$arr_update[$id];
					
					$_POST[Webmodel::$model[$model_name]->idmodel]=$id;
					
					if(!$this->$func_update($model_name, $arr_fields, $_POST, $id, $where_sql))
					{

						ob_start();
						
						echo '<p class="error">'.I18n::lang('common', 'cannot_update_insert_in_model', 'Cannot insert or update this item in the database').' '.$model_name.': '.Webmodel::$model[$model_name]->std_error.'</p>';

						$post=Webmodel::filter_fields_array($arr_fields, $_POST);
						
						ModelForm::set_values_form($post, Webmodel::$model[$model_name]->forms);

						echo View::loadView(array(Webmodel::$model[$model_name]->forms, $arr_fields, $url_post, Webmodel::$model[$model_name]->enctype, '_generate_admin_'.$model_name, $this->arr_categories), 'common/forms/updatemodelform');

						$cont_index=ob_get_contents();

						ob_end_clean();

						echo View::loadView(array(I18n::lang('common', 'edit', 'Edit'), $cont_index), 'content');

					}
					else
					{

						//die(header('Location: '.$url_admin.'/success/1'));
						
						
						
						Routes::redirect( $url_back );
							
						return;

						
					}

				break;

			}

			if($goback==1)
			{
		
				?>
				<p><a href="<?php echo $url_back; ?>" class="<?php echo $this->goback_class; ?>"><?php echo I18n::lang('common', 'go_back', 'Go back'); ?></a></p>
				<?php

			}

		}
		else
		{

			

		}
	
	}
	
	public function set_url_post($url_post)
	{
	
		$this->url_options=$url_post;
		$this->url_back=$url_post;
	
	}
	
	public function set_url_back($url_back)
	{
		
		$this->url_back=$url_back;
		
	}
	
	function basic_insert_model($model_name, $arr_fields, $post)
	{

		//Check $std_error if fail

		$post=Webmodel::filter_fields_array($arr_fields, $post);

		if( Webmodel::$model[$model_name]->insert($post) )
		{

			return 1;

		}

		return 0;

	}

	function basic_update_model($model_name, $arr_fields, $post, $id)
	{


		if( Webmodel::$model[$model_name]->update($post, 'where '.Webmodel::$model[$model_name]->idmodel.'='.$id) )
		{
			
			return 1;

		}

		return 0;

	}
	
	
	function basic_delete_model($model_name, $id)
	{

		settype($id, 'integer');
		
		if( Webmodel::$model[$model_name]->delete('where '.Webmodel::$model[$model_name]->idmodel.'='.$id) )
		{
			
			return 1;

		}

		return 0;

	}

	function config_insert_model($model_name, $arr_fields, $post, $id, $where_sql='')
	{

		$num_insert=Webmodel::$model[$model_name]->select_count($where_sql, Webmodel::$model[$model_name]->idmodel);
		
		$func_update='insert';

		if($num_insert>0)
		{

			$func_update='update';

		}
		
		if(Webmodel::$model[$model_name]->$func_update($post, $where_sql.' limit 1'))
		{
			
			return 1;

		}
		
		return 0;

	}

	function config_update_model($model_name, $arr_fields, $post, $id)
	{

		return 0;

	}

	function config_delete_model($model_name, $id)
	{

		return 0;

	}
	
	
	function generate_position_model($field_name, $field_position, $url, $where='')
	{

	settype($_GET['action_field'], 'integer');
	
	$model_name=$this->model_name;
	
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

			while(list($id, $name, $position)=Webmodel::$model[$model_name]->fetch_row($query))
			{
				$name=Webmodel::$model[$model_name]->components[$field_name]->show_formatted($name);

				echo '<p><label for="'.$field_position.'">'.$name.'</label><input type="text" name="position['.$id.']" value="'.$position.'" size="3"/></p>';

			}
			echo '<input type="submit" value="'.I18n::lang('common', 'send', 'Send').'"/>';
			echo '</div>';
			echo '</form>';
			
			$cont_order=ob_get_contents();

			ob_end_clean();

			echo View::loadView(array(I18n::lang('common', 'order', 'Order'), $cont_order), 'content');

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
			
			Routes::redirect($url);
			
			die;

		break;

		}

	}
	else
	{

		echo '<p>'.I18n::lang('common', 'no_exists_elements_to_order', 'There is no item to order').'</p>';

	}

}
}

class ListModelClass {

	public $model_name, $arr_fields, $url_options, $options_func='BasicOptionsListModel', $options_func_extra_args=array(), $where_sql='', $arr_fields_form=array(), $type_list='Basic', $no_search=false, $yes_id=1, $yes_options=1, $extra_fields=array(), $separator_element='<br />', $simple_redirect=0, $admin_class, $arr_fields_no_showed;
	
	public $search_asc;
	public $search_desc;

	function __construct($model_name, $arr_fields, $url_options, $options_func='BasicOptionsListModel', $options_func_extra_args=array(), $where_sql='', $arr_fields_form=array(), $type_list='Basic', $no_search=false, $yes_id=1, $yes_options=1, $extra_fields=array(), $separator_element='<br />', $simple_redirect=0)
	{
	
		$this->model_name=$model_name;
		$this->arr_fields=$arr_fields;
		$this->url_options=$url_options;
		$this->options_func=$options_func; 
		$this->options_func_extra_args=$options_func_extra_args; 
		$this->where_sql=$where_sql; 
		$this->arr_fields_form=$arr_fields_form; 
		$this->type_list=$type_list; 
		$this->no_search=$no_search; 
		$this->yes_id=$yes_id; 
		$this->yes_options=$yes_options; 
		$this->extra_fields=$extra_fields; 
		$this->separator_element=$separator_element; 
		$this->simple_redirect=$simple_redirect;
		$this->search_asc=I18n::lang('common', 'ascent', 'Ascendent');
		$this->search_desc=I18n::lang('common', 'descent', 'Descendent');
		$this->show_goback=1;
		$this->separator_element_opt='<br />';
		$this->arr_fields_order=$this->arr_fields;
		$this->arr_fields_search=$this->arr_fields;
		$this->arr_fields_no_showed=array();
		$this->admin_class=new GenerateAdminClass($this->model_name);
		
		//For paginator
		
		$this->num_by_page=20;
		$this->initial_num_pages=20;
	
	}
	
	public function show()
	{
	
		settype($_GET['op_edit'], 'integer');

		if( count(Webmodel::$model[$this->model_name]->forms)==0)
		{	
			Webmodel::$model[$this->model_name]->create_form();
		}
		
		switch($_GET['op_edit'])
		{

		default:

			$arr_label_fields=array();
			$cell_sizes=array();
			/*$where_sql='';*/
			$arr_where_sql='';
			$location='';
			$arr_order=array();
			$show_form=1;
			
			//$search_class=New SearchFormClass($this->model_name, $this->arr_fields_search, $this->arr_fields_order, $this->url_options);
			
			if($this->no_search==true)
			{
			
				$show_form=0;
				//$search_class->search_form();
			
			}
			
			/*if($no_search==false)
			{*/
				$search=new SearchInFieldClass($this->model_name, $this->arr_fields_order, $this->arr_fields_search, $this->where_sql, $this->url_options, $this->yes_id, $show_form);
				
				$search->lang_asc=$this->search_asc;
				$search->lang_desc=$this->search_desc;
			
				list($this->where_sql, $arr_where_sql, $location, $arr_order)=$search->search();
				
				//list($where_sql, $arr_where_sql, $location, $arr_order)=SearchInField($this->model_name, $arr_fields, $arr_fields, $where_sql, $url_options, $yes_id, $show_form);
			//}
			//Num elements in page
			/*
			if(!function_exists(Webmodel::$model[$this->model_name]->func_update.'List'))
			{
				
				BasicList($this->model_name, $this->where_sql, $arr_where_sql, $location, $arr_order, $this->arr_fields, $cell_sizes, $this->options_func, $this->url_options, $this->yes_id, $this->yes_options, $this->extra_fields, $this->separator_element);

			}
			else
			{
				
				$func_list=Webmodel::$model[$this->model_name]->func_update.'List';

				$func_list($this->model_name, $this->where_sql, $arr_where_sql, $location, $arr_order, $this->arr_fields, $cell_sizes, $this->options_func, $this->url_options, $this->yes_id, $this->yes_options, $this->extra_fields, $this->separator_element);

			}*/
			
			$list=new SimpleList($this->model_name);
			
			$list->arr_fields=$this->arr_fields;
			
			$list->arr_fields_no_showed=$this->arr_fields_no_showed;
			
			if($this->yes_id)
			{
			
				array_unshift($list->arr_fields, Webmodel::$model[$this->model_name]->idmodel);
				
				Webmodel::$model[$this->model_name]->components[Webmodel::$model[$this->model_name]->idmodel]->label='#Id';
				
			}
			
			$list->arr_cell_sizes=array(Webmodel::$model[$this->model_name]->idmodel => ' width="25"');
			
			$list->num_by_page=$this->num_by_page;
			
			$list->initial_num_pages=$this->initial_num_pages;
			
			/*if(preg_match('/^where|WHERE/', $list->where_sql))
			{
			
				$list->where_sql=' AND ';
			
			}*/
			
			$list->where_sql=$this->where_sql.$arr_where_sql.' order by '.$location.'`'.$this->model_name.'`.`'.$_GET['order_field'].'` '.$arr_order[$_GET['order_desc']];
			
			//echo $list->where_sql;
			
			$list->url_options=$this->url_options;
			
			$list->options_func=$this->options_func;
			$list->options_func_extra_args=$this->options_func_extra_args;
			
			$list->show();

		break;

		case 1:
			
			settype($_GET[Webmodel::$model[$this->model_name]->idmodel], 'integer');
			
			$query=Webmodel::$model[$this->model_name]->select('where '.Webmodel::$model[$this->model_name]->idmodel.'='.$_GET[Webmodel::$model[$this->model_name]->idmodel], $this->arr_fields_form, true);
			
			$post=Webmodel::$model[$this->model_name]->fetch_array($query);
			
			//model_set_form($this->model_name, $post, NO_SHOW_ERROR);
			
			ModelForm::set_values_form($post, Webmodel::$model[$this->model_name]->forms, 0);
			
			$url_options_edit=Routes::addGetParameters($this->url_options, array('op_edit' =>1, Webmodel::$model[$this->model_name]->idmodel => $_GET[Webmodel::$model[$this->model_name]->idmodel]) );
			
			//$this->admin_class->url_back=$this->url_options;
			//echo $this->admin_class->url_back; die;
			$this->admin_class->url_options=$url_options_edit;
			
			if($this->url_back!='')
			{
			
				$this->admin_class->url_back=$this->url_back;
			
			}
			
			//InsertModelForm($this->model_name, $url_options_edit, $this->url_options, $this->arr_fields_form, $_GET[Webmodel::$model[$this->model_name]->idmodel], $this->show_goback, $this->simple_redirect);
			
			$this->admin_class->number_id=$_GET[Webmodel::$model[$this->model_name]->idmodel];
			
			$this->admin_class->insert_model_form();
			
			/*ob_start();
			
			echo View::loadView(array(Webmodel::$model[$this->model_name]->forms, $this->arr_fields_form, $this->url_options, Webmodel::$model[$this->model_name]->enctype, '_generate_admin_'.$this->model_name), 'common/forms/updatemodelform');

			$cont_index=ob_get_contents();

			ob_end_clean();

			echo View::loadView(array(I18n::lang('common', 'edit', 'Edit'), $cont_index), 'content');*/
			
		break;

		case 2:

			settype($_GET[Webmodel::$model[$this->model_name]->idmodel], 'integer');

			$func_delete=Webmodel::$model[$this->model_name]->func_update.'_delete_model';
			
			$url_options_delete=Routes::addGetParameters($this->url_options, array('success_delete' => 1) );

			if($this->admin_class->$func_delete($this->model_name, $_GET[ Webmodel::$model[$this->model_name]->idmodel ]))
			{	
				
				Routes::redirect($url_options_delete);
				die;

			}
			else
			{

				echo 'Error: '.PhangoVar::$std_error;

			}
		
		break;

		}
	
	}

}
/**
* Function for overwrite old admin functions for old code
*
* @deprecated
* NO USE THIS FUNCTION, use GenerateAdminClass in all places.
*/

function generate_admin_model_ng($model_name, $arr_fields, $arr_fields_edit, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic', $no_search=false)
{

	$admin=new GenerateAdminClass($model_name);
	
	$admin->arr_fields=$arr_fields;
	
	$admin->arr_fields_edit=$arr_fields_edit;
	
	$admin->set_url_post($url_options);
	
	$admin->options_func=$options_func;
	
	$admin->no_search=$no_search;
	
	$admin->show();

}

/**
* Function for overwrite old admin functions for old code
*
* @deprecated
* NO USE THIS FUNCTION, use GenerateAdminClass in all places.
*/

function ListModel($model_name, $arr_fields, $url_options, $options_func='BasicOptionsListModel', $where_sql='', $arr_fields_form=array(), $type_list='Basic', $no_search=false, $yes_id=1, $yes_options=1, $extra_fields=array(), $separator_element='<br />', $simple_redirect=0)
{

	$list=new SimpleList($model_name);
	
	$list->arr_fields=$arr_fields;
	
	$list->url_options=$url_options;
	
	$list->options_func=$options_func;
	
	$list->where_sql=$where_sql;
	
	$list->no_search=$no_search;
	
	$list->yes_options=$yes_options;
	
	$list->separator_element=$separator_element;
	
	$list->show();
	
}
		

/*
class SearchFormClass {

	public $model_name, $arr_search_field, $arr_order_field, $arr_order_select, $url_options;

	public function __construct($model_name, $arr_search_field, $arr_order_field,  $url_options)
	{
	
		$this->model_name=$model_name;
		$this->arr_search_field=$arr_search_field;
		$this->arr_order_field=$arr_order_field;
		$this->url_options=$url_options;
	
	}


	public function search_form()
	{
	
		echo View::loadView(array($this->arr_search_field, $this->arr_order_field, $this->arr_order_select, $this->url_options), 'common/forms/searchform');
	
	}
	
	//Return search on format WhereSql
	
	public function search()
	{
	
		
	
	}

}
*/
/*
class ListModelAjaxClass {


	public $where_sql, $arr_fields, $arr_fields_no_showed, $extra_fields, $yes_pagination, $limit_num;

	function __construct($model_name, $arr_fields=array(), $arr_fields_showed=array(), $extra_fields=array(), $where_sql='')
	{
	
		$this->model_name=$model_name;
		$this->where_sql=$where_sql;
		$this->arr_fields=$arr_fields;
		$this->arr_fields_no_showed=$arr_fields_no_showed;
		$this->extra_fields=$extra_fields;
		$this->yes_pagination=1;
		$this->limit_num=20;
	}
	
	function show()
	{
	
		//The limit is defined in query...
	
		$total_elements=Webmodel::$model[$this->model_name]->select_count($this->where_sql);
		
		$query=Webmodel::$model[$this->model_name]->select($this->where_sql, $arr_fields);
	
		echo View::loadView(array($query, $this), 'utilities/list');
		
	
	}
	
	function obtain_data_ajax($page)
	{
	
		
	
	}

}*/

?>