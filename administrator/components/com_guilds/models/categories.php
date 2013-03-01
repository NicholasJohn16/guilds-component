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

    class GuildsModelCategories extends JModel {
        
        function getCategories(){
            if(empty($this->categories)){
                $db = JFactory::getDBO();
                //$query = " SELECT * FROM #__char_categories WHERE published = 1 ORDER BY ordering ";
                $query = " SELECT * FROM jos_char_categories LEFT JOIN (SELECT parent as id2, group_concat(id) as children FROM jos_char_categories GROUP BY parent) A ON (jos_char_categories.id = A.id2) WHERE published = 1 ORDER BY ordering ";
                $db->setQuery($query);
                $this->categories = $db->loadObjectList();	
            }
            return $this->categories;
        }
                
        function getCategoricalInfoByType($type) {
            if(empty($this->categoriesByType)) {
                $db = JFactory::getDBO();
                $query = " SELECT * FROM jos_char_categories LEFT JOIN (SELECT parent as id2, group_concat(id) as children FROM jos_char_categories GROUP BY parent) A ON (jos_char_categories.id = A.id2) WHERE published = 1 AND type LIKE ".$db->quote($type)." ORDER BY ordering ";
                $db->setQuery($query);
                $this->categoriesByType = $db->loadObjectList();
            }
            return $this->categoriesByType;
        }

        function getCategoriesByType() {
            $type = $this->getState('type');
            if(empty($this->categoriesByType)) {
                $db = JFactory::getDBO();
                $query = " SELECT *, id AS value, name AS text FROM jos_char_categories WHERE published = 1 AND type LIKE ".$db->quote($type)." ORDER BY ordering ";
                $db->setQuery($query);
                $this->categoriesByType = $db->loadObjectList();
            }
            return $this->categoriesByType;
        }
    }