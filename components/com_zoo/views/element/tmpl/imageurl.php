<?php
/**
* @package   Zoo Component
* @version   1.0.1 2009-03-20 11:31:22
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) 2007 - 2009 YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<?php if ($url) : ?>
	<img src="<?php echo $link; ?>" title="<?php echo $title; ?>" alt="<?php echo $title; ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" />
<?php else : ?>
	<?php echo JText::_('No file selected.'); ?>
<?php endif; ?>