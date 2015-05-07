<?php

/**
*
*/

class ArrayField extends SerializeField {

	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value, $key_value='')
	{
	
		$real_value=unserialize($value);
	
		if($key_value==='')
		{
			
			return implode(', ', $return_value);
			
		}
		else
		if(isset($real_value[$key_value]))
		{
		
			return $real_value[$key_value];
		
		}

	}

}



/**
* Booleanfield is a field for boolean values.
*/

class BooleanField extends PhangoField {

	public $size=1;
	public $value=0;
	public $label="";
	public $required=0;
	public $form="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $default_value=0;

	function __construct()
	{

		$this->size=1;
		$this->form='SelectForm';

	}

	function check($value)
	{

		//$this->value=form_text($value);
		settype($value, "integer");

		if($value!=0 && $value!=1)
		{

			$value=0;

		}

		return $value;

	}

	function get_type_sql()
	{

		//Int for simple compatibility with sql dbs.
	
		return 'INT('.$this->size.') NOT NULL';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		switch($value)
		{
			default:

				return I18n::lang('common', 'no', 'No');

			break;

			case 1:

				return I18n::lang('common', 'yes', 'Yes');

			break;

	
		}

	}

	function get_parameters_default()
	{
	
		$arr_values=array($this->default_value, I18n::lang('common', 'no', 'No'), 0, I18n::lang('common', 'yes', 'Yes'), 1);;

		return array($this->name_component, '', $arr_values);

	}

}



/**
* CharField is a PhangoField that define a varchar element in the model-table.
* 
* A simple PhangoField that define in the database a varchar element with the size that you like.
*/




class CharField extends PhangoField {

	//Basic variables that define the field

	/**
	* Size of field in database
	*/
	public $size=20;
	

	/**
	* Construct field with basic data...
	*
	* @param integer $size The size of the varchar. If you put 250, for example, you will can put strings with 250 characters on this.
	* @param boolean $multilang Don't use, don't need for nothing.
	*
	*/

	function __construct($size=20)
	{

		$this->size=$size;
		$this->form='TextForm';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}
	
	/**
	* This function is for check if the value for field is valid
	*/

	public function check($value)
	{

		//Delete Javascript tags and simple quotes.
		$this->value=Utils::form_text($value);
		return Utils::form_text($value);

	}


}







/**
*
*/

class ChoiceField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $form="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $type='integer';
	public $arr_values=array();
	public $arr_formatted=array();
	public $default_value='';

	

	function __construct($size=11, $type='integer', $arr_values=array(), $default_value='')
	{

		$this->size=$size;
		$this->form='SelectForm';
		$this->type=$type;
		$this->arr_values=$arr_values;
		$this->default_value=$default_value;
		$this->arr_formatted['']='';
		
		foreach($arr_values as $value)
		{
			
			$this->arr_formatted[$value]=$value;
		
		}
	
	}
	
	function restart_formatted()
	{
	
		foreach($this->arr_values as $value)
		{
			
			$this->arr_formatted[$value]=$value;
		
		}
	
	}

	function check($value)
	{
		
		switch($this->type)
		{
		
			case 'integer':

				settype($value, "integer");

			break;

			case 'string':

				$value=Utils::form_text($value);

			break;

		}
		
		if(in_array($value, $this->arr_values))
		{	
			
			return $value;

		}
		else
		{

			return $this->default_value;

		}

	}

	function get_type_sql()
	{

		switch($this->type)
		{
		
			case 'integer':

			return 'INT('.$this->size.') NOT NULL';
			
			break;

			case 'string':

			return 'VARCHAR('.$this->size.') NOT NULL';

			break;

		 }	

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{
		
		return $this->arr_formatted[$value];

	}

	function get_parameters_default()
	{	

		if(count($this->arr_values)>0)
		{
			$arr_return=array($this->default_value);

			foreach($this->arr_values as $value)
			{

				$arr_return[]=$this->arr_formatted[$value];
				$arr_return[]=$value;

			}

			$arr_values=$arr_return;

		}
		else
		{

			$arr_values=array(0, 'Option 1', 0, 'Option 2', 1);

		}
		
		return array($this->name_component, '', $arr_values);

	}

}






/** 
* Datefield is a field for save dates in timestamp, this value is a timestamp and you need use form_date or form_time for format DateField
*/

class DateField extends PhangoField {

	public $size=11;	
	public $value="";	
	public $required=0;
	public $form="";
	public $label="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $set_default_time=0;
	public $std_error='';

	function __construct($size=11)
	{

		$this->size=$size;
		$this->form='DateForm';

	}

	//The check have 3 parts, in a part you have a default time, other part if you have an array from a form, last part if you send a timestamp directly.
	
