<?php

//Loader from composer.

if(is_file(__DIR__ . '/vendor/composer/autoload_classmap.php'))
{

	$classMap = require __DIR__ . '/vendor/composer/autoload_classmap.php';
	
	if(count($classMap)>0)
	{
		
		spl_autoload_register(function ($className) use ($classMap) {

		if (isset($classMap[$className])) {
		
			require $classMap[$className];
		
		}
		
		});
		
	}
	else
	{
	
		include(__DIR__.'/vendor/autoload.php');
		
	}

}

?>