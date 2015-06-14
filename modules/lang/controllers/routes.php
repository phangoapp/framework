<?php

function obtain_routes_from_app($route)
{
	
	$route->add_routes('index', 'index', $values=array('check_string'));

	return $route->retRoutes();	

}

?>
