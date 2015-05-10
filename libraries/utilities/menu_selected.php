<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraUtils/Menus
*
*
*/

function menu_selected($activation, $arr_op, $type=0)
{

	echo load_view(array($activation, $arr_op, $type=0), 'common/utilities/menuselected');

}

?>
