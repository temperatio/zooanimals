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

// register parent class
JLoader::register('ElementSimpleParams', ZOO_ADMIN_PATH.'/elements/simpleparams/simpleparams.php');

/*
	Class: ElementImage
		The image element class
*/
class ElementImageUrl extends ElementSimpleParams {

	/*
	   Function: Constructor
	*/
	function ElementImageUrl() {

		// call parent constructor
		parent::ElementSimpleParams();

		// init vars
		$this->type  = 'imageurl';

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

		// init vars
		$params =& $this->getParams();
		$title  = $params->get('title', $this->_item->name);
		$width  = $params->get('width', 0);
		$height = $params->get('height', 0);
		$link   = $this->value;

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('url' => $this->value, 'title' => $title, 'link' => $link, 'width' => $width, 'height' => $height));
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

		// init vars
		$params =& $this->getParams();
		$title  = htmlspecialchars(html_entity_decode($params->get('title'), ENT_QUOTES), ENT_QUOTES);
		$width  = $params->get('width', 0);
		$height = $params->get('height', 0);

		// image preview
		$javascript = "var img = new ElementImageUrl('".$this->name."_value', '".$this->name."_preview'); img.attachEvents();";
		$preview[]  = '<div id="'.$this->name.'_preview" class="image-preview"><img src="'.$this->value.'" /></div>';
		$preview[]  = "<script type=\"text/javascript\">\n// <!--\n$javascript\n// -->\n</script>\n";

		// create info
		$info[] = JText::_('Width').': '.$width;
		$info[] = JText::_('Height').': '.$height;
		$info   = ' ('.implode(', ', $info).')';


		$value = htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

		// set default, if item is new
		if ($this->default != '' && $this->_item != null && $this->_item->id == 0) {
			$value = $this->default;
		}


		// create html
		$html  = '<table>';
		$html .= JHTML::_('element.editrow', JText::_('Url'), JHTML::_('control.text', $this->name.'_value', $value, 'size="60" maxlength="1024" id="' . $this->name. '_value"'). '<br />' .$info);
		$html .= JHTML::_('element.editrow', JText::_('Title'), JHTML::_('control.text', $this->name.'_params[title]', $title, 'maxlength="255"'));
		$html .= JHTML::_('element.editrow', '', implode("\n", $preview));
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
		JHTML::script('imageurl.js', 'administrator/components/com_zoo/elements/imageurl/');
		JHTML::stylesheet('imageurl.css', 'administrator/components/com_zoo/elements/imageurl/');
	}

	/*
    	Function: bind
    	  Binds a named array/hash to this object.

		Parameters:
	      $data - An associative array or object.

	   Returns:
	      Void
 	*/
	function bind($data) {

		parent::bind($data);

		// init vars
		$params   =& $this->getParams();
		$url = $this->value;

		// set image width/height
		if ($size = getimagesize($url)) {
			$params->set('width', $size[0]);
			$params->set('height', $size[1]);
		} else {
			$params->set('width', 0);
			$params->set('height', 0);
		}

		$this->params = $params->toString();
	}

}