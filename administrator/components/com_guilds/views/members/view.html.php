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

class GuildsViewMembers extends JView {
    
    function display() {
        $layout = $this->getLayout();
        
        switch ($layout) {
            case 'default':
                $this->displayList();
                break;
            case 'form':
                $this->displayForm();
                break;
        }
    }

    function displayList($tpl = null) {
        JToolBarHelper::title(JText::_('Members'), 'generic.png');
        JToolBarHelper::editList();
        JToolBarHelper::back();
        
        $model = $this->getModel('members');
        $members = $model->getMembers();
        $pagination = $model->getPagination();
        
        $key = 'com_guilds'.'member'.'default';
        $mainframe = JFactory::getApplication();
        $search = $mainframe->getUserStateFromRequest($key.'search','search','','string' );
        $order = $mainframe->getUserStateFromRequest($key.'order','','string');
        $direction = $mainframe->getUserStateFromRequest($key.'direction','','string');
        
        $this->assignRef('members', $members);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('search', $search);
        $this->assignRef('order', $order);
        $this->assignRef('direction', $direction);
        
        parent::display($tpl);
    }
    
    function displayForm(){
        JHTML::_('behavior.mootools');
        JHTML::_('behavior.calendar');
        
        JToolBarHelper::title('Edit Member','generic.png');
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        
        $id = JRequest::getVar('id',NULL,'');
        // Incase its submitted as an array,
        //check and return first value
        $id = (is_array($id)) ? $id[0] : $id;
        
        $model = $this->getModel('members');
        $model->setState('id',$id);
        $member = $model->getMember();
        
        $this->assignRef('member', $member);
        
        parent::display();
    }

}