<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraUtils
*
*
*/

/**
* A simple function used for obtain icons for html forms.
*
*/

function set_emoticons()
{

	$smiley_text=array();
	$smiley_img=array();
	//
	$smiley_text[0] = ":D";
	$smiley_img[0] = View::getMediaUrl("images/smileys/icon_biggrin.gif");

	$smiley_text[1] = ":)";
	$smiley_img[1] = View::getMediaUrl("images/smileys/icon_smile.gif");

	$smiley_text[2] = ":(";
	$smiley_img[2] = View::getMediaUrl("images/smileys/icon_sad.gif");

	$smiley_text[3] = ":o";
	$smiley_img[3] = View::getMediaUrl("images/smileys/icon_surprised.gif");

	$smiley_text[4] = ":shock:";
	$smiley_img[4] = View::getMediaUrl("images/smileys/icon_eek.gif");

	$smiley_text[5] = "8)";
	$smiley_img[5] = View::getMediaUrl("images/smileys/icon_cool.gif");

	$smiley_text[6] = ":lol:";
	$smiley_img[6] = View::getMediaUrl("images/smileys/icon_lol.gif");

	$smiley_text[7] = ":x";
	$smiley_img[7] = View::getMediaUrl("images/smileys/icon_mad.gif");

	$smiley_text[8] = ":P";
	$smiley_img[8] = View::getMediaUrl("images/smileys/icon_razz.gif");

	$smiley_text[9] = ":oops:";
	$smiley_img[9] = View::getMediaUrl("images/smileys/icon_redface.gif");
		
	$smiley_text[10] = ":cry:";
	$smiley_img[10] = View::getMediaUrl("images/smileys/icon_cry.gif");

	$smiley_text[11] = ":evil:";
	$smiley_img[11] = View::getMediaUrl("images/smileys/icon_evil.gif");

	$smiley_text[12] = ":twisted:";
	$smiley_img[12] = View::getMediaUrl("images/smileys/icon_twisted.gif");

	$smiley_text[13] = ":roll:";
	$smiley_img[13] = View::getMediaUrl("images/smileys/icon_rolleyes.gif");

	$smiley_text[14] = ":wink:";
	$smiley_img[14] = View::getMediaUrl("images/smileys/icon_wink.gif");

	$smiley_text[15] = ":quest:";
	$smiley_img[15] = View::getMediaUrl("images/smileys/icon_question.gif");

	$smiley_text[16] = ":exclaim:";
	$smiley_img[16] = View::getMediaUrl("images/smileys/icon_exclaim.gif");

	/*$smiley_text[19] = ":porro:";
	$smiley_img[19] = View::getMediaUrl("images/smileys/icon_arrow.gif");*/

	$smiley_text[17] = ":neutral:";
	$smiley_img[17] = View::getMediaUrl("images/smileys/icon_neutral.gif");

	$smiley_text[18] = ":confused:";
	$smiley_img[18] = View::getMediaUrl("images/smileys/icon_confused.gif");

	$smiley_text[19] = ":idea:";
	$smiley_img[19] = View::getMediaUrl("images/smileys/icon_idea.gif");

	if(is_dir(PhangoVar::$base_path . 'application/media/smileys/smileys_user'))
	{
		if ( $handle = opendir( PhangoVar::$base_path . 'application/media/smileys/smileys_user' ) )
		{
		while ( false !== ( $file = readdir( $handle ) ) )
		{
			if ( !preg_match('/^\./', $file) )
			{	
			$name_file = substr( $file, 0, strlen( $file )-4 );
			$smiley_text[] = ":$name_file:";
			$smiley_img[] = $file;
			} 
		} 
		closedir( $handle );
		}
	}

	return array($smiley_text, $smiley_img);

} 


?>
