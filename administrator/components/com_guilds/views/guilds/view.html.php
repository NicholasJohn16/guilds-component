<?php

/*
 * @package		Character Manager
 * @subpackage	Components
 * @link			http://www.nicholasjohn16.com
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class GuildsViewGuilds extends JView {

    function display($tpl = null) {
        $document = JFactory::getDocument();
        $document->addStyleDeclaration('.icon-32-refresh {
                background-image: url("templates/khepri/images/toolbar/icon-32-refresh.png");
            }');
        
        
        JToolBarHelper::title(JText::_('Guilds Manager'), 'generic.png');
        JToolBarHelper::custom('buildTables','refresh','','Rebuild Tables',false,false);
        JToolBarHelper::preferences('com_guilds',350);
        parent::display($tpl);
    }

}