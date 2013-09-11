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

    public function getAllTypes() {
        if (empty($this->allTypes)) {
            $db = JFactory::getDBO();
            $sql  = ' SELECT * ';
            $sql .= ' FROM #__guilds_types ';
            $sql .= ' ORDER BY ordering ';
            $db->setQuery($sql);
            $this->allTypes = $db->loadObjectList();
        }
        return $this->allTypes;
    }

    public function getTypes() {
        if (empty($this->types)) {
            $db = JFactory::getDBO();
            $sql = ' SELECT * ';
            $sql .= ' FROM #__guilds_types ';
            $sql .= ' WHERE published = 1 ';
            $sql .= ' ORDER BY ordering ';
            $db->setQuery($sql);
            $this->types = $db->loadObjectList();
        }

        return $this->types;
    }

    public function getType() {
        $db = JFactory::getDBO();
        $id = $this->getState('id');

        if ($id == NULL) {
            $type = new stdClass();
            $type->id = NULL;
            $type->name = NULL;
            $type->ordering = NULL;
            $type->published = NULL;
        } else {
            $sql = ' SELECT * ';
            $sql .= ' FROM #__guilds_types ';
            $sql .= ' WHERE id IN (' . implode(',', $id) . ') ';
            $db->setQuery($sql);
            $type = $db->loadObject();

            if (!$type) {
                return false;
            }
        }
        return $type;
    }

    public function save() {
        $id = $this->getState('id');

        if ($id[0] < 1) {
            return $this->insert();
        } else {
            return $this->update();
        }
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

        $sql = ' UPDATE #__guilds_types SET ';
        $sql .= implode(',', $values);
        $sql .= ' WHERE id IN (' . implode(',', $id) . ')';
        
        $db->setQuery($sql);
        $result = $db->query();
        return $result;
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
        $sql = " INSERT INTO #__guilds_types ";
        $sql .= " ( `" . implode("`, `", array_keys($fields)) . "` )";
        $sql .= " VALUES ( " . implode(", ", $fields) . ") ";
        // set the query in the db object
        $db->setQuery($sql);
        // execute it
        $result = $db->query();

        // if new type was inserted
        if ($result) {
            // Get the id for the new type
            $id = $db->insertid();
            // alter the characters table 
            // to include the new column
            $result = $this->addToCharacters();

            // if alter succeded
            if ($result) {
                return true;
            } else {
                // set the id state to the new type id
                $this->setState('id', $id);
                // and delete it
                $this->delete();
                return false;
            }
        } else {
            return false; // because the type wasn't inserted
        }
    }

    public function delete() {
        $db = JFactory::getDBO();
        $id = $this->getState('id');

        // get the type we're about to delete
        $type = $this->getType();
        // build the query
        $sql  = " DELETE FROM `#__guilds_types` ";
        $sql .= " WHERE id IN (" . implode(',', $id) . ")";
        //set it on the databse object
        $db->setQuery($sql);
        // and execute it
        $result = $db->query();
        // type was deleted
        if($result) {
            $this->setState('fields',array('name'=>$type->name));
            // then drop the column from the characters table
            $result = $this->dropFromCharacters();

            if($result) {
                return true; // Both were deleted
            } else {
                // recreate the type 
                // add it back into the table
                $fields['name'] = $type->name;
                $fields['ordering'] = $type->ordering;
                $fields['published'] = $type->published;
                $this->setState('fields',$fields);
                $result = $this->insert();
                return false;
            }
        } else {
            return false; //because the type wasn't deleted
        }
    }

    public function saveorder() {
        $db = JFactory::getDBO();
        $ids = $this->getState('ids');
        $order = $this->getState('order');

        $sql = ' UPDATE #__guilds_types ';
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

    public function addToCharacters() {
        $db = JFactory::getDBO();
        $fields = $this->getState('fields');

        $sql = ' ALTER TABLE `#__guilds_characters` ';
        $sql .= ' ADD ' . $db->nameQuote($fields['name']);
        $sql .= ' INT( 6 ) NULL DEFAULT NULL ';

        $db->setQuery($sql);
        $result = $db->query();
        return $result;
    }

    public function dropFromCharacters() {
        $db = JFactory::getDBO();
        $fields = $this->getState('fields');

        $sql = ' ALTER TABLE `#__guilds_characters` ';
        $sql .= ' DROP ' . $db->nameQuote($fields['name']);

        $db->setQuery($sql);
        $result = $db->query();
        return $result;
    }

}

?>