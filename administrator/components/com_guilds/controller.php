<?php

/*
 * @package		Character Manager
 * @subpackage	Components
 * @link			http://www.nicholasjohn16.com
 * @license		GNU/GPL
 */

// No direct access

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class GuildsController extends JController {

    function display() {
        parent::display();
    }
    
    public function buildTables() {
        $model = $this->getModel('guilds');
        $result = $model->buildTables();
                
        if($result) {
            $msg = 'Tables were rebuilt.';
            $type = 'message';
        } else {
            $msg = 'Rebuilding failed.';
            $type = 'error';
        }
        $url = JRoute::_('index.php?option=com_guilds',false);
        $this->setRedirect($url,$msg,$type);
    }

}