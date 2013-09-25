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

class GuildsModelCharacters extends JModel {

    /**
     * array of Character objects
     * @var arrry
     */
    var $characters = null;

    /**
     * Items total
     * @var integer
     */
    var $total = null;

    /** 	
     * Pagination object
     * @var object
     */
    var $pagination = null;
    
    var $categories = array();

    /**
     * Constructor
     */
    
    function __construct() {
        global $mainframe, $option;
        parent::__construct();

        // Get the view and layout so we can make pagination values unique
        $view = JRequest::getVar('view');
        $layout = JRequest::getVar('layout');

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . $view . $layout . 'limitstart', 'limitstart', 0);

        // Get filter values for Roster view
        $order = $mainframe->getUserStateFromRequest($option . $view . $layout . 'order', 'order', null, 'cmd');
        $direction = $mainframe->getUserStateFromRequest($option . $view . $layout . "direction", 'direction', null, 'word');
        $search = $mainframe->getUserStateFromRequest($option . $view . $layout . 'search', 'search', '', 'string');
        $filters = $mainframe->getUserStateFromRequest($option . $view . $layout . 'category', 'category', array(), 'array');

        // In case limit has been changed, adjust it
        //$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('order', $order);
        $this->setState('direction', $direction);
        $this->setState('search', $search);
        $this->setState('filters', $filters);
    }

    function buildQuery() {
        $select = $this->buildSelect();
        $where = $this->buildWhere();
        $order = $this->buildOrderBy();
        $query = $select . $where . $order;
        
        return $query;
    }

    function buildSelect() {
        $types_model = $this->getInstance('types', 'GuildsModel');
        $types = $types_model->getTypes();
        $category_fields = array();
        $category_joins = array();
        
        for($i = 0,$c = 100;$i < count($types);$i++,$c++) {
            $category_fields[] = 'a.'.$types[$i]->name.' AS '.$types[$i]->name.'_id';
            $category_fields[] = chr($c).'.name AS '.$types[$i]->name.'_name';
        }
        
        for($i = 0,$c = 100;$i < count($types);$i++,$c++) {
            $category_joins[] = ' LEFT JOIN #__guilds_categories AS '.chr($c).' ON '.chr($c).'.id = a.'.$types[$i]->name;
        }
        
        $query = ' SELECT a.id, a.user_id, a.name, a.checked, '
                . ' a.unpublished_date, a.invite, a.name as name, '
                . ' a.id AS id, a.published AS published, '
                . ' b.sto_handle, b.tor_handle, b.gw2_handle, a.handle, '
                . ' b.appdate, c.status as status, ';
        $query .= implode(', ',$category_fields);
        $query .= ' FROM #__guilds_characters AS a ';
        $query .= ' LEFT JOIN #__guilds_members AS b ON a.user_id = b.user_id ';
        $query .= ' LEFT JOIN #__guilds_ranks AS c ON b.status = c.id ';
        $query .= implode(' ',$category_joins);

        return $query;
    }

    function buildWhere() {
        $search = $this->getState('search');
        $filters = $this->getState('filters');

        $and = array();
        $or = array();

        if ($search != "" || !empty($search)) {
            // Split the search string into an array
            $terms = explode(",", $search);
            // Trim each term, check if their ints and make strings lowercase
            foreach ($terms as $term) {
                trim($term);
                strtolower($term);
                if (is_numeric($term)) {
                    $or[] = '`a.user_id` = '.intval($term);
                    $or[] = '`a.id` = '.intval($term);
                } else {
                    $or[] = 'LOWER(a.name) LIKE "%'.$term.'%"';
                    $or[] = 'LOWER(b.username) LIKE "%'.$term.'%"';
                    $or[] = 'LOWER(a.handle) LIKE "%'.$term.'%"';
                }
            }
        }
        // Add all the type filters to the Where array
        foreach ($filters as $type => $value) {
            if ($value != "") {
                $and[] = "a.".$type.' = '.$value;
            }
        }
        
        if(!empty($and) && !empty($or)) {
            $where = ' WHERE ('.implode(') AND (',$and).') AND ('.implode(') OR (',$or).') ';
        } elseif(!empty($and)) {
            $where = ' WHERE '.implode(' AND ',$and);
        } elseif(!empty($or)) {
            $where = ' WHERE '.implode(' OR ',$or);
        } else {
            $where = false;
        }
        
        return $where;
    }

    function buildOrderBy() {
        $order = $this->getState("order");
        $direction = $this->getState("direction");

        $orderBy = " ORDER BY a." . $order . " " . $direction;

        if ($order == null || $direction == null) {
            $orderBy = "";
        }

        return $orderBy;
    }

    // Gets multiple characters
    function getCharacters() {

        if (empty($this->characters)) {
            $query = $this->buildQuery();
            
            $characters = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
            
            if(empty($characters)) {
                return false;
            } else {
                $ranks = $this->getInstance('ranks','GuildsModel');
                $ranks->setState('members',$characters);
                $ranks->updateStatus();

                $characters = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

                $this->characters = $characters;
            }
        }
        return $this->characters;
    }

    //Gets a single character by character id
    //If id is not supplied, returns empty object
    function getCharacter() {
        $id = $this->getState('id');
        $id = (is_array($id)) ? $id[0] : $id;
        $types_model = $this->getInstance('types', 'GuildsModel');
        $types = $types_model->getTypes();
        
        if (empty($this->character)) {
            if ($id == NULL) {
                $this->character = new stdClass();
                $this->character->user_id = NULL;
                $this->character->id = NULL;
                $this->character->name = NULL;
                $this->character->handle = NULL;
                $this->character->invite = NULL;
                $this->character->checked = NULL;
                $this->character->published = NULL;
                $this->character->unpublished_date = NULL;
                foreach ($types as $type) {
                    $type_id = $type->name . '_id';
                    $this->character->$type_id = NULL;
                }
            } else {
                $db = & JFactory::getDBO();
                $sql = $this->buildSelect();
                $sql .= " WHERE a.id = " . $id;
                $db->setQuery($sql);
                $character = $db->loadObject();
                
                $ranks = $this->getInstance('ranks','GuildsModel');
                $ranks->setState('members',$character);
                $ranks->updateStatus();
                
                $db->setQuery($sql);
                $this->character = $db->loadObject();
            }
        }
        
        return $this->character;
    }

    function getCharactersByUserID() {
        $db = JFactory::getDBO();
        $user_id = $this->getState('user_id');
        $publishedOnly = $this->getState('publishedOnly');

        if (empty($this->charactersForUser)) {
            $query = $this->buildSelect();
            $query .= " WHERE a.user_id = " . $user_id;
            if ($publishedOnly) {
                $query .= " AND a.published = 1 ";
            }
            $db->setQuery($query);
            $this->charactersForUser = $db->loadObjectList();
        }

        return $this->charactersForUser;
    }

    /* Task functions */

    function insert() {
        // Get the database object and all necessary states
        $db = $this->getDBO();
        $fields = array();
        $user = JFactory::getUser();
        
        $fields['user_id'] = $this->getState('user_id');
        $fields['name'] = $this->getState('name');
        $fields['handle'] = $this->getState('handle');
        $fields['invite'] = $this->getState('invite');
        $fields['checked'] = $this->getState('checked');
        $fields['published'] = $this->getState('published');
        if($fields['user_id'] === NULL || $fields['user_id'] === '') {
            $fields['user_id'] = $user->id;
        }
        $fields['created_by'] = $user->id;
        $fields['created_on'] = date('Y-m-d H:i:s');
        
        $categories = $this->getState('categories');
        if(is_array($categories)){
            foreach($categories as $name => $value) {
                // force categoriy ids to be ints so aren't quoted later
                $fields[$name] = (int)$value;
            }
        }
        
        foreach($fields as $name => $value) {
            //if the value is null and an empty string
            if($value === NULL || $value === "") {
                // remove it from the fields
                unset($fields[$name]);
                
            } elseif(is_string($value)) {
                
                $fields[$name] = $db->quote($value);
            }   
        }
        $query  = " INSERT INTO #__guilds_characters ";
        $query .= " ( `" . implode("`, `",array_keys($fields)) . "` )";
        $query .= " VALUES ( " . implode(", ",$fields) . ") ";
        $db->setQuery($query);
        $result = $db->query();
        return $result;
    }

    function delete() {
        $db = $this->getDBO();
        $ids = $this->getState('ids');
        $query = " DELETE FROM `#__guilds_characters` WHERE id IN (" . implode(',', $ids) . ")";
        $db->setQuery($query);
        return $db->query();
    }

    function update() {
        // Get the database object and all necessary states
        $db = $this->getDBO();
        $id = $this->getState('id');
        
        $fields = array();
        $fields['user_id'] = $this->getState('user_id');
        $fields['name'] = $this->getState('name');
        $fields['handle'] = $this->getState('handle');
        $fields['invite'] = $this->getState('invite');
        $fields['checked'] = $this->getState('checked');
        $fields['published'] = $this->getState('published');
        $fields['unpublished_date'] = $this->getState('unpublished_date');
        $fields['invite'] = $this->getState('invite');
        
        $categories = $this->getState('categories');
        if(is_array($categories)) {
            foreach($categories as $name => $value) {
                // force categoriy ids to be ints so aren't quoted later
                $fields[$name] = (int)$value;
            }
        }
        
        // Filter out fields that aren't being updated
        foreach($fields as $name => $value) {
            //if the value is null or an empty string
            if($value === NULL){
                // remove it from the fields
                unset($fields[$name]);
            }
            // if a value is an empty string, change it to NULL
            if($value === "") {
                $fields[$name] = "NULL";
            } elseif(is_string($value)) {
                // if its a string, go ahead and quote it
                $fields[$name] = $db->quote($value);
            }   
        }
        
        foreach($fields as $name => $value) {
            $values[] = " `".$name."` = ".$value." ";
        }
      
        $sql  = " UPDATE #__guilds_characters SET ";
        $sql .= implode(", ", $values);
        $sql .= " WHERE id IN (".implode(',',$id).')';
        
        $db->setQuery($sql);
        $result = $db->query();
        return $result;
    }

    function save() {
        $id = $this->getState('id');

        if ($id < 1) {
            return $this->insert();
        } else {
            return $this->update();
        }
    }

    /* Pagination functions */

    function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->pagination)) {
            jimport('joomla.html.pagination');
            $this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->pagination;
    }

    function getPendingInvites() {
        $db = JFactory::getDBO();
        // Get menu item params
        $itemId = JRequest::getInt('Itemid');
        $menu = JSite::getMenu();
        $params = $menu->getParams($itemId);
        $game = $params->get('game');

        if (empty($this->invites)) {
            $sql = $this->buildSelect();
            $sql .= " WHERE invite = 1 ";
            $sql .= ' AND `game` = '.$game;
            $db->setQuery($sql);
            $invites = $db->loadObjectList();
            
            $ranks = $this->getInstance('ranks','GuildsModel');
            $ranks->setState('members',$invites);
            $ranks->updateStatus();
            
            $db->setQuery($sql);
            $this->invites = $db->loadObjectList();
        }

        return $this->invites;
    }

    function getPendingPromotions() {
        $db = JFactory::getDBO();
        $today = date('Y-m-d');
        // Get menu item params
        $itemId = JRequest::getInt('Itemid');
        $menu = JSite::getMenu();
        $params = $menu->getParams($itemId);
        $game = $params->get('game');
        
        if (empty($this->promotions)) {
            $sql = $this->buildSelect();
            $sql .= ' WHERE ((date_add(appdate,INTERVAL 14 DAY) <= "'.$today.'"'
                 . ' AND date( checked ) < date_add(appdate,INTERVAL 14 DAY) )'
                 . ' OR ( checked IS NULL AND appdate IS NOT NULL )) '
                 . ' AND game = '.$game;
            $db->setQuery($sql);
            
            $promotions = $db->loadObjectList();
            
            $ranks = $this->getInstance('ranks','GuildsModel');
            $ranks->setState('members',$promotions);
            $ranks->updateStatus();
            
            $db->setQuery($sql);
            $this->promotions = $db->loadObjectList();
        }
        return $this->promotions;
    }

    function buildQueryforTotal() {
        //$orderby = $this->_buildContentOrderBy();
        $where = $this->buildWhere();
        $sql = " SELECT a.id";
        $sql .= " FROM #__guilds_characters AS a ";
        $sql .= ' LEFT JOIN #__guilds_members AS b ON a.user_id = b.user_id';
        //$query .= " LEFT JOIN #__users AS b ON a.user_id = b.id ";
        $sql .= $where;
        //$query .= $orderby;
        return $sql;
    }

    function getTotal() {
        // Load the content if it doesn't already exist
        if (empty($this->total)) {
            $sql = $this->buildQueryforTotal();
            $this->total = $this->_getListCount($sql);
        }
        return $this->total;
    }

}