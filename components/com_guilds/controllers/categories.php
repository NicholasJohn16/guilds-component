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

    jimport('joomla.application.component.controller');

    class GuildsControllerCategories extends JController {
        
        function __construct($config = array()) {
            $this->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');
            parent::__construct($config);
        }
        
        function display() {
            $format = JRequest::getVar('format','default','','string');
            $type = JRequest::getVar('type',NULL,'','string');
            $model = $this->getModel('categories');
            $model->setState('type',$type);
            $view = $this->getView('categories',$format);
            $view->setModel($model);
            $view->display();
        }
        
    }