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
    
    /*
     *  Gets all Categories from Database
     *  Regardles of state 
     */
    public function getAllCategories() {
        $db = JFactory::getDBO();
        $children = array();
        
        $sql  = ' SELECT a.id, a.name, b.name AS type, b.id AS type_id, ';
        $sql .= ' a.ordering, a.published, a.parent ';
        $sql .= ' FROM #__guilds_categories AS a ';
        $sql .= ' LEFT JOIN #__guilds_types AS b ON a.type = b.id ';
        $sql .= ' ORDER by a.ordering ';
        
        $db->setQuery($sql);
        $categories = $db->loadObjectList();
        
        // Establish hierarchy
        // First pass: collect children
        foreach ($categories as $category) {
            $parent = $category->parent;
            $list = @$children[$parent] ? $children[$parent] : array();
            array_push($list, $category);
            $children[$parent] = $list;
        }
        // Second pass: get an indent list of the items
        $tree = JHTML::_('menu.treerecurse', 0, '', array(), $children);

        return $tree;
    }

    function getCategories() {
        if (empty($this->categories)) {
            $db = JFactory::getDBO();
            $sql = ' SELECT a.id, a.name, a.parent, a.type, a.ordering, '
                 . ' a.published, b.children, c.name as type, c.id as type_id '
                 . ' FROM #__guilds_categories AS a '
                 . ' LEFT JOIN ( '
                 . ' SELECT parent as id2, group_concat(id) as children '
                 . ' FROM #__guilds_categories GROUP BY parent '
                 . ') AS b ON ( a.id = b.id2 ) '
                 . ' LEFT JOIN #__guilds_types AS c ON a.type = c.id ' 
                 . ' WHERE a.published = 1 ORDER BY a.ordering ';
            $db->setQuery($sql);
            $this->categories = $db->loadObjectList();
        }
        return $this->categories;
    }

    function getCategoriesByType() {
        $type = $this->getState('type');
        if (empty($this->categoriesByType)) {
            $db = JFactory::getDBO();
            $sql = ' SELECT *, id AS value, name AS text '
                    . ' FROM #__guilds_categories AS a '
                    . ' LEFT JOIN #__guilds_types AS b ON a.type = b.id '
                    . ' WHERE published = 1 '
                    . ' AND type LIKE ' . $db->quote($type)
                    . ' ORDER BY ordering ';
            $db->setQuery($sql);
            $this->categoriesByType = $db->loadObjectList();
        }
        return $this->categoriesByType;
    }

    public function getCategory() {
        $db = JFactory::getDBO();
        $id = $this->getState('id');

        if ($id == NULL) {
            $category = new stdClass();
            $category->id = NULL;
            $category->name = NULL;
            $category->ordering = NULL;
            $category->published = NULL;
            $category->parent = NULL;
            $category->type = NULL;
            $category->type_id = NULL;
        } else {
            $sql  = ' SELECT a.id, a.name, b.name AS type, b.id AS type_id, ';
            $sql .= ' a.ordering, a.published, a.parent ';
            $sql .= ' FROM #__guilds_categories AS a ';
            $sql .= ' LEFT JOIN #__guilds_types AS b ON a.type = b.id  ';
            $sql .= ' WHERE a.id = ' . $id;
            $db->setQuery($sql);
            $category = $db->loadObject();
        }
        return $category;
    }

    public function saveorder() {
        $db = JFactory::getDBO();
        $ids = $this->getState('ids');
        $order = $this->getState('order');

        $sql = ' UPDATE #__guilds_categories ';
        $sql .= ' SET `ordering` = CASE `id` ';
        for ($i = 0; $i < count($ids); $i++) {
            $sql .= ' WHEN ' . $ids[$i] . ' THEN ' . $order[$i] . ' ';
        }
        $sql .= ' END ';
        $sql .= ' WHERE id IN (' . implode(',', $ids) . ') ';

        $db->setQuery($sql);
        $result = $db->query();
        return $result;
    }
    
    public function save() {
        $id = $this->getState('id');

        if ($id[0] < 1) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }
    
    public function insert() {
        $db = JFactory::getDBO();
        $fields = $this->getState('fields');

        // Filter out fields that aren't being updated
        foreach ($fields as $name => $value) {
            //if the value is null or an empty string
            if ($value === NULL) {
                // remove it from the fields
                unset($fields[$name]);
            }
            // if a value is an empty string, change it to NULL
            if ($value === "") {
                $fields[$name] = "NULL";
            } elseif (is_string($value)) {
                // if its a string, go ahead and quote it
                $fields[$name] = $db->quote($value);
            }
        }
        // Build the query
        $sql = " INSERT INTO #__guilds_categories ";
        $sql .= " ( `" . implode("`, `", array_keys($fields)) . "` )";
        $sql .= " VALUES ( " . implode(", ", $fields) . ") ";
        // set the query in the db object
        $db->setQuery($sql);
        // execute it
        $result = $db->query();

        return $result;
    }

    public function update() {
        $db = JFactory::getDBO();
        $id = $this->getState('id');
        $fields = $this->getState('fields');
        $values = array();

        // Filter out fields that aren't being updated
        foreach ($fields as $name => $value) {
            //if the value is null or an empty string
            if ($value === NULL) {
                // remove it from the fields
                unset($fields[$name]);
            }
            // if a value is an empty string, change it to NULL
            if ($value === "") {
                $fields[$name] = "NULL";
            } elseif (is_string($value) && $name != 'ordering') {
                // if its a string, go ahead and quote it
                $fields[$name] = $db->quote($value);
            }
        }

        foreach ($fields as $name => $value) {
            $values[] = " `" . $name . "` = " . $value . " ";
        }

        $sql = ' UPDATE #__guilds_categories SET ';
        $sql .= implode(',', $values);
        $sql .= ' WHERE id IN (' . implode(',', $id) . ')';

        $db->setQuery($sql);
        $result = $db->query();
        return $result;
    }

    public function delete() {
        $db = JFactory::getDBO();
        $id = $this->getState('id');

        // build the query
        $sql = " DELETE FROM `#__guilds_categories` ";
        $sql .= " WHERE id IN (" . implode(',', $id) . "); ";

        $db->setQuery($sql);
        $result = $db->query();

        if ($result) {
            $sql = ' UPDATE `#__guilds_categories` ';
            $sql .= ' SET parent = 0 ';
            $sql .= ' WHERE parent IN (' . implode(',', $id) . '); ';

            $db->setQuery($sql);
            $result = $db->query();
        }

        return $result;
    }

}