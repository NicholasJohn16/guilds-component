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
        $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models');
        parent::__construct($config);
    }

    /* Task Functions */

    function add() {
        // For the add task, we're doing basically the same thing as editing
        $this->edit();
    }

    function save() {
        dump(JRequest::get('post'), "Add Character POST");
        $id = JRequest::getVar('id', null, '', 'int');
        $user_id = JRequest::getVar('user_id', null, '', 'int');
        $name = JRequest::getVar('name', null, '', 'string');
        $categories = JRequest::getVar('category', array(), '', 'array');
        $checked = JRequest::getVar('checked', null, '', 'string');
        $invite = JRequest::getVar('invite', null, '', 'int');
        $layout = JRequest::getVar('layout','','','string');

        if ($name == "") {
            JError::raiseError(500, 'Character name not given');
        }
        if ($user_id == "") {
            JError::raiserError(500, 'User is not specified');
        }
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
        // This is to make sure that Admins get redirected to the Roster view
        // When editing a character through the form
        $url = 'index.php?option=com_guilds&view=characters';
        $url .= ($layout == '') ? '' : 'layout='.$layout;
        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters&layout='.$layout, false));
    }

    function edit() {
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
        dump($ids,'IDs from Controller');
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
        // Just unpublish the character, so it isn't visible to the Member anymore
        $this->unpublish();
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
                $id = JRequest::getVar('id', null, '', 'int');
                JRequest::setVar('tmpl', 'component');
                JRequest::setVar('format','raw');
                $characters_model->setState('id', $id);
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
                $id = JFactory::getUser()->id;
                $characters_model->setState('id', $id);
                $characters_model->setState('publishedOnly', true);
                $view->displayCharacters();
        }
    }

    function update() {
        JRequest::setVar('template', 'component');
        $name = JRequest::getVar('name', NULL, '', 'string');
        $id = JRequest::getVar('pk', NULL, '', 'int');
        $value = JRequest::getVar('value', NULL, '', 'string');
        $model = $this->getModel('characters');

        if ($name == NULL || $id == NULL) {
            JError::raiseError('500', 'Name or pk is missing from the request.');
        }

        if (!$model->update($name, $id, $value)) {
            JError::raiseError('500', 'Update failed');
        }
    }

    function publish() {
        
    }

    function unpublish() {
        $id = JRequest::getVar('id', NULL, '', 'int');

        if ($id == "") {
            JError::raiseError('500', 'Character ID missing from request!');
        }

        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $result = $model->unpublish();

        if ($result) {
            alertsHelper::alert(array('title' => 'Character deleted!', 'msg' => 'Your character was successfully deleted.', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Delete failed', 'msg' => 'I\'m sorry there was an error processing your request.  If error percists, please notify the leadership.', 'class' => 'error'));
        }
    }

    function invite() {
        $id = JRequest::getVar('id', NULL, '', 'int');
        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $result = $model->invite();

        if ($result) {
            alertsHelper::alert(array('title' => 'Invite Pending', 'msg' => 'Your invite has been requested.', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Invite Failed!', 'msg' => 'There was an error requesting your invite.', 'class' => 'error'));
        }

        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters', false));
    }

    function invited() {
        $id = JRequest::getVar('id', NULL, '', 'int');
        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $result = $model->invited();
        if ($result) {
            alertsHelper::alert(array('title' => 'Invite Sent!', 'msg' => 'Character invite was sent.', 'class' => 'success'));
        } else {
            alertsHelper::alert(array('title' => 'Invite Failed!', 'msg' => 'There was an error updating the invite request.', 'class' => 'error'));
        }
        $this->setRedirect(JRoute::_('index.php?option=com_guilds&view=characters&layout=pending', false));
    }

}