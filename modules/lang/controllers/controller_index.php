<?php

class indexSwitchController extends ControllerSwitchClass {


	public function index($lang)
	{
		
		if(in_array($lang, I18n::$arr_i18n))
		{
		
			$_SESSION['language']=$lang;
			
			if($_SERVER['HTTP_REFERER']=='')
			{

				$_SERVER['HTTP_REFERER']=Routes::$root_url;

			}
			
			//http://localhost/phangodev/index.php/user/show/change_lang/change_language/language/en-US
			
			if(  preg_match('/\/lang\//', $_SERVER['HTTP_REFERER']) )
			{
			
				$_SERVER['HTTP_REFERER']=$base_url;
			
			}
			
			Routes::redirect($_SERVER['HTTP_REFERER']);
		
		}
	
	}

}

?>