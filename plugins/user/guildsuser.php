<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgUserGuildsUser extends JPlugin {
    
    function plgUserGuildsUser(&$subject, $config) {
        parent::__construct($subject, $config);
    }
    
    function onAfterStoreUser($user,$isNew,$success,$msg) {
        
        if($success) {
            $db = JFactory::getDBO();
            if($isNew) {
                $sql  = ' INSERT INTO `#__guilds_members` ';
                $sql .= ' ( `user_id`, `username` ) ';
                $sql .= ' VALUES ( '.$user['id'].', "'.$user['username'].'" ) ';    
            } else {
                $sql  = ' UPDATE `#__guilds_members` ';
                $sql .= ' SET `username` = "'.$user['username'].'"';
                $sql .= ' WHERE `user_id` = '.$user['id'];
            }
            
        $db->setQuery($sql);
        $db->query();
        
        if($db->getErrorNum()) {
            JError::raiseError(500,$db->stderr());
        }
        }
    }
    
    function onAfterDeleteUser($user,$success,$msg) {
        if($success) {
            $db = JFactory::getDBO();
            
            $sql  = ' DELETE FROM `#__guilds_members` ';
            $sql .= ' WHERE `user_id` =  '.$user['id'];
            
            $db->setQuery($sql);
            $db->query();
            
            if($db->getErrorNum()) {
                JError::raiseError(500,$db->stderr());
            }
            
            $sql  = ' DELETE FROM `#__guilds_characters` ';
            $sql .= ' WHERE `user_id` = '.$user['id'];
            
            $db->setQuery($sql);
            $db->query();
            
            if($db->getErrorNum()) {
                JError::raiseError(500,$db->stderr());
            }
        }
    }
    
}

?>
