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
        
        if($id === NULL) {
            $url = JRoute::_('index.php?option=com_guilds&view=characters',false);
            $msg = 'Can\'t edit a character when I don\'t know which one!';
            $this->setRedirect($url,$msg,'error');
        }
        
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
        $msg = 'Editing was cancelled';
        $this->setRedirect($url,$msg,'notice');
    }
    
    public function publish() {
        $id = JRequest::getVar('id',NULL,'');
        $id = (is_array($id)) ? $id[0] : $id;
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
        $id = JRequest::getVar('id',NULL,'');
        $id = (is_array($id)) ? $id[0] : $id;
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
    
}