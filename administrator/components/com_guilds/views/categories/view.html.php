<?php
/*
* @package	Guilds Manager
* @subpackage	Components
* @link		http://stonewallgaming./net
* @license	GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class GuildsViewCategories extends JView {

    function display($tpl = null) {
        JToolBarHelper::title(JText::_('Categories'), 'generic.png');
        JToolBarHelper::back();
        parent::display($tpl);
    }

}