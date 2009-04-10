<?php
/**
* @package   Zoo Component
* @version   1.0.0 Sun Apr 05 2009 17:24:47 GMT+0200 (CEST)
* @author    César Gómez http://www.temperatio.com
* @copyright Copyright (C) 2008 - 2009 Temperatio
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register parent class
JLoader::register('ElementSimpleParams', ZOO_ADMIN_PATH.'/elements/simpleparams/simpleparams.php');

/*
   Class: ElementText
       The text element class
*/
class ElementImageGallery extends ElementSimpleParams {

	/*
	   Function: Constructor
	*/
	function ElementImageGallery() {

		// call parent constructor
		parent::ElementSimpleParams();

		// init vars
		$this->type  = 'imagegallery';

		// set defaults
		$params =& JComponentHelper::getParams('com_media');
		$this->directory = $params->get('image_path');
	}


	/*
		Function: render
			Renders the element.

	   Parameters:
            $view - current view

		Returns:
			String - html
	*/
	function render($view = ZOO_VIEW_ITEM) {
		jimport('joomla.filesystem.file');

		// get parameters
		$params	=& $this->getParams();
		$title			= $params->get('title');
		$look			= $params->get('look');
		$path   		= trim($this->directory, '/').'/'.$this->value;
		$presentation 	= $params->get('presentation');
		$thumbshape 	= $params->get('thumbshape');
		$thumblong	 	= $params->get('thumblong',64);
		$auto		 	= $params->get('auto',0);
		$continuous 	= $params->get('continuous',0);
		$delay		 	= $params->get('delay',5);
		$gname			= $this->name;

		//Build data structures
		$mediaparams =& JComponentHelper::getParams('com_media');
		$img_ext = str_replace(',', '|', trim($mediaparams->get('image_extensions'), ','));

		// Read image files form directory
		$files	= YFile::readDirectoryFiles($path, '', '/^.*('.$img_ext.')$/i', false);
		$thumbs	=  array();
		$error = '';
		//Check if need to generate thumbtails
		if($presentation == '1stthn' || $presentation == 'allthn'){
			foreach($files as $file){
				// get name and ext
				$ext =JFile::getExt($file);
				$name = JFile::stripExt($file);
				$thumbs[] =  $thumbshape . '_' . $thumblong . '_' . $name . '.jpg';
				// Create the thumbs forder if not exits

				if(!file_exists(JPATH_ROOT . '/' . $path . '/gthumbs')){
					if(!mkdir(JPATH_ROOT . '/' . $path . '/gthumbs')){
						$error = JText::_('Can\'t create thumbnails folder');
						break;
					}
				}
				$filepath = JPATH_ROOT . '/' . $path . '/' . $file;

				$thumbpath = JPATH_ROOT . '/' . $path . '/gthumbs/' . $thumbshape . '_' . $thumblong . '_' . $name . '.jpg';

				//generate the thumbnails if needed
				if(!file_exists($thumbpath)){
					$this->createThumb($filepath, $thumbpath, $thumblong, $thumbshape);
				}

				// Break if I only need the first thumbnail
				if($presentation == '1stthn'){
					break;
				}
			}
		}

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout(
				$layout, array(
					'error' 		=> $error,
					'path' 			=> $path,
					'title' 		=> $title,
					'look' 			=> $look,
					'files' 		=> $files,
					'thumbs' 		=> $thumbs,
					'auto' 			=> $auto,
					'delay' 		=> $delay,
					'continuous'	=> $continuous,
					'gname' 		=> $gname,
					'presentation'	=> $presentation
				)
			);
		}

