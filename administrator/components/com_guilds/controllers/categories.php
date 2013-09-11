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

class GuildsControllerCategories extends GuildsController {
    
    
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
        $url = JRoute::_('index.php?option=com_guilds&view=categories',false);
        $this->setRedirect($url);
    }
    
}