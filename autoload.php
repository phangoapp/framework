<?php

//Loader from composer.

if(is_file(__DIR__ . '/vendor/composer/autoload_classmap.php'))
{

	/*$classMap = require __DIR__ . '/vendor/composer/autoload_classmap.php';
	
	if(count($classMap)>0)
	{
		
		spl_autoload_register(function ($className) use ($classMap) {
		
		if (isset($classMap[$className])) {
		
			require $classMap[$className];
		
		}
		
		});
		
	}
	else
	{*/
	
		require(__DIR__.'/vendor/autoload.php');
		
	//}

}

//Internal functions autoloading.
/*
spl_autoload_register(function ($className) {

	//Need caching using arrays...

	$prefix='PhangoApp\\Framework\\';
	
	if(strpos($className, $prefix)!==false)
	{
		
		$path=__DIR__.'/src/'.str_replace('\\', '/', str_replace($prefix, '', $className) ).'.php';
		
		require $path;
	
	}
	else
	{
	
		return;
	
	}
	
});*/

?>