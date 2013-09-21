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

jimport( 'joomla.application.component.view');

class GuildsViewMembers extends JView {
	
	function display($tmpl = null) {
		switch($this->getLayout()) {
			case 'form':
				$this->displayForm();
				break;
			default:
				$this->displayList();	
		}
		parent::display($tmpl);
    }
    
    function displayList() {
        JHTML::stylesheet('guilds.css','components/com_guilds/media/css/');
        JHTML::stylesheet('bootstrap.min.css','components/com_guilds/media/css/');
        JHTML::stylesheet('bootstrap-editable.css','components/com_guilds/media/css/');
        JHTML::stylesheet('bootstrap-datepicker.css','components/com_guilds/media/css/');
        //JHTML::stylesheet('bootstrap-notify.css','components/com_guilds/media/css/');
        JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
        JHTML::script('bootstrap.js','components/com_guilds/media/js/',false);
        //JHTML::script('bootstrap-notify.js','components/com_guilds/media/js/',false);
        JHTML::script('bootstrap-editable.js','components/com_guilds/media/js/',false);
        JHTML::script('members.js','components/com_guilds/media/js/',false);
        
        // Supress module positions so they don't take up room
        $document =& JFactory::getDocument();
        $positions = array('right','left','sidebar-a','sidebar-b');
        foreach($positions as $position) {
            $document->setBuffer(false, 'modules',$position);
        }
        
        $itemId = JRequest::getInt('Itemid');
        $menu = JSite::getMenu();
        $params = $menu->getParams($itemId);
        $game_handle = $params->get('game_handle');
        
        $members = $this->get('Members');
        $pagination = $this->get('Pagination');
        $ranks = $this->get('Ranks');
        $types = $this->get('Types','types');
        $categories = $this->get('Categories','categories');
        
        global $mainframe, $option;
        // Get the layout so it can be used to make request variable layout specific
        $layout	= $this->getLayout();
        
        $this->order		= $mainframe->getUserStateFromRequest($option.'member'.$layout.'order','order',null,'cmd' );
        $this->direction	= $mainframe->getUserStateFromRequest($option.'member'.$layout.'direction','direction',null,'word');
        $this->search		= $mainframe->getUserStateFromRequest($option.'member'.$layout.'search','search','','string' );

        $this->assignRef('members',$members);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('ranks',$ranks);
        $this->assignRef('types',$types);
        $this->assignRef('categories', $categories);
        $this->assignRef('game_handle',$game_handle);
    }
    
    function displayForm() {
    	JHTML::stylesheet('bootstrap.css','components/com_guilds/media/css/');
        JHTML::stylesheet('guilds.css','components/com_guilds/media/css/');
        JHTML::stylesheet('bootstrap-datepicker.css','components/com_guilds/media/css/');
        JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
        JHTML::script('bootstrap.js','components/com_guilds/media/js/',false);
        JHTML::script('bootstrap-datepicker.js','components/com_guilds/media/js/',false);
        
        // Supress module positions so they don't take up room
        $document =& JFactory::getDocument();
        $positions = array('right','left','sidebar-a','sidebar-b');
        foreach($positions as $position) {
            $document->setBuffer(false, 'modules',$position);
        }
        

        $member = $this->get('Member');
        $ranks = $this->get('Ranks');

        $this->assignRef('member',$member);
        $this->assignRef('ranks', $ranks);
    	
    }
    
	function pagination() {
            $pagination = $this->get('Pagination');
            $total = $pagination->{'pages.total'};
            $limit = $pagination->limit;
            $limitstart = $pagination->limitstart;
            $cur_page = $limitstart/$limit +1;
            $link = 'index.php?option=com_guilds&view=members&limitstart=';

            if($total > 0) {
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
                return '<div class="com-guilds-pagination"><ul>'.$first_page.$previous_page.implode($pages).$next_page.$last_page.'</ul></div>';
            } else {
                return false;
            }
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
?>