	function check($value)
	{

		$final_value=0;

		if($this->set_default_time==0)
		{

			$final_value=mktime(date('H'), date('i'), date('s'));
		
		}
		
		if(gettype($value)=='array')
		{
			
			settype($value[0], 'integer');
			settype($value[1], 'integer');
			settype($value[2], 'integer');
			settype($value[3], 'integer');
			settype($value[4], 'integer');
			settype($value[5], 'integer');
			
			if($value[0]>0 && $value[1]>0 && $value[2]>0)	
			{

				/*$substr_time=$user_data['format_time']/3600;
	
				$value[3]-=$substr_time;*/

				$final_value=mktime ($value[3], $value[4], $value[5], $value[1], $value[0], $value[2] );
	
			}
			
			/*echo date('H-i-s', $final_value);
			
			//echo $final_value;
			
			die;*/

		}
		else if(strpos($value, '-')!==false)
		{
		
			$arr_time=explode('-',trim($value));
			
			settype($arr_time[0], 'integer');
			settype($arr_time[1], 'integer');
			settype($arr_time[2], 'integer');
			
			$final_value=mktime (0, 0, 0, $arr_time[1], $arr_time[0], $arr_time[2] );
			
			if($final_value===false)
			{
			
				$final_value=mktime (0, 0, 0, $arr_time[1], $arr_time[2], $arr_time[0] );
			
			}
		
		}
		else
		if(gettype($value)=='string' || gettype($value)=='integer')
		{
			
			settype($value, 'integer');
			$final_value=$value;

		}
		
		$this->value=Utils::form_text($final_value);

		return $final_value;

	}

	function get_type_sql()
	{

		return 'INT('.$this->size.') NOT NULL';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $this->format_date($value);

	}
	
	static public function format_date($value)
	{

		load_libraries(array('form_date'));
		
		return form_date( $value );
	
	}

	function get_parameters_default()
	{

		return array($this->name_component, '', time());

	}
	
}



/**
* Doublefield is a field for doubles values.
*/




class DoubleField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $form="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';

	function __construct($size=11)
	{

		$this->size=$size;
		$this->form='TextForm';

	}

	function check($value)
	{

		$this->value=Utils::form_text($value);
		settype($value, "double");
		return $value;

	}

	function get_type_sql()
	{

		return 'DOUBLE NOT NULL';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', 0);

	}


}



/**
* Emailfield is a field that only accepts emails
*/




class EmailField extends PhangoField {

	public $size=200;
	public $value="";
	public $label="";
	public $form="TextForm";
	public $class="";
	public $required=0;
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';

	function __construct($size=200)
	{

		$this->size=$size;

	}

	//Method for accept valid emails only
	
	function check($value)
	{
		
		//Delete Javascript tags and simple quotes.

		

		$value=Utils::form_text($value);

		$this->value=$value;

		$email_expression='([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*(?:[\w\!\#$\%\'\*\+\-\/\=\?\^\`{\|\}\~]|&amp;)+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)';
		
		if(preg_match('/^'.$email_expression.'$/i', $value))
		{
			
			return $value;

		}
		else
		{
			
			$this->std_error.='Email format error';
			
			return '';

		}
		

	}

	function get_type_sql()
	{

		return 'VARCHAR('.$this->size.') NOT NULL';

	}

	/**
	* This function is used for show the value on a human format
	*/
	
	public function show_formatted($value)
	{

		return $value;

	}


}






/**
*
*/

class FileField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="FileForm";
	public $name_file="";
	public $path="";
	public $url_path="";
	//public $type='';
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';

	function __construct($name_file, $path, $url_path)
	{

		$this->name_file=$name_file;
		$this->path=$path;
		$this->url_path=$url_path;
		//$this->type=$type;

	}

	//Check if the file is correct..
	
	function check($file)
	{	
		
		$file_field=$this->name_file;

		settype($_POST['delete_'.$file_field], 'integer');
		
		if($_POST['delete_'.$file_field]==1)
		{

			$file_delete=Utils::form_text($_POST[$file_field]);

			if($file_delete!='')
			{

				@unlink($this->path.'/'.$file_delete);

				$file='';

			}

		}
		
		if(isset($_FILES[$file_field]['tmp_name']))
		{
				
			if($_FILES[$file_field]['tmp_name']!='')
			{
	
				if( move_uploaded_file ( $_FILES[$file_field]['tmp_name'] , $this->path.'/'.$_FILES[$file_field]['name'] ) )
				{

					return $_FILES[$file_field]['name'];

					//return $this->path.'/'.$_FILES[$file]['name'];
					
				}
				else
				{

					$this->std_error=I18n::lang('common', 'error_cannot_upload_this_file_to_the_server', 'Error: Cannot upload this file to the server');

					return '';

				}
					

			}
			else if($file!='')
			{

				return $file;

			}

		}
		else
		{
		
			$this->std_error=I18n::lang('error_model', 'check_error_enctype_for_upload_file', 'Please, check enctype form of file form');
		
			return '';
		
		}

		$this->value='';
		
		return '';


	}


	function get_type_sql()
	{

		return 'VARCHAR(255) NOT NULL';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}
	
	function show_file_url($value)
	{

		return $this->url_path.'/'.$value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', '');

	}
	
	function process_delete_field($model, $name_field, $conditions)
	{
	
		$query=$model->select($conditions, array($name_field));
		
		while(list($file_name)=webtsys_fetch_row($query))
		{
		
			if(!unlink($this->path.'/'.$file_name))
			{
			
				$this->std_error=I18n::lang('common', 'cannot_delete_file', 'Cannot delete the file');
			
			}
		
		}
	
	}

}






