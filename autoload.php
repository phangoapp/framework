<?php

spl_autoload_register(function ($class) {

	
	//die;
	
	$arr_class=explode('\\', $class);
	
	array_shift($arr_class);
	
	if(isset($arr_class[1]))
	{
	
		$dir_file=strtolower($arr_class[0]);
		
		array_shift($arr_class);
		
		$php_file=implode('/', $arr_class).'.php';
		
		//unset($arr_class[count($arr_class)-1]);
		
		$path_file=__DIR__.'/vendor/'.$dir_file.'/src/'.$php_file;
		
		//echo __DIR__.'/'.$dir_file.'/src/'.$php_file.'<p>';
		
		require($path_file);
		
	}
	else
	{
	
		return;
	
	}
	
	
  
});
