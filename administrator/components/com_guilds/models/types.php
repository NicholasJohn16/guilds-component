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
	
	jimport('joomla.application.component.model');
	
	/**
	 * Guilds Manager Component Member Model
	 *
	 * @author      Stonewall Gaming Network
	 * @package		Joomla
	 * @subpackage	Guilds Manager
	 * @since 1.5
	 */
	class GuildsModelTypes extends JModel {
		
		function getTypes(){
			if(empty($this->types)){
				$db =& JFactory::getDBO();
				$query = " SELECT * FROM #__char_types WHERE published = 1 ORDER BY ordering ";
				$db->setQuery($query);
				$this->types = $db->loadObjectList();
			}

			return $this->types;
		}
		
	}
?>