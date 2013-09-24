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
class GuildsModelMembers extends JModel {

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

    /**
     * Constructor
     */
    function __construct() {
        parent::__construct();

        $option = JRequest::getCmd('option');
        $mainframe = JFactory::getApplication();

        // Get the view and layout so we can make pagination values unique
        $view = JRequest::getVar('view');
        $layout = JRequest::getVar('layout');

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . 'limitstart', 'limitstart', 0);

        // Get filter values for Roster view
        $order = $mainframe->getUserStateFromRequest($option . $view . $layout . 'order', 'order', null, 'cmd');
        $direction = $mainframe->getUserStateFromRequest($option . $view . $layout . 'direction', 'direction', null, 'word');
        $search = $mainframe->getUserStateFromRequest($option . $view . $layout . 'search', 'search', '', 'string');
        $filter_type = $mainframe->getUserStateFromRequest($option . $view . $layout . 'filter_type', 'filter_type', array(), 'array');

        // In case limit has been changed, adjust it
        //$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('order', $order);
        $this->setState('direction', $direction);
        $this->setState('search', $search);
        $this->setState('filter_type', $filter_type);
        $this->setState('users', null);
    }

    protected function buildQuery() {
        $select = $this->buildSelect();
        $where = $this->buildWhere();
        //$groupBy = ' GROUP BY id ';
        $orderBy = $this->buildOrderBy();
        $query = $select . $where . $orderBy;
        return $query;
    }

    function buildSelect() {
        $select = ' SELECT a.user_id as id, a.user_id, a.username AS username, a.appdate, b.status AS status, '
                . ' a.notes, a.edit_id, c.username as editor, a.edit_time, '
                . ' a.sto_handle, a.gw2_handle, a.tor_handle ';
        return $select;
    }
    
    function buildFrom() {
        $from = ' FROM `#__guilds_members` AS a '
              . ' LEFT JOIN  `#__guilds_ranks` as b on a.status = b.id '
              . ' LEFT JOIN `#__guilds_members` AS c ON a.edit_id = c.user_id ';
        return $from;
    }

    function buildWhere() {
        $search = $this->getState('search');
        $users = $this->getState('users');
        $conditions = array();

        if ($search != "" || !empty($search)) {
            // Split the search string into an array
            $terms = explode(",", $search);
            // Trim each term, check if their ints and make strings lowercase
            foreach ($terms as $term) {
                strtolower(trim($term));
                if (is_numeric($term)) {
                    $conditions[] = ' a.user_id = ' . intval($term);
                } else {
                    $conditions[] = ' LOWER(a.username) LIKE "%' . $term . '%" ';
                    $conditions[] = ' LOWER(a.sto_handle) LIKE "%' . $term . '%" ';
                    $conditions[] = ' LOWER(a.tor_handle) LIKE "%' . $term . '%" ';
                    $conditions[] = ' LOWER(a.gw2_handle) LIKE "%' . $term . '%" ';
                    // Removed because searching by status doesn't work
                    //$conditions[] = ' LOWER(b.status) LIKE "%' . $term . '%" ';
                }
            }
        }

        $where = implode(" OR ", $conditions);

        if ($where != "") {
            return " WHERE " . $where;
        }
    }

    function buildOrderBy() {
        $order = $this->getState('order');
        $direction = $this->getState('direction');

        if ($order != null || $direction != null) {
            $orderBy = ' ORDER BY a.' . $order . ' ' . $direction;
        }

        if (isset($orderBy)) {
            return $orderBy;
        }
    }

    function getMember() {
        $id = $this->getState('id');
        $db = JFactory::getDBO();
        $ranks = $this->getInstance('ranks','GuildsModel');

        $sql  = $this->buildSelect();
        $sql .= $this->buildFrom();
        $sql .= ' WHERE a.user_id = '.$id;
        $sql .= ' LIMIT 1 ';
        
        $db->setQuery($sql);
        $member = $db->loadObject();
        
        // set it as an array cause that's what updateStatus expects
        $ranks->setState('members',$member);
        $result = $ranks->updateStatus();
        
        if(!$result) {
            return false;
        } else {
            $db->setQuery($sql);
            $member = $db->loadObject();
            return $member;
        }
    }

    function getMembers() {
        $ranks = $this->getInstance('ranks','GuildsModel');

        // Load the data
        if (empty($this->members)) {
            $ids = $this->getMemberIDs();
            
            // If no ids were found, return false
            if(empty($ids)) {
                return false; 
            } else {
                $this->setState('ids',$ids);
                $members = $this->getMembersByIds();
                
                $ranks->setState('members',$members);
                $ranks->updateStatus();
                
                $this->members = $this->getMembersByIds();
            }
            
        }
        return $this->members;
    }
    
    function getMemberIDs() {
        $db = JFactory::getDBO();
        
        if(empty($this->ids)) {
            $sql = ' SELECT a.user_id ';
            $sql .= $this->buildFrom();
            $sql .= $this->buildWhere();
            $sql .= $this->buildOrderBy();
            
            $db->setQuery($sql,$this->getState('limitstart'),$this->getState('limit'));
            $this->ids = $db->loadResultArray();
        }
        
        return $this->ids;
    }

    function getMembersByIds() {
        $db = JFactory::getDBO();
        $ids = $this->getState('ids');
        
        $sql  = $this->buildSelect();
        $sql .= $this->buildFrom();
        $sql .= ' WHERE a.user_id IN ('.implode(',',$ids).')';
        $sql .= $this->buildOrderBy();
        $db->setQuery($sql);
        $members = $db->loadObjectList();

        return $members;
    }

    function getHandleList() {
        $db = JFactory::getDBO();
        $name = trim($this->getState('name'));

        if (empty($this->handle_list)) {
            $sql = ' ( SELECT user_id AS id, username AS text ';
            $sql .= '   FROM #__guilds_members ';
            $sql .= '   WHERE username LIKE "%' . $name . '%" ) ';
            $sql .= ' UNION ';
            $sql .= ' ( SELECT user_id AS id , sto_handle AS text ';
            $sql .= '   FROM #__guilds_members AS b ';
            $sql .= '   WHERE sto_handle LIKE "%' . $name . '%"  ) ';
            $sql .= ' UNION ';
            $sql .= ' ( SELECT user_id AS id, tor_handle AS text ';
            $sql .= '   FROM #__guilds_members AS c ';
            $sql .= '   WHERE tor_handle LIKE "%' . $name . '%"  ) ';
            $sql .= ' UNION ';
            $sql .= ' ( SELECT user_id AS id, gw2_handle AS text ';
            $sql .= '   FROM #__guilds_members AS d ';
            $sql .= '   WHERE gw2_handle LIKE "%' . $name . '%" ) ';
            $sql .= ' UNION ';
            $sql .= '( SELECT user_id AS id, handle as text ';
            $sql .= '  FROM #__guilds_characters AS e ';
            $sql .= '  WHERE handle LIKE "%' . $name . '%"  ) ';
            $sql .= ' ORDER BY text asc ';
            $sql .= ' LIMIT 10 ';
            $db->setQuery($sql);

            $this->handle_list = $db->loadObjectList();
        }

        return $this->handle_list;
    }

    function getTotal() {
        // Load the content if it doesn't already exist
        if (empty($this->total)) {
            
            $sql = ' SELECT a.id ';
            $sql .= $this->buildFrom();
            $sql .= $this->buildWhere();
            
            $this->total = $this->_getListCount($sql);
        }
        return $this->total;
    }

    function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->pagination)) {
            jimport('joomla.html.pagination');
            $this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }
        return $this->pagination;
    }
    
    function update() {
        $id = $this->getState('id');
        $db = JFactory::getDBO();
        $values = array();
        $fields = array();
        $fields['sto_handle'] = $this->getState('sto_handle');
        $fields['tor_handle'] = $this->getState('tor_handle');
        $fields['gw2_handle'] = $this->getState('gw2_handle');
        $fields['appdate'] = $this->getState('appdate');
        $fields['notes'] = $this->getState('notes');
        $fields['edit_id'] = JFactory::getUser()->id;
        $fields['edit_time'] = date('Y-m-d H:i:s');

        foreach ($fields as $name => $value) {
            if ($value === NULL) {
                unset($fields[$name]);
            }
            if ($value === "") {
                $fields[$name] = 'NULL';
            } elseif (is_string($value)) {
                $fields[$name] = $db->quote($value);
            }
        }

        foreach ($fields as $name => $value) {
            $values[] = " `" . $name . "` = " . $value . " ";
        }

        $sql = ' UPDATE #__guilds_members SET ';
        $sql .= implode(", ", $values);
        $sql .= ' WHERE `user_id` = ' . $id;
        
        $db->setQuery($sql);
        $result = $db->query();

        return $result;
    }

}

?>