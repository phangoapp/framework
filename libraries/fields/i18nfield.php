<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file i18n_fields.php
* @package ExtraFields\I18nFields
*
*
*/

/**
* Multilanguage fields. 
*
* With this field you can create fields for i18n sites.
*/

class I18nField extends PhangoField {

	public $value="";
	public $label="";
	public $required=0;
	public $form="MultiLangForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $related_field='';
	public $type_field='';

	//This method is used for check all members from serialize

	function __construct($type_field)
	{

		$this->type_field=&$type_field;

	}

	function check($value)
	{
		
		settype($value, 'array');
		
		foreach(I18n::$arr_i18n as $lang_item)
		{

			settype($value[$lang_item], 'string');
		
			$value[$lang_item]=$this->type_field->check($value[$lang_item]);

		}

		if($this->required==1 && $value[I18n::$language]=='')
		{

			$this->std_error=I18n::$lang['common']['error_you_need_this_language_field'].' '.I18n::$language;

			return '';

		}
		
		$ser_value=addslashes(serialize($value));

		return $ser_value;

	}
	
	/*static function set_valid_value($value, $language, $arr_lang)
	{
	
		$arr_lang[$language]=$value;
		
		return $arr_lang;
	
	}
	
	static function set_valid_arr_value($arr_value)
	{
		$arr_lang=array();
	
		foreach($arr_value as $lang => $value)
		{
		
			$arr_lang=I18nField::set_valid_value($value, $language, $arr_lang);
		
		}
		
		return $arr_lang;
	
	}*/

	function get_type_sql()
	{

		return 'TEXT NOT NULL';
		

	}

	static function show_formatted($value)
	{

		$arr_lang=@unserialize($value);

		settype($arr_lang, 'array');
		
		settype($arr_lang[I18n::$language], 'string');

		settype($arr_lang[I18n::$language], 'string');

		if($arr_lang[I18n::$language]=='' && $arr_lang[I18n::$language]=='')
		{
			
			//Need  view var with text...
			
			//$arr_lang_first=array_unique($arr_lang);
			foreach($arr_lang as $key_lang => $val_lang)
			{
			
				if($val_lang!='')
				{
				
					return $val_lang;
				
				}
			
			}

		}
		else if($arr_lang[I18n::$language]=='')
		{
			
			return $arr_lang[I18n::$language];
		
		}
		
		return $arr_lang[I18n::$language];

	}
	
	function add_slugify_i18n_post($field, $post)
	{
	
	
		foreach(I18n::$arr_i18n as $lang_field)
		{
		
			$post[$field.'_'.$lang_field]=SlugifyField::check($post[$field][$lang_field]);
		
		}
		
		return $post;
	
	}
	
}

/** This class can be used for create orders or searchs in mysql if you need other thing distinct to default search of default order (default order don't work fine with serializefields how i18nfield). The programmer have the responsability of update this fields via update or insert method.
*
*/

class SlugifyField extends PhangoField {


	public $value="";
	public $label="";
	public $required=0;
	public $form="TextForm";
	public $quot_open='\'';
	public $quot_close='\'';
	public $std_error='';
	public $type='TEXT';

	static function check($value)
	{
		
		return Utils::slugify($value);
	}

	function get_type_sql()
	{

		return $this->type.' NOT NULL';
		

	}
	
	static function add_slugify_i18n_fields($model_name, $field)
	{
	
		foreach(I18n::$arr_i18n as $lang_field)
		{

			Webmodel::$model[$model_name]->components[$field.'_'.$lang_field]=new SlugifyField();
			
		}
	
	}
	
}




function MultiLangForm($field, $class='', $arr_values=array(), $type_form='TextForm')
{
	//make a foreach with all langs
	//default, es_ES, en_US, show default if no exists translation for selected language.

	ob_start();

	//echo $type_form($field, 'hidden_form', 'control_field');

	if(gettype($arr_values)!='array')
	{

		$arr_values = @unserialize( $arr_values );
		
		if(gettype($arr_values)!='array')
		{

			$arr_values=array();
			
		}
		
	}
	
	
	foreach(I18n::$arr_i18n as $lang_select)
	{

		$arr_selected[Utils::slugify($lang_select)]='hidden_form';
		$arr_selected[Utils::slugify(I18n::$language)]='no_hidden_form';
		
		settype($arr_values[$lang_select], 'string');
		echo '<div class="'.$arr_selected[Utils::slugify($lang_select)].'" id="'.$field.'_'.$lang_select.'">';
		echo $type_form($field.'['.$lang_select.']', '', $arr_values[$lang_select]);
		echo '</div>';

	}
	?>
	<div id="languages">
	<?php

	$arr_selected=array();

	foreach(I18n::$arr_i18n as $lang_item)
	{
		//set

		$arr_selected[Utils::slugify($lang_item)]='no_choose_flag';
		$arr_selected[Utils::slugify(I18n::$language)]='choose_flag';

		?>
		<a class="<?php echo $arr_selected[Utils::slugify($lang_item)]; ?>" id="<?php echo $field.'_'.$lang_item; ?>_flag" href="#" onclick="change_form_language_<?php echo $field; ?>('<?php echo $field; ?>', '<?php echo $field.'_'.$lang_item; ?>'); return false;"><img src="<?php echo View::getMediaUrl('images/languages/'.$lang_item.'.png'); ?>" alt="<?php echo $lang_item; ?>"/></a>&nbsp;
		<?php

	}

	?>
	</div>
	<hr />
	<script language="Javascript">
		
		function change_form_language_<?php echo $field; ?>(field, lang_field)
		{

			if(typeof jQuery == 'undefined') 
			{
				alert('<?php echo I18n::$lang['common']['cannot_load_jquery']; ?>');
				return false;

			}

			<?php

			foreach(I18n::$arr_i18n as $lang_item)
			{

				?>
				$("#<?php echo $field.'_'.$lang_item; ?>").hide();//removeClass("no_hidden_form").addClass("hidden_form");
				$("#<?php echo $field.'_'.$lang_item; ?>_flag").removeClass("choose_flag").addClass("no_choose_flag");
				<?php

			}

			?>
			
			lang_field=lang_field.replace('[', '\\[');
			lang_field=lang_field.replace(']', '\\]');

			$("#"+lang_field).show();//.removeClass("hidden_form").addClass("no_hidden_form");
			$("#"+lang_field+'_flag').removeClass("no_choose_flag").addClass("choose_flag");
			
		}

	</script>
	<?php


	$text_form=ob_get_contents();

	ob_end_clean();

	return $text_form;

}

function MultiLangFormSet($post, $value)
{
	
	if(!gettype($value)=='array')
	{

		settype($arr_value, 'array');

		$arr_value = @unserialize( $value );
		
		return $arr_value;

	}
	else
	{

		return $value;

	}

}


?>