/**
* ForeignKeyfield is a relantioship between two models...
*
*/

/**
* ForeignKeyfield is a relantioship between two models...
*
*/

class ForeignKeyField extends IntegerField{

	//field related in the model...
	public $related_model='';
	public $container_model='';
	public $null_relation=1;
	public $params_loading_mod=array();
	public $default_id=0;
	public $yes_zero=0;
	public $fields_related_model;
	public $name_field_to_field;
	
	function __construct($related_model, $size=11, $null_relation=1, $default=0)
	{

		$this->size=$size;
		$this->form='SelectForm';
		$this->related_model=&$related_model;
		$this->container_model=$this->related_model->name;
		//Fields obtained from related_model if you make a query...
		$this->fields_related_model=array();
		//Representative field for related model...
		$this->name_field_to_field='';
		$this->null_relation=$null_relation;
		$this->default_id=$default;
		
		//PhangoVar::$model[$related_model]->related_models_delete[]=array('model' => $this->name_model, 'related_field' => $this->name_component);
		
		//echo get_parent_class();

	}
	
	function set_relationships()
	{
		
		//We need the model loaded...
		
		if(isset($this->related_model))
		{
			$this->related_model->related_models_delete[]=array('model' => $this->name_model, 'related_field' => $this->name_component);
		}
		else
		{
		
			//show_error('You need load model before set relantionship', $this->related_model.' model not exists. You need load model before set relantionship with ForeignKeyField with '.$this->name_model.' model', $output_external='');
			
			throw new \Exception($this->related_model.' model not exists. You need load model before set relantionship with ForeignKeyField with '.$this->name_model.' model');
			
			die;
		
		}
	}

	function check($value)
	{
		
		settype($value, "integer");

		//Reload related model if not exists, if exists, only check cache models...

		if(!isset($this->related_model))
		{

			load_model($this->container_model);

		}

		//Need checking if the value exists with a select_count
		
		$num_rows=$this->related_model->select_count('where '.$this->related_model.'.'.$this->related_model->idmodel.'='.$value, $this->related_model->idmodel);
		
		if($num_rows>0)
		{
		
			if($value==0 && $this->yes_zero==0)
			{
				
				return NULL;
			
			}
			
			return $value;

		}
		else
		{
		
			if($this->default_id<=0 && $this->yes_zero==0)
			{
			
				return NULL;
				
			}
			else
			{
			
				return $this->default_id;
			
			}

		}
		

	}
	
	function simple_check($value)
	{
	
		settype($value, 'integer');
		
		return $value;
	
	}
	
	
	function get_type_sql()
	{
	
		$arr_null[0]='NOT NULL';
		$arr_null[1]='NULL';

		return 'INT('.$this->size.') '.$arr_null[$this->null_relation];

	}

	/**
	* This function is used for show the value on a human format
	*/
	
	public function show_formatted($value)
	{
		
		return $this->related_model->components[$this->name_field_to_field]->show_formatted($value);

		//return $value;

	}

	function get_parameters_default()
	{
		
		
		load_libraries(array('forms/selectmodelform'));
		
		//SelectModelForm($name, $class, $value, $model_name, $identifier_field, $where='')
		
		//Prepare parameters for selectmodelform
		
		if(isset($this->name_component) && $this->name_field_to_field!='' && $this->name_model!='' && count(PhangoVar::$model[$this->name_model]->forms)>0)
		{
			PhangoVar::$model[$this->name_model]->forms[$this->name_component]->form='SelectModelForm';
			
			return array($this->name_component, '', '', $this->related_model, $this->name_field_to_field, '');
			
		}
		else
		{
		
			$arr_values=array('', I18n::lang('common', 'any_option_chosen', 'Any option chosen'), '');
			
			return array($this->name_component, '', $arr_values);
			
		}

	}
	
