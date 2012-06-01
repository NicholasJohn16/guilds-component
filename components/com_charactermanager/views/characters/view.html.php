<?php
/**
 * Joomla! 1.5 component Character Manager
 *
 * @version 
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Character Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Member Manager component
 */
class CharactermanagerViewCharacters extends JView {
	
	function display($tmpl = null) {
		switch($this->getLayout()) {
			case 'roster':
				$this->displayRoster();
				break;
			case 'character':
				$this->displayCharacter();
				break;
			case 'form':
				$this->displayForm();
				break;
			default:
				$this->displayCharacters();
		}
        parent::display($tmpl);
    }
    
    function displayCharacters() {
    	JHTML::stylesheet('bootstrap.css','media/guilds/css/');
    	JHTML::stylesheet('characters.css','media/guilds/css/');
		
		$characters =& $this->get('Characters');
		$types =& $this->get('Types');
		$pagination =& $this->get('Pagination');
		
		//Change $items to $characters
		$this->assignRef('characters',$characters);
		$this->assignRef('types',$types);
		$this->assignRef('pagination',$pagination);
    }
    
    function displayRoster() {
    	// Add scripts and stylesheets to the head
		JHTML::stylesheet('characters.css','media/guilds/css/');
		JHTML::stylesheet('bootstrap.css','media/guilds/css/');
		JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
		JHTML::script('bootstrap.js','media/guilds/js/',false);
		JHTML::script('characters.jquery.js','media/guilds/js/',false);
		
		global $mainframe, $option;
		
		// Get data from the model
		$characters =& $this->get('Characters');
		$pagination =& $this->get('Pagination');
		$types		=& $this->get('Types');	
		$categories =& $this->get('Categories');
		
		//Get values from the request for search and filters
		$filter_order		= $mainframe->getUserStateFromRequest($option."filter_order",'filter_order','a.name','cmd' );
		$filter_order_dir	= $mainframe->getUserStateFromRequest($option."filter_order_Dir",'filter_order_Dir','','word');
		$search				= $mainframe->getUserStateFromRequest($option."search",'search','','string' );
		$filter_type 		= $mainframe->getUserStateFromRequest($option.'filter_type','filter_type',array(),'array');
		
		// Assign them references so they can be accessed in the tmpl
		$this->assignRef('search',$search);
		$this->assignRef('filter_type',$filter_type);
		$this->assignRef('filter_order',$filter_order);
		$this->assignRef('filter_order_dir',$filter_order_dir);
		$this->assignRef('characters',$characters);
		$this->assignRef('types', $types);
		$this->assignRef('categories',$categories);
		$this->assignRef('pagination',$pagination);
		
    }
    
    function displayCharacter() {
    	

    }
    
    function displayForm() {
    	JHTML::stylesheet('members.css','media/guilds/css/');
		JHTML::stylesheet('bootstrap.css','/media/guilds/css/');
		JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
		JHTML::script('bootstrap.js','media/guilds/js/',false);
		JHTML::script('characters.jquery.js','media/guilds/js/',false);
    	
		$character =& $this->get('Character');
		$types =& $this->get('Types');
		$categories =& $this->get('Categories');
		
		$this->assignRef('character',$character);
		$this->assignRef('types', $types);
		$this->assignRef('categories', $categories);
    }
    
	function pagination() {
		$pagination = $this->get('Pagination');
		$layout = $this->getLayout();
		$total = $pagination->{'pages.total'};
		$limit = $pagination->limit;
		$limitstart = $pagination->limitstart;
		$cur_page = $limitstart/$limit +1;
		
		$link = 'index.php?option=com_charactermanager&view=characters&layout='.$layout.'&limitstart=';
		
		$current_range = array(($cur_page-2 < 1 ? 1 : $cur_page-2), ($cur_page+2 > $total ? $total : $cur_page+2));
		
		// First and Last pages
		$first_page = $cur_page > 3 ? '<li><a href="'.$link.'0">First</a></li>' : null;
		$last_page = $cur_page < $total-2 ? '<li><a href="'.$link.(($total-1)*$limit).'">Last</a></li>' : null;
		
		// Previous and next page
		$previous_page = $cur_page > 1 ? '<li><a title="Previous" href="'.$link.($limitstart-$limit).'">&laquo;</a></li>' : null;
		$next_page = $cur_page < $total ? '<li><a title="Next" href="'.$link.($limitstart+$limit).'">&raquo;</a></li>' : null;
		
		// Display pages that are in range
		for ($x=$current_range[0];$x <= $current_range[1]; ++$x) {
			$pages[] = '<li '.($x == $cur_page ? 'class="active"':null).'><a href="'.$link.($x-1)*$limit.'">'.$x.'</a></li>';
		}
		
		return '<div class="com-cm-pagination"><ul>'.$first_page.$previous_page.implode($pages).$next_page.$last_page.'</ul></div>';
	}
    
}