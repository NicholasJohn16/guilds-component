<?php
/**
 * Joomla! 1.5 Component Guilds Manager
 *
 * @version $Id: controller.php 2011-10-28 10:20:36 svn $
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Guilds Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Guilds Manager Component Controller
 */
class GuildsControllerMembers extends JController {
	
	function __construct() {
		
		global $mainframe;
		$user =& JFactory::getUser();
		if($user->guest){$mainframe->redirect('index.php?option=com_user&view=login');}
		
		parent::__construct();
	} 
	
	function display(){
		parent::display();
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
        
	function update() {
            $field = JRequest::getVar('name',NULL,'','string');
            $id = JRequest::getVar('pk',NULL,'','int');
            $value = JRequest::getVar('value',NULL,'','string');
            $value = ($value == '') ? NULL : $value;
            $model = $this->getModel('members');
            dump($value,'Value');
            if($field == NULL || $id == NULL ) {
                dump('Error was in controller');
                JError::raiseError('500','Invalid Request');
            }
            
            $model->update($field,$id,$value);
            
        }
	
	function getRanks() {
		$model = $this->getModel('members');
		$ranks = $model->getRanks();
                echo json_encode($ranks);
	}
}
?>