	function get_all_fields()
	{
		
		return array_keys($this->related_model->components);
	
	}

}







/**
* Imagefield is a field for upload images
* 
* This field don't have for now a maximum width and height. To fix in next releases.
*/

class ImageField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="ImageForm";
	public $name_file="";
	public $path="";
	public $url_path="";
	public $type='';
	public $thumb=0;
	public $img_width=100;
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $quality_jpeg=75;
	public $min_size=array(0, 0);
	public $prefix_id=1;
	public $img_minimal_height=array();
	public $func_token='Utils::get_token';
	public $move_file_func='move_uploaded_file';

	function __construct($name_file, $path, $url_path, $type, $thumb=0, $img_width=array('mini' => 150), $quality_jpeg=85)
	{

		$this->name_file=$name_file;
		$this->path=$path;
		$this->url_path=$url_path;
		$this->type=$type;
		$this->thumb=$thumb;
		$this->img_width=$img_width;
		$this->quality_jpeg=$quality_jpeg;

	}

	//Check if the image is correct..
	
	function check($image)
	{	
		//Only accept jpeg, gif y png
		
		
		
		$file=$this->name_file;
		$image=basename($image);

		settype($_POST['delete_'.$file], 'integer');
		
		if($_POST['delete_'.$file]==1)
		{

			//Delete old_image

			$image_file=Utils::form_text($_POST[$file]);

			if($image_file!='')
			{

				@unlink($this->path.'/'.$image_file);
				
				foreach($this->img_width as $key => $value)
				{

					@unlink($this->path.'/'.$key.'_'.$image_file);
				
				}

				$image='';

			}

		}
		
		if(isset($_FILES[$file]['tmp_name']))
		{
				
			if($_FILES[$file]['tmp_name']!='')
			{	
			
			
				$arr_image=getimagesize($_FILES[$file]['tmp_name']);
				
				$_FILES[$file]['name']=Utils::slugify(Utils::form_text($_FILES[$file]['name']));
				
				if($this->prefix_id==1)
				{
				
					$func_token=$this->func_token;
				
					$_FILES[$file]['name']=$func_token().'_'.$_FILES[$file]['name'];
				
				}
				
				$this->value=$_FILES[$file]['name'];
				
				//Check size
				
				if($this->min_size[0]>0 && $this->min_size[1]>0)
				{
				
					if($arr_image[0]<$this->min_size[0] || $arr_image[1]<$this->min_size[1])
					{
					
						$this->std_error=I18n::lang('common', 'image_size_is_not_correct', 'Image size is wrong').'<br />'.I18n::lang('common', 'min_size', 'Minimal size').': '.$this->min_size[0].'x'.$this->min_size[1];
						
						$this->value='';
						return '';
						
					
					}
				
				}
				
				//Delete other image if exists..
				
				if($image!='')
				{
				
					unlink($this->path.'/'.$image);
				
				}
				
				//gif 1
				//jpg 2
				//png 3
				//Only gifs y pngs...
				
				//Need checking gd support...
				
				$func_image[1]='imagecreatefromgif';
				$func_image[2]='imagecreatefromjpeg';
				$func_image[3]='imagecreatefrompng';
				
				if($arr_image[2]==1 || $arr_image[2]==2 || $arr_image[2]==3)
				{
				
					$image_func_create='imagejpeg';

					switch($arr_image[2])
					{

						case 1:

							//$_FILES[$file]['name']=str_replace('.gif', '.jpg', $_FILES[$file]['name']);
							$image_func_create='imagegif';

						break;

						case 3:

							//$_FILES[$file]['name']=str_replace('.png', '.jpg', $_FILES[$file]['name']);
							$image_func_create='imagepng';
							//Make conversion to png scale
							$this->quality_jpeg=floor($this->quality_jpeg/10);
							
							if($this->quality_jpeg>9)
							{
							
								$this->quality_jpeg=9;
							
							}

						break;

					}
					
					$move_file_func=$this->move_file_func;

					
					if( $move_file_func ( $_FILES[$file]['tmp_name'] , $this->path.'/'.$_FILES[$file]['name'] ))
					{
						
						//Make jpeg.

						$func_final=$func_image[$arr_image[2]];

						$img = $func_final($this->path.'/'.$_FILES[$file]['name']);
						
						//imagejpeg ( $img, $this->path.'/'.$_FILES[$file]['name'], $this->quality_jpeg );
						
						/*$mini_photo=$_FILES[$file]['name'];
				
						$mini_photo=str_replace('.gif', '.jpg', $mini_photo);
						$mini_photo=str_replace('.png', '.jpg', $mini_photo);*/
						
						//Reduce size for default if $this->img_width['']
						
						if(isset($this->img_width['']))
						{
							if($arr_image[0]>$this->img_width[''])
							{
								$width=$this->img_width[''];
							
								$ratio = ($arr_image[0] / $width);
								$height = round($arr_image[1] / $ratio);
							
								$thumb = imagecreatetruecolor($width, $height);
								
								imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $width, $height, $arr_image[0], $arr_image[1]);
								
								$image_func_create ( $thumb, $this->path.'/'.$_FILES[$file]['name'], $this->quality_jpeg );
								
							}
							
							unset($this->img_width['']);
						}

						//Make thumb if specific...
						if($this->thumb==1)
						{
							
							//Convert to jpeg.
							
							foreach($this->img_width as $name_width => $width)
							{
							
								$ratio = ($arr_image[0] / $width);
								$height = round($arr_image[1] / $ratio);
								
								if(isset($this->img_minimal_height[$name_width]))
								{
									
									if($height<$this->img_minimal_height[$name_width])
									{
											
										//Need recalculate the adecuate width and height.
										
										$height=$this->img_minimal_height[$name_width];
										
										$ratio=($arr_image[1] / $height);
										
										$width=round($arr_image[0]/$ratio);
										
										//$width=
									
									}
								
								}
							
								$thumb = imagecreatetruecolor($width, $height);
								
								imagecopyresampled ($thumb, $img, 0, 0, 0, 0, $width, $height, $arr_image[0], $arr_image[1]);
								
								$image_func_create ( $thumb, $this->path.'/'.$name_width.'_'.$_FILES[$file]['name'], $this->quality_jpeg );
								;
								//imagepng ( resource $image [, string $filename [, int $quality [, int $filters ]]] )

							}

						}
						
						//unlink($_FILES[$file]['tmp_name']);
						
						//Unlink if exists image
						
						if(isset($_POST[$file]))
						{
						
							if($_POST[$file]!='')
							{
								$image_file=Utils::form_text($_POST[$file]);

								if($image_file!='')
								{

									@unlink($this->path.'/'.$image_file);
									
									foreach($this->img_width as $key => $value)
									{

										@unlink($this->path.'/'.$key.'_'.$image_file);
									
									}

									$image='';

								}
						
							}
						
						}
						
						return $_FILES[$file]['name'];

						//return $this->path.'/'.$_FILES[$file]['name'];
						
					}
					else
					{

						$this->std_error=I18n::lang('common', 'error_cannot_upload_this_image_to_the_server', 'Error: Cannot upload this image to the server');
						
						if(DEBUG==1)
						{
						
							$this->std_error.=' Image origin '.$_FILES[$file]['tmp_name'].' in this path '.$this->path;
						
						}

						return '';

					}
					

				}
				else
				{

					$this->std_error.=I18n::lang('error_model', 'img_format_error', 'Img format error, only accept gif, jpg and png formats');

				}

			}
			else if($image!='')
			{

				return $image;

			}


		}
		else if($image!=='')
		{
			
			
			if(file_exists($this->path.'/'.$image))
			{

				$this->value=$this->path.'/'.$image;
				return $image;

			}
			else
			{
			
				$this->std_error=I18n::lang('error_model', 'check_error_enctype_for_upload_file', 'Please, check enctype form of file form');
				return '';
			
			}
			
			

		}
		else
		{
		
			$this->std_error=I18n::lang('error_model', 'check_error_enctype_for_upload_file', 'Please, check enctype form of file form');
		
		}

		$this->value='';
		return '';


	}


	function get_type_sql()
	{

		return 'VARCHAR(255) NOT NULL';

	}
	
	function show_image_url($value)
	{
  
		return $this->url_path.'/'.$value;

	}
	
	function process_delete_field($model, $name_field, $conditions)
	{
	
		
		
		//die;
		$query=$model->select($conditions, array($name_field));
		
		while(list($image_name)=webtsys_fetch_row($query))
		{
		
			if( file_exists($this->path.'/'.$image_name) && !is_dir($this->path.'/'.$image_name) )
			{
				if(unlink($this->path.'/'.$image_name))
				{
				
					//Unlink mini_images
					
					unset($this->img_width['']);
					
					foreach($this->img_width as $key => $value)
					{
					
						if(!unlink($this->path.'/'.$key.'_'.$image_name))
						{
							
							$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$key.'_'.$image_name;
						
						}
					
					}
				
					$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$image_name;
				
				}
				else
				{
				
					$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$image_name;
				
				}
				
			}
			else
			{
			
				$this->std_error.=I18n::lang('common', 'cannot_delete_image', 'Cannot delete the image').': '.$image_name;
			
			}
		
		}
	
	}
	
	/**
	* Method for return a formatted value readable for humans.
	*/
	
	public function show_formatted($value)
	{
	
		//Size
		
		$size=150;
	
		if($this->thumb==1)
		{
		
			reset($this->img_width);
			
			if(isset($this->img_width['']))
			{
			
				next($this->img_width);
			
			}
			
			$key=key($this->img_width);
			
			$value=$key.'_'.$value;
			
			$size=$this->img_width[$key];
			
		
		}
	
		return '<img src="'.$this->show_image_url($value).'" width="'.$size.'"/>';
	
	}

}



