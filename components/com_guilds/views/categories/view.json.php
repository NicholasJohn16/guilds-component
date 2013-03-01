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

    class GuildsViewCategories extends JView {
        
        function display() {
            $model = $this->getModel('categories');
            $categories = $model->getCategoriesByType();
            echo json_encode($categories);
        }
    }