<?php

/**
 * Joomla! 1.5 Component Guilds Manager
 *
 * @version 
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Guilds Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class GuildsViewHoldings extends JView { 
    
    public function display() {
        JHTML::stylesheet('bootstrap.css', 'components/com_guilds/media/css/');
        JHTML::script('jquery.js', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
        JHTML::script('bootstrap.js', 'components/com_guilds/media/js/', false);
        
        parent::display();
    }
}