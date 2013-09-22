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
        // Add admin models folder as model path
        $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models');
                
        $user = & JFactory::getUser();
        $params = JComponentHelper::getParams('com_guilds');
        $admin_groups = $params->get('admin_groups');
        $layout = JRequest::getCmd('layout');
        
        if ($user->guest) {
            $url = JRoute::_('index.php?option=com_user&view=login',false);
            $this->setRedirect($url);
        } else {
            if(in_array($layout,array('roster','pending','admin-form')) 
                    && !in_array($user->gid,$admin_groups)) {
                JError::raiseError(403,'Only Admins may access this page.');
            }
        }
        
        parent::__construct($config);
    }
    
    /* Task Functions */

    function ajaxSave() {
        $id = JRequest::getVar('id', null, '', 'array');
        $user_id = JRequest::getVar('user_id', null, '', 'int');
        $name = JRequest::getVar('name', null, '', 'string');
        $handle = JRequest::getVar('handle',null,'','string');
        $categories = JRequest::getVar('category', array(), '', 'array');
        $checked = JRequest::getVar('checked', null, '', 'string');
        $invite = JRequest::getVar('invite', null, '', 'int');
        dump($id,'Id from controller');
        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $model->setState('user_id', $user_id);
        $model->setState('name', $name);
        $model->setState('handle',$handle);
        $model->setState('categories', $categories);
        $model->setState('checked', $checked);
        $model->setState('invite', $invite);
        $result = $model->save();

        if ($result) {
            $character = $model->getCharacter();
            echo json_encode($character);
        } else {
            JError::raiseError(400,'Unable to save character.');
        }
    }

    function save() {
        $id = JRequest::getVar('id', null, '', 'array');
        $user_id = JRequest::getVar('user_id', null, '', 'int');
        $name = JRequest::getVar('name', null, '', 'string');
        $categories = JRequest::getVar('category', array(), '', 'array');
        $checked = JRequest::getVar('checked', null, '', 'string');
        $invite = JRequest::getVar('invite', null, '', 'int');
        
//        if ($name == "") {
//            JError::raiseError(400, 'Character name not given');
//        }
//        if ($user_id == "") {
//            JError::raiseError(400, 'User is not specified');
//        }
        
        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $model->setState('user_id', $user_id);
        $model->setState('name', $name);
        $model->setState('categories', $categories);
        $model->setState('checked', $checked);
        $model->setState('invite', $invite);
        $result = $model->save();
        
        if ($result) {
            alertsHelper::alert(array('title' => 'Character Saved', 'msg' => 'Character was saved successfully!', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Save Failed', 'msg' => 'There was an error and your character could not be saved', 'class' => 'error'));
        }
        
        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters', false));
    }
    
    function add() {
        $this->form();
    }
    
    function edit() {
        $id = JRequest::getVar('id',null,'','int');
        
        // If there isn't an id in the request
        // then the character can't be loaded
        // return error
        if($id == null || $id < 0) {
            alertsHelper::alert(array('title'=>'Edit Failed','msg'=>'Uh oh! There was no Character ID in the request so we can\'t edit it!','class'=>'error'));
            $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters',false));
            return false;
        } else {
            $this->form();
        }
    }

    function form() {
        $id = JRequest::getVar('id', null, '', 'int');
        $redirect = JRequest::getVar('redirect','','','string');
        $view = $this->getView('characters', 'html');
        $characters_model = $this->getModel('characters');
        $categories_model = $this->getModel('categories');
        $types_model = $this->getModel('types');
        $characters_model->setState('id', $id);
        $view->setModel($characters_model, true);
        $view->setModel($categories_model);
        $view->setModel($types_model);
        $view->setLayout('form');
        $view->redirect = $redirect;
        $view->displayForm();
    }

    function delete() {
        $layout = JRequest::getVar('layout', 'default', '', 'string');
        $ids = JRequest::getVar('ids', null, '', 'array');
        $model = $this->getModel('characters');
        $model->setState('ids', $ids);
        $model->delete();

        if ($layout != 'ajax') {
            $this->setRedirect('index.php?option=com_guilds&view=characters&layout=' . $layout);
        }

        $params = array('title' => 'Character(s) deleted.', 'msg' => 'The character(s) were deleted successfully', 'class' => 'success');
        alertsHelper::alert($params);
    }

    function drop() {
        $id = JRequest::getVar('id',NULL,'','int');
        $model = $this->getModel('characters');
        $model->setState('id',$id);
        $model->setState('published',0);
        $model->setState('unpublisheddate',date('Y-m-d'));
        $model->setState('invite',0);
        // Just unpublish the character, so it isn't visible to the Member anymore
        if ($model->update()) {
            alertsHelper::alert(array('title' => 'Character deleted!', 'msg' => 'Your character was successfully deleted.', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Delete failed', 'msg' => 'I\'m sorry, Dave. I\'m affarid I can\'t do that.  If error persists, please notify the leadership.', 'class' => 'error'));
        }
        
        //Redirect back to the Characters view
        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters', false));
    }

    function display() {
        // Get the view info so we can call the correct one
        $layout = JRequest::getVar('layout', 'default', '', 'string');
        $format = JRequest::getVar('format', 'html', '', 'string');
        $view = $this->getView('characters', $format);

        // Get all the models and push them into the view
        $characters_model = $this->getModel('characters');
        $categories_model = $this->getModel('categories');
        $types_model = $this->getModel('types');
        $view->setModel($characters_model, true);
        $view->setModel($categories_model);
        $view->setModel($types_model);
        $view->setLayout($layout);

        // Depending on the model, we need to set the user to different values
        switch ($layout) {
            case 'ajax':
                $user_id = JRequest::getVar('id', null, '', 'int');
                JRequest::setVar('tmpl', 'component');
                JRequest::setVar('format','raw');
                $characters_model->setState('user_id', $user_id);
                $characters_model->setState('publishedOnly', false);
                $view->displayAjax();
                break;
            // Isn't needed because form layout's task is edit
            //case 'form':
            //    break;
            case 'pending':
                $view->displayPending();
                break;
            case 'roster':
                $view->displayRoster();
                break;
            default:
                $user_id = JFactory::getUser()->id;
                $characters_model->setState('user_id', $user_id);
                $characters_model->setState('publishedOnly', true);
                $view->displayCharacters();
        }
    }
    
    function update() {
        JRequest::setVar('template', 'component');
        $name = JRequest::getVar('name',NULL, '', 'string');
        $id = JRequest::getVar('pk', NULL, '', 'array');
        $value = JRequest::getVar('value', NULL, '', 'string');
        $model = $this->getModel('characters');
        $model->setState('id',$id);
        $model->setState($name,$value);

        if ($name == NULL || $id == NULL) {
            JError::raiseError('400', 'Name or pk is missing from the request.');
        }

        if (!$model->update()) {
            JError::raiseError('400', 'Update failed');
        }
    }

    function publish() {
        $id = JRequest::getVar('id',NULL,'','array');
        
        if($id == "") {
            JError::raiseError('500',"Charcter ID missing from request!");
        }
        
        $model = $this->getModel('characters');
        $model->setState('id',$id);
        $model->setState('published',1);
        $result = $model->update();
        
        if(!$result) {
            JError::raiseError('400','Character could not be updated.');
        }
    }

    function unpublish() {
        $id = JRequest::getVar('id', NULL, '', 'array');

        if ($id == "") {
            JError::raiseError('400', 'Character ID missing from request!');
        }

        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $model->setState('published',0);
        $result = $model->update();
        
        if(!$result) {
            JError::raiseError('400','Character could not be updated.');
        }
    }

    function invite() {
        $id = JRequest::getVar('id', NULL, '', 'array');
        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $model->setState('invite',1);
        $result = $model->update();

        if ($result) {
            alertsHelper::alert(array('title' => 'Invite Pending', 'msg' => 'Your invite has been requested.', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Invite Failed!', 'msg' => 'There was an error requesting your invite.', 'class' => 'error'));
        }

        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters', false));
    }

    function invited() {
        $id = JRequest::getVar('id', NULL, '', 'array');
        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $model->setState('invite',0);
        $result = $model->update();
        if ($result) {
            alertsHelper::alert(array('title' => 'Invite Sent!', 'msg' => 'Character invite was sent.', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Invite Failed!', 'msg' => 'There was an error updating the invite request.', 'class' => 'error'));
        }
        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters&layout=pending', false));
    }
    
    function promoted() {
        $id = JRequest::getVar('id',NULL,'','array');
        $model = $this->getModel('characters');
        $model->setState('id',$id);
        $model->setState('checked',date('Y-m-d'));
        $result = $model->update();
                
        if($result) {
              alertsHelper::alert(array('title' => 'Character Promoted!', 'msg' => 'The character was promoted successfully', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Promotion Failed!', 'msg' => 'There was an error promoting the character', 'class' => 'error'));
        }
        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters&layout=pending',false));
    }
    
    function characters() {
        JRequest::setVar('tmpl','component');
        $user_id = JRequest::getVar('user_id',NULL,'','int');
        $model = $this->getModel('characters');
        $model->setState('user_id',$user_id);
        $characters = $model->getCharactersByUserID();
        $names = array();
        foreach($characters as $character) {
            $names[] = array('id'=>$character->id,'text'=>$character->name);
        }
        
        echo json_encode($names);
    }

}