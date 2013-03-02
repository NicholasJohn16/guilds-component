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

                    // Get pagination request variables
                    $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
                    $limitstart = $mainframe->getUserStateFromRequest($option.'limitstart','limitstart',0);

                    // Get filter values for Roster view
                    $order = $mainframe->getUserStateFromRequest($option."order",'order',null,'cmd' );
                    $direction = $mainframe->getUserStateFromRequest($option."direction",'direction',null,'word');
                    $search = $mainframe->getUserStateFromRequest($option."search",'search','','string' );
                    $filter_type = $mainframe->getUserStateFromRequest($option.'filter_type','filter_type',array(),'array');

                    // In case limit has been changed, adjust it
                    //$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

                    $this->setState('limit', $limit);
                    $this->setState('limitstart', $limitstart);
                    $this->setState('order',$order);
                    $this->setState('direction',$direction);
                    $this->setState('search',$search);
                    $this->setState('filter_type',$filter_type);
                    $this->setState('users',null);
                }
    
	    protected function buildQuery() {
	    	$select = $this->buildSelect();
	    	$where = $this->buildWhere();
	    	$groupBy = ' GROUP BY id ';
	    	$orderBy = $this->buildOrderBy();
			$query = $select.$where.$groupBy.$orderBy;
	    	return $query;
	    }
    
	    function buildSelect() {
	    	$users = $this->getState('users');
	    	
	    	if($users == null) {
	    		$select = ' SELECT a.id '
		        	.' FROM #__users AS a '
		        	.' LEFT JOIN #__community_fields_values AS b ON b.user_id = a.id '
		        	.' LEFT JOIN #__community_fields_values as h on h.user_id = a.id '
					.' LEFT JOIN #__kunena_users AS c ON c.userid = a.id '
					.' LEFT JOIN #__kunena_ranks AS d ON d.rank_id = c.rank '
					.' LEFT JOIN #__kunena_messages AS e ON e.userid = a.id '
					.' LEFT JOIN #__guilds_members AS f ON f.user_id = a.id '
					.' LEFT JOIN #__guilds_ranks AS g on g.id = f.status ';
	    	} else {
	       		$select = ' SELECT a.id,a.username,b.value AS handle,f.appdate,FROM_UNIXTIME(e.time) AS time,g.status,f.tbd,d.rank_id,d.rank_title,h.value AS guilds '
		        	.' FROM #__users AS a '
		        	.' LEFT JOIN #__community_fields_values AS b ON b.user_id = a.id '
		        	.' LEFT JOIN #__community_fields_values as h on h.user_id = a.id '
					.' LEFT JOIN #__kunena_users AS c ON c.userid = a.id '
					.' LEFT JOIN #__kunena_ranks AS d ON d.rank_id = c.rank '
					.' LEFT JOIN #__kunena_messages AS e ON e.userid = a.id '
					.' LEFT JOIN #__guilds_members AS f ON f.user_id = a.id '
					.' LEFT JOIN #__guilds_ranks AS g on g.id = f.status ';
	    	}
	    	return $select;	
	    }
	    
	    function buildWhere() {
	    	$search = $this->getState("search");
	    	$where = 'WHERE (b.field_id = 29) AND '
	    			.' (h.field_id = 18) AND '
	    			.' (e.parent = 0 AND e.catid = 6) ';
	    	$conditions = array();
	    	$users = $this->getState('users');
	    	
	    	if($search != "" || !empty($search)) {
				// Split the search string into an array
				$terms = explode(",",$search);
				// Trim each term, check if their ints and make strings lowercase
				foreach($terms as $term) {
					trim($term);
					strtolower($term);
					if(is_numeric($term)) {
						$conditions[] = array("OR","a.id","=",intval($term));	
					} else {
						$conditions[] = array("OR","LOWER(a.username)","LIKE",'"%'.$term.'%"');
						
					}
				}
			}
	    	
			if($users != null) {
	    		$conditions[] = array("AND","a.id","IN",'('.implode(',',$users).')');
	    	}
			
	    	if(count($conditions) == 1 ) {
	    		$where .= " ".$conditions[0][0]." ".$conditions[0][1]." ".$conditions[0][2]." ".$conditions[0][3]." ";
	    	} elseif(count($conditions) > 1) {
	    		$where .= " AND ( ";
				foreach($conditions as $condition) {
					$where .= " ".$condition[0]." ".$condition[1]." ".$condition[2]." ".$condition[3]." ";	
				}
				$where .= " ) ";
	    	}
	    	
	    	return $where;
	    }
	    
	    function buildOrderBy() {
	    	$order = $this->getState('order');
	    	$direction = $this->getState('direction');
	    	
	    	if($order != null || $direction != null ) {
	    		$orderBy = ' ORDER BY a.'.$order.' '.$direction;	
	    	} else {
	    		$orderBy = '';
	    	}
	    	
	    	return $orderBy;
	    }
	    function getMember() {
	    	$id = $this->getState('id');
	    }
    
		function getMembers() {
		    // Load the data
		    if (empty( $this->members )) {
		        $query = $this->buildQuery();
		        $this->_db->setQuery($query);
		        $this->member_ids = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		        
		        foreach($this->member_ids as $member) { $members[] = $member->id; }
		        $this->setState('users',$members);
		        
		        // Update the status values for the members in the current view
		        $this->updateStatus();
		        
		        $query = $this->buildQuery();
		        $this->_db->setQuery($query);
		        $this->members = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		        
		    }
		    return $this->members;
		}
		
        function getTypeahead() {
            $query = 'SELECT id,username,"User"
                FROM jos_users
                WHERE lower(username) LIKE \'%john%\'
                UNION
                SELECT user_id,value,"Handle"
                FROM jos_community_fields_values
                WHERE field_id = 29 AND
                lower(value) LIKE \'%john%\'';
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
                $this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
            }
            return $this->pagination;
        }
  	
        function getCharactersByUserId($user_id) {
                $db =& JFactory::getDBO();

                $query = " SELECT * " 
                                . " FROM #__char_characters "
                                . " WHERE user_id = " . $user_id;
                $db->setQuery($query);
                $result = $db->loadObjectList();

                return $result;
        }
	  	
        function updateStatus() {
                $users = $this->getState('users');

        }
        
        function update($field,$id,$value){
            $db =& JFactory::getDBO();

            switch($field) {
                case "forum_rank":
                    $query = " UPDATE " . $db->nameQuote('#__kunena_users')
                            . " SET rank = " . $db->quote($value) 
                            . " WHERE userid  = " . $db->quote($id);
                    break;
                case "appdate":
                    $query = " UPDATE " . $db->nameQuote('#__guilds_members');
                    if($value) {
                        $query .= " SET appdate = " . $db->quote($value);
                    } else {
                        $query .= " SET appdate = NULL ";
                    }
                    $query .= " WHERE " . $db->nameQuote('user_id') . " = " . $db->quote($id);
                    break;
                case "handle";
                    $query = " UPDATE ". $db->nameQuote('#__community_fields_values')
                        ." SET value = ". $db->quote($value)
                        ." WHERE ".$db->nameQuote('field_id')." = ". $db->quote('29')
                        ." AND ".$db->nameQuote('user_id')." = " . $db->quote($id);
                    break;
                default:
                    JError::raiseError('500','Invalid request',"Name option is incorrect");
            }
                $db->setQuery($query);
                $db->query();
        }

        function getHandles() {
            $users = $this->getState('users');	

            if(empty($this->handles)) {
                $db = JFactory::getDBO();
                $query = " SELECT value "
                        ." FROM #__community_fields_values "
                        ." WHERE `field_id` =  29 "
                        ." AND `user_id` IN (".implode(",",$users).");";
                $db->setQuery($query);
                $this->handles = $this->loadObjectList();
            }

            return $this->handles;
        }

        function getForumRanks() {
            $users = $this->getState($users);

            if(empty($this->forumRanks)) {
                $db = JFactory::getDBO();
                $query = " SELECT `userid`, `rank`, `rank_title` "
                        ." FROM `jos_kunena_users` AS a "
                        ." LEFT JOIN `jos_kunena_ranks` AS b ON a.rank = b.rank_id"
                        ." AND `user_id` IN (".implode(",",$users).");";
                $db->setQuery($query);
                $this->forumRanks = $this->loadObjectList();
            }

            return $this->handles;
        }

        function getRanks() {
            if(empty($this->ranks)) {
                $db =& JFactory::getDBO();
                $query  = " SELECT ".$db->nameQuote('rank_id')." AS value, ".$db->nameQuote('rank_title'). " AS text";
                $query .= " FROM ".$db->nameQuote('#__kunena_ranks');
                $db->setQuery($query);
                $this->ranks = $db->loadObjectList();
            }
            return $this->ranks;
         }
  	 
		
	}
?>