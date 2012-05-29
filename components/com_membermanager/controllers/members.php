<?php
/**
 * Joomla! 1.5 component Member Manager
 *
 * @version $Id: controller.php 2011-10-28 10:20:36 svn $
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Member Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Member Manager Component Controller
 */
class MembermanagerControllerMembers extends JController {
	
	function __construct() {
		parent::__construct();
	
	} 
	
	function display(){
		parent::display();
	}
	
	function updateHandle() {
		$new_handle = JRequest::getVar('value','','','string');
		$user_id = JRequest::getVar('userid','','','int');
		
		$model = $this->getModel();
		$model->updateHandle($new_handle,$user_id);
		
		$this->display();
	}
	
	function getRanks() {
		$model = $this->getModel();
		$model->getRanks();
	}
	
	function test(){
		JLoader::import('characters',JPATH_BASE.DS.'components'.DS.'com_charactermanager'.DS.'models');
		$model = JModel::getInstance('characters','charactermanagerModel');
		dump($model);
	}
	
	function edit() {
		$id = JRequest::getVar('user_id',null,'','int');
		
		if($id === null){
			// Report an error if there was no id
			JError::raiseError(500,'ID parameter missing from the request');
		}
		$view = $this->getView("members","html");
		$model = $this->getModel("Members");
		$model->setState('id',$id);
		$view->setModel($model,true);
		$view->setLayout('form');
		
		$view->display();
	}
}
?>