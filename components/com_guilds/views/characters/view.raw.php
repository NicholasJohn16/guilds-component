<?php
/**
 * Joomla! 1.5 Component Guilds Manager
 *
 * @version 
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Guilds Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class GuildsViewCharacters extends JView {
    
    function displayAjax() {
            $characters =& $this->get('CharactersByUserId');
            $types =& $this->get('Types','types');
            $pagination =& $this->get('Pagination');

            $this->assignRef('characters',$characters);
            $this->assignRef('types',$types);
            $this->assignRef('pagination',$pagination);
            
            parent::display();
        }
}