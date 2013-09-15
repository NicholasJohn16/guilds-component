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
        dump($query,'Characters Query');
        return $query;
    }

    function buildSelect() {
        $types_model = $this->getInstance('types', 'GuildsModel');
        $types = $types_model->getTypes();
        $category_fields = array();
        $category_joins = array();
        dump($types);
        for($i = 0,$c = 99;$i < count($types);$i++,$c++) {
            $category_fields[] = 'a.'.$types[$i]->name.' AS '.$types[$i]->name.'_id';
            $category_fields[] = chr($c).'.name AS '.$types[$i]->name.'_name';
        }
        
        for($i = 0,$c = 99;$i < count($types);$i++,$c++) {
            $category_joins[] = ' LEFT JOIN #__guilds_categories AS '.chr($c).' ON '.chr($c).'.id = a.'.$types[$i]->name;
        }
        
        $query = ' SELECT a.id, a.user_id, a.name, a.checked, '
                . ' a.unpublisheddate, a.invite, a.name as name, '
                . ' a.id AS id, a.published AS published, '
                . ' b.sto_handle, b.tor_handle, b.gw2_handle, a.handle, '
                . ' b.appdate, b.status, ';
        $query .= implode(', ',$category_fields);
        $query .= " FROM #__guilds_characters AS a ";
        $query .= " LEFT JOIN #__guilds_members AS b ON a.user_id = b.user_id ";
        $query .= implode(' ',$category_joins);

        return $query;
    }

    function buildWhere() {
        $user = $this->getState('user');
        $search = $this->getState('search');
        $filters = $this->getState('filters');

        $where = " WHERE ";
        $conditions = array();

        //Check to see if the user is set and if it is add it to the where array
        if ($user != null || $user != 0) {
            $conditions[] = array("AND", "a.user_id", "=", $user);
        }

        if ($search != "" || !empty($search)) {
            // Split the search string into an array
            $terms = explode(",", $search);
            // Trim each term, check if their ints and make strings lowercase
            foreach ($terms as $term) {
                trim($term);
                strtolower($term);
                if (is_numeric($term)) {
                    $conditions[] = array('OR', 'a.user_id', '=', intval($term));
                    $conditions[] = array('OR', 'a.id', '=', intval($term));
                } else {
                    $conditions[] = array('OR', 'LOWER(a.name)', 'LIKE', '"%' . $term . '%"');
                    $conditions[] = array('OR', 'LOWER(b.username)', 'LIKE', '"%' . $term . '%"');
                }
            }
        }
        // Add all the type filters to the Where array
        foreach ($filters as $type => $value) {
            if ($value != "") {
                $conditions[] = array("AND", "a." . $type, "=", $value);
            }
        }
        
        if (count($conditions) == 0) {
            $where = "";
        } elseif (count($conditions) == 1) {
            $where.= " " . $conditions[0][1] . " " . $conditions[0][2] . " " . $conditions[0][3] . " ";
        } else {
            $count = count($conditions);
            $where .= $conditions[0][1] . " " . $conditions[0][2] . " " . $conditions[0][3];

            for ($i = 1; $i < $count; $i++) {
                $where .= " " . $conditions[$i][0] . " " . $conditions[$i][1] . " " . $conditions[$i][2] . " " . $conditions[$i][3] . " ";
            }
        }

        return $where;
    }

    function buildOrderBy() {
        $order = $this->getState("order");
        $direction = $this->getState("direction");

        $orderBy = "ORDER BY a." . $order . " " . $direction;

        if ($order == null || $direction == null) {
            $orderBy = "";
        }

        return $orderBy;
    }

    // Gets multiple characters
    function getCharacters() {
        $today = time();

        if (empty($this->characters)) {
            $query = $this->buildQuery();
            $characters = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

            foreach ($characters as $character) {
                // Default all statuses to Recruit
                $character->status = "Recruit";
                // If they have provided an in-game handle
                if (!empty($character->sto_handle) || !empty($character->tor_handle) || !empty($character->gw2_handle)) {
                    // Set their status to Cadet
                    $character->status = "Cadet";
                    // Calculate the number of days between now and their application
                    $seconds_ago = $today - strtotime($character->appdate);
                    $days_ago = floor($seconds_ago / (60 * 60 * 24));
                    // If it's great than 14, then promote them to Member
                    if (!empty($character->appdate) && $days_ago > 14) {
                        $character->status = "Member";
                    }
                }
            }

            $this->characters = $characters;
        }
        return $this->characters;
    }

    //Gets a single character by character id
    //If id is not supplied, returns empty object
    function getCharacter() {
        $id = $this->getState('id');
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
                $this->character->unpublisheddate = NULL;
                foreach ($types as $type) {
                    $type_id = $type->name . '_id';
                    $this->character->$type_id = NULL;
                }
            } else {
                $db = & JFactory::getDBO();
                $query = $this->buildSelect();
                $id = $this->getState('id');
                $query .= " WHERE a.id = " . $id;
                $db->setQuery($query);
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

    function add() {
        // Get the database object and all necessary states
        $db = $this->getDBO();
        $fields = array();
        $fields['user_id'] = $this->getState('user_id');
        $fields['name'] = $this->getState('name');
        $fields['handle'] = $this->getState('handle');
        $fields['invite'] = $this->getState('invite');
        $fields['checked'] = $this->getState('checked');
        $fields['published'] = $this->getState('published');
        if($fields['user_id'] === NULL || $fields['user_id'] === '') {
            $fields['user_id'] = JFactory::getUser()->id;
        }
        
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
        $fields['unpublisheddate'] = $this->getState('unpublisheddate');
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
            return $this->add();
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

        if (empty($this->pendingInvites)) {
            $query = $this->buildSelect();
            $query .= " WHERE invite = 1 ";
            $db->setQuery($query);
            $this->pendingInvites = $db->loadObjectList();
        }

        return $this->pendingInvites;
    }

    function getPendingPromotions() {
        $db = JFactory::getDBO();
        $today = date('Y-m-d');
        
        if (empty($this->pendingPromotions)) {
            $sql = $this->buildSelect();
            $sql .= ' WHERE (date_add(appdate,INTERVAL 14 DAY) <= "'.$today.'"'
                 . ' AND date( checked ) < date_add(appdate,INTERVAL 14 DAY) )'
                 . ' OR ( checked IS NULL AND appdate IS NOT NULL ) ';
            $db->setQuery($sql);
            
            $this->pendingPromotions = $db->loadObjectList();
        }
        return $this->pendingPromotions;
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