/**
* Integerfield is a field for integers values.
*  
*/




class IntegerField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $only_positive=false;
	public $min_num=0;
	public $max_num=0;

	function __construct($size=11, $only_positive=false, $min_num=0, $max_num=0)
	{

		$this->size=$size;
		$this->form='TextForm';
		$this->only_positive=$only_positive;
		$this->min_num=$min_num;
		$this->max_num=$max_num;

	}

	function check($value)
	{

		$this->value=Utils::form_text($value);
		
		settype($value, "integer");
		
		if($this->only_positive==true && $value<0)
		{
		
			$value=0;
		
		}
		
		if($this->min_num<>0 && $value<$this->min_num)
		{
		
			$value=$this->min_num;
		
		}
		
		if($this->max_num<>0 && $value>$this->max_num)
		{
		
			$value=$this->max_num;
		
		}
		
		return $value;

	}

	function get_type_sql()
	{

		return 'INT('.$this->size.') NOT NULL';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', 0);

	}

}






/**
* Keyfield is a indexed field in a sql statement...
*/

class KeyField extends PhangoField {

	public $size=11;
	public $value=0;
	public $label="";
	public $required=0;
	public $form="";
	public $quot_open='\'';
	public $quot_close='\'';
	public $fields=array();
	public $table='';
	public $model='';
	public $ident='';
	public $std_error='';

