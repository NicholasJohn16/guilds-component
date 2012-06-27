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
	
	function __construct() {
		global $mainframe, $option;
		// Get the layout so it can be used to make request variable layout specific
		$layout	= $this->getLayout();
		 
		$this->order		= $mainframe->getUserStateFromRequest($option.$layout."filter_order",'filter_order',null,'cmd' );
		$this->direction	= $mainframe->getUserStateFromRequest($option.$layout."filter_order_dir",'filter_order_dir',null,'word');
		$this->search		= $mainframe->getUserStateFromRequest($option.$layout."search",'search','','string' );
		$this->filter_type	= $mainframe->getUserStateFromRequest($option.$layout.'filter_type','filter_type',array(),'array');
		
		parent::__construct();
	}
	
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
    	JHTML::stylesheet('bootstrap.css','components/com_guilds/media/css/');
    	JHTML::stylesheet('guilds.css','components/com_guilds/media/css/');
		
		$characters =& $this->get('Characters');
		$types =& $this->get('Types');
		$pagination =& $this->get('Pagination');
		
		$this->assignRef('characters',$characters);
		$this->assignRef('types',$types);
		$this->assignRef('pagination',$pagination);
    }
    
    function displayRoster() {
    	// Add scripts and stylesheets to the head
		JHTML::stylesheet('guilds.css','components/com_guilds/media/css/');
		JHTML::stylesheet('bootstrap.css','components/com_guilds/media/css/');
		JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
		JHTML::script('bootstrap.js','components/com_guilds/media/js/',false);
		JHTML::script('characters.jquery.js','components/com_guilds/media/js/',false);
		
		global $mainframe, $option;
		
		// Get data from the model
		$characters =& $this->get('Characters');
		$pagination =& $this->get('Pagination');
		$types		=& $this->get('Types');	
		$categories =& $this->get('Categories');
		
		dump($characters,"Characters");
		
		// Assign them references so they can be accessed in the tmpl
		$this->assignRef('search',$this->search);
		$this->assignRef('filter_type',$this->filter_type);
		$this->assignRef('order',$this->order);
		$this->assignRef('direction',$this->direction);
		$this->assignRef('characters',$characters);
		$this->assignRef('types', $types);
		$this->assignRef('categories',$categories);
		$this->assignRef('pagination',$pagination);
		
    }
    
    function displayCharacter() {
    	

    }
    
    function displayForm() {
    	JHTML::stylesheet('guilds.css','components/com_guilds/media/css/');
		JHTML::stylesheet('bootstrap.css','components/com_guilds/media/css/');
		JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
		JHTML::script('bootstrap.js','components/com_guilds/media/js/',false);
		JHTML::script('characters.jquery.js','components/com_guilds/media/js/',false);
    	
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

		$link = 'index.php?option=com_guilds&view=characters&layout='.$layout.'&limitstart=';
		
		$current_range = array(($cur_page-2 < 1 ? 1 : $cur_page-2), ($cur_page+2 > $total ? $total : $cur_page+2));
		
		// First and Last pages
		$first_page = $cur_page > 3 ? '<li><a href="'.$link.'0">First</a></li>' : null;
		$last_page = $cur_page < $total-2 ? '<li><a href="'.$link.(($total-1)*$limit).'">Last</a></li>' : null;
		
		// Previous and next page
		$previous_page = $cur_page > 1 ? '<li><a title="Previous" href="'.$link.($limitstart-$limit).'">&laquo;</a></li>' : null;
		$next_page = $cur_page < $total ? '<li><a title="Next" href="'.$link.($limitstart+$limit).'">&raquo;</a></li>' : null;
		
		if($total == 0) {
			$pages = array();
		}
		
		// Display pages that are in range
		for ($x=$current_range[0];$x <= $current_range[1]; ++$x) {
			$pages[] = '<li '.($x == $cur_page ? 'class="active"':null).'><a href="'.$link.($x-1)*$limit.'">'.$x.'</a></li>';
		}
		
		return '<div class="com-guilds-pagination"><ul>'.$first_page.$previous_page.implode($pages).$next_page.$last_page.'</ul></div>';
	}
	
	function sortable($title,$order = null){
		$direction  = $this->direction;
		// if the direction is not set, give it a default of ascending
		$direction	= ($direction == null) ? 'asc' : $this->direction;
		// if the order is not set, set it equal to the title
		$order 		= ($order == null) ? str_replace(" ","_",strtolower($title)) : $order;
		$images		= array( 'arrow-up.png', 'arrow-down.png' );
		$index		= intval( $direction == 'desc' );
		
		// If the current title is what's being ordered
		if($this->order == $order ) {
			// then set the direction for its click event to the opposite of what it currently is
			$direction	= ($direction == 'desc') ? 'asc' : 'desc';
		}

		$html  = '<a href="#" data-order="'.$order.'" data-direction="'.$direction.'">';
		$html .= $title; 
		if ($this->order == $order) {
			$html .= '<img src="components/com_guilds/media/img/'.$images[$index].'"/>';
		}
		$html .= '</a>';
		
		return $html;
	}
    
}