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

class GuildsControllerCharacters extends JController {
function __construct(){
			//JRequest::setVar('tmpl','component');
			parent::__construct();
		}
		
		/* Task Functions */
		
		
		
		function add() {
			$user = JRequest::getVar('user',null,'','int');
			$character_name = JRequest::getVar('character_name',null,'','string');
			$categories = JRequest::getVar('category',array(),'','array');
			$checked = JRequest::getVar('checked',null,'','string');
			
			if($character_name == "") {
				JError::raiseError(500,'Character name not given');
			}
			if($user == "") {
				JError::raiserError(500,'User is not specified');
			}
			
			$model = $this->getModel('characters');
			$model->setState('user',$user);
			$model->setState('character_name',$character_name);
			$model->setState('categories',$categories);
			$model->setState('checked',$checked);
			$model->add();
		}
		
		function ajax() {
			JRequest::setVar('tmpl','component');
			
			$user = JRequest::getVar('user_id',null,'','int');
			
			$view = $this->getView('characters','ajax');
			$model = $this->getModel('characters');
			$model->setState('user',$user);
			$view->setModel($model,true);
			$view->setLayout('ajax');
			$view->display(); 
			
		}
		
		function edit() {
			$character = JRequest::getVar('character',null,'','int');	
			
			if($character === null){
				// Report an error if there was no id
				JError::raiseError(500,'ID parameter missing from the request');
			}
			$view = $this->getView('characters','html');
			$model = $this->getModel('characters');
			$model->setState('character',$character);
			$view->setModel($model,true);
			$view->setLayout('form');
			$view->display();
		}
		
		function delete() {
			$layout = JRequest::getVar('layout','default','','string');
			$characters = JRequest::getVar('characters',null,'','array');
			$ids = implode(',',$characters);
			$model = $this->getModel('characters');
			$model->setState('characters',$ids);
			$model->delete();
			
			if($layout != 'ajax') {
				$this->setRedirect('index.php?option=com_guilds&view=characters&layout='.$layout);
			}
			
			$alertsHelper = new alertsHelper();
			$alert = $alertsHelper->newAlert();
			$alert->title = "Character(s) deleted.";
			$alert->msg = "The character(s) were deleted successfully.";
		}
		
		function display() {
			$layout = JRequest::getVar('layout','default','','string');
			$format = JRequest::getVar('format','html','','string');
			$view = $this->getView('characters',$format);
			$model = $this->getModel('characters');
			$view->setModel($model,true);
			$view->setLayout($layout);
			
			// Depending on the model, we need to set the user to different values
			switch($layout) {
				case 'roster':
					//The roster layout should have all characters so we set it to null
					$user = null;
					break;
				case 'ajax':
					//The ajax layout should only get characters for the called user
					$user = JRequest::getVar('user',0,'','int');
					break;
				default:
					//For the default layout, we should get the current user's characters only
					$user = JFactory::getUser()->id;
			}

			$model->setState('user',$user);
			$model->setState('layout',$layout);
			$view->display();
		}
		
		function update() {
			
			
		}
		
		function publish() {
			
			
		}
		
		function unpublish() {
			
			
		}
}