	function __construct($size=11)
	{

		$this->size=$size;
		$this->form='TextForm';

	}

	function check($value)
	{

		$this->value=Utils::form_text($value);

		settype($value, "integer");
		return $value;

	}

	function get_type_sql()
	{

		return 'INT('.$this->size.') NOT NULL';

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

}






/**
*
*/

class ParentField extends IntegerField{

	//field related in the model...
	public $parent_model='';

	function __construct($parent_model, $size=11)
	{

		$this->parent_model=&$parent_model;
		$this->size=$size;
		$this->form='SelectForm';

	}

	function check($value)
	{
		
		settype($value, "integer");

		//Check model
		
		$num_rows=$this->parent_model->select_count('where '.$this->parent_model->idmodel.'='.$value, $this->parent_model->idmodel);
		
		if($num_rows>0)
		{

			return $value;

		}
		else
		{
			
			return 0;

		}
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

	function get_parameters_default()
	{
		

		$arr_values=array('', I18n::lang('common', 'any_option_chosen', 'Any option chosen'), '');
		
		return array($this->name_component, '', $arr_values);

	}
	
	public function process_update_field($class, $name_field, $conditions, $value)
	{
	
		$num_rows=$class->select_count($conditions.' and '.$class->idmodel.'='.$value);
		
		if($num_rows==0)
		{
		
			return true;
		
		}
		else
		{
		
			return false;
		
		}
	
	}
	
	public function obtain_parent_tree($id, $field_ident, $url_op)
	{
		
		$arr_parent=array();
		$arr_link_parent=array();
		
		$query=$this->parent_model->select('', array( $this->parent_model->idmodel, $this->name_component, $field_ident) );
		
		while(list($id_block, $parent, $name)=webtsys_fetch_row($query))
		{
		
			$arr_parent[$id_block]=array($parent, $name);
		
		}
		
		$arr_link_parent=$this->obtain_recursive_parent($id, $arr_parent, $arr_link_parent, $field_ident, $url_op);
		
		$arr_link_parent=array_reverse($arr_link_parent, true);
		
		return $arr_link_parent;
	
	}
	
	public function obtain_recursive_parent($id, $arr_parent, $arr_link_parent, $field_ident, $url_op)
	{
	
		//$arr_link_parent[]=array('nombre', 'enlace');
		
		//$arr_link_parent=array();
		
		if($id>0)
		{
			
			$arr_link_parent[$id]=array($this->parent_model->components[$field_ident]->show_formatted($arr_parent[$id][1]), add_extra_fancy_url($url_op, array($this->name_component => $id) ) );
			
			if($arr_parent[$id][0]>0)
			{
			
				$arr_link_parent=$this->obtain_recursive_parent($arr_parent[$id][0], $arr_parent, $arr_link_parent, $field_ident, $url_op);
		
			}
		
		}
	
		return $arr_link_parent;
	}

}




/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package CoreFields
*
*/

/**
* PhangoField class is the base for make class used on Webmodel::components property.
*
*/




class PhangoField {

