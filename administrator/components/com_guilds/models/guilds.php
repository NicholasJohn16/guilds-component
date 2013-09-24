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
class GuildsModelGuilds extends JModel {
 
    public function buildTables() {
        $db = JFactory::getDBO();
        $prefixes = array('swvg','kos');
        $sql = '';
        
        foreach($prefixes as $prefix) {
            $sql .= 'DROP VIEW IF EXISTS '.$prefix.'_guilds_types;';
            $sql .= 'DROP VIEW IF EXISTS '.$prefix.'_guilds_categories;';
            $sql .= 'DROP VIEW IF EXISTS '.$prefix.'_guilds_ranks;';
            $sql .= 'DROP VIEW IF EXISTS '.$prefix.'_guilds_characters;';
            $sql .= 'DROP VIEW IF EXISTS '.$prefix.'_guilds_members;';
            $sql .= 'CREATE VIEW '.$prefix.'_guilds_types AS SELECT * FROM #__guilds_types;';
            $sql .= 'CREATE VIEW '.$prefix.'_guilds_categories AS SELECT * FROM #__guilds_categories;';
            $sql .= 'CREATE VIEW '.$prefix.'_guilds_ranks AS SELECT * FROM #__guilds_ranks;';
            $sql .= 'CREATE VIEW '.$prefix.'_guilds_characters AS SELECT * FROM #__guilds_characters;';
            $sql .= 'CREATE VIEW '.$prefix.'_guilds_members AS SELECT * FROM #__guilds_members;';
        }
        
        $db->setQuery($sql);
        $result = $db->queryBatch();
        
        return $result;
    }
    
}
