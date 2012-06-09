<?php
	// no direct access
	defined('_JEXEC') or die('Restricted access');
	
	// Require the base controller
	require_once JPATH_COMPONENT.DS.'controller.php';
	
	// Require specific controll if requested
	if($controller = JRequest::getWord('view')) {
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
		if(file_exists($path)) {
			require_once($path);
		} else {
			$controller = '';
		}
	}
	
	// Initialize the controller
	$classname = 'GuildsController'.$controller;
	$controller = new $classname();
	
	//Perform the Request task
	$controller->execute(JRequest::getWord('task'));
	
	// Redirect if set by the controller
	$controller->redirect();