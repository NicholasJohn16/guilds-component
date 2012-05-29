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

/*
 * Define constants for all pages
 */
define( 'COM_MEMBERMANAGER_DIR', 'images'.DS.'membermanager'.DS );
define( 'COM_MEMBERMANAGER_BASE', JPATH_ROOT.DS.COM_MEMBERMANAGER_DIR );
define( 'COM_MEMBERMANAGER_BASEURL', JURI::root().str_replace( DS, '/', COM_MEMBERMANAGER_DIR ));

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

// Require the base controller
require_once JPATH_COMPONENT.DS.'helpers'.DS.'helper.php';

// Initialize the controller
$controller = new MembermanagerController( );

// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>