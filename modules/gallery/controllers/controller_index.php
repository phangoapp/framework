<?php

class indexSwitchController extends ControllerSwitchClass {

	public function index()
	{
	
		Utils::load_libraries();
	
		I18n::load_lang('gallery');
		
		ob_start();
		
		
		
		$content=ob_get_contents();
		
		ob_end_clean();

		echo View::load_view(array('title' => I18n::lang('gallery', 'gallery', 'Gallery'), 'content' => $content), 'common/common');

	}

}

?>