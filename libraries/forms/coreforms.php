<?php
/**
* Base file include where basic function and methods for create MVC applications
*
* This file contains principal functions and methods for create models, text formatting, forms creation, definition of basic variables, basic ORM that use on 90% of db searchs, etc...
*
* Functions used for generate forms from models 
* This functions are called via $model->form
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package CoreForms
*
*/

/* Function form used for text fields on a form. Show a text html input.
*  
*
* @param string $name Name of this text field for use in forms
* @param string $class Css class used in the text field
* @param string $value Initial value for the form
*/

function TextForm($name="", $class='', $value='')
{

	return '<input type="text" name="'.$name.'" id="'.$name.'_field_form" class="'.$class.'" value="'.$value.'" />';

}

//Prepare a value for input text

function TextFormSet($post, $value)
{

	$value = Utils::replace_quote_text( $value );
	return $value;

}

//Create a input password

function PasswordForm($name="", $class='', $value='')
{

	$value = Utils::replace_quote_text( $value );

	return '<input type="password" name="'.$name.'" class="'.$class.'" id="'.$name.'_field_form" value="'.$value.'" />';

}

//Prepare a value for input password

function PasswordFormSet($post, $value)
{

	$value = ''; //Utils::replace_quote_text( $value );

	return $value;

}

//Create a input file

function FileForm($name="", $class='', $value='', $delete_inline=0, $path_file='')
{
	
	

	$file_url=$path_file.'/'.$value;
	
	$file_exist='';

	if($value!='')
	{

		$file_exist='<a href="'.$file_url.'">'.basename($value).'</a> ';
		
		if($delete_inline==1)
		{

			$file_exist.=I18n::lang('common', 'delete_file', 'Delete file').' <input type="checkbox" name="delete_'.$name.'" class="'.$class.'" value="1" />';

		}

	}

	return '<input type="hidden" name="'.$name.'" value="'.$value.'"/><input type="file" name="'.$name.'" class="'.$class.'" value="" /> '.$file_exist;

}

//Prepare a value for input password

function FileFormSet($post, $value)
{
	
	$value = Utils::replace_quote_text( $value );

	return $value;

}


//Create a special form for a image

function ImageForm($name="", $class='', $value='', $delete_inline=0, $path_image='')
{

	$image_url=$path_image.'/'.$value;
	
	$image_exist='';

	if($value!='')
	{

		$image_exist='<a href="'.$image_url.'">'.basename($value).'</a> ';
		
		if($delete_inline==1)
		{

			$image_exist.=I18n::lang('common', 'delete_image', 'Delete image').' <input type="checkbox" name="delete_'.$name.'" class="'.$class.'" value="1" />';

		}

	}

	return '<input type="hidden" name="'.$name.'" value="'.$value.'"/><input type="file" name="'.$name.'" class="'.$class.'" value="" /> '.$image_exist;

}

//Prepare a value for input password

function ImageFormSet($post, $value)
{
	
	$value = Utils::replace_quote_text( $value );

	return $value;

}

//Create a textarea 

function TextAreaForm($name="", $class='', $value='')
{

	return '<textarea name="'.$name.'" class="'.$class.'" id="'.$name.'_field_form">'.$value.'</textarea>';

}

//Prepare the value for the textarea

function TextAreaFormSet($post, $value)
{

	$value = Utils::replace_quote_text( $value );

	return $value;

}

//Create a input hidden

function HiddenForm($name="", $class='', $value='')
{

	return '<input type="hidden" name="'.$name.'" value="'.$value.'" id="'.$name.'_field_form"/>';

}

//Prepare the value for a input hidden

function HiddenFormSet($post, $value)
{

	$value = Utils::replace_quote_text( $value );

	return $value;

}

//Create a input checkbox

function CheckBoxForm($name="", $class='', $value='')
{
	
	$arr_checked[$value]='';

	$arr_checked[0]='';
	$arr_checked[1]='checked';
	
	return '<input type="checkbox" name="'.$name.'" value="1" id="'.$name.'_field_form" class="'.$class.'" '.$arr_checked[$value].'/>';

}

//Prepare the value for the checkbox

function CheckBoxFormSet($post, $value)
{

	settype($value, 'integer');
	
	return $value;

}

//Create a select

function SelectForm($name="", $class='', $value='', $more_options='')
{
	
	$select='<select name="'.$name.'" id="'.$name.'_field_form" class="'.$class.'" '.$more_options.'>'."\n";

	list($key, $default)= each($value);

	$arr_selected=array();

	$arr_selected[$default]="selected=\"selected\"";

	//Check if array is safe. 
	
	$z=count($value);
	
	for($x=1;$x<$z;$x+=2)
	{
		
		$val=$value[$x+1];
		
		settype($val, "string");
		settype($arr_selected[$val], "string");

		if($val=='optgroup')
		{
    			$select.='<optgroup label="'.$value[$x].'">';
		}
		else 
		if($val=="end_optgroup")
		{

			$select.='</optgroup>';

		}
		else
		{

			$select.= '<option value="'.$val.'" '.$arr_selected[$val].'>'.$value[$x].'</option>'."\n";

		}
	}

	$select.='</select>'."\n";

	return $select;

}

