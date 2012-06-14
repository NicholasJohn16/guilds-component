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
			$order		= $mainframe->getUserStateFromRequest($option."order",'order',null,'cmd' );
			$direction	= $mainframe->getUserStateFromRequest($option."direction",'direction',null,'word');
			$search		= $mainframe->getUserStateFromRequest($option."search",'search','','string' );
			$filter_type= $mainframe->getUserStateFromRequest($option.'filter_type','filter_type',array(),'array');
			
			// In case limit has been changed, adjust it
			//$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
		 
			$this->setState('limit', $limit);
			$this->setState('limitstart', $limitstart);
			$this->setState('order',$order);
			$this->setState('direction',$direction);
			$this->setState('search',$search);
			$this->setState('filter_type',$filter_type);
	    }
    
	    protected function buildQuery() {
	    	$select = $this->buildSelect();
	    	$where = $this->buildWhere();
	    	$orderBy = $this->buildOrderBy();
			$query = $select.$where.' GROUP BY id '.$orderBy;
			dump($query);
	    	return $query;
	    }
    
	    function buildSelect($users = null) {
	    	
	    	if($users == null) {
	    		$select = ' id ';
	    	} else {
	       		$select = ' SELECT a.id,a.username,f.appdate,FROM_UNIXTIME(e.time) AS time,g.status,f.tbd,d.rank_id,d.rank_title '
		        	.' FROM #__users AS a '
					.' LEFT JOIN #__kunena_users AS c ON c.userid = a.id '
					.' LEFT JOIN #__kunena_ranks AS d ON d.rank_id = c.rank '
					.' LEFT JOIN #__kunena_messages AS e ON e.userid = a.id '
					.' LEFT JOIN adv_user_manager AS f ON f.user_id = a.id '
					.' LEFT JOIN adv_status_values AS g on g.id = f.status ';
	    	}
	    	return $select;	
	    }
	    
	    function buildWhere() {
	    	$search = $this->getState("search");
	    	$where = ' WHERE '
					.' e.parent = 0 AND '
					.' e.catid = 6 ';
					//  b.field_id = 29 AND	
	    	$conditions = array();
	    	
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
	    		$orderBy = ' ORDER BY '.$order.' '.$direction;	
	    	} else {
	    		$orderBy = ' ORDER BY e.time ';
	    	}
	    	
	    	return $orderBy;
	    }
	    function getMember() {
	    	$id = $this->getState('id');
	    	dump($id);
	    }
    
		function getMembers() {
		    // Load the data
		    if (empty( $this->members )) {
		        $query = $this->buildQuery();
		        $this->_db->setQuery( $query );
		        $this->members = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		    }
	//	    if (!$this->_data) {
	//	        $this->_data = new stdClass();
	//	        $this->_data->id = 0;
	//	        $this->_data->greeting = null;
	//	    }
						
		    return $this->members;
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
  	
	  	function updateHandle($new_handle,$user_id){
	  		$db =& JFactory::getDBO();
	  		$query = " UPDATE ". $db->nameQuote('#__community_fields_values')
	  				." SET value = ". $db->quote($new_handle)
	  				." WHERE ".$db->nameQuote('field_id')." = ". $db->quote('29')
	  				." AND ".$db->nameQuote('user_id')." = " . $db->quote($user_id);
	  		$db->setQuery($query);
	  		$db->query();
	  	}
  	
	  	function getRanks() {
	  		if(empty($this->ranks)) {
		  		$db =& JFactory::getDBO();
		  		$query  = " SELECT ".$db->nameQuote('rank_id')." AS id, ".$db->nameQuote('rank_title'). " AS title";
		  		$query .= " FROM ".$db->nameQuote('#__kunena_ranks');
		  		$db->setQuery($query);
		  		$this->ranks = $db->loadObjectList();
	  		}
	  		return $this->ranks;
	  	 }
  	 
		function getTypes(){
			if(empty($this->types)){
				$db =& JFactory::getDBO();
				$query = " SELECT * FROM #__char_types WHERE published = 1 ORDER BY ordering ";
				$db->setQuery($query);
				$this->types = $db->loadObjectList();
			}

			return $this->types;
		}
		
		function getCategories(){
			if(empty($this->categories)){
				$db =& JFactory::getDBO();
				//$query = " SELECT * FROM #__char_categories WHERE published = 1 ORDER BY ordering ";
				$query = " SELECT * FROM jos_char_categories LEFT JOIN (SELECT parent as id2, group_concat(id) as children FROM jos_char_categories GROUP BY parent) A ON (jos_char_categories.id = A.id2) WHERE published = 1 ORDER BY ordering ";
				$db->setQuery($query);
				$this->categories = $db->loadObjectList();	
			}
			
			return $this->categories;
		}
	}
?>