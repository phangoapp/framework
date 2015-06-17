<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraForms\TextAreaBB\CkEditor
*
*
*/

/**
* Function used for load necessary javascript for use tinymce with all options on
*
*/

function load_jscript_editor_tinymce_all($name_editor, $value, $profiles='all', $class="textareabb")
{
	
	Utils::load_libraries(array('emoticons'));

	list($smiley_text, $smiley_img)=set_emoticons();
	
	/*ob_start();
	
	View::$header[]=ob_get_contents();
	
	ob_end_clean();*/
	
	//PhangoVar::$arr_cache_jscript[]='tinymce_path.js';
	View::$js[]='jquery.min.js';
	View::$js[]='textbb/tinymce/tinymce.min.js';

	$name_editor=Utils::slugify($name_editor);
	
	ob_start();
	
	?>
	
	<script type="text/javascript">
	
	
	$(document).ready( function () {
	
		
		tinymce.init({
		selector: "textarea.<?php echo $name_editor; ?>_class",
		theme: "modern",
		height: 300,
		plugins: [
			"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
			"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			"save table contextmenu directionality emoticons template paste textcolor"
		],
		file_browser_callback: function(field_name, url, type, win){
					var filebrowser = "<?php Routes::make_module_url('gallery', 'filebrowser', $method='index', $values=array(), $get=array()); ?>";
					tinymce.activeEditor.windowManager.open({
					title : "Insertar fichero",
					width : 520,
					height : 400,
					url : filebrowser
					}, {
					window : win,
					input : field_name
					});
					return false;
					}

		});
		
		

	});

	</script>

	<?php

View::$header[]=ob_get_contents();

ob_end_clean();
	
}

?>
