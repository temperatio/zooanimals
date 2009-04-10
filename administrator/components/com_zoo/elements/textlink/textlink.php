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
class ElementTextLink extends ElementSimpleParams {

	/** @var string */
	var $idaccount = '';

	/*
	   Function: Constructor
	*/
	function ElementTextLink() {

		// call parent constructor
		parent::ElementSimpleParams();

		// init vars
		$this->type  = 'textlink';
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

		// get parameters
		$params	=& $this->getParams();
		$text	= $params->get('text');
		$target	= $params->get('target');
		$width	= $params->get('width', 0);
		$height	= $params->get('height',0);

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout(
				$layout, array(
					'value' => $this->value,
					'text' => $text,
					'target' => $target,
					'width' => $width,
					'height' => $height
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
		$params	=& $this->getParams();
		$text  	= htmlspecialchars(html_entity_decode($params->get('text'), ENT_QUOTES), ENT_QUOTES);
		$target	= $params->get('target');
		$width	= $params->get('width',0);
		$height	= $params->get('height',0);
		$value	= htmlspecialchars(html_entity_decode($this->value, ENT_QUOTES), ENT_QUOTES);

		//create javascript

		$js = "window.addEvent('domready', function(){
	if($('". $this->name ."_paramstarget').value != 'shadowbox'){
		$('". $this->name ."_paramswidth').disabled = 1;
		$('". $this->name ."_paramsheight').disabled = 1;
	} else {
		$('". $this->name ."_sbwarn').innerHTML = '".JText::_('Remember: You must activate shadowbox script.')."';
	}
	$('". $this->name ."_paramstarget').addEvent('change', function(event){
		if($('". $this->name ."_paramstarget').value == 'shadowbox'){
			$('". $this->name ."_paramswidth').disabled = 0;
			$('". $this->name ."_paramsheight').disabled = 0;
			$('". $this->name ."_sbwarn').innerHTML = '".JText::_('Remember: You must activate shadowbox script.')."';
		} else {
			$('". $this->name ."_paramswidth').disabled = 1;
			$('". $this->name ."_paramsheight').disabled = 1;
			$('". $this->name ."_sbwarn').innerHTML = '';
		}
	});
});";
		$document->addScriptDeclaration($js);

		// create html
		$html  = '<table>';
		$html .= JHTML::_('element.editrow', JText::_('Url'), JHTML::_('control.text', $this->name.'_value', $value, 'size="60" maxlength="1024" id="' . $this->name. '_value"'). '<br />');
		$html .= JHTML::_('element.editrow', JText::_('Text'), JHTML::_('control.text', $this->name.'_params[text]', $text, 'size="60" maxlength="255"'));

		// prepare the options
		$options = array();
		$options[] = JHTML::_('select.option', '_blank', JText::_('New Window'));
		$options[] = JHTML::_('select.option', '_self', JText::_('Same Window'));
		$options[] = JHTML::_('select.option', 'lightbox', JText::_('Lightbox'));
		$options[] = JHTML::_('select.option', 'shadowbox', JText::_('Shadowbox'));

		// render the options
		$html .= JHTML::_('element.editrow', JText::_('Target'), JHTML::_('select.genericlist', $options, $this->name.'_params[target]', null, 'value', 'text', $target) . ' <span class="sbwarn" id="'. $this->name .'_sbwarn"></span>');
		$html .= JHTML::_('element.editrow', JText::_('Width'), JHTML::_('control.text', $this->name.'_params[width]', $width, 'size="4" maxlength="4" id="' . $this->name .'_paramswidth"'));
		$html .= JHTML::_('element.editrow', JText::_('Height'), JHTML::_('control.text', $this->name.'_params[height]', $height, 'size="4" maxlength="4" id="' . $this->name .'_paramsheight"'));
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

}