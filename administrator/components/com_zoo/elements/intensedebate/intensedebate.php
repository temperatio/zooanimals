<?php
/**
* @package   Zoo Component
* @version   1.0.0 Sun Apr 05 2009 17:24:17 GMT+0200 (CEST)
* @author    César Gómez http://www.temperatio.com
* @copyright Copyright (C) 2008 - 2009 Temperatio
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register parent class
JLoader::register('ElementSimple', ZOO_ADMIN_PATH.'/elements/simple/simple.php');

/*
   Class: ElementText
       The text element class
*/
class ElementIntenseDebate extends ElementSimple {

	/** @var string */
	var $idaccount = '';

	/*
	   Function: Constructor
	*/
	function ElementIntenseDebate() {

		// call parent constructor
		parent::ElementSimple();

		// init vars
		$this->type  = 'intensedebate';
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

		$itemid = $this->_item->id;

		// render layout
		if ($layout = $this->getLayout()) {
			return Element::renderLayout($layout, array('value' => $this->value, 'idaccount' => $this->idaccount, 'itemid' => $itemid));
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
		$value = $this->value;

		return JHTML::_('select.booleanlist', $this->name.'_value', '', $value);
	}

}