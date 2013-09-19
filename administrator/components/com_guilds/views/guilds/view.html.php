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
        JToolBarHelper::title(JText::_('Guilds Manager'), 'generic.png');
        // Path should be 'administrator'.DS.'components'.DS.'com_guilds'.DS.'installation.xml'
        // or try seperating config and install xmls
        JToolBarHelper::preferences('com_guilds');
        parent::display($tpl);
    }

}