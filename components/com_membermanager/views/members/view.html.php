<?php
/**
 * Joomla! 1.5 component Member Manager
 *
 * @version $Id: controller.php 2011-10-28 10:20:36 svn $
 * @author Nick Swinford
 * @package Joomla
 * @subpackage Member Manager
 * @license Copyright (c) 2011 - All Rights Reserved
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the Member Manager component
 */
class membermanagerViewMembers extends JView {
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
    	//TODO SW: Fix stylesheet PATH
		JHTML::stylesheet('members.css','media/guilds/css/');
		JHTML::stylesheet('bootstrap.css','media/guilds/css/');
		JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
		JHTML::script('bootstrap.js','media/guilds/js/',false);
		JHTML::script('members.jquery.js','media/guilds/js/',false);
		
		$members = $this->get('Members');
		$pagination = $this->get('Pagination');
		$ranks = $this->get('Ranks');
		$types = $this->get('Types');
		$categories = $this->get('Categories');
		
		dump($members,"Members");
		
		$this->assignRef('members',$members);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('ranks',$ranks);
		$this->assignRef('types',$types);
		$this->assignRef('categories', $categories);
    }
    
    function displayForm() {
    	//TODO SW: Fix stylesheet PATH
		JHTML::stylesheet('members.css','media/guilds/css/');
		JHTML::stylesheet('bootstrap.css','media/guilds/css/');
		JHTML::script('jquery.js','https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/');
		JHTML::script('bootstrap.js','media/guilds/js/',false);
		JHTML::script('members.jquery.js','media/guilds/js/',false);
		
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
		$link = 'index.php?option=com_membermanager&view=members&limitstart=';
		
		$current_range = array(($cur_page-2 < 1 ? 1 : $cur_page-2), ($cur_page+2 > $total ? $total : $cur_page+2));
		
		// First and Last pages
		$first_page = $cur_page > 3 ? '<li><a href="index.php?option=com_membermanager&view=members">First</a></li>' : null;
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
?>