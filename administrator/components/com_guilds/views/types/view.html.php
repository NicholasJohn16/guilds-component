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

class GuildsViewTypes extends JView {

    function display($tpl = null) {
        $layout = $this->getLayout();
        
        switch($layout) {
            case 'default':
                $this->displayList();
                break;
            case 'form':
                $this->displayForm();
                break;
        }
    }
    
    public function displayList() {
        JToolBarHelper::title(JText::_('Types'), 'generic.png');
        JToolBarHelper::back('Back',JRoute::_('index.php?option=com_guilds'));
        JToolBarHelper::divider();
        JToolBarHelper::addNew();
        JToolBarHelper::deleteList('Deleting a type will remove all assosicated data.  Are you sure?','delete');
        
        $types = $this->get('types');
        jimport('joomla.html.pagination');
        $pagination = new JPagination(count($types),0,count($types));
        
        $this->assignRef('types',$types);
        $this->assignRef('pagination', $pagination);
        
        parent::display();
    }
    
    public function displayForm() {
        
        $id = JRequest::getVar('id',NULL);
        $isNew = ($id == NULL) ? true : false;
        $title = ($isNew) ? 'Add Type' : 'Edit Type';
        
        JToolBarHelper::title($title,'generic.png');
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        
        
        
    }

}