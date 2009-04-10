<?php
/**
* @package
* @version   1.0.0 Tue Apr 07 2009 20:44:35 GMT+0200 (CEST)
* @author    César Gómez http://www.temperatio.com
* @copyright Copyright (C) 2008 - 2009 Temperatio
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

if($error == '') {

	$init = 0;
	$display = '';

	// Check Slideshow options
	$ssoptions = "animSequence:'sync',counterType:'skip'";
	if($auto == 1){
		$ssoptions .= ",slideshowDelay:" . $delay;
	}
	if($continuous == 1){
		$ssoptions .= ",continuous:true";
	}

	// Generate the first item if I need it.
	if($presentation == 'tlink') {
		$file = $files[$init++];
		$link = $path . '/' . $file;
		echo JHTML::_('link', $link, $title, 'rel="'.$look.'['.$gname.'];options={'.$ssoptions.'}" title="' . $title .'"');
	}elseif($presentation == '1stthn') {
		$file = $files[$init];
		$thumb = $thumbs[$init++];
		$link = $path . '/' . $file;
		$image = $path . '/gthumbs/' . $thumb;
		echo JHTML::_('link', $link, JHTML::_('image', $image, $title, 'title="' . $title .'" class="gthumb"'), 'rel="'.$look.'['.$gname.'];options={'.$ssoptions.'}" title="' . $title .'"');
	}

	for($i = $init; $i < count($files); $i++){

		$file = $files[$i];
		$link = $path . '/' . $file;
		if($presentation == 'allthn'){
			$thumb = $thumbs[$i];
			$image = $path . '/gthumbs/' . $thumb;
			$oblinked = JHTML::_('image', $image, $title, 'title="' . $title .'" class="gthumb"');
		} else {
			$oblinked = '';
		}
		echo JHTML::_('link', $link, $oblinked, 'rel="'.$look.'['.$gname.'];options={'.$ssoptions.'}" title="' . $title .'"');
	}
} else {
	echo '<span class="error">'.$error.'</span>';
}