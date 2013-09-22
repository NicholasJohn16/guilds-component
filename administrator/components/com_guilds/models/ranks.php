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
class GuildsModelRanks extends JModel {
    
    public function updateStatus() {
        $members = $this->getState('members');
        $db = JFactory::getDBO();
        $today = time();
        $fields = array();
        $ids = array();
        
        if(!is_array($members)) {
            $members = array($members);
        }
        
        foreach ($members as $member) {
            $member->status = 4;  // Recruit
            if (!empty($member->sto_handle) || !empty($member->tor_handle) || !empty($member->gw2_handle)) {
                $member->status = 5; // Cadet

                $seconds_ago = $today - strtotime($member->appdate);
                $days_ago = floor($seconds_ago / (60 * 60 * 24));
                if (!empty($member->appdate) && $days_ago > 14) {
                    $member->status = 6; // Member
                }
            }
        }
        
        foreach($members as $member) {
            $fields[] = ' WHEN '.$member->id.' THEN '.$member->status;
            $ids[] = $member->id;
        }
        
        $sql  = ' UPDATE #__guilds_members ';
        $sql .= ' SET `status` = CASE `user_id` ';
        $sql .= implode(' ',$fields);
        $sql .= ' END ';
        $sql .= ' WHERE `user_id` IN ('.implode(',',$ids).') ';
        
        $db->setQuery($sql);
        $result = $db->query();

        return $result;
    }
    
}