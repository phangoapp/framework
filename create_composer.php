<?php

/**
* Cli for generate composer.json files for install the system...
*
*/

/*
ob_start();

?>

{

	"name": "phangoapp/framework",
	"description": "A framework for create nice apps",
	
	"license": "GPL",
	"authors": [
		{
		"name": "Antonio de la Rosa",
		"email": "webmaster@web-t-sys.com"
		}
	],
	"minimum-stability": "dev",

	"repositories": [{
		"type": "vcs",
		"url": "https://github.com/phangoapp/pharouter.git"
		}, 
		{
		"type": "vcs",
		"url": "https://github.com/phangoapp/phaview.git"
		},
		{
		"type": "vcs",
		"url": "https://github.com/phangoapp/phamodels.git"
		},
		{
		"type": "vcs",
		"url": "https://github.com/phangoapp/phautils.git"
		},
		{
		"type": "vcs",
		"url": "https://github.com/phangoapp/phai18n.git"
		}
		],

	"require": {

		"phangoapp/pharouter": "dev-master",
		"phangoapp/phaview": "dev-master",
		"phangoapp/phamodels": "dev-master",
		"phangoapp/phautils": "dev-master",
		"phangoapp/phai18n": "dev-master",
		"ext-gd": "*",
		"ext-libxml": "*",
		"league/climate": "dev-master"
		
	}
}

<?php

$cont_json=ob_get_contents();

ob_end_clean();

$arr_composer=json_decode($cont_json, true);*/

include('composer.php');


$root_dir=__DIR__.'/modules/';

$arr_dir=scandir($root_dir);

//print_r($arr_dir);

foreach($arr_dir as $dir)
{

	$arr_extra=array();

	if(!preg_match('/^\./', $dir))
	{

		//echo $dir."\n";
		
		$final_file=$root_dir.$dir.'/extra_composer.php';
		
		if(is_file($final_file))
		{
		
			include($final_file);
		
			/*$extra_json=file_get_contents($final_file);
			
			//Check json file
			
			if(!($arr_extra=json_decode($extra_json, true)))
			{
			
				echo "Error: json malformed on ${final_file}\n";
				die;
			
			}
			
			//print_r($arr_extra);
			
			//$arr_composer['includes']=array( $final_file => array());
			//"\"includes\": { \"modules/extra.json\": {} }" 
			
			foreach($arr_extra as $key_item => $item)
			{
			
				//$arr_composer[$key_item][]=$item;
			
			}*/
			
			
		
		}
		
	}

}


echo json_encode($arr_composer, JSON_PRETTY_PRINT)."\n";

?>