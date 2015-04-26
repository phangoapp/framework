<?php

//include(__DIR__.'/vendor/autoload.php');
//echo __DIR__ . '/vendor/composer/autoload_classmap.php';

/*$files = require __DIR__ . '/vendor/composer/autoload_files.php';
foreach ($files as $file) {
    require $file;
}*/

//Loader from composer.

if(is_file(__DIR__ . '/vendor/composer/autoload_classmap.php'))
{

	$classMap = require __DIR__ . '/vendor/composer/autoload_classmap.php';

	spl_autoload_register(function ($className) use ($classMap) {

	if (isset($classMap[$className])) {
	
		require $classMap[$className];
	
	}
	
	});
	
}

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
		
		if(is_file($path_file))
		{
		
			require($path_file);
			
		}
		else
		{
		
			return;
		
		}
		
	}
	else
	{
	
		return;
	
	}
	
	
  
});


