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
    
    function __construct() {
        dump(JRequest::get('Post'),'Post');
        parent::__construct();
    }
    
        
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
    
    public function orderdown() {
        $id = JRequest::getVar('id',NULL,'','array');
        $order = JRequest::getVar('order',NULL,'','array');
        $model = $this->getModel('types');
        
        $model->setState('id',$id);
        
        
        $url = JRoute::_('index.php?option=com_guilds&view=types',false);
        $this->setRedirect($url);
    }
    
    public function orderup() {
        
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
    
}