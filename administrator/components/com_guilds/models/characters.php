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

            // Get the view and layout so we can make pagination values unique
            $view = JRequest::getVar('view');
            $layout = JRequest::getVar('layout');
            
            // Get pagination request variables
            $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
            $limitstart = $mainframe->getUserStateFromRequest($option.$view.$layout.'limitstart','limitstart',0);

            // Get filter values for Roster view
            $order = $mainframe->getUserStateFromRequest($option.$view.$layout."order",'order',null,'cmd' );
            $direction = $mainframe->getUserStateFromRequest($option.$view.$layout."direction",'direction',null,'word');
            $search = $mainframe->getUserStateFromRequest($option.$view.$layout."search",'search','','string' );
            $filter_type = $mainframe->getUserStateFromRequest($option.$view.$layout.'filter_type','filter_type',array(),'array');

            // In case limit has been changed, adjust it
            //$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

            $this->setState('limit',$limit);
            $this->setState('limitstart',$limitstart);
            $this->setState('order',$order);
            $this->setState('direction',$direction);
            $this->setState('search',$search);
            $this->setState('filter_type',$filter_type);
}
            function buildQuery() {
                    $select = $this->buildSelect();
                    $where = $this->buildWhere();
                    $order = $this->buildOrderBy();
                    return $select.$where.$order;
            }

            function buildSelect(){
                    $types_model = $this->getInstance('types','GuildsModel');
                    $types = $types_model->getTypes();
                
                    //$types = $this->getTypes();
                    $i = 99;
                    $n = 99;
                    $query  = " SELECT *,a.name as name,a.id as id, a.published as published ";
                    foreach($types AS $type) {
                            $query .= ",a.".$type->name." AS ".$type->name."_id ";
                            $query .= ",".chr($i).".name AS ".$type->name."_name ";
                            $i++;
                    }
                    $query .= " FROM #__guilds_characters AS a ";
                    $query .= " LEFT JOIN #__users AS b ON a.user_id = b.id ";
                    foreach($types AS $type){
                            $query .= " LEFT JOIN #__guilds_categories AS ".chr($n)." ON ".chr($n).".id = a.".$type->name." ";
                            $n++;
                    }

                    return $query;
            }

            function buildWhere(){
                    $user = $this->getState('user');
                    $search = $this->getState('search');
                    $filter_type = $this->getState('filter_type');

                    $where = " WHERE ";
                    $conditions = array();

                    //Check to see if the user is set and if it is add it to the where array
                    if($user != null || $user != 0) {
                            $conditions[] = array("AND","a.user_id","=",$user);
                    }

                    if($search != "" || !empty($search)) {
                            // Split the search string into an array
                            $terms = explode(",",$search);
                            // Trim each term, check if their ints and make strings lowercase
                            foreach($terms as $term) {
                                    trim($term);
                                    strtolower($term);
                                    if(is_numeric($term)) {
                                            $conditions[] = array("OR","a.user_id","=",intval($term));
                                            $conditions[] = array("OR","a.id","=",intval($term));	
                                    } else {
                                            $conditions[] = array("OR","LOWER(a.name)","LIKE",'"%'.$term.'%"');
                                            $conditions[] = array("OR","LOWER(b.username)","LIKE",'"%'.$term.'%"');
                                    }
                            }
                    }
                    // Add all the type filters to the Where array
                    foreach($filter_type as $type => $value) {
                            if($value != "") {
                                    $conditions[] = array("AND","a.".$type,"=",$value);
                            }
                    }

                    if(count($conditions) == 0 ) {
                            $where = "";
                    } elseif (count($conditions) == 1) {
                            $where.= " ".$conditions[0][1]." ".$conditions[0][2]." ".$conditions[0][3]." ";
                    } else {
                            $count = count($conditions);
                            $where .= $conditions[0][1]." ".$conditions[0][2]." ".$conditions[0][3];

                            for($i = 1;$i < $count; $i++) {
                                    $where .= " ".$conditions[$i][0]." ".$conditions[$i][1]." ".$conditions[$i][2]." ".$conditions[$i][3]." ";
                            }
                    }

                    return $where;
            }

            function buildOrderBy(){
                    $order = $this->getState("order");
                    $direction = $this->getState("direction");

                    $orderBy = "ORDER BY ".$order." ".$direction;

                    if( $order == null || $direction == null ) {
                            $orderBy = "";	

                    }

                    return $orderBy;
            }

            // Gets multiple characters
            function getCharacters(){
                    if(empty($this->characters)) {
                            $query = $this->buildQuery();
                            $this->characters = $this->_getList($query,$this->getState('limitstart'),$this->getState('limit'));
                    }
                    return $this->characters;
            }


            //Gets a single character by character id
            //If id is not supplied, returns empty object
            function getCharacter() {
                $id = $this->getState('id');
                $types_model = $this->getInstance('types','GuildsModel');
                $types = $types_model->getTypes();
                if(empty($this->character)){
                    if($id == NULL ) {
                        $this->character = new stdClass();
                        $this->character->user_id = JFactory::getUser()->id;
                        $this->character->id = NULL;
                        $this->character->username = JFactory::getUser()->username;
                        $this->character->name = NULL;
                        $this->character->invite = NULL;
                        $this->character->checked = NULL;
                        $this->character->published = NULL;
                        foreach($types as $type) {
                            $type_id = $type->name.'_id';
                            $this->character->$type_id = NULL;
                        }
                    } else {
                        $db =& JFactory::getDBO();
                        $query = $this->buildSelect();
                        $id = $this->getState('id');
                        $query .= " WHERE a.id = ".$id;
                        $db->setQuery($query);
                        $this->character = $db->loadObject();
                    }
                }
                return $this->character;
            }
            
            function getCharactersByUserID() {
                $db = JFactory::getDBO();
                $id = $this->getState('id');
                $publishedOnly = $this->getState('publishedOnly');
                
                if(empty($this->charactersForUser)) {
                    $query = $this->buildSelect();
                    $query .= " WHERE user_id = ".$id;
                    if($publishedOnly) { $query .= " AND a.published = 1 "; }
                    $db->setQuery($query);
                    $this->charactersForUser = $db->loadObjectList();
                }
                
                return $this->charactersForUser;
            }

            /* Task functions */

            function add(){
                    // Get the database object and all necessary states
                    $db = $this->getDBO();
                    $user_id = $this->getState('user_id');
                    $name = $this->getState('name');
                    $categories = $this->getState('categories');
                    $invite = $this->getState('invite');
                    $checked = ($this->getState('checked') == "" ? 'NULL' : $this->getState('checked'));

                    // Create category arries and loop over the category input
                    // to create the necessary fields and values
                    $category_names = array();
                    $category_values = array();
                    foreach($categories as $category_name => $category_value) {
                            $category_names[] = $category_name;
                            $category_value = ($category_value == "" ? "NULL" : $category_value);
                            $category_values[] = $category_value;
                    }
                    // Create the query
                    // implode the arrays created earlier so their values are included
                    $query = 'INSERT INTO #__guilds_characters '
                            . '(`user_id`, `name`, `checked`,`published`,`invite`,`'.implode('`,`',$category_names).'`)'
                            . ' VALUES ('.$user_id.',"'.$name.'",'.$checked.',1,'.$invite.','.implode(',',$category_values).')';
                    $db->setQuery($query);
                    return $db->query();
            }

            function delete(){
                $db = $this->getDBO();
                $ids = $this->getState('ids');
                $query = " DELETE FROM `#__guilds_characters` WHERE id IN (".implode(',', $ids).")";
                $db->setQuery($query);
                return $db->query();
            }

            function edit(){
                // Get the database object and all necessary states
                    $db = $this->getDBO();
                    $user_id = $this->getState('user_id');
                    $id = $this->getState('id');
                    $name = $this->getState('name');
                    $categories = $this->getState('categories');
                    $invite = $this->getState('invite');
                    //$checked = ($this->getState('checked') == "" ? 'NULL' : $this->getState('checked'));

                    // Create the query
                    $sql = ' UPDATE #__guilds_characters SET '
                         . ' `name` = '.$db->quote($name).', '
                         //. ' `checked` = '.$db->quote($checked).', '
                         . ' `invite` = '.$db->quote($invite).' ';
                    foreach($categories as $category => $value) {
                        $sql .= ', '.$db->nameQuote($category).' = '.$db->quote($value).' ';
                    }
                    $sql .= ' WHERE id = '.$id;
                    $db->setQuery($sql);
                    $result = $db->query();
                    return $result;

            }

            function update($name,$id,$value) {
                $db = JFactory::getDBO();
                $query  = " UPDATE #__guilds_characters ";
                // If we're trying to reset the Checked date set it to NULL
                if($name == 'checked' && $value == '') {
                    $query .= " SET `checked` = NULL ";
                } else {
                    $query .= " SET " . $db->nameQuote($name) . " = " . $db->quote($value);
                }
                $query .= " WHERE " . $db->nameQuote('id') .  " = " . $id;
                $db->setQuery($query);

                return $db->query();
            }

            function publish(){
                
            }
            
            function save() {
                $id = $this->getState('id');
                
                if($id < 1) {
                    return $this->add();
                } else {
                    return $this->edit();
                }
                
            }

            function unpublish(){
                $db = JFactory::getDBO();
                $id = $this->getState('id');
                $sql = " UPDATE #__guilds_characters SET published = 0 WHERE id = ".$id;
                $db->setQuery($sql);
                return $db->query();
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
        
        function getPendingInvites() {
            $db = JFactory::getDBO();

            if(empty($this->pendingInvites)) {
                $query = $this->buildSelect();
                $query .= " WHERE invite = 1 ";
                $db->setQuery($query);
                $this->pendingInvites = $db->loadObjectList();
            }

            return $this->pendingInvites;
        }
        
        function getPendingPromotions() {
            
            
        }

        function buildQueryforTotal(){
            //$orderby = $this->_buildContentOrderBy();
            $where = $this->buildWhere();
            $query  = " SELECT a.id";
            $query .= " FROM #__guilds_characters AS a ";
            $query .= " LEFT JOIN #__users AS b ON a.user_id = b.id ";
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
        
        function invite() {
            $id = $this->getState('id');
            $db = JFactory::getDBO();
            $sql = " UPDATE `#__guilds_characters` SET `invite` = '1' WHERE `id` = ".$id;
            $db->setQuery($sql);
            $result = $db->query();
            return $result;
        }
        
        function invited() {
            $id = $this->getState('id');
            $db = JFactory::getDBO();
            $sql = " UPDATE `#__guilds_characters` SET `invite` = '0' WHERE `id` = ".$id;
            $db->setQuery($sql);
            $result = $db->query();
            return $result;
        }

    }