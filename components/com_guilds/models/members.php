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
		
		$mainframe = JFactory::getApplication();
 
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest('$option.limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	 
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
    }
    
    protected function buildQuery() {
    	$query = 'SELECT a.id,a.username,b.value,f.appdate,FROM_UNIXTIME(e.time) AS time,g.status,f.tbd,d.rank_id,d.rank_title'
	        .' FROM #__users AS a '
			.' LEFT JOIN #__community_fields_values AS b ON b.user_id = a.id '
			.' LEFT JOIN #__kunena_users AS c ON c.userid = a.id '
			.' LEFT JOIN #__kunena_ranks AS d ON d.rank_id = c.rank '
			.' LEFT JOIN #__kunena_messages AS e ON e.userid = a.id '
			.' LEFT JOIN adv_user_manager AS f ON f.user_id = a.id '
			.' LEFT JOIN adv_status_values AS g on g.id = f.status '
			.' WHERE b.field_id = 29 AND '
			.' e.parent = 0 AND '
			.' e.catid = 6 '
			.' GROUP BY id '
			.' ORDER BY e.time ';
    		return $query;
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