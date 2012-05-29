<?php
/**
 * Joomla! 1.5 component Member Manager
 *
 * @version $Id: controller.php 2011-10-28 10:20:36 svn $
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Member Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Member Manager component
 */
class MembermanagerViewMembers extends JView {
	
	function __construct() {
		$user_id = JRequest::getVar('userid','','','int');
		$task = lcfirst(str_replace("update","",JRequest::getVar('task','','','string')));
	}

	
	function display($tmpl = null) {
		$user =& JFactory::getUser();
				
		echo "This is the ajax layout";
		
		/*
		Query to update fields
		UPDATE
			jos_users AS a,
			jos_community_fields_values AS b,
			jos_community_fields_values AS c,
			jos_kunena_users AS d,
			adv_user_manager as e
		SET
			b.value = 'new_kurganxy'
		WHERE
			b.field_id = 29 AND
			c.field_id = 39 AND
			a.id = b.user_id AND
			a.id = c.user_id AND
			a.id = d.userid AND
			a.id = e.user_id AND
			a.id = 168
		 */
	}
}