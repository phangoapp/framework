<?php

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