		return null;
	}

	/*
	   Function: edit
	       Renders the edit form field.

	   Returns:
	       String - html
	*/
	function edit() {

		$document =& JFactory::getDocument();

		// init vars
		$params			=& $this->getParams();
		$value			= $this->value;
		$title  		= htmlspecialchars(html_entity_decode($params->get('title'), ENT_QUOTES), ENT_QUOTES);
		$look			= $params->get('look');
		$presentation 	= $params->get('presentation');
		$thumbshape 	= $params->get('thumbshape');
		$thumblong	 	= $params->get('thumblong',64);
		$auto		 	= $params->get('auto',0);
		$continuous 	= $params->get('continuous',0);
		$delay		 	= $params->get('delay',5);

		//create javascript

		$js = "window.addEvent('domready', function(){
	if($('". $this->name ."_paramslook').value == 'shadowbox'){

	}
	$('". $this->name ."_paramslook').addEvent('change', function(event){
		if($('". $this->name ."_paramslook').value == 'shadowbox'){
			$('". $this->name ."_sbwarn').innerHTML = '".JText::_('Remember: You must activate shadowbox script.')."';
		} else {
			$('". $this->name ."_sbwarn').innerHTML = '';
		}
	});
});";
		$document->addScriptDeclaration($js);

		// create html
		$html  = '<table>';
		$html .= JHTML::_('element.editrow', JText::_('Images Folder'), JHTML::_('control.selectdirectory', JPATH_ROOT.DS.$this->directory, '', $this->name.'_value', $value));
		$html .= JHTML::_('element.editrow', JText::_('Title'), JHTML::_('control.text', $this->name.'_params[title]', $title, 'size="40" maxlength="255"'));

		// prepare the look options
		$lookoptions = array();
		$lookoptions[] = JHTML::_('select.option', 'lightbox', JText::_('Lightbox'));
		$lookoptions[] = JHTML::_('select.option', 'shadowbox', JText::_('Shadowbox'));

		// render the options
		$html .= JHTML::_('element.editrow', JText::_('Look'), JHTML::_('select.genericlist', $lookoptions, $this->name.'_params[look]', null, 'value', 'text', $look) . ' <span class="sbwarn" id="'. $this->name .'_sbwarn"></span>');

		// prepare the presentations options
		$presoptions = array();
		$presoptions[] = JHTML::_('select.option', 'tlink', JText::_('Title Link'));
		$presoptions[] = JHTML::_('select.option', '1stthn', JText::_('1st Thumbnail'));
		$presoptions[] = JHTML::_('select.option', 'allthn', JText::_('All Thumbnails'));

		$html .= JHTML::_('element.editrow', JText::_('Presentation'), JHTML::_('select.genericlist', $presoptions, $this->name.'_params[presentation]', null, 'value', 'text', $presentation));

		$html .= '<td colspan="2" style="color:#666666; text-align:center;">'.JText::_('Thumbnails Options').'</td>';
		// prepare the thumbnails options
		$thumboptions = array();
		$thumboptions[] = JHTML::_('select.option', 'sq', JText::_('Squared'));
		$thumboptions[] = JHTML::_('select.option', 'ka', JText::_('Keep Aspect Ratio'));

		$html .= JHTML::_('element.editrow', JText::_('Shape'), JHTML::_('select.genericlist', $thumboptions, $this->name.'_params[thumbshape]', null, 'value', 'text', $thumbshape));

		$html .= JHTML::_('element.editrow', JText::_('Longest Side'), JHTML::_('control.text', $this->name.'_params[thumblong]', $thumblong, 'size="4" maxlength="4" id="' . $this->name .'_paramsthumblong"').' px');

		$html .= '<td colspan="2" style="color:#666666; text-align:center;">'.JText::_('Slideshow Options').'</td>';
		$html .= JHTML::_('element.editrow', JText::_('Auto'), JHTML::_('select.booleanlist', $this->name.'_params[auto]', '', $auto));
		$html .= JHTML::_('element.editrow', JText::_('Continuous'), JHTML::_('select.booleanlist', $this->name.'_params[continuous]', '', $continuous));
		$html .= JHTML::_('element.editrow', JText::_('Delay'), JHTML::_('control.text', $this->name.'_params[delay]', $delay, 'size="4" maxlength="4" id="' . $this->name .'_paramsdelay"').' secs');

		$html .= '</table>';

		return $html;
	}

/*
		Function: loadAssets
			Load elements css/js assets.

		Returns:
			Void
	*/
	function loadAssets() {
		JHTML::stylesheet('textlink.css', 'administrator/components/com_zoo/elements/textlink/');
	}

/*
    	Function: bind
    	  Binds a named array/hash to this object.

		Parameters:
	      $data - An associative array or object.

	   Returns:
	      Void

	function bind($data) {

		parent::bind($data);



		// init vars
		$params   =& $this->getParams();
		$this->params = $params->toString();
	}*/


	/**
	 * Create a thumbnail from a given image
	 * @param $source - The source image path
	 * @param $dest - The destination thumbnail path
	 * @param $thumb_size - Longest side of thumbnail
	 * @param $mode - Thumbnail genaration mode (eq = squared, ka = Keep aspect ratio)
	 * @return void
	 */
	function createThumb($source,$dest,$thumb_size,$mode = 'sq') {

		list ($width, $height) = getimagesize($source);

		$ext =JFile::getExt($source);

		if (preg_match('/jpg|jpeg/',$ext)){
			$src_img=imagecreatefromjpeg($source);
		}
		if (preg_match('/png/',$ext)){
			$src_img=imagecreatefrompng($source);
		}

		$new_width = 0;
		$new_height =0;

		if($mode == 'sq'){
			$new_width = $thumb_size;
			$new_height = $thumb_size;
			if($width > $height) {
				$x = ceil(($width - $height) / 2 );
				$y = 0;
				$width = $height;
			} elseif($height > $width) {
				$x = 0;
				$y = ceil(($height - $width) / 2);
				$height = $width;
			}
		} else {
			$x = 0;
			$y = 0;
 			if($width > $height) {
				$new_width = $thumb_size;
				$new_height =  ceil($height*($new_width/$width));
			} elseif($height > $width) {
				$new_height = $thumb_size;
				$new_width = ceil($width*($new_height/$height));
			} else { // squared sources
 				$new_width = $thumb_size;
				$new_height = $thumb_size;
			}
		}


		$dst_img = ImageCreatetruecolor($new_width,$new_height);
		imagecopyresampled($dst_img,$src_img,0,0,$x,$y,$new_width,$new_height,$width,$height);
		imagejpeg($dst_img,$dest,95);
		imagedestroy($dst_img);
		imagedestroy($src_img);

	}
}