	/**
	* Property used for set this field how indexed in the database table.
	*/

	public $indexed=0;
	
	/**
	* Property used for set this field how unique value in the database table.
	*/

	public $unique=0;
	
	/**
	* The name of the model where this component or field live
	*/
	
	public $name_model='';
	
	/**
	* Name of the field or component.
	*/
	
	public $name_component='';
	
	/**
	* Method used for internal searchs for format the values.
	*
	* 
	*/
	
	/**
	* Required define if this field is required when insert or update a row of this model...
	*/
	
	public $required=0;
	
	/** 
	* $quote_open is used if you need a more flexible sql sentence, 
	* @warning USE THIS FUNCTION IF YOU KNOW WHAT YOU ARE DOING
	*/
	public $quot_open='\'';
	
	/** 
	* $quote_close is used if you need a more flexible sql sentence, 
	* @warning USE THIS PROPERTY IF YOU KNOW WHAT YOU ARE DOING
	*/
	
	public $quot_close='\'';
	
	/**
	* $std_error contain error in field if exists...
	*/
	
	public $std_error='';
	
	/**
	* Label is the name of field
	*/
	public $label="";
	
	/**
	* Value of field...
	*/
	public $value="";
	
	/**
	* Form define the function for use in forms...
	*/
	
	public $form="";
	
	/**
	* Array for create initial parameters for form..
	*/
	
	public $parameters=array();
	
	/**
	* Method used for internal tasks related with searchs. You can overwrite this method in your PhangoField object if you need translate the value that the user want search to a real value into the database.
	*/
	
	function search_field($value)
	{
	
		return Utils::form_text($value);
	
	}
	
	/**
	* Method used for internal tasks related with foreignkeys. By default make nothing.
	*
	* 
	*/
	
	function set_relationships()
	{
	
		
	
	}

	/** 
	* This method is used for describe the new field in a sql language format.
	*/

	public function get_type_sql()
	{

		return 'VARCHAR('.$this->size.') NOT NULL';

	}
	
	/** 
	* This method is used for return a default value for a form.
	*/

	public function get_parameters_default()
	{

		return array($this->name_component, '', '');

	}
	
	/**
	* This method is used for simple checking, used for WhereSql.
	*/
	
	public function simple_check($value)
	{
	
		return $this->check($value);
	
	}
	
	
}






/**
* PrimaryField is used for primary keys for models
*
* PrimaryField is the most important and the only component used by default for models.
*/

class PrimaryField extends PhangoField {
	
	/**
	* Initial value for the field.
	*/
	
	public $value=0;
	
	/**
	* Initial label for the field. The label is used for create forms from a PhangoField.
	*/
	
	public $label="";
	
	/**
	* Boolean value that is used for check if the field is required for fill a row in the db model.
	*/
	
	public $required=0;
	
	/**
	* By default, the form used for this field is HiddenForm.
	*/
	
	public $form="HiddenForm";

	/**
	* Check function that convert the value on a PrimaryField value.
	*
	* @param string $value The value to convert on a PrimaryField value.
	*/
	
	public function check($value)
	{

		$this->value=Utils::form_text($value);
		settype($value, "integer");
		return $value;

	}
	
	/**
	* Method for return the sql type for this PhangoField
	*/
	
	public function get_type_sql()
	{

		return 'INT PRIMARY KEY AUTO_INCREMENT';

	}
	
	/**
	* Method for return a formatted value readable for humans.
	*/

	public function show_formatted($value)
	{

		return $value;

	}

}






/**
* Serializefield is a field if you need save serialize values
*/

class SerializeField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="TextForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $related_type='';
	
	//type_data can be any field type that is loaded IntegerField, etc..
	
	function __construct($related_type)
	{
		
		$this->related_type=&$related_type;
		
	}
	
	public $type_data='';

	//This method is used for check all members from serialize

	function recursive_form($value)
	{

		if(gettype($value)=="array")
		{

			foreach($value as $key => $value_key)
			{

				if(gettype($value_key)=="array")
				{

					$value[$key]=$this->recursive_form($value_key);

				}
				else
				{

					//Create new type.
					//$type_field=new $this->related_type();
				
					$value[$key]=$this->related_type->check($value_key);

				}

			}

		}

		return $value;

	}

	function check($value)
	{
		
		$value=$this->recursive_form($value);

		$this->value=$value;
		
		return webtsys_escape_string(serialize($value));

	}

	function get_type_sql()
	{

		return 'TEXT NOT NULL';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		$real_value=unserialize($value);
		
		return implode(', ', $return_value);

	}
	
