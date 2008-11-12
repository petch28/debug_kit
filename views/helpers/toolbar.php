<?php
/* SVN FILE: $Id$ */
/**
 * Abstract Toolbar helper.  Provides Base methods for content
 * specific debug toolbar helpers.  Acts as a facade for other toolbars helpers as well.
 *
 * helps with development.
 *
 * PHP versions 4 and 5
 *
 * CakePHP :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2006-2008, Cake Software Foundation, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2006-2008, Cake Software Foundation, Inc.
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP Project
 * @package       debug_kit
 * @subpackage    debug_kit.views.helpers
 * @since         v 1.0
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Vendor', 'DebugKit.DebugKitDebugger');

class ToolbarHelper extends AppHelper {
/**
 * settings property to be overloaded.  Subclasses should specify a format
 *
 * @var array
 * @access public
 */
	var $settings = array();
/**
 * Construct the helper and make the backend helper.
 *
 * @param string $options 
 * @access public
 * @return void
 */
	function __construct($options = array()) {
		$this->_myName = get_class($this);
		if ($this->_myName !== 'ToolbarHelper') {
			return;
		}
		if (!isset($options['backend'])) {
			$options['backend'] = 'DebugKit.HtmlToolbar';
		}
		App::import('Helper', $options['backend']);
		$className = $options['backend'];
		if (strpos($options['backend'], '.') !== false) {
			list($plugin, $className) = explode('.', $options['backend']);
		}
		$this->_backEndClassName =  $className;
		$this->helpers = array($options['backend']);
	}
/**
 * call__
 *
 * Allows method calls on backend helper
 *
 * @param string $method 
 * @param mixed $params 
 * @access public
 * @return void
 */	
	function call__($method, $params) {
		if (method_exists($this->{$this->_backEndClassName}, $method)) {
			return $this->{$this->_backEndClassName}->dispatchMethod($method, $params);
		}
	}
/**
 * beforeLayout method
 *
 * @return void
 * @access public
 */
	function beforeLayout() {
		DebugKitDebugger::startTimer('layoutRender', __('Rendering Layout', true));
		$this->_beforeLayout();
	}
/**
 * _beforeLayout - callback for back end helpers.
 *
 * @access public
 * @return void
 */
	function _beforeLayout() {
		//Override me.
	}

/**
 * afterLayout method
 *
 * @return void
 * @access public
 */
	function afterLayout() {
		DebugKitDebugger::stopTimer('layoutRender');
		DebugKitDebugger::stopTimer('controllerRender');
		$this->_send();
	}

/**
 * beforeRender view callback
 *
 * @return void
 **/
	function beforeRender() {
		DebugKitDebugger::startTimer('renderViewFile', __('Rendering View file', true));
	}

/**
 * afterRender view callback
 *
 * @return void
 **/
	function afterRender() {
		DebugKitDebugger::stopTimer('renderViewFile');
	}

/**
 * _send - apply content changes to view output.
 *
 * Overloaded in sub classes
 *
 * @param string $format
 * @return void
 * @access protected
 */
	function _send() {
		//Override me
	}
}