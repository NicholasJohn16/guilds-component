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

class GuildsControllerTypes extends GuildsController {
    
    public function add() {
        $this->form();
    }

    public function edit() {
        $this->form();
    }

    public function form() {
        JRequest::setVar('hidemainmenu',1);
        JRequest::setVar('layout','form');
        parent::display();
    }
    
    public function cancel() {
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url);
    }
    
    public function save() {
        $id = JRequest::getVar('id',NULL,'','array');
        $fields['name'] = JRequest::getVar('name',NULL,'','string');
        $fields['ordering'] = JRequest::getVar('ordering',NULL,'','int');
        $fields['published'] = JRequest::getVar('published',NULL,'','int');
        
        $model = $this->getModel('types');
        $model->setState('id',$id);
        $model->setState('fields',$fields);
        $result = $model->save();
        
        if($result) {
            $msg = 'Type saved.';
            $type = 'message';
        } else {
            $msg = 'Type save failed';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function delete() {
        $id = JRequest::getVar('id',NULL,'','array');
        $model = $this->getModel('types');
        $model->setState('id',$id);
        $result = $model->delete();
        
        if($result) {
            $msg = 'Type(s) deleted.';
            $type = 'message';
        } else {
            $msg = 'Deletion failed';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function orderdown() {
        $id = JRequest::getVar('id',NULL,'','array');
        $fields['ordering'] = ' `ordering` + 1 ';
        $model = $this->getModel('types');
        $model->setState('id',$id);
        $model->setState('fields',$fields);
        $result = $model->update();
        
        if($result) {
            $msg = 'Type has been moved down!';
            $type = 'message';
        } else {
            $msg = 'Type move has failed!';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function orderup() {
        $id = JRequest::getVar('id',NULL,'','array');
        $fields['ordering'] = ' `ordering` - 1 ';
        $model = $this->getModel('types');
        $model->setState('id',$id);
        $model->setState('fields',$fields);
        $result = $model->update();
        
        if($result) {
            $msg = 'Type has been moved up!';
            $type = 'message';
        } else {
            $msg = 'Type move has failed!';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function saveorder() {
        $ids = JRequest::getVar('id',NULL,'','array');
        $order = JRequest::getVar('order',NULL,'','array');
        $model = $this->getModel('types');
        $model->setState('ids',$ids);
        $model->setState('order',$order);
        $result = $model->saveorder();
        
        if($result) {
            $msg = 'Order saved.';
            $type = 'message';
        } else {
            $msg = 'Order save failed';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function publish() {
        $id = JRequest::getVar('id',NULL,'','array');
        $model = $this->getModel('types');
        $model->setState('id',$id);
        $model->setState('fields',array('published'=>1));
        $result = $model->update();
        
        if($result) {
            $msg = 'Type(s) published.';
            $type = 'message';
        } else {
            $msg = 'Type(s) publish failed.';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url,$msg,$type);
    }
    
    public function unpublish() {
        $id = JRequest::getVar('id',NULL,'','array');
        $model = $this->getModel('types');
        $model->setState('id',$id);
        $model->setState('fields',array('published'=>0));
        $result = $model->update();
        
        if($result) {
            $msg = 'Type(s) unpublished.';
            $type = 'message';
        } else {
            $msg = 'Type(s) unpublished failed.';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url,$msg,$type);
    }
    
}