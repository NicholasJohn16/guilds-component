<?php
/*
* @package	Guilds Manager
* @subpackage	Components
* @link		http://stonewallgaming./net
* @license	GNU/GPL
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class GuildsViewCharacters extends JView {
    
    function display() {
        $layout = $this->getLayout();
        
        switch($layout) {
            case 'default':
                $this->displayList();
                break;
            case 'form':
                $this->displayForm();
                break;
        }
        parent::display();
    }

    function displayList() {
        JToolBarHelper::title(JText::_('Characters'), 'generic.png');
        JToolBarHelper::back('Back',JRoute::_('index.php?option=com_guilds'));
        JToolBarHelper::divider();
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::customX('invited','send.png','','Invite');
        JToolBarHelper::deleteList('Are you sure?','delete');
        
        $characters = $this->get('characters');
        $types = $this->get('types','types');
        $categories = $this->get('categories','categories');
        $pagination = $this->get('pagination');
        
        $key = 'com_guilds'.'characters'.'default';
        $mainframe = JFactory::getApplication();
        $search = $mainframe->getUserStateFromRequest($key.'search','search',NULL,'string' );
        $order = $mainframe->getUserStateFromRequest($key.'order','order',NULL,'cmd');
        $direction = $mainframe->getUserStateFromRequest($key.'direction','direction',NULL,'word');
        $filters = $mainframe->getUserStateFromRequest($key.'category','category',array(),'array');
        
        $this->assignRef('characters', $characters);
        $this->assignRef('types',$types);
        $this->assignRef('categories', $categories);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('search', $search);
        $this->assignRef('order', $order);
        $this->assignRef('direction', $direction);
        $this->assignRef('filters',$filters);
        
    }
    
    function displayForm() {
        JHTML::_('behavior.mootools');
        JHTML::_('behavior.calendar');
        
        $isNew = (JRequest::getVar('id',NULL,'') == NULL) ? true : false;
        
        $title = $isNew ? 'Add Character' : 'Edit Character';
        
        JToolBarHelper::title($title,'generic.png');
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        
        $character = $this->get('character');
        $types = $this->get('types','types');
        $categories = $this->get('categories','categories');
        
        $this->assignRef('character', $character);
        $this->assignRef('types', $types);
        $this->assignRef('categories',$categories);
        $this->assignRef('isNew',$isNew);
        
    }
    
    function invite( $invite )
    {
        $img 	= $invite ? 'tick.png' : 'publish_x.png';
        $title 	= $invite ? 'Invite Pending' : '';

        $html = '<img src="images/'. $img .'" border="0" title="'. $title .'" /></a>';

        return $html;
    }
}