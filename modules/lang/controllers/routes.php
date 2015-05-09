<?php

function obtain_routes_from_app($route)
{
	
	$route->addRoutes('index', 'index', $values=array('checkString'));

	return $route->retRoutes();	

}

?>