	static function unserialize($value)
	{

		$real_value=@unserialize($value);
		
		if($real_value!==false)
		{
			return $real_value;
		}
		else
		{
		
			//$this->std_error='';
			return false;
		
		}

	}
	
}






/**
* Textfield is a field for long text values.
*/

class TextField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="TextAreaForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $multilang=0;
	public $br=1;

	function __construct($multilang=0)
	{

		$this->form='TextAreaForm';
		$this->multilang=$multilang;

	}

	function check($value)
	{
		
		//Delete Javascript tags and simple quotes.
		$this->value=$value;
		return Utils::form_text($value, $this->br);

	}

	//Function check_form

	function get_type_sql()
	{

		return 'TEXT NOT NULL';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', '');

	}
	
}





/**
* TextHTMLfield is a field for long text values based in html.
*/

class TextHTMLField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="TextAreaForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $multilang=0;

	//This variable is used for write rules what accept html tags

	public $allowedtags=array();

	function __construct($multilang=0)
	{

		$this->form='TextAreaForm';
		$this->multilang=$multilang;
		$this->set_safe_html_tags();

	}

	function check($value)
	{
		
		//Delete Javascript tags and simple quotes.
		
		$txt_without_tags=str_replace('&nbsp;', '', strip_tags($value, '<img>') );
		
		$txt_without_tags=trim(str_replace(' ', '', $txt_without_tags));
		
		if($txt_without_tags=='')
		{
		
			return '';
		
		}

		if(Utils::$textbb_type=='')
		{
			
			$this->value=Utils::unform_text($value);

		}
		else
		{
			
			$this->value=$value;

		}
		
		return Utils::form_text_html($value, $this->allowedtags);

	}

	//Methot for show the allowed html tags to the user

	function show_allowedtags()
	{

		$arr_example_tags=array();

		foreach($this->allowedtags as $tag => $arr_tag)
		{

			$arr_example_tags[]=htmlentities($arr_tag['example']);

		}
		
		return implode(', ', $arr_example_tags);

	}

	function get_type_sql()
	{

		return 'TEXT NOT NULL';
		

	}
	
	/**
	* This function is used for show the value on a human format
	*/

	public function show_formatted($value)
	{

		return $value;

	}

	function get_parameters_default()
	{

		return array($this->name_component, '', '');

	}

	function set_safe_html_tags()
	{

		$this->allowedtags['a']=array('pattern' => '/&lt;a.*?href=&quot;(http:\/\/.*?)&quot;.*?&gt;(.*?)&lt;\/a&gt;/', 'replace' => '<a_tmp href="$1">$2</a_tmp>', 'example' => '<a href=""></a>');
		$this->allowedtags['p']=array('pattern' => '/&lt;p.*?&gt;(.*?)&lt;\/p&gt;/s', 'replace' => '<p_tmp>$1</p_tmp>','example' => '<p></p>');
		$this->allowedtags['br']=array('pattern' => '/&lt;br.*?\/&gt;/', 'replace' => '<br_tmp />', 'example' => '<br />');
		$this->allowedtags['strong']=array('pattern' => '/&lt;strong.*?&gt;(.*?)&lt;\/strong&gt;/s', 'replace' => '<strong_tmp>$1</strong_tmp>', 'example' => '<strong></strong>');
		$this->allowedtags['em']=array('pattern' => '/&lt;em.*?&gt;(.*?)&lt;\/em&gt;/s', 'replace' => '<em_tmp>$1</em_tmp>', 'example' => '<em></em>');
		$this->allowedtags['i']=array('pattern' => '/&lt;i.*?&gt;(.*?)&lt;\/i&gt;/s', 'replace' => '<i_tmp>$1</i_tmp>', 'example' => '<i></i>');
		$this->allowedtags['u']=array('pattern' => '/&lt;u.*?&gt;(.*?)&lt;\/u&gt;/s', 'replace' => '<u_tmp>$1</u_tmp>', 'example' => '<u></u>');
		$this->allowedtags['blockquote']=array('pattern' => '/&lt;blockquote.*?&gt;(.*?)&lt;\/blockquote&gt;/s', 'replace' => '<blockquote_tmp>$1</blockquote_tmp>', 'example' => '<blockquote></blockquote>', 'recursive' => 1);
		//$this->allowedtags['img']=array('pattern' => '/&lt;img.*?alt=&quot;([aA-zZ]+)&quot;.*?src=&quot;('.str_replace('/', '\/', PhangoVar::$base_url).'\/media\/smileys\/[^\r\n\t<"].*?)&quot;.*?\/&gt;/', 'replace' => '<img_tmp alt="$1" src="$2"/>', 'example' => '<img alt="emoticon" src="" />');	

	}
	
}

?>