<?php
	/**
	 * Joomla! 1.5 component Character Manager
	 *
	 * @version $Id: controller.php 2011-10-28 10:20:36 svn $
	 * @author Nick Swinford
	 * @package Joomla
	 * @subpackage Character Manager
	 * @license Copyright (c) 2011 - All Rights Reserved
	 */

	// no direct access
	defined('_JEXEC') or die('Restricted access');
	
	jimport('joomla.application.component.model');

	class CharactermanagerModelCharacters extends JModel {
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

		// In case limit has been changed, adjust it
		//$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	 
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
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
		
		function buildQuery(){
			//$orderby = $this->buildOrderBy(); 
			$where = $this->buildWhere();
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
			$query .= $where;
			//$query .= $orderby;
			
			return $query;
		}
		
		function buildWhere(){
			$character = $this->getState('character');
			$user = $this->getState('user');
			
			$where = " WHERE ";
			
			$arrWhere = array();
			
			// Example of how to build where array
			//$arrWhere[] = array('a.id',$character);
			if($user != null || $user != 0) {
				$where .= " a.user_id = ".$user;
			}
			if($character != null) {
				$where .= " a.id = ".$character;
			}

			if($where != " WHERE ") {
				return $where;	
			}
		}
		
		function buildOrderBy(){
			
		}
		
//		function getCharactersByUser() {
//			$user = $this->getState('user');
//			if($user == null) {
//				$user = JFactory::getUser();
//			}
//			dump($user,"User");
//			$db = $this->getDBO();
//			$query = $this->buildQuery();
//			$query .= " WHERE user_id = ".$user; 
//			$db->setQuery($query);
//			$characters = $db->loadObjectList();
//			return $characters;
//		}
		
		// Gets multiple characters
		// 1. Gets the current user's characters
		//	 if layout is default or user parameter is not set
		// 2. Gets all current characters
		//	 if layout is roster and user parameter is not set
		// 3. Gets characters for a specific user
		//	 if layout is ajax and user parameter is set
		
		function getCharacters(){
			$db = $this->getDBO();
			
			if(empty($this->characters)) {
				$query = $this->buildQuery();
				$limit = $this->getState('limitstart');
				$limitstart = $this->getState('limit');
				$user = $this->getState('user');
				
				$this->characters = $this->_getList($query,$this->getState('limitstart'),$this->getState('limit'));
			}
						
			return $this->characters;
		}
		
		
		//Gets a single character
		function getCharacter() {
			if(empty($this->character)){
				$db =& JFactory::getDBO();
				$db->setQuery($this->buildQuery());
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