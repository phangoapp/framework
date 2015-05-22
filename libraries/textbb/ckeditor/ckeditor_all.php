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
* Function used for load necessary javascript for use ckeditor with all options on
*
*/

function load_jscript_editor_ckeditor_all($name_editor, $value, $profiles='all')
{
	
	Utils::load_libraries(array('emoticons'));

	list($smiley_text, $smiley_img)=set_emoticons();
	
	/*ob_start();
	
	View::$header[]=ob_get_contents();
	
	ob_end_clean();*/
	
	//PhangoVar::$arr_cache_jscript[]='ckeditor_path.js';
	View::$js[]='jquery.min.js';
	View::$js[]='textbb/ckeditor/ckeditor.js';

	ob_start();
	
	?>
	
	<script type="text/javascript">
		// This call can be placed at any point after the
		// <textarea>, or inside a <head><script> in a
		// window.onload event handler.

		// Replace the <textarea id="editor"> with an CKEditor
		// instance, using default configurations.
	<?php
	/*filebrowserImageBrowseUrl : '<?php echo make_fancy_url(PhangoVar::$base_url, 'jscript', 'browser_image', 'browser_image', array()); ?>',
			
			filebrowserBrowseUrl: '<?php echo make_fancy_url(PhangoVar::$base_url, 'jscript', 'browser_image', 'browser_image', array()); ?>',*/
	?>
	
	$(document).ready( function () {
	
		//CKEDITOR.config.baseHref='<?php echo PhangoVar::$base_url; ?>/media/jscript/textbb/ckeditor/';
		
		CKEDITOR.replace( '<?php echo $name_editor; ?>' );
	
		/*CKEDITOR.replace( '<?php echo $name_editor; ?>' , 
		{
			//Here, function, load_profile
			//extraPlugins : 'bbcodeweb,devtools',
			//removePlugins: 'flash,div,filebrowser,flash,format,forms,horizontalrule,iframe',
			
			filebrowserWindowWidth : '800',
			filebrowserWindowHeight : '600',
			
			removePlugins: 'div,forms,iframe',
			enterMode : CKEDITOR.ENTER_BR,
			language: '<?php echo PhangoVar::$arr_i18n_ckeditor[I18n::$language]; ?>',

			smiley_columns: 10,
			smiley_path: [''], //['<?php echo PhangoVar::$base_url; ?>/media/smileys/'],
			smiley_images :
			[
				
				<?php
				echo '\''.implode('\',\'', $smiley_img).'\'';

				?>
				
			],
			
			smiley_descriptions :
			[
				
				<?php

				$arr_smiley=array();

				$c=count($smiley_img);

				for($x=0;$x<$c;$x++)
				{

					$arr_smiley[]=basename( preg_replace('/icon_(.*?).gif/', '$1', $smiley_img[$x]) );

				}

				echo '\''.implode('\',\'', $arr_smiley).'\'';

				?> 
			
			]

		}
	);*/

});

	</script>

	<?php

View::$header[]=ob_get_contents();

ob_end_clean();
	
}

?>