//Prepare the value for the select

function SelectFormSet($post, $value)
{
	
	$value = preg_replace('/<(.*?)\/(.*?)option(.*?)>/', '', $value);
	
	$post[0]=$value;
	
	return $post;

}

//Crate a multiple select

function SelectManyForm($name="", $class='', $value='', $more_options='' )
{
	
	$select='<select name="'.$name.'[]" id="'.$name.'_field_form" class="'.$class.'" '.$more_options.' multiple>'."\n";

	list($key, $arr_values)= each($value);
	
	$arr_selected=array();
	
	foreach($arr_values as $default)
	{

		$arr_selected[$default]="selected";

	}

	//Check if array is safe. 
	
	$z=count($value);
	
	for($x=1;$x<$z;$x+=2)
	{
		
		$val=$value[$x+1];
		
		settype($val, "string");
		settype($arr_selected[$val], "string");

		if($val=='optgroup')
		{
    			$select.='<optgroup label="'.$value[$x].'">';
		}
		else 
		if($val=="end_optgroup")
		{

			$select.='</optgroup>';

		}
		else
		{

			$select.= '<option value="'.$val.'" '.$arr_selected[$val].'>'.$value[$x].'</option>'."\n";

		}
	}

	$select.='</select>'."\n";

	return $select;

}

//Prepare the value for the multiple select

function SelectManyFormSet($post, $value)
{
	
	if(gettype($value)!='array')
	{
	
		$arr_value=unserialize($value);
	}
	else
	{
	
		$arr_value=$value;
	
	}
	//$value = preg_replace('/<(.*?)\/(.*?)option(.*?)>/', '', $value);
	
	$post[0]=$arr_value;
	
	return $post;

}

//A special form for dates in format day/month/year

function DateForm($field, $class='', $value='', $set_time=1, $see_title=1)
{
	
	if($value==0)
	{

		$day='';
		$month='';
		$year='';
		$hour='';
		$minute='';
		$second='';

	}
	else
	{
		
		//$value+=$user_data['format_time'];
		
		$day=date('j', $value);
		$month=date('n', $value);
		$year=date('Y', $value);
		$hour=date('G', $value);
		$minute=date('i', $value);
		$second=date('s', $value);
	}
	
	$date='<span id="'.$field.'_field_form" class="'.$class.'">';
	
	if($set_time<=1)
	{

		$date.='<input type="text" name="'.$field.'[]" value="'.$day.'" size="2" maxlength="2"/>'."\n";
		$date.='<input type="text" name="'.$field.'[]" value="'.$month.'" size="2" maxlength="2"/>'."\n";
		$date.='<input type="text" name="'.$field.'[]" value="'.$year.'" size="4" maxlength="4"/>'."\n&nbsp;&nbsp;&nbsp;";
		
	}
	
	if($set_time>0)
	{
	
		$hour_txt=I18n::lang('common', 'hour', 'Hour');
		$minute_txt=I18n::lang('common', 'minute', 'Minute');
		$second_txt=I18n::lang('common', 'second', 'Second');
		
		if($see_title==0)
		{
		
			$hour_txt='';
			$minute_txt='';
			$second_txt='';
		
		}

		$date.=$hour_txt.' <input type="text" name="'.$field.'[]" value="'.$hour.'" size="2" maxlength="2" />'."\n";
		$date.=$minute_txt.' <input type="text" name="'.$field.'[]" value="'.$minute.'" size="2" maxlength="2" />'."\n";
		$date.=$second_txt.' <input type="text" name="'.$field.'[]" value="'.$second.'" size="2" maxlength="2" />'."\n";
		
	}

	echo '</span>';
	
	return $date;

}

//Prepare value form dateform

function DateFormSet($post, $value)
{

	if(gettype($value)=='array')
	{
		foreach($value as $key => $val)
		{

			settype($value[$key], 'integer');

		}
		
		settype($value[3], 'integer');
		settype($value[4], 'integer');
		settype($value[5], 'integer');
		
		$final_value=mktime ($value[3], $value[4], $value[5], $value[1], $value[0], $value[2] );

	}
	else
	{

		settype($value, 'integer');

		$final_value=$value;

	}


	return $final_value;

}

function RadioIntForm($name="", $class='', $value=array(), $more_options='')
{
	$select='';

	list($key, $default)= each($value);

	$arr_selected=array();

	$arr_selected[$default]="checked";

	//Check if array is safe. 

	$z=count($value);
	
	for($x=1;$x<$z;$x+=2)
	{
	
		$val=$value[$x+1];
		
		settype($arr_selected[$val], "string");
	
		$select.= $value[$x].' <input type="radio" name="'.$name.'" value="'.$val.'" '.$arr_selected[$val].' />'."\n";
		
	}

	return $select;

}

//Prepare the value for the select

function RadioIntFormSet($post, $value)
{
	
	settype($value, 'integer');

	$post[0]=$value;
	
	return $post;

}

?>