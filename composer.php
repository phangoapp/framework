<?php

$arr_composer=array(

    "name" => "phangoapp/framework",
    "description" => "A framework for create nice apps",
    
     "license" => "GPL",
    "authors"=> [
        array(
            "name"=> "Antonio de la Rosa",
            "email"=> "webmaster@web-t-sys.com"
        )
    ],
    
    "minimum-stability" => "dev",

    "repositories" => [array(
            "type"=> "vcs",
            "url"=> "https://github.com/phangoapp/pharouter.git"
       ), 
       array(
            "type"=> "vcs",
            "url"=> "https://github.com/phangoapp/phaview.git"
        ),
	array(
            "type"=> "vcs",
            "url"=> "https://github.com/phangoapp/phamodels.git"
        ),
        array(
            "type"=> "vcs",
            "url"=> "https://github.com/phangoapp/phautils.git"
        ),
	array(
            "type"=> "vcs",
            "url"=> "https://github.com/phangoapp/phai18n.git"
        )
	],

    "require" => array(

	"phangoapp/pharouter"=> "dev-master",
        "phangoapp/phaview"=> "dev-master",
	"phangoapp/phamodels"=> "dev-master",
	"phangoapp/phautils"=> "dev-master",
	"phangoapp/phai18n"=> "dev-master",
	"ext-gd"=> "*",
	"ext-libxml"=> "*",
	"league/climate"=> "dev-master"
	
    )

);

?>