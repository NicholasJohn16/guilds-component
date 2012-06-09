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
	    /**
		 * Constructor
		 */
	function __construct() {
		global $mainframe, $option;
		parent::__construct();
		
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.'limitstart','limitstart',0);
		
		// Get filter values for Roster view
		$filter_order		= $mainframe->getUserStateFromRequest($option."filter_order",'filter_order','a.name','cmd' );
		$filter_order_dir	= $mainframe->getUserStateFromRequest($option."filter_order_Dir",'filter_order_Dir','','word');
		$search				= $mainframe->getUserStateFromRequest($option."search",'search','','string' );
		$filter_type 		= $mainframe->getUserStateFromRequest($option.'filter_type','filter_type',array(),'array');

		// In case limit has been changed, adjust it
		//$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	 
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter_order',$filter_order);
		$this->setState('filter_order_dir',$filter_order_dir);
		$this->setState('search',$search);
		$this->setState('filter_type',$filter_type);
    }
    
	//function buildWhere(){
//		$user = JRequest::getVar('user', 0, '', 'int');
//		$approved_gids = Array(24,25,31);
//		$current_user =& JFactory::getUser();
//		if(!$user == 0 && in_array($current_user->gid,$approved_gids)){
//			return $query = " WHERE user_id = ".$user;
//		} elseif ($user == 0){
//			return $query = " WHERE user_id = ".$current_user->id;
//		} else {
//			die("You are not authorized to view this resource.");
//			//return $query = " WHERE a.name IS NULL";
//		}
	//}
	
	/* Query Functions */
		
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
		
		function buildQuery() {
			$select = $this->buildSelect();
			$where = $this->buildWhere();
			$order = $this->buildOrderBy();
			dump($select.$where.$order,"Query");
			return $select.$where.$order;
			
		}
		
		function buildSelect(){
			$types = $this->getTypes();
			$i = 99;
			$n = 99;
			$query  = " SELECT a.id,a.user_id,a.name,b.username,a.rosterchecked,a.published,a.unpublisheddate ";
			foreach($types AS $type) {
				$query .= ",a.".$type->name." AS ".$type->name."_id ";
				$query .= ",".chr($i).".name AS ".$type->name."_name ";
				$i++;
			}
			$query .= " FROM #__char_characters AS a ";
			$query .= " LEFT JOIN #__users AS b ON b.id = a.user_id ";
			foreach($types AS $type){
				$query .= " LEFT JOIN #__char_categories AS ".chr($n)." ON ".chr($n).".id = a.".$type->name." ";
				$n++;
			}
					
			return $query;
		}
		
		
		// 1. Gets the current user's characters
		//	 if layout is default or user parameter is not set
		// 2. Gets all current characters
		//	 if layout is roster and user parameter is not set
		// 3. Gets characters for a specific user
		//	 if layout is ajax and user parameter is set
		function buildWhere(){
			$user = $this->getState('user');
			$filter_order = $this->getState('filter_order');
			$filter_order_dir = $this->getState('filter_order_dir');
			$search = $this->getState('search');
			$filter_type = $this->getState('filter_type');
			
			$where = " WHERE ";
			$conditions = array();
			
			//Check to see if the user is set and if it is add it to the where array
			if($user != null || $user != 0) {
				$conditions[] = "a.user_id = ".$user;
			}
			// Add all the type filters to the Where array
			foreach($filter_type as $type => $value) {
				if($value != "") {
					$conditions[] = "a.".$type." = ".$value;
				}
			}
					
			if(count($conditions) == 0 ) {
				return "";
			} elseif (count($conditions) == 1) {
				return $where." ".$conditions[0];
			} else {
				$where .= implode(' AND ',$conditions);
				return $where;
			}
		}
		
		function buildOrderBy(){
			
		}
				
		// Gets multiple characters
		function getCharacters(){
			$db = $this->getDBO();
			
			if(empty($this->characters)) {
				$query = $this->buildQuery();
				$limit = $this->getState('limitstart');
				$limitstart = $this->getState('limit');
								
				$this->characters = $this->_getList($query,$this->getState('limitstart'),$this->getState('limit'));
			}
						
			return $this->characters;
		}
		
		
		//Gets a single character by character id
		function getCharacter() {
			if(empty($this->character)){
				$db =& JFactory::getDBO();
				$query = $this->buildSelect();
				$character = $this->getState('character');
				$query .= "WHERE a.id = ".$character;
				$db->setQuery($query);
				$this->character = $db->loadObject();
			}
			return $this->character;
		}
		
		/* Task functions */
		
		function add(){
	  		
	  	}
	  	
	  	function delete(){
	  		$db = $this->getDBO();
	  		$id = JRequest::getVar('id',0,'','int');
	  		$query = " DELETE * FROM `#__char_characters` WHERE id = ".$id;
	  		if($db->setQuery($query)){
	  			return true;
	  		} else {
	  			return false;
	  		}
	  	}
	  	
	  	function edit(){
  		
  		}
  		
  		function publish(){
  			
  		}
  		
  		function unpublish(){
  			
  		}
		
		/* Pagination functions */
		
		function getPagination(){
			// Load the content if it doesn't already exist
			if (empty($this->pagination)) {
				jimport('joomla.html.pagination');
				$this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
			}
			return $this->pagination;
		}
		
		function buildQueryforTotal(){
			//$orderby = $this->_buildContentOrderBy();
			$where = $this->buildWhere();
			$query  = " SELECT id";
			$query .= " FROM #__char_characters AS a";
			$query .= $where;
			//$query .= $orderby;
			return $query;
		
		}
		
		function getTotal(){
			// Load the content if it doesn't already exist
			if (empty($this->total)) {
				$query = $this->buildQueryforTotal();
				$this->total = $this->_getListCount($query);	
			}
			return $this->total;
		}

}