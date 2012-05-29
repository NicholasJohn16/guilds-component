<?php
/**
 * Joomla! 1.5 component Character Manager
 *
 * @version $Id: controller.php 2011-10-28 10:20:36 svn $
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Character Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class CharactermanagerControllerCharacters extends JController {
function __construct(){
			//JRequest::setVar('tmpl','component');
			parent::__construct();
		}
		
		/* Task Functions */
		
		
		
		function add() {
			
			
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
			
			
		}
		
		function display() {
			$layout = JRequest::getVar('layout','default','','string');
			$format = JRequest::getVar('format','html','','string');
			$view = $this->getView('characters',$format);
			$model = $this->getModel('characters');
			$view->setModel($model,true);
			$view->setLayout($layout);
			
			$post = JRequest::get('post');
			dump($post,"Post");
			
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
			
			$view->display();
		}
		
		function update() {
			
			
		}
		
		function publish() {
			
			
		}
		
		function unpublish() {
			
			
		}
}