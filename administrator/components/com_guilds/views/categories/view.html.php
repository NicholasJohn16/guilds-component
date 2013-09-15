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

class GuildsViewCategories extends JView {
    
    public function display() {
        $layout = $this->getLayout();
        
        switch($layout) {
            case 'form':
                $this->displayForm();
                break;
            default:
                $this->displayList();
                break;
        }
        
        parent::display();
    }

    function displayList() {
        JToolBarHelper::title(JText::_('Categories'), 'generic.png');
        JToolBarHelper::back('Back',JRoute::_('index.php?option=com_guilds'));
        JToolBarHelper::divider();
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
        JToolBarHelper::publishList();
        JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList('Are you sure?','delete');
        
        $categories = $this->get('AllCategories');
        
        jimport('joomla.html.pagination');
        $pagination = new JPagination(count($categories),0,count($categories));
        
        $this->assignRef('categories', $categories);
        $this->assignRef('pagination', $pagination);
    }
    
    public function displayForm() {
        $id = JRequest::getVar('id',NULL,'');
        // Incase its submitted as an array,
        //check and return first value
        $id = (is_array($id)) ? $id[0] : $id;
        $isNew = ($id) ? true : false;
        $title = $isNew ? 'Edit Character' : 'Add Character';
        
        JToolBarHelper::title($title,'generic.png');
        JToolBarHelper::save();
        JToolBarHelper::cancel();
        
        $category = $this->get('category');
        $categories = $this->get('AllCategories','categories');
        $types = $this->get('AllTypes','types');
        
        $sql = ' SELECT ordering AS value, name AS text FROM #__guilds_categories ORDER BY ordering asc ';
        
        $this->assignRef('category',$category);
        $this->assignRef('categories',$categories);
        $this->assignRef('types',$types);
        $this->assignRef('sql',$sql);
    }

}