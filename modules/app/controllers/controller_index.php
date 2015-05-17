<?php

use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaView\View;
use PhangoApp\PhaModels\Webmodel;
/*use Monolog\Logger;
use Monolog\Handler\StreamHandler;*/

class indexSwitchController extends Controller {

	public function index()
	{
	
		echo 'Hello world';
	
	}

	//A example with symlink
	
	public function page($integer, $string)
	{
		
		echo 'Pagina Id.'.$integer.' title:'.$string;
		
	}

}

?>
