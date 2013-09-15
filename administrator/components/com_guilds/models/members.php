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

        $select = " SELECT a.user_id as id "
                // . ", a.username, b.appdate, b.status, b.tbd, "
                // . " b.sto_handle, b.gw2_handle, b.tor_handle, c.status "
                //. " FROM jos_users AS a "
                . " FROM #__guilds_members AS a "
                . " LEFT JOIN #__guilds_ranks AS b ON a.status = b.id ";

        return $select;
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
                    $conditions[] = ' LOWER(b.status) LIKE "%' . $term . '%" ';
                }
            }
        }

        $where = implode(" OR ", $conditions);

//                if($users != null) {
//                    $conditions[] = array("AND","a.id","IN",'('.implode(',',$users).')');
//                }
//	    	if(count($conditions) == 1 ) {
//	    		$where .= " ".$conditions[0][0]." ".$conditions[0][1]." ".$conditions[0][2]." ".$conditions[0][3]." ";
//	    	} elseif(count($conditions) > 1) {
//	    		$where .= " AND ( ";
//				foreach($conditions as $condition) {
//					$where .= " ".$condition[0]." ".$condition[1]." ".$condition[2]." ".$condition[3]." ";	
//				}
//				$where .= " ) ";
//	    	}

        if ($where != "") {
            return " WHERE " . $where;
        }
    }

    function buildOrderBy() {
        $order = $this->getState('order');
        $direction = $this->getState('direction');

        if ($order != null || $direction != null) {
//            switch ($order) {
//                case 'id':
//                case 'username':
//                case 'user_id':
                    $orderBy = ' ORDER BY a.' . $order . ' ' . $direction;
//                    break;
//                case 'status':
//                    $orderBy = ' ORDER BY b.status ' . $direction;
//                    break;
//                default:
//                    $orderBy = ' ORDER BY b.' . $order . ' ' . $direction;
//            }
        }

        if (isset($orderBy)) {
            return $orderBy;
        }
    }

    function getMember() {
        $id = $this->getState('id');
        $db = JFactory::getDBO();

        if (empty($this->member)) {
            $sql = ' SELECT a.id, a.user_id, a.username AS username, a.appdate, b.status AS status, '
                    . ' a.notes, a.edit_id, c.username as editor, a.edit_time, '
                    . ' a.sto_handle, a.gw2_handle, a.tor_handle '
                    . ' FROM `#__guilds_members` AS a '
                    . ' LEFT JOIN  `#__guilds_ranks` as b on a.status = b.id '
                    . ' LEFT JOIN `#__guilds_members` AS c ON a.edit_id = c.user_id '
                    //. ' LEFT JOIN `#__guilds_members` AS d ON a.user_id = d.user_id '
                    . ' WHERE a.user_id = ' . $id;
            $db->setQuery($sql);
            $this->member = $db->loadObject();
        }

        return $this->member;
    }

    function getMembers() {
        $db = JFactory::getDBO();
        
        // Load the data
        if (empty($this->members)) {
            $query = $this->buildQuery();
            
            $db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
            $ids = $db->loadResultArray();
            
            // Incase no matching members were found
            // return false
            if (empty($ids)) {
                return false;
            }
            $this->setState('ids', $ids);
            $members = $this->getMembersByIds();
            $this->setState('members', $members);
            $this->members = $this->updateStatus();
        }
        return $this->members;
    }

    function getMembersByIds() {
        $db = JFactory::getDBO();
        $ids = $this->getState('ids');

        $query = " SELECT a.user_id as id, "
                . " a.username, a.appdate, a.status, a.tbd, "
                . " a.sto_handle, a.gw2_handle, a.tor_handle, b.status "
                . " FROM #__guilds_members AS a "
                . " LEFT JOIN #__guilds_ranks AS b ON a.status = b.id ";
        $query .= " WHERE a.user_id IN (" . implode(',', $ids) . ")";
        $query .= $this->buildOrderBy();
        $db->setQuery($query);
        $members = $db->loadObjectList();
        
        return $members;
    }

    function getHandleList() {
        $db = JFactory::getDBO();
        $name = trim($this->getState('name'));

        if (empty($this->handle_list)) {
            $sql = ' ( SELECT user_id, username AS text ';
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
            $query = $this->buildQuery();
            $this->total = $this->_getListCount($query);
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

    function updateStatus() {
        $members = $this->getState('members');
        $today = time();

        foreach ($members as $member) {
            $member->status = "Recruit";
            if (!empty($member->sto_handle) || !empty($member->tor_handle) || !empty($member->gw2_handle)) {
                $member->status = "Cadet";

                $seconds_ago = $today - strtotime($member->appdate);
                $days_ago = floor($seconds_ago / (60 * 60 * 24));
                if (!empty($member->appdate) && $days_ago > 14) {
                    $member->status = "Member";
                }
            }
        }

        return $members;
    }

    function getForumRanks() {
        $users = $this->getState('member_ids');

        if (empty($this->forumRanks)) {
            $db = JFactory::getDBO();
            $query = " SELECT `userid`, `rank`, `rank_title` "
                    . " FROM `jos_kunena_users` AS a "
                    . " LEFT JOIN `jos_kunena_ranks` AS b ON a.rank = b.rank_id"
                    . " AND `user_id` IN (" . implode(",", $users) . ");";
            $db->setQuery($query);
            $this->forumRanks = $this->loadObjectList();
        }

        return $this->handles;
    }

    function getRanks() {
        if (empty($this->ranks)) {
            $db = & JFactory::getDBO();
            $query = " SELECT " . $db->nameQuote('rank_id') . " AS value, " . $db->nameQuote('rank_title') . " AS text";
            $query .= " FROM " . $db->nameQuote('#__kunena_ranks');
            $db->setQuery($query);
            $this->ranks = $db->loadObjectList();
        }
        return $this->ranks;
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
        
        foreach($fields as $name => $value) {
            if($value === NULL) {
                unset($fields[$name]);
            }
            if($value === "") {
                $fields[$name] = 'NULL';
            } elseif(is_string($value)) {
                $fields[$name] = $db->quote($value);
            }
        }
        
        foreach($fields as $name => $value) {
            $values[] = " `".$name."` = ".$value." ";
        }
        
        $sql  = ' UPDATE #__guilds_members SET ';
        $sql .= implode(", ", $values);
        $sql .= ' WHERE `user_id` = ' . $id;
        
        $db->setQuery($sql);
        $result = $db->query();
        
        return $result;
    }

}

?>