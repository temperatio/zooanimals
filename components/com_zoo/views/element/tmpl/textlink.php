<?php
/**
* @package
* @version   1.0.0 05/04/2009
* @author    César Gómez http://www.temperatio.com
* @copyright Copyright (C) 2008 - 2009 Temperatio
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Check if there is a text to link
if($text == ''){
	$text = $value;
}

// Check the target parameter and compose additional link attributes according this
if($target == 'lightbox' || $target == 'shadowbox') {
	$sbattr = '';
	if($width > 0 && $height > 0){
		$sbattr = ';height=' . $height . ';width=' . $width;
	}
	$attributes = 'rel="' . $target . $sbattr .'"';
} else {
	$attributes = 'target="' . $target . '"';
}

echo JHTML::_('link', $value, $text, $attributes);