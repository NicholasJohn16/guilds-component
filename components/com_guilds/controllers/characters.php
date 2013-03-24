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
    
    function __construct($config = array()) {
        // When the drop task is called, use the unpublish function
        //$this->registerTask('drop','unpublish');
        $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');
        parent::__construct($config);
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

                $params = array('title'=>'Character(s) deleted.','msg'=>'The character(s) were deleted successfully','class'=>'success');
                alertsHelper::alert($params);
        }
        
        function drop() {
            $this->unpublish();
            
            $this->setRedirect('index.php?option=com_guilds&view=characters');
        }

        function display() {
                $layout = JRequest::getVar('layout','default','','string');
                $format = JRequest::getVar('format','html','','string');
                $view = $this->getView('characters',$format);
                $characters_model = $this->getModel('characters');
                $categories_model = $this->getModel('categories');
                $types_model = $this->getModel('types');
                $view->setModel($characters_model,true);
                $view->setModel($categories_model);
                $view->setModel($types_model);
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

                $characters_model->setState('user',$user);
                $characters_model->setState('layout',$layout);
                $view->display();
        }

        function update() {
                JRequest::setVar('template','component');
                $name = JRequest::getVar('name',NULL,'','string');
                $id = JRequest::getVar('pk',NULL,'','int');
                $value = JRequest::getVar('value',NULL,'','string');
                $model = $this->getModel('characters');

                if($name == NULL || $id == NULL) {
                    JError::raiseError('500','Invalid Request');
                }

                if(!$model->update($name,$id,$value)) {
                    JError::raiseError('500','Update failed');
                }
        }

        function publish() {


        }

        function unpublish() {
            $id = JRequest::getVar('id',NULL,'','int');
            
            if($id == "") {
                JError::raiseError('500','Character ID missing from request!');
            }
            
            $model = $this->getModel('characters');
            $model->setState('id',$id);
            $result = $model->unpublish();
            
            if($result) {
                alertsHelper::alert(array('title'=>'Character deleted!','msg'=>'Your character was successfully deleted.','class'=>'success'));
            } else {
                alertsHelper::alert(array('title'=>'Delete failed','msg'=>'I\'m sorry there was an error processing your request.  If error percists, please notify the leadership.','class'=>'error'));
            }

        }
        
        function invite() {
            $id = JRequest::getVar('id',NULL,'','int');
            $model = $this->getModel('characters');
            $model->setState('id',$id);
            $result = $model->invite();
            
            if($result) {
                alertsHelper::alert(array('title'=>'Invite Pending','msg'=>'Your invite has been requested.','class'=>'success'));
            } else {
                alertsHelper::alert(array('title'=>'Invite Failed!','msg'=>'There was an error requesting your invite.','class'=>'error'));
            }
            
            $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters'));
        }
        
        function invited() {
            $id = JRequest::getVar('id',NULL,'','int');
            $model = $this->getModel('characters');
            $model->setState('id',$id);
            $result = $model->invited();
            if($result) {
                alertsHelper::alert(array('title'=>'Invite Sent!','msg'=>'Character invite was sent.','class'=>'success'));
            } else {
                alertsHelper::alert(array('title'=>'Invite Failed!','msg'=>'There was an error updating the invite request.','class'=>'error'));
            }
            $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters&layout=pending'));
        }
}