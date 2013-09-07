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

class GuildsControllerMembers extends GuildsController {
    
    public function edit() {
        $id = JRequest::getVar('id',null,'','int');
        if($id === NULL) {
            $url = JRoute::_('index.php?option=com_guilds&view=members',false);
            $msg = 'Can\'t edit a user when I don\'t know which one!';
            $this->setRedirect($url,$msg,'error');
        }
        
        JRequest::setVar( 'hidemainmenu', 1 );
        JRequest::setVar('layout','form');
        parent::display();
    }
    
    public function cancel() {
        $url = JRoute::_('index.php?option=com_guilds&view=members',false);
        $msg = 'Editing was cancelled';
        $this->setRedirect($url,$msg,'notice');
    }
    
    function save() {
        $id = JRequest::getVar('user_id', null, '', 'int');
        $sto_handle = JRequest::getVar('sto_handle', null, '', 'string');
        $tor_handle = JRequest::getVar('tor_handle', null, '', 'string');
        $gw2_handle = JRequest::getVar('gw2_handle', null, '', 'string');
        $appdate = JRequest::getVar('appdate', null, '', 'string');
        $notes = JRequest::getVar('notes', null, '', 'string');
        
        $model = $this->getModel('members');
        $model->setState('id', $id);
        $model->setState('sto_handle', $sto_handle);
        $model->setState('tor_handle', $tor_handle);
        $model->setState('gw2_handle', $gw2_handle);
        $model->setState('appdate', $appdate);
        $model->setState('notes', $notes);
        $result = $model->update();

        if ($result) {
            $msg = 'Save successful';
            $type = 'message';
        } else {
            $msg = 'Save failed';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds&view=members',false);
        $this->setRedirect($url,$msg,$type);
    }
}