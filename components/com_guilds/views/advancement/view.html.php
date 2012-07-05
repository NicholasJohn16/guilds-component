<?php
	/**
	 * Joomla! 1.5 Component Guilds Manager
	 *
	 * @version $Id: controller.php 2011-10-28 10:20:36 svn $
	 * @author Nick Swinford
	 * @package Joomla
	 * @subpackage Guilds Manager
	 * @license Copyright (c) 2011 - All Rights Reserved
	 */
	
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	
	jimport( 'joomla.application.component.view');
	
	class GuildsViewAdvancement extends JView {
		
		function display() {
			JHTML::stylesheet('guilds.css','components/com_guilds/media/css/');
			JHTML::stylesheet('bootstrap.css','components/com_guilds/media/css/');
			JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
			JHTML::script('bootstrap.js','components/com_guilds/media/js/',false);
			JHTML::script('advancement.jquery.js','components/com_guilds/media/js/',false);
			
			parent::display();
		}
		
	}