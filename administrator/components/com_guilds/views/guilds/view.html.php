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
        parent::display($tpl);
    }

}