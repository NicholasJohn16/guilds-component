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

class GuildsViewCharacters extends JView {
	
	function display(){
		$characters = $this->get('Characters');
		
		$characters = $this->get('Characters');
		$types = $this->get('Types');
		$categories = $this->get('Categories');
		
		if(empty($characters)){
			$this->setLayout('error');
		}
		
		$this->assignRef('characters',$characters);
		$this->assignRef('types',$types);
		$this->assignRef('categories',$categories);
		
		parent::display();
	}
	
	function pagination() {
		$pagination = $this->get('Pagination');
		$total = $pagination->{'pages.total'};
		$limit = $pagination->limit;
		$limitstart = $pagination->limitstart;
		$cur_page = $limitstart/$limit +1;
		$link = 'index.php?option=com_guilds&view=members&limitstart=';
		
		$current_range = array(($cur_page-2 < 1 ? 1 : $cur_page-2), ($cur_page+2 > $total ? $total : $cur_page+2));
		
		// First and Last pages
		$first_page = $cur_page > 3 ? '<button class="btn"><a href="index.php?option=com_guilds&view=members">First</a></button>' : null;
		$last_page = $cur_page < $total-2 ? '<button class="btn"><a href="'.$link.(($total-1)*$limit).'">Last</a></button>' : null;
		
		// Previous and next page
		$previous_page = $cur_page > 1 ? '<button class="btn"><a title="Previous" href="'.$link.($limitstart-$limit).'"><i class="icon-chevron-left"></i></a></button>' : null;
		$next_page = $cur_page < $total ? '<button class="btn"><a title="Next" href="'.$link.($limitstart+$limit).'"><i class="icon-chevron-right"></i></a></button>' : null;
		
		// Display pages that are in range
		for ($x=$current_range[0];$x <= $current_range[1]; ++$x) {
			$pages[] = '<button '.'class="btn '.($x == $cur_page ? 'active':null).'"><a href="'.$link.($x-1)*$limit.'">'.$x.'</a></button>';
		}
		return '<div id="pagination" class="btn-group">'.$first_page.$previous_page.implode($pages).$next_page.$last_page.'</div>';
	}
	
}