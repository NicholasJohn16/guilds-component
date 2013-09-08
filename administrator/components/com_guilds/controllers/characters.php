<?php
/*
* @package	Guilds Manager
* @subpackage	Components
* @link		http://stonewallgaming./net
* @license	GNU/GPL
*/

// No direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

class GuildsControllerCharacters extends GuildsController {
    
    public function display() {
        $view = $this->getView('characters','html');
        $view->setModel($this->getModel('characters'),true);
        $view->setModel($this->getModel('types'));
        $view->setModel($this->getModel('categories'));
        
        $view->display();
    }
    
    public function add() {
        $this->form();
    }
    
    public function edit() {
        $this->form();
    }
    
    public function form() {
        $id = JRequest::getVar('id',NULL,'');
        $id = (is_array($id)) ? $id[0] : $id;
        
        JRequest::setVar('hidemainmenu',1);
        
        $model = $this->getModel('characters');
        $model->setState('id',$id);
        
        $view = $this->getView('characters','html');
        $view->setLayout('form');
        $view->setModel($model,true);
        $view->setModel($this->getModel('types'));
        $view->setModel($this->getModel('categories'));
        
        $view->display();
    }
    
    public function cancel() {
        $url = JRoute::_('index.php?option=com_guilds&view=characters',false);
        $this->setRedirect($url);
    }
    
    public function delete() {
        $ids = JRequest::getVar('id',NULL,'','array');
        
        $model = $this->getModel('characters');
        $model->setState('ids',$ids);
        $result = $model->delete();
        
        if($result) {
            $msg = 'Character(s) were deleted.';
            $type = 'message';
        } else {
            $msg = 'Character(s) weren\'t deleted.';
            $type = 'error';
        }
        $url = JRoute::_("index.php?option=com_guilds&view=characters",false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function save() {
        $id = JRequest::getVar('id', null, '', 'int');
        $user_id = JRequest::getVar('user_id', null, '', 'int');
        $name = JRequest::getVar('name', null, '', 'string');
        $categories = JRequest::getVar('category', array(), '', 'array');
        $checked = JRequest::getVar('checked', null, '', 'string');
        $invite = JRequest::getVar('invite', null, '', 'int');
        $published = JRequest::getVar('published',null,'','int');
        
        $model = $this->getModel('characters');
        $model->setState('id', $id);
        $model->setState('user_id', $user_id);
        $model->setState('name', $name);
        $model->setState('categories', $categories);
        $model->setState('checked', $checked);
        $model->setState('invite', $invite);
        $model->setState('published',$published);
        $result = $model->save();
        
        JRequest::setVar('categories',NULL);
        
        if ($result) {
            $msg = 'Character was saved successfully!';
            $type = 'message';
            $url = JRoute::_('index.php?option=com_guilds&view=characters',false);
        } else {
            $msg = 'There was an error and your character could not be saved';
            $type = 'error';
            if($id == '') {
                $url = JRoute::_('index.php?option=com_guilds&view=characters&task=add',false);
            } else {
                $url = JRoute::_('index.php?option=com_guilds&view=characters&task=edit&id='.$id,false);
            }
        }
        
        $this->setRedirect($url,$msg,$type);   
    }
    
    public function publish() {
        $id = JRequest::getVar('id',NULL,'','array');
        //$id = (is_array($id)) ? $id[0] : $id;
        $model = $this->getModel('characters');

        $model->setState('id',$id);
        $model->setState('published',1);
        $result = $model->update();
        
        if($result) {
            $msg = 'Character has been published!';
            $type = 'message';
        } else {
            $msg = 'Characater wasn\'t published';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=characters',false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function unpublish() {
        $id = JRequest::getVar('id',NULL,'','array');
        //$id = (is_array($id)) ? $id[0] : $id;
        $model = $this->getModel('characters');

        $model->setState('id',$id);
        $model->setState('published',0);
        $result = $model->update();
        
        if($result) {
            $msg = 'Character has been unpublished!';
            $type = 'message';
        } else {
            $msg = 'Characater wasn\'t unpublished';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=characters',false);
        $this->setRedirect($url,$msg,$type);
        
    }
    
    public function invited() {
        $id = JRequest::getVar('id',NULL,'','array');
        $model = $this->getModel('characters');
        $model->setState('id',$id);
        $model->setState('invite',0);
        $result = $model->update();
        
        if($result) {
            $msg = 'Character(s) were invited.';
            $type = 'message';
        } else {
            $msg = 'Character(s) weren\'t invited';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=characters',false);
        $this->setRedirect($url,$msg,$type);
    